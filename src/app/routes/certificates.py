"""Certificate generation routes."""
from __future__ import annotations

from pathlib import Path
import csv
from datetime import datetime

from flask import Blueprint, render_template, request, redirect, url_for, g
from sqlalchemy.exc import SQLAlchemyError
from werkzeug.utils import secure_filename

from .. import db
from ..models import Certificate, Event
from ..schemas import CertificateCSVRow, EventMetadata
from ..utils.auth import login_required
from ..utils.helpers import log_activity

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
        except ValueError:
            return render_template("cert_generate.html", error="Invalid date format")

        metadata = EventMetadata(
            event_name=request.form.get("event_name", ""),
            event_date=event_date,
            is_inter=request.form.get("eventType", "0") == "1",
        )

        if not metadata.event_name:
            return render_template("cert_generate.html", error="Event name is required")

        filename = secure_filename(file.filename)
        folder = Path("public/Generated Certificate") / Path(filename).stem
        folder.mkdir(parents=True, exist_ok=True)
        filepath = folder / filename
        file.save(filepath)

        reader = csv.DictReader(open(filepath, newline=""))
        rows = []
        counter = 0

        for row in reader:
            # Convert year to int if present, handle type conversion
            year_value = None
            if row.get('year'):
                try:
                    year_value = int(row['year'])
                except (ValueError, TypeError):
                    year_value = None

            # Create data with proper types
            data = CertificateCSVRow(
                event_name=metadata.event_name,
                name=row.get('name', ''),
                regno=row.get('regno'),
                dept=row.get('dept'),
                year=year_value,
                section=row.get('section'),
                email=row.get('email', ''),
                position=row.get('position'),
                college=row.get('college')
            )
            cert_link = folder / f"Certificate-{counter}.png"

            cert = Certificate()
            cert.name = data.name
            cert.regno = data.regno
            cert.dept = data.dept
            cert.year = data.year
            cert.section = data.section
            cert.email = data.email
            cert.position = data.position
            cert.cert_link = str(cert_link)
            cert.event_name = data.event_name
            cert.college = data.college
            db.session.add(cert)
            rows.append(
                {
                    "name": data.name,
                    "regno": data.regno,
                    "college": data.college,
                    "position": data.position,
                    "event_name": data.event_name,
                    "cert_link": str(cert_link),
                }
            )
            counter += 1

        event = Event.query.filter_by(event_name=metadata.event_name).first()
        if not event:
            event = Event()
            event.event_name = metadata.event_name
            event.date = metadata.date
            event.isInter = metadata.is_inter
            db.session.add(event)

        try:
            db.session.commit()
        except SQLAlchemyError:
            db.session.rollback()
            return render_template("cert_generate.html", error="Database error")

        log_activity(g.user, f"Generated {counter} certificates for {metadata.event_name}")

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

