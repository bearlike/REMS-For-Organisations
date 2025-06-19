from __future__ import annotations

"""Certificate generation routes."""

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
        metadata = EventMetadata(
            event_name=request.form.get("event_name", ""),
            event_date=request.form.get("date"),
            is_inter=request.form.get("eventType", "0") == "1",
        )
        if not file or not metadata.event_name:
            return render_template("cert_generate.html", error="Missing fields")

        filename = secure_filename(file.filename)
        folder = Path("public/Generated Certificate") / Path(filename).stem
        folder.mkdir(parents=True, exist_ok=True)
        filepath = folder / filename
        file.save(filepath)

        reader = csv.DictReader(open(filepath, newline=""))
        rows = []
        counter = 0
        for row in reader:
            data = CertificateCSVRow(event_name=metadata.event_name, **row)
            cert_link = folder / f"Certificate-{counter}.png"
            cert = Certificate(
                name=data.name,
                regno=data.regno,
                dept=data.dept,
                year=str(data.year) if data.year else None,
                section=data.section,
                email=data.email,
                position=data.position,
                cert_link=str(cert_link),
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
                    "cert_link": str(cert_link),
                }
            )
            counter += 1

        event = Event.query.filter_by(event_name=metadata.event_name).first()
        if not event:
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

        log_activity(g.user, f"Generated {counter} certificates for {metadata.event_name}")
        return render_template(
            "cert_generate.html",
            success=True,
            rows=rows,
            inter=metadata.is_inter,
        )

    return render_template("cert_generate.html")

