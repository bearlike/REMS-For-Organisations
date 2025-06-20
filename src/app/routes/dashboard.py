from __future__ import annotations

"""Member dashboard routes."""

from flask import Blueprint, render_template, g
from sqlalchemy import text
from sqlalchemy.exc import SQLAlchemyError

from .. import db
from ..models import Event, Login
from ..utils.auth import login_required
from ..utils.helpers import is_admin


dashboard_bp = Blueprint("dashboard", __name__, url_prefix="/dashboard")


@dashboard_bp.route("/")
@login_required
def index() -> str:
    """Display statistics for members."""
    events_count = Event.query.count()
    latest_event = Event.query.order_by(Event.date.desc()).first()
    registration_count = 0
    latest_event_name = ""
    if latest_event:
        latest_event_name = latest_event.event_name
        table_name = f"event_{latest_event.event_name.replace(' ', '_')}"
        try:
            registration_count = db.session.execute(text(f"SELECT count(*) FROM {table_name}")).scalar()
        except SQLAlchemyError:
            registration_count = 0

    members_count = Login.query.count()
    return render_template(
        "dashboard.html",
        registration_count=registration_count,
        events_count=events_count,
        latest_event=latest_event_name,
        members_count=members_count,
        is_admin=is_admin(g.user),
    )
