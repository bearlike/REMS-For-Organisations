from __future__ import annotations

"""Member dashboard routes."""

from flask import Blueprint, render_template
from sqlalchemy import text
from sqlalchemy.exc import SQLAlchemyError

from .. import db
from ..models import Event
from ..utils.auth import login_required


dashboard_bp = Blueprint("dashboard", __name__, url_prefix="/dashboard")


@dashboard_bp.route("/")
@login_required
def index() -> str:
    """Display statistics for members."""
    events_count = Event.query.count()
    latest_event = Event.query.order_by(Event.date.desc()).first()
    registration_count = 0
    if latest_event:
        table_name = f"event_{latest_event.event_name.replace(' ', '_')}"
        try:
            registration_count = db.session.execute(text(f"SELECT count(*) FROM {table_name}")).scalar()
        except SQLAlchemyError:
            registration_count = 0
    return render_template(
        "dashboard.html", registration_count=registration_count, events_count=events_count
    )
