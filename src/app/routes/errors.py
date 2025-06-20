from __future__ import annotations

"""Application error handlers."""

from flask import Blueprint, render_template

errors_bp = Blueprint("errors", __name__)


@errors_bp.app_errorhandler(404)
def not_found(_: Exception):
    """Render the 404 page."""
    return render_template("errors/404.html"), 404


@errors_bp.app_errorhandler(500)
def server_error(err_msg: Exception):
    """Render the database error page."""
    return render_template("errors/db_error.html", error_message=str(err_msg)), 500
