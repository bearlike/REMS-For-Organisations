"""Public-facing routes for the Flask application."""

from flask import Blueprint, render_template, request, redirect, url_for
from sqlalchemy import func

from ..models import Certificate, Event
from ..utils.pagination import Pagination
from ..utils.logger import logger
from .. import db

public_bp = Blueprint("public", __name__)


@public_bp.route("/")
def home():
    """Render the landing page for certificate search."""
    event_query = request.args.get("event")
    status = request.args.get("status")
    searched_event = request.args.get("searched_event")

    logger.debug(f"Home page accessed with event={event_query} status={status}")

    # Handle different error states
    event_not_found = False
    error_message = ""

    if status == "notfound":
        event_not_found = True
        if searched_event:
            error_message = f"Event '{searched_event}' not found. Are you sure you're spelling it right?"
            logger.warning(f"Event not found: {searched_event}")
        else:
            error_message = "Event not found. Please enter a valid event name."
    elif event_query and not status:
        # Event was provided but we're back at home, likely means it was found and redirected to cds-public
        return redirect(url_for("public.cds_public", event=event_query))
    elif request.args and not event_query and not status:
        event_not_found = True
        error_message = "Please enter an event name to search."

    return render_template(
        "home.html", event_not_found=event_not_found, error_message=error_message
    )


@public_bp.route("/cds-public")
def cds_public():
    """Display certificates or events based on query parameters."""
    page = int(request.args.get("page", 1))
    per_page = int(request.args.get("perPage", 10))
    pagination = Pagination(page, per_page)

    logger.debug(f"cds_public accessed: {request.args}")

    if "mode" in request.args:
        total = db.session.query(func.count(Event.id)).scalar()
        pagination.set_total_pages(total)
        events = (
            Event.query.order_by(Event.date)
            .offset(pagination.offset)
            .limit(pagination.per_page)
            .all()
        )
        logger.info(f"Listing events page {page}")
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
        return redirect(url_for("public.home", status="notfound", error="missing"))

    event_exists = Event.query.filter_by(event_name=event).first()
    if not event_exists:
        logger.warning(f"Attempt to access unknown event {event}")
        return redirect(url_for("public.home", status="notfound", searched_event=event))

    is_inter = db.session.query(Event.isInter).filter_by(event_name=event).scalar()
    search = request.args.get("search", "")
    base_query = Certificate.query.filter_by(event_name=event)
    if search:
        base_query = base_query.filter(Certificate.name.ilike(f"%{search}%"))

    total = base_query.count()
    pagination.set_total_pages(total)
    results = (
        base_query.order_by(Certificate.name)
        .offset(pagination.offset)
        .limit(pagination.per_page)
        .all()
    )
    logger.info(f"Listing certificates for {event} page {page}")

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
