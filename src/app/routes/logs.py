"""Routes for viewing activity logs."""

from __future__ import annotations
from flask import Blueprint, render_template, request, g

from ..models import LogEntry
from ..utils.auth import login_required

logs_bp = Blueprint("logs", __name__, url_prefix="/logs")


@logs_bp.route("/")
@login_required
def list_logs():
    """List activity logs for the logged-in user."""
    page = int(request.args.get("page", 1))
    per_page = int(request.args.get("perPage", 10))

    base = LogEntry.query.filter_by(userid=g.user)
    total_logs = base.count()
    total_pages = (
        max(1, (total_logs + per_page - 1) // per_page) if total_logs > 0 else 1
    )

    # Ensure page is within valid range
    page = max(1, min(page, total_pages))
    offset = per_page * (page - 1)

    logs = base.order_by(LogEntry.id.desc()).offset(offset).limit(per_page).all()

    return render_template(
        "logs.html",
        logs=logs,
        page=page,
        per_page=per_page,
        total_pages=total_pages,
        total_logs=total_logs,
    )
