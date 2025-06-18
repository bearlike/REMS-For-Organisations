"""Public-facing routes for the Flask application."""

from flask import Blueprint, render_template, request, redirect, url_for
from sqlalchemy import func

from ..models import Certificate, Event
from ..utils.pagination import Pagination
from .. import db

public_bp = Blueprint("public", __name__)


@public_bp.route("/")
def home():
    """Render the landing page for certificate search."""
    event_query = request.args.get("event")
    event_not_found = bool(request.args) and not event_query
    return render_template("home.html", event_not_found=event_not_found)


@public_bp.route("/cds-public")
def cds_public():
    """Display certificates or events based on query parameters."""
    page = int(request.args.get("page", 1))
    per_page = int(request.args.get("perPage", 10))
    pagination = Pagination(page, per_page)

    if "mode" in request.args:
        total = db.session.query(func.count(Event.id)).scalar()
        events = (
            Event.query.order_by(Event.date)
            .offset(pagination.offset)
            .limit(pagination.per_page)
            .all()
        )
        return render_template(
            "cds_public.html",
            mode=True,
            results=events,
            pagination=pagination,
            total=total,
            event="Events Details",
        )

    event = request.args.get("event", "").lower()
    if not event:
        return redirect(url_for("public.home", status="notfound"))

    if not Event.query.filter_by(event_name=event).first():
        return redirect(url_for("public.home", status="notfound"))

    is_inter = db.session.query(Event.isInter).filter_by(event_name=event).scalar()
    search = request.args.get("search", "")
    base_query = Certificate.query.filter_by(event_name=event)
    if search:
        base_query = base_query.filter(Certificate.name.ilike(f"%{search}%"))

    total = base_query.count()
    results = (
        base_query.order_by(Certificate.name)
        .offset(pagination.offset)
        .limit(pagination.per_page)
        .all()
    )

    return render_template(
        "cds_public.html",
        mode=False,
        results=results,
        is_inter=is_inter,
        pagination=pagination,
        total=total,
        event=event,
    )


@public_bp.route("/congrats")
def congrats():
    """Render the congratulations page."""
    return render_template("congrats.html")


@public_bp.route("/bad-request")
def bad_request():
    """Render a generic bad request page."""
    return render_template("bad_request.html"), 400
