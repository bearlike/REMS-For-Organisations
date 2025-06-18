"""Authentication and password management routes."""

from __future__ import annotations

from flask import Blueprint, render_template, request, redirect, url_for, session
from sqlalchemy import text
from sqlalchemy.exc import SQLAlchemyError

# pylint: disable=wrong-import-position
from .. import db
from ..models import Login
from ..schemas import LoginForm, ChangePasswordForm
from ..utils.auth import login_required
from ..utils.helpers import log_activity
from ..utils.email import send_mail, build_mail

# pylint: disable=relative-beyond-top-level
from ...config import docker_secrets


auth_bp = Blueprint("auth", __name__, url_prefix="/auth")


@auth_bp.route("/login", methods=["GET", "POST"])
def login() -> str:
    """Render the login form and authenticate the user."""
    if request.method == "POST":
        form = LoginForm(
            username=request.form.get("uname", ""),
            password=request.form.get("password", ""),
            remember=bool(request.form.get("remember")),
        )
        user = Login.query.filter_by(
            LoginName=form.username, PasswordHash=form.password
        ).first()
        if user:
            session["user"] = form.username
            session["remember"] = form.remember
            log_activity(form.username, f"Logged in from {request.remote_addr}")
            return redirect(url_for("dashboard.index"))
        return render_template("login.html", error="Invalid credentials")
    if session.get("user"):
        return redirect(url_for("dashboard.index"))
    return render_template("login.html")


@auth_bp.route("/logout")
@login_required
def logout() -> str:
    """Clear the current session."""
    session.clear()
    return redirect(url_for("auth.login"))


@auth_bp.route("/change-password/<string:token>", methods=["GET", "POST"])
def change_password(token: str) -> str:
    """Allow a user to reset their password using a token."""
    diff = db.session.execute(
        text("SELECT PasswordLinkVerification(:gen) AS diff"), {"gen": token}
    ).scalar()
    if diff is None or diff >= 1800:
        db.session.execute(
            text("DELETE FROM forgot_password WHERE gen_key=:gen"), {"gen": token}
        )
        db.session.commit()
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
        except SQLAlchemyError:
            db.session.rollback()
            return render_template(
                "change_password.html", token=token, error="Database error"
            )
        log_activity(username, "Password changed")
        return render_template("change_password.html", token=token, success=True)

    return render_template("change_password.html", token=token)


@auth_bp.route("/forgot-password", methods=["GET", "POST"])
def forgot_password() -> str:
    """Handle password reset requests."""
    if request.method == "POST":
        email = request.form.get("email", "")
        if not email:
            return render_template("forgot_password.html", error="Email required")

        exists = (
            db.session.execute(
                text("SELECT count(1) FROM login WHERE Email=:email"),
                {"email": email},
            ).scalar()
            or 0
        )
        if not exists:
            return render_template(
                "forgot_password.html", error="Account does not exist"
            )

        key = db.session.execute(
            text("SELECT ForgotPasswordHash(:email) AS val"), {"email": email}
        ).scalar()
        cfg = docker_secrets.CONFIG
        link = f"{cfg.hostName}{cfg.forgotPwdExtension}?gen={key}"
        body = build_mail(
            "email/forgot_password.html",
            button_url=link,
            email_reach=cfg.reachEmail,
        )
        try:
            send_mail(email, "Request For Password - Reg", body)
        except Exception:
            return render_template("forgot_password.html", error="Failed to send email")
        log_activity(email, "Requested password reset link")
        return render_template("forgot_password.html", success=True)

    return render_template("forgot_password.html")
