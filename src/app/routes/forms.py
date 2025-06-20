"""Form generation and registration routes."""

from __future__ import annotations

import csv
import io

from flask import (
    render_template,
    Blueprint,
    redirect,
    Response,
    request,
    url_for,
    g,
)
from sqlalchemy import text
from sqlalchemy.exc import SQLAlchemyError

from .. import db
from ..utils.auth import login_required
from ..utils.helpers import log_activity, is_admin, sanitize_identifier
from ..utils.pagination import Pagination
from ..schemas import FormGeneratorSchema


forms_bp = Blueprint("forms", __name__)


@forms_bp.route("/entry", methods=["POST"])
def submit_entry():
    """Handle event registration form submissions."""
    form_data = request.form.to_dict()
    event_name = form_data.pop("event_name", None)
    if not event_name:
        return redirect(url_for("public.bad_request"))

    table = f"event_{sanitize_identifier(event_name)}"
    columns = ", ".join(f"`{k}`" for k in form_data.keys())
    placeholders = ", ".join(f":{k}" for k in form_data.keys())
    sql = text(f"INSERT INTO {table} ({columns}) VALUES ({placeholders})")
    engine = db.get_engine(bind="forms")
    try:
        with engine.begin() as conn:
            conn.execute(sql, form_data)
    except SQLAlchemyError:
        return redirect(url_for("public.bad_request"))

    return redirect(url_for("public.congrats"))


@forms_bp.route("/forms/generator", methods=["GET", "POST"])
@login_required
def generator() -> str:
    """Create a new registration form table and list existing ones."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    engine = db.get_engine(bind="forms")
    error = None
    success = False

    if request.method == "POST":
        form = FormGeneratorSchema(
            event_name=request.form.get("event_name", ""),
            event_description=request.form.get("event_description", ""),
            event_type=request.form.get("event_type", "individual"),
            number_participants=int(request.form.get("number_participants", 1)),
            fields=request.form.getlist("fields[]"),
        )

        if not form.event_name.strip():
            error = "Event name required"
        else:
            table = f"event_{sanitize_identifier(form.event_name)}"
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
                with engine.begin() as conn:
                    conn.execute(sql)
            except SQLAlchemyError:
                error = "Database error"
            else:
                log_activity(g.user, f"Generated form table {table}")
                success = True

    with engine.connect() as conn:
        tables = [row[0] for row in conn.execute(text("SHOW TABLES LIKE 'event_%'"))]

    events = [
        {
            "name": tbl[6:].replace("_", " "),
            "slug": tbl[6:],
        }
        for tbl in tables
    ]

    return render_template(
        "forms/generator.html",
        error=error,
        success=success,
        events=events,
    )


@forms_bp.route("/forms/register/<event>")
def register_form(event: str) -> str:
    """Render a registration form for the given event table."""
    table = f"event_{sanitize_identifier(event)}"
    engine = db.get_engine(bind="forms")
    with engine.connect() as conn:
        tables = [row[0] for row in conn.execute(text("SHOW TABLES LIKE 'event_%'"))]
        if table not in tables:
            return redirect(url_for("public.bad_request"))
        columns = [row[0] for row in conn.execute(text(f"SHOW COLUMNS FROM {table}"))]

    fields = [c for c in columns if c not in ("id", "timestamp")]

    grouped: dict[int, list[str]] = {}
    for f in fields:
        base = f.rstrip("0123456789")
        suffix = f[len(base):]
        idx = int(suffix) if suffix.isdigit() else 0
        grouped.setdefault(idx, []).append(base)

    order = [idx for idx in sorted(grouped)]
    groups = [{"index": idx, "fields": grouped[idx]} for idx in order]

    return render_template(
        "forms/register_form.html", event=event, groups=groups
    )


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
            columns = [
                row[0] for row in conn.execute(text(f"SHOW COLUMNS FROM {event}"))
            ]
            total = conn.execute(text(f"SELECT count(*) FROM {event}")).scalar() or 0
            offset = pagination.offset
            # Use row._mapping to convert SQLAlchemy Row to dict and avoid TypeError
            result = conn.execute(
                text(f"SELECT * FROM {event} ORDER BY id LIMIT :limit OFFSET :offset"),
                {"limit": pagination.per_page, "offset": offset},
            )
            rows = [dict(r._mapping) for r in result]

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
