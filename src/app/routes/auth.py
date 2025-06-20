"""Authentication and password management routes."""

from __future__ import annotations

from flask import Blueprint, render_template, request, redirect, url_for, session
import hashlib
from sqlalchemy import text
from sqlalchemy.exc import SQLAlchemyError

# pylint: disable=wrong-import-position
from .. import db
from ..models import Login
from ..schemas import LoginForm, ChangePasswordForm
from ..utils.auth import login_required
from ..utils.helpers import log_activity
from ..utils.logger import logger
from ..utils.email import send_mail, build_mail

# pylint: disable=relative-beyond-top-level
from ...config import docker_secrets


auth_bp = Blueprint("auth", __name__, url_prefix="/auth")


@auth_bp.route("/login", methods=["GET", "POST"])
def login() -> str:
    """Render the login form and authenticate the user."""
    if request.method == "POST":
        logger.debug(f"Login attempt for user {request.form.get('uname', '')}")
        form = LoginForm(
            username=request.form.get("uname", ""),
            password=request.form.get("password", ""),
            remember=bool(request.form.get("remember")),
        )
        hashed_password = hashlib.sha1(form.password.encode()).hexdigest()
        user = Login.query.filter_by(
            LoginName=form.username, PasswordHash=hashed_password
        ).first()
        if user:
            session["user"] = form.username
            session["remember"] = form.remember
            log_activity(form.username, f"Logged in from {request.remote_addr}")
            logger.info(f"User {form.username} logged in")
            return redirect(url_for("dashboard.index"))
        logger.warning(f"Invalid login attempt for user {form.username}")
        return render_template("login.html", error="Invalid credentials")
    if session.get("user"):
        logger.debug(f"User {session.get('user')} already authenticated")
        return redirect(url_for("dashboard.index"))
    return render_template("login.html")


@auth_bp.route("/logout")
@login_required
def logout() -> str:
    """Clear the current session."""
    user = session.get("user")
    session.clear()
    logger.info(f"User {user} logged out")
    return redirect(url_for("auth.login"))


@auth_bp.route("/change-password/<string:token>", methods=["GET", "POST"])
def change_password(token: str) -> str:
    """Allow a user to reset their password using a token."""
    logger.trace(f"Password reset with token {token}")
    diff = db.session.execute(
        text("SELECT PasswordLinkVerification(:gen) AS diff"), {"gen": token}
    ).scalar()
    if diff is None or diff >= 1800:
        db.session.execute(
            text("DELETE FROM forgot_password WHERE gen_key=:gen"), {"gen": token}
        )
        db.session.commit()
        logger.warning(f"Expired or invalid password reset token {token}")
        return render_template("link_expired.html"), 400

    if request.method == "POST":
        form = ChangePasswordForm(
            pwd=request.form.get("pwd"), pwd_confirm=request.form.get("pwd_confirm")
        )
        if not form.is_valid:
            return render_template(
                "change_password.html", token=token, error="Passwords do not match"
            )
        username = db.session.execute(
            text("SELECT GetUserName(:gen) AS uname"), {"gen": token}
        ).scalar()
        try:
            db.session.execute(
                text("CALL SetPassword(:gen, :password)"),
                {"gen": token, "password": form.pwd},
            )
            db.session.commit()
        except SQLAlchemyError as exc:
            db.session.rollback()
            logger.exception("Failed to change password: {}", exc)
            return render_template(
                "change_password.html", token=token, error="Database error"
            )
        log_activity(username, "Password changed")
        logger.info(f"Password changed for user {username}")
        return render_template("change_password.html", token=token, success=True)

    return render_template("change_password.html", token=token)


@auth_bp.route("/forgot-password", methods=["GET", "POST"])
def forgot_password() -> str:
    """Handle password reset requests."""
    if request.method == "POST":
        email = request.form.get("email", "")
        if not email:
            return render_template("forgot_password.html", error="Email required")

        logger.info(f"Password reset requested for {email}")

        # Check if user exists - exact same query as PHP
        exists = (
            db.session.execute(
                text("SELECT count(1) as isPresent FROM login WHERE Email=:email"),
                {"email": email},
            ).scalar()
            or 0
        )

        if exists:
            # Generate hash key - same function as PHP
            key = db.session.execute(
                text("SELECT ForgotPasswordHash(:email) AS link_value"),
                {"email": email},
            ).scalar()

            cfg = docker_secrets.CONFIG
            link = f"{cfg.hostName}{cfg.forgotPwdExtension}?gen={key}"

            # Build email using the forgot password template
            body = build_mail(
                "email/forgot_password.html",
                button_url=link,
                email_reach=cfg.reachEmail,
                dark_logo=cfg.darkLogo,
                logo_href=cfg.logoHREF,
            )

            try:
                send_mail(email, "Request For Password - Reg", body)
                log_activity(email, "Requested password reset link")
                logger.debug(f"Password reset email sent to {email}")
                return render_template("forgot_password.html", success=True)
            except Exception as e:
                logger.exception("Failed to send password reset email: {}", e)
                return render_template(
                    "forgot_password.html", error=f"Mail error: {str(e)}"
                )
        else:
            # Match exact error message from PHP
            logger.warning(f"Password reset requested for unknown account {email}")
            return render_template(
                "forgot_password.html", error="This account does not exist!"
            )

    return render_template("forgot_password.html")
