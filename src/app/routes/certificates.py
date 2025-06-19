"""Certificate generation routes."""

from __future__ import annotations

from pathlib import Path
import csv
from datetime import datetime
from loguru import logger
from PIL import Image, ImageDraw, ImageFont

from flask import Blueprint, render_template, request, redirect, url_for, g, current_app
from sqlalchemy.exc import SQLAlchemyError
from werkzeug.utils import secure_filename

from .. import db
from ..models import Certificate, Event
from ..schemas import CertificateCSVRow, EventMetadata
from ..utils.auth import login_required
from ..utils.helpers import log_activity

TEMPLATE_DIR = Path("members/CDS_Admin/Certificate Templates")
FONTS_DIR = Path("members/CDS_Admin/Fonts/Raleway")
FONT_LIGHT = FONTS_DIR / "Raleway-Light.ttf"
FONT_REGULAR = FONTS_DIR / "Raleway-Regular.ttf"
FONT_MEDIUM = FONTS_DIR / "Raleway-Medium.ttf"


def _check_files_exist() -> None:
    """Ensure required template and font files exist."""
    missing_files = []

    if not TEMPLATE_DIR.exists():
        missing_files.append(f"Template directory {TEMPLATE_DIR}")
    if not FONT_LIGHT.exists():
        missing_files.append(f"Font file {FONT_LIGHT}")
    if not FONT_REGULAR.exists():
        missing_files.append(f"Font file {FONT_REGULAR}")
    if not FONT_MEDIUM.exists():
        missing_files.append(f"Font file {FONT_MEDIUM}")

    if missing_files:
        error_message = f"Missing required files: {', '.join(missing_files)}"
        logger.error(error_message)
        raise FileNotFoundError(error_message)

    logger.debug("All required files are present.")


_check_files_exist()


def _create_template(metadata: EventMetadata, folder: Path) -> Path:
    """Generate the base certificate template with event details."""
    img = Image.open(TEMPLATE_DIR / "Participation.png").convert("RGBA")
    draw = ImageDraw.Draw(img)
    light = ImageFont.truetype(str(FONT_LIGHT), 50)
    medium = ImageFont.truetype(str(FONT_MEDIUM), 60)
    draw.text(
        (206, 1019), "This certificate is presented to", fill="#535453", font=light
    )
    draw.text((206, 1317), "For participating in", fill="#535453", font=light)
    draw.text((206, 1439), metadata.event_name, fill="#535453", font=medium)
    draw.text((206, 1559), "Conducted by", fill="#535453", font=light)
    draw.text((706, 1562), "SVCE ACM Student Chapter", fill="#535453", font=medium)
    draw.text((206, 1672), "on", fill="#535453", font=light)
    draw.text(
        (315, 1672), metadata.date.strftime("%d-%m-%Y"), fill="#535453", font=medium
    )
    template_path = folder / "template.png"
    img.save(template_path)
    return template_path


def _wrap_text(text: str, line_limit: int = 58) -> list[str]:
    """Split text into lines with a character limit."""
    words = text.split()
    lines: list[str] = [""]
    for word in words:
        if len(lines[-1]) + len(word) + 1 > line_limit:
            lines.append(word)
        else:
            lines[-1] = (lines[-1] + " " + word).strip()
    while len(lines) < 4:
        lines.append("")
    return lines[:4]


def _create_certificate_image(
    data: CertificateCSVRow,
    metadata: EventMetadata,
    template_path: Path,
    folder: Path,
    counter: int,
) -> Path:
    """Create a certificate image for a single participant."""
    if metadata.is_inter:
        pos_file = {
            "Winner": "Winner.png",
            "Runner": "Runner.png",
        }.get((data.position or "").title(), "Participation.png")
        img = Image.open(TEMPLATE_DIR / pos_file).convert("RGBA")
        regular = ImageFont.truetype(str(FONT_REGULAR), 50)
        sentence = f"of {data.college} for participating in {metadata.event_name} conducted by SVCE ACM Student Chapter from {metadata.date.strftime('%d-%m-%Y')}"
        if data.position == "Winner":
            sentence = f"of {data.college} for Winning {metadata.event_name} conducted by SVCE ACM Student Chapter from {metadata.date.strftime('%d-%m-%Y')}"
        elif data.position == "Runner":
            sentence = f"of {data.college} for being the Runner in {metadata.event_name} conducted by SVCE ACM Student Chapter from {metadata.date.strftime('%d-%m-%Y')}"
        lines = _wrap_text(sentence)
        draw = ImageDraw.Draw(img)
        name_font_size = (
            100 if len(data.name) <= 22 else max(30, 100 - ((len(data.name) - 22) * 7))
        )
        name_font = ImageFont.truetype(str(FONT_MEDIUM), name_font_size)
        draw.text(
            (206, 1019),
            "This certificate is presented to",
            fill="#535453",
            font=regular,
        )
        draw.text((206, 1185), data.name, fill="#535453", font=name_font)
        y = 1317
        for line in lines:
            draw.text((206, y), line, fill="#535453", font=regular)
            y += 122
    else:
        img = Image.open(template_path).convert("RGBA")
        draw = ImageDraw.Draw(img)
        name_font_size = (
            100 if len(data.name) <= 22 else max(30, 100 - ((len(data.name) - 22) * 7))
        )
        name_font = ImageFont.truetype(str(FONT_MEDIUM), name_font_size)
        draw.text((206, 1185), data.name, fill="#535453", font=name_font)

    cert_path = folder / f"Certificate-{counter}.png"
    img.save(cert_path)
    return cert_path


cert_bp = Blueprint("certificates", __name__, url_prefix="/certificates")


@cert_bp.route("/generate", methods=["GET", "POST"])
@login_required
def generate():
    """Generate certificates from an uploaded CSV file."""
    if request.method == "POST":
        file = request.files.get("file")
        event_date_str = request.form.get("date")
        if not file or not file.filename:
            return render_template("cert_generate.html", error="No file selected")

        if not event_date_str:
            return render_template("cert_generate.html", error="Event date is required")

        try:
            event_date = datetime.strptime(event_date_str, "%Y-%m-%d").date()
        except ValueError as parsing_error:
            return render_template(
                "cert_generate.html", error=f"Invalid date format: {parsing_error}"
            )

        metadata = EventMetadata(
            event_name=request.form.get("event_name", ""),
            event_date=event_date,
            is_inter=request.form.get("eventType", "0") == "1",
        )

        if not metadata.event_name:
            return render_template("cert_generate.html", error="Event name is required")

        # Create certificates directory in static folder for proper serving
        static_folder = Path(current_app.static_folder or "src/app/static")
        certificates_dir = static_folder / "certificates"
        certificates_dir.mkdir(exist_ok=True)

        # Use event name for folder instead of CSV filename for consistency
        # Sanitize event name for filesystem use
        safe_event_name = secure_filename(metadata.event_name)
        folder = certificates_dir / safe_event_name
        folder.mkdir(parents=True, exist_ok=True)

        # Save uploaded CSV file in the event folder
        filename = secure_filename(file.filename)
        filepath = folder / filename
        file.save(filepath)

        with open(filepath, newline="", encoding="utf-8-sig") as csvfile:
            reader = csv.DictReader(csvfile)
            expected_headers = {
                "name",
                "regno",
                "dept",
                "year",
                "section",
                "email",
                "position",
                "college",
            }
            if reader.fieldnames is None or not expected_headers.issubset(
                {h.strip() for h in reader.fieldnames}
            ):
                return render_template(
                    "cert_generate.html", error="Invalid CSV headers"
                )

            rows = []
            counter = 0

            template_path = _create_template(metadata, folder)

            for row in reader:
                year_value = None
                if row.get("year"):
                    try:
                        year_value = int(row["year"])
                    except (ValueError, TypeError):
                        year_value = None

                data = CertificateCSVRow(
                    event_name=metadata.event_name,
                    name=row.get("name", ""),
                    regno=row.get("regno"),
                    dept=row.get("dept"),
                    year=year_value,
                    section=row.get("section"),
                    email=row.get("email", ""),
                    position=row.get("position"),
                    college=row.get("college"),
                )

                cert_link = _create_certificate_image(
                    data, metadata, template_path, folder, counter
                )
                # Convert absolute path to relative path for static serving
                relative_cert_path = cert_link.relative_to(
                    Path(current_app.static_folder or "src/app/static")
                )
                # Use POSIX format for URL paths (platform-agnostic)
                cert_url = url_for("static", filename=relative_cert_path.as_posix())

                # pylint: disable=reportCallIssue
                cert = Certificate(
                    name=data.name,
                    regno=data.regno,
                    dept=data.dept,
                    year=data.year,
                    section=data.section,
                    email=data.email,
                    position=data.position,
                    cert_link=cert_url,
                    event_name=data.event_name,
                    college=data.college,
                )
                db.session.add(cert)
                rows.append(
                    {
                        "name": data.name,
                        "regno": data.regno,
                        "college": data.college,
                        "position": data.position,
                        "event_name": data.event_name,
                        "cert_link": cert_url,
                    }
                )
                counter += 1        # Handle event creation or updating
        event = Event.query.filter_by(event_name=metadata.event_name).first()
        is_regeneration = False
        if event:
            # Event exists - update it and clear existing certificates
            event.date = metadata.date
            event.isInter = metadata.is_inter
            is_regeneration = True

            # Delete existing certificates for this event to avoid duplicates
            existing_certificates = Certificate.query.filter_by(event_name=metadata.event_name).all()
            for cert in existing_certificates:
                db.session.delete(cert)
        else:
            # Create new event
            event = Event(
                event_name=metadata.event_name,
                date=metadata.date,
                isInter=metadata.is_inter,
            )
            db.session.add(event)

        try:
            db.session.commit()
        except SQLAlchemyError:
            db.session.rollback()
            return render_template("cert_generate.html", error="Database error")

        log_activity(
            g.user,
            f"{'Regenerated' if is_regeneration else 'Generated'} {counter} certificates for {metadata.event_name}"
        )

        # Calculate file metadata like PHP version
        file.seek(0, 2)  # Seek to end to get size
        file_size_bytes = file.tell()
        file_size_kb = round(file_size_bytes / 1024, 2)
        file.seek(0)  # Reset file pointer

        return render_template(
            "cert_generate.html",
            success=True,
            rows=rows,
            inter=metadata.is_inter,
            file_name=filename,
            file_type=file.content_type,
            file_size=f"{file_size_kb} Kb",
        )

    return render_template("cert_generate.html")


@cert_bp.route("/serve/<path:filename>")
def serve_certificate(filename):
    """Serve certificate files from the static certificates directory."""
    try:
        static_folder = Path(current_app.static_folder or "src/app/static")
        certificates_dir = static_folder / "certificates"

        # Security check: ensure the file is within the certificates directory
        file_path = certificates_dir / filename
        file_path.resolve().relative_to(certificates_dir.resolve())

        if file_path.exists() and file_path.is_file():
            # Use POSIX format for URL paths (platform-agnostic)
            cert_relative_path = Path("certificates") / filename
            return redirect(url_for("static", filename=cert_relative_path.as_posix()))
        else:
            return render_template("errors/404.html"), 404
    except (ValueError, OSError):
        # Path traversal attempt or file access error
        return render_template("errors/404.html"), 404
