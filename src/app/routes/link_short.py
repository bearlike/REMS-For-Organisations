from __future__ import annotations

"""URL shortening routes."""

import requests
from flask import Blueprint, render_template, request, redirect, url_for, g

from ..utils.auth import login_required
from ..utils.helpers import log_activity, is_admin
from ..schemas import ShortenURLForm

link_short_bp = Blueprint("link_short", __name__, url_prefix="/short")


@link_short_bp.route("/", methods=["GET", "POST"])
@login_required
def create_short_url() -> str:
    """Create a short URL using an external API."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    short_url: str | None = None
    if request.method == "POST":
        form = ShortenURLForm(url=request.form.get("url", ""))
        if form.url:
            try:
                resp = requests.get(
                    "https://tinyurl.com/api-create.php", params={"url": form.url}, timeout=5
                )
                resp.raise_for_status()
                short_url = resp.text
                log_activity(g.user, f"Shortened URL {form.url}")
            except Exception:
                return render_template("link_short.html", error="Failed to shorten URL")
    return render_template("link_short.html", short_url=short_url)
