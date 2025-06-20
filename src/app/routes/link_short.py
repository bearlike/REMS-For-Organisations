#!/usr/bin/env python3
"""URL shortening routes."""
from __future__ import annotations

import json
import requests
from flask import Blueprint, render_template, request, redirect, url_for, g

from ..utils.auth import login_required
from ..utils.helpers import log_activity, is_admin
from ..utils.logger import logger
from ...config.docker_secrets import CONFIG

link_short_bp = Blueprint("link_short", __name__, url_prefix="/short")


@link_short_bp.route("/", methods=["GET", "POST"])
@login_required
def create_short_url() -> str:
    """Create a short URL using Short.cm API."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    short_url: str | None = None
    error: str | None = None

    logger.debug("Short URL request by %s", g.user)

    if request.method == "POST":
        original_url = request.form.get("url", "")
        url_path = request.form.get("path", "")

        if original_url:
            try:
                # Prepare the payload for Short.cm API
                payload = {"originalURL": original_url, "domain": CONFIG.shortcm_domain}

                # Add path if provided
                if url_path.strip():
                    payload["path"] = url_path.strip()

                headers = {
                    "authorization": CONFIG.shortcm_authorization,
                    "content-type": "application/json",
                }

                response = requests.post(
                    "https://api.short.cm/links",
                    data=json.dumps(payload),
                    headers=headers,
                    timeout=30,
                )

                if response.status_code == 200:
                    result = response.json()
                    short_url = result.get("shortURL", "")
                    if short_url:
                        log_activity(
                            g.user,
                            f"In Link-Short, [{original_url}] -> [{short_url}] shortened",
                        )
                        logger.info("Shortened %s to %s", original_url, short_url)
                    else:
                        error = "Failed to get short URL from response"
                        logger.warning("Short.cm API returned no short URL")
                else:
                    error = f"API Error: {response.status_code}"
                    logger.error("Short.cm API error %s", response.status_code)

            except requests.exceptions.RequestException as e:
                error = f"Network error: {str(e)}"
                logger.exception("Network error shortening URL: {}", e)
            except json.JSONDecodeError:
                error = "Invalid response from URL shortening service"
                logger.error("Invalid JSON from Short.cm")
            except Exception as e:
                error = f"Failed to shorten URL: {str(e)}"
                logger.exception("Unexpected error: {}", e)

    return render_template("link_short.html", short_url=short_url, error=error)
