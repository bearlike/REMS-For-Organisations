"""Public-facing routes for the Flask application."""

from flask import Blueprint, render_template, request

public_bp = Blueprint("public", __name__)


@public_bp.route("/")
def home():
    """Render the landing page for certificate search."""
    event_query = request.args.get("event")
    event_not_found = bool(request.args) and not event_query
    return render_template("home.html", event_not_found=event_not_found)
