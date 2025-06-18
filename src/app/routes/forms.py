"""Form generation and registration routes."""

from __future__ import annotations

import csv
import io
from pathlib import Path

from flask import (
    Blueprint,
    redirect,
    request,
    url_for,
    render_template,
    g,
    Response,
)
from sqlalchemy import text
from sqlalchemy.exc import SQLAlchemyError

from .. import db
from ..utils.auth import login_required
from ..utils.helpers import log_activity, is_admin
from ..utils.pagination import Pagination
from ..schemas import FormGeneratorSchema
from ...config import docker_secrets

forms_bp = Blueprint("forms", __name__)


@forms_bp.route("/entry", methods=["POST"])
def submit_entry():
    """Handle event registration form submissions."""
    form_data = request.form.to_dict()
    event_name = form_data.pop("event_name", None)
    if not event_name:
        return redirect(url_for("public.bad_request"))

    table = f"event_{event_name.replace(' ', '_')}"
    columns = ", ".join(form_data.keys())
    placeholders = ", ".join(f":{k}" for k in form_data.keys())
    sql = text(f"INSERT INTO {table} ({columns}) VALUES ({placeholders})")
    try:
        db.session.execute(sql, form_data)
        db.session.commit()
    except SQLAlchemyError:
        db.session.rollback()
        return redirect(url_for("public.bad_request"))

    return redirect(url_for("public.congrats"))


@forms_bp.route("/forms/generator", methods=["GET", "POST"])
@login_required
def generator() -> str:
    """Create a new registration form table."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    if request.method == "POST":
        form = FormGeneratorSchema(
            event_name=request.form.get("event_name", ""),
            event_description=request.form.get("event_description", ""),
            event_type=request.form.get("event_type", "individual"),
            number_participants=int(request.form.get("number_participants", 1)),
            fields=request.form.getlist("fields[]"),
        )

        table = f"event_{form.event_name.replace(' ', '_')}"
        columns = [
            "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY",
            "timestamp DATETIME DEFAULT CURRENT_TIMESTAMP",
        ]
        for field in form.fields:
            columns.append(f"`{field}` VARCHAR(255)")
        if form.event_type == "team":
            for idx in range(1, form.number_participants + 1):
                for field in form.fields:
                    columns.append(f"`{field}{idx}` VARCHAR(255)")

        sql = text(f"CREATE TABLE IF NOT EXISTS {table} ({', '.join(columns)})")
        try:
            db.session.execute(sql)
            db.session.commit()
        except SQLAlchemyError:
            db.session.rollback()
            return render_template("forms/generator.html", error="Database error")

        log_activity(g.user, f"Generated form table {table}")
        return render_template("forms/generator.html", success=True)

    return render_template("forms/generator.html")


@forms_bp.route("/forms/registrations")
@login_required
def view_registrations() -> str:
    """Display registrations for a selected event."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    event = request.args.get("event")
    page = int(request.args.get("page", 1))
    per_page = int(request.args.get("perPage", 10))
    pagination = Pagination(page, per_page)

    engine = db.get_engine(bind="forms")
    with engine.connect() as conn:
        events = [row[0] for row in conn.execute(text("SHOW TABLES LIKE 'event_%'"))]
        rows: list[dict] = []
        columns: list[str] = []
        total = 0
        if event in events:
            columns = [row[0] for row in conn.execute(text(f"SHOW COLUMNS FROM {event}"))]
            total = conn.execute(text(f"SELECT count(*) FROM {event}")).scalar() or 0
            offset = pagination.offset
            data = conn.execute(
                text(f"SELECT * FROM {event} ORDER BY id LIMIT :limit OFFSET :offset"),
                {"limit": pagination.per_page, "offset": offset},
            )
            rows = [dict(r) for r in data]

    return render_template(
        "forms/registrations.html",
        events=events,
        selected_event=event,
        columns=columns,
        rows=rows,
        pagination=pagination,
        total=total,
    )


@forms_bp.route("/forms/registrations/csv")
@login_required
def download_csv() -> Response:
    """Return a CSV export of an event table."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    event = request.args.get("event")
    if not event:
        return redirect(url_for("forms.view_registrations"))

    engine = db.get_engine(bind="forms")
    with engine.connect() as conn:
        tables = [row[0] for row in conn.execute(text("SHOW TABLES LIKE 'event_%'"))]
        if event not in tables:
            return redirect(url_for("public.bad_request"))
        columns = [row[0] for row in conn.execute(text(f"SHOW COLUMNS FROM {event}"))]
        data = conn.execute(text(f"SELECT * FROM {event} ORDER BY id")).fetchall()

    output = io.StringIO()
    writer = csv.writer(output)
    writer.writerow(columns)
    for row in data:
        writer.writerow(row)
    response = Response(output.getvalue(), mimetype="text/csv")
    response.headers["Content-Disposition"] = f"attachment; filename={event}.csv"
    log_activity(g.user, f"Downloaded CSV for {event}")
    return response
