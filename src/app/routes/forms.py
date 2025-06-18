"""Form submission routes."""

from __future__ import annotations

from flask import Blueprint, redirect, request, url_for
from sqlalchemy import text
from sqlalchemy.exc import SQLAlchemyError

from .. import db

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
