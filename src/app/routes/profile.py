from __future__ import annotations

"""User profile management routes."""

from pathlib import Path

from flask import Blueprint, render_template, request, redirect, url_for, g, current_app
from werkzeug.utils import secure_filename

from .. import db
from ..models import Login
from ..utils.auth import login_required
from ..utils.helpers import log_activity
from ..utils.logger import logger
from ..schemas import ProfileUpdateForm

profile_bp = Blueprint("profile", __name__, url_prefix="/profile")

UPLOAD_DIR = Path("assets/img/avatars/users")
UPLOAD_DIR.mkdir(parents=True, exist_ok=True)


@profile_bp.route("/", methods=["GET", "POST"])
@login_required
def view_profile() -> str:
    """Display and update profile information."""
    logger.trace(f"Rendering profile page for {g.user}")
    user = Login.query.filter_by(LoginName=g.user).first()
    if not user:
        return redirect(url_for("public.bad_request"))

    if request.method == "POST":
        form = ProfileUpdateForm(
            email=request.form.get("email"),
            first_name=request.form.get("first_name"),
            last_name=request.form.get("last_name"),
            address=request.form.get("address"),
            phno=request.form.get("phno"),
            signature=request.form.get("signature"),
        )

        # Handle base64 image data
        picture_base64 = request.form.get("picture_base64")
        if picture_base64:
            # Validate that it's a proper base64 image
            if picture_base64.startswith('data:image/'):
                user.imgsrc = picture_base64
                log_activity(g.user, "Updated profile picture (base64)")
            else:
                logger.warning(f"Invalid base64 image data from user {g.user}")

        user.Email = form.email or user.Email
        user.FirstName = form.first_name or user.FirstName
        user.LastName = form.last_name or user.LastName
        user.Address = form.address or user.Address
        user.Phno = form.phno or user.Phno
        user.Signature = form.signature or user.Signature
        db.session.commit()
        logger.info(f"User {g.user} updated profile")
        log_activity(g.user, "Updated profile details")
        return redirect(url_for("profile.view_profile"))

    return render_template("profile.html", user=user)
