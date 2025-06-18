from __future__ import annotations

"""Routes for viewing activity logs."""

from flask import Blueprint, render_template, request, g
from sqlalchemy import func

from ..models import LogEntry
from ..utils.pagination import Pagination
from ..utils.auth import login_required

logs_bp = Blueprint("logs", __name__, url_prefix="/logs")


@logs_bp.route("/")
@login_required
def list_logs():
    """List activity logs for the logged-in user."""
    page = int(request.args.get("page", 1))
    per_page = int(request.args.get("perPage", 10))
    pagination = Pagination(page, per_page)

    base = LogEntry.query.filter_by(userid=g.user)
    total = base.count()
    results = (
        base.order_by(LogEntry.id.desc())
        .offset(pagination.offset)
        .limit(pagination.per_page)
        .all()
    )
    return render_template(
        "logs.html", logs=results, pagination=pagination, total=total
    )

