from __future__ import annotations

"""Generic database management routes."""

from flask import Blueprint, render_template, request, redirect, url_for, g
from sqlalchemy import text
from sqlalchemy.exc import SQLAlchemyError

from .. import db
from ..utils.auth import login_required
from ..utils.helpers import log_activity, is_admin
from ..utils.logger import logger
from ...config import docker_secrets


db_bp = Blueprint("db", __name__, url_prefix="/db")


# Helper --------------------------------------------------------------------

def _get_bind(db_code: str) -> tuple[str | None, str]:
    """Return the SQLAlchemy bind key and database name for the given code."""
    cfg = docker_secrets.CONFIG
    if db_code == "2":
        return "forms", cfg.formDB
    if db_code == "3":
        return "mail", cfg.mailerDB
    return None, cfg.MainDB


# Routes --------------------------------------------------------------------

@db_bp.route("/manage")
@login_required
def manage() -> str:
    """Render a simple interface for browsing database tables."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    db_code = request.args.get("db", "1")
    table = request.args.get("table")
    page = int(request.args.get("page", 1))
    per_page = int(request.args.get("perPage", 10))

    logger.info("DB manage view by %s: db=%s table=%s", g.user, db_code, table)

    bind_key, db_name = _get_bind(db_code)
    engine = db.get_engine(bind=bind_key)

    with engine.connect() as conn:
        tables = [row[0] for row in conn.execute(text(f"SHOW TABLES FROM {db_name}"))]

        rows = []
        columns = []
        total_pages = 1
        total_rows = 0
        if table:
            columns = [row[0] for row in conn.execute(text(f"SHOW COLUMNS FROM {table}"))]
            total_rows = conn.execute(text(f"SELECT count(*) FROM {table}")).scalar() or 0
            total_pages = max(1, (total_rows + per_page - 1) // per_page)
            offset = per_page * (page - 1)
            sql = text(f"SELECT * FROM {table} ORDER BY id LIMIT :limit OFFSET :offset")
            rows = conn.execute(sql, {"limit": per_page, "offset": offset}).mappings().all()

    return render_template(
        "db/manage.html",
        db_code=db_code,
        table=table,
        tables=tables,
        rows=rows,
        columns=columns,
        page=page,
        per_page=per_page,
        total_pages=total_pages,
        total_rows=total_rows,
    )


@db_bp.route("/insert", methods=["POST"])
@login_required
def insert_row() -> str:
    """Insert a row into the selected table."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    db_code = request.form.get("db", "1")
    table = request.form.get("table")
    data = {k: v for k, v in request.form.items() if k not in {"db", "table"}}

    bind_key, _ = _get_bind(db_code)
    engine = db.get_engine(bind=bind_key)

    columns = ", ".join(f"`{k}`" for k in data)
    placeholders = ", ".join(f":{k}" for k in data)
    sql = text(f"INSERT INTO {table} ({columns}) VALUES ({placeholders})")

    try:
        with engine.begin() as conn:
            conn.execute(sql, data)
    except SQLAlchemyError as exc:
        logger.exception("Insert failed: {}", exc)
        return redirect(url_for("db.manage", db=db_code, table=table))

    log_activity(g.user, f"Inserted row in table {table} of database {db_code}")
    logger.info("Row inserted into %s by %s", table, g.user)
    return redirect(url_for("db.manage", db=db_code, table=table))


@db_bp.route("/delete")
@login_required
def delete_row() -> str:
    """Delete a row from the selected table."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    db_code = request.args.get("db", "1")
    table = request.args.get("table")
    row_id = request.args.get("id")

    bind_key, _ = _get_bind(db_code)
    engine = db.get_engine(bind=bind_key)

    sql = text(f"DELETE FROM {table} WHERE id=:id")
    try:
        with engine.begin() as conn:
            conn.execute(sql, {"id": row_id})
    except SQLAlchemyError as exc:
        logger.exception("Delete failed: {}", exc)
        return redirect(url_for("db.manage", db=db_code, table=table))

    log_activity(g.user, f"Removed id={row_id} from {table} of database {db_code}")
    logger.info("Row %s deleted from %s by %s", row_id, table, g.user)
    return redirect(url_for("db.manage", db=db_code, table=table))


@db_bp.route("/modify/<int:row_id>", methods=["GET", "POST"])
@login_required
def modify_row(row_id: int) -> str:
    """Display and update a row in the selected table."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    db_code = request.values.get("db", "1")
    table = request.values.get("table")
    bind_key, _ = _get_bind(db_code)
    engine = db.get_engine(bind=bind_key)

    if request.method == "POST":
        data = {k: v for k, v in request.form.items() if k not in {"db", "table", "id"}}
        assignments = ", ".join(f"`{k}`=:{k}" for k in data)
        sql = text(f"UPDATE {table} SET {assignments} WHERE id=:id")
        data["id"] = row_id
        try:
            with engine.begin() as conn:
                conn.execute(sql, data)
        except SQLAlchemyError as exc:
            logger.exception("Update failed: {}", exc)
            return redirect(url_for("db.modify_row", row_id=row_id, db=db_code, table=table))
        log_activity(g.user, f"Modified id={row_id} in {table} of database {db_code}")
        logger.info("Row %s updated in %s by %s", row_id, table, g.user)
        return redirect(url_for("db.manage", db=db_code, table=table))

    with engine.connect() as conn:
        row = conn.execute(
            text(f"SELECT * FROM {table} WHERE id=:id"), {"id": row_id}
        ).mappings().first()
        if not row:
            return redirect(url_for("db.manage", db=db_code, table=table))
        columns = list(row.keys())

    return render_template(
        "db/modify.html",
        row=row,
        columns=columns,
        db_code=db_code,
        table=table,
    )


@db_bp.route("/push-alert", methods=["POST"])
@login_required
def push_alert() -> str:
    """Insert a dashboard alert for the current user."""
    bind_key, _ = _get_bind("1")  # Main database
    engine = db.get_engine(bind=bind_key)

    sql = text(
        "INSERT INTO notification (`user`, `message`, `type`, `clickURL`) "
        "VALUES (:user, :message, :type, :clickURL)"
    )
    data = {
        "user": g.user,
        "message": request.form.get("alertMessage", ""),
        "type": request.form.get("type", "info"),
        "clickURL": "#",
    }
    try:
        with engine.begin() as conn:
            conn.execute(sql, data)
    except SQLAlchemyError as exc:
        logger.exception("Failed to push alert: {}", exc)
        return redirect(url_for("dashboard.index"))

    log_activity(g.user, "Pushed dashboard alert")
    logger.info("Dashboard alert pushed by %s", g.user)
    return redirect(url_for("dashboard.index"))
