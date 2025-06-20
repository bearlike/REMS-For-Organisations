from __future__ import annotations

"""Settings placeholder routes."""

from flask import Blueprint, render_template

settings_bp = Blueprint("settings", __name__, url_prefix="/settings")


@settings_bp.route("/")
def page_not_found() -> tuple[str, int]:
    """Return a placeholder 404 page."""
    return render_template("errors/404.html"), 404
