#!/usr/bin/env python3
from __future__ import annotations

import csv
import io

from flask import Blueprint, render_template, request, redirect, url_for, g
from sqlalchemy import text
from sqlalchemy.exc import SQLAlchemyError

from .. import db
from ..utils.auth import login_required
from ..utils.helpers import log_activity, is_admin, sanitize_identifier
from ..utils.email import send_mail, build_mail
from ..schemas import BulkMailForm
from ...config import docker_secrets

mailing_bp = Blueprint("mailing", __name__, url_prefix="/mailing")


@mailing_bp.route("/list", methods=["GET", "POST"])
@login_required
def list_manager() -> str:
    """Create a new mailing list from an uploaded CSV."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    if request.method == "POST":
        file = request.files.get("file")
        name = request.form.get("mailer_name", "").strip()
        if not file or not name:
            return render_template("mailing/list.html", error="Missing data")
        table = sanitize_identifier(name)
        engine = db.get_engine(bind="mail")
        create_sql = text(
            f"CREATE TABLE IF NOT EXISTS `{table}` "
            "(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), email VARCHAR(255))"
        )
        try:
            with engine.begin() as conn:
                conn.execute(create_sql)
                stream = io.StringIO(file.stream.read().decode())
                reader = csv.reader(stream)
                next(reader, None)
                for row in reader:
                    if len(row) < 2:
                        continue
                    conn.execute(
                        text(
                            f"INSERT INTO `{table}` (name, email) VALUES (:name, :email)"
                        ),
                        {"name": row[0].strip(), "email": row[1].strip()},
                    )
        except SQLAlchemyError:
            return render_template("mailing/list.html", error="Database error")
        log_activity(g.user, f"Created mailing list {table}")
        return render_template("mailing/list.html", success=True)

    return render_template("mailing/list.html")


@mailing_bp.route("/bulk", methods=["GET", "POST"])
@login_required
def bulk_mail() -> str:
    """Send a bulk email to recipients in the chosen list."""
    if not is_admin(g.user):
        return redirect(url_for("public.bad_request"))

    cfg = docker_secrets.CONFIG
    engine = db.get_engine(bind="mail")
    with engine.connect() as conn:
        lists = [
            row[0] for row in conn.execute(text(f"SHOW TABLES FROM {cfg.mailerDB}"))
        ]

    if request.method == "POST":
        form = BulkMailForm(
            mailing_list=request.form.get("mailing_list", ""),
            mail_subject=request.form.get("mail_subject", ""),
            mail_title=request.form.get("mail_title", ""),
            mail_button_label=request.form.get("mail_button_label", cfg.buttonLabel),
            mail_button_url=request.form.get("mail_button_url", cfg.buttonURL),
            mail_logo_url=request.form.get("mail_logo_url", cfg.logoURL),
            mail_coverimg_url=request.form.get("mail_coverimg_url", cfg.coverURL),
            mail_body=request.form.get("mail_body", ""),
        )
        if not form.mailing_list or not form.mail_subject:
            return render_template(
                "mailing/bulk.html", mailing_lists=lists, cfg=cfg, error="Missing data"
            )
        content = build_mail(
            "email/basic_mail.html",
            title=form.mail_title,
            body=form.mail_body,
            button_url=form.mail_button_url,
            button_label=form.mail_button_label,
            logo_url=form.mail_logo_url,
            cover_img_url=form.mail_coverimg_url,
        )
        try:
            list_name = sanitize_identifier(form.mailing_list)
            with engine.connect() as conn:
                recipients = [
                    r["email"]
                    for r in conn.execute(
                        text(f"SELECT email FROM `{list_name}`")
                    ).mappings()
                ]
            for recipient in recipients:
                send_mail(recipient, form.mail_subject, content)
        except Exception:
            return render_template(
                "mailing/bulk.html",
                mailing_lists=lists,
                cfg=cfg,
                error="Failed to send emails",
            )
        log_activity(g.user, f"Sent bulk mail to list {list_name}")
        return render_template(
            "mailing/bulk.html", mailing_lists=lists, cfg=cfg, success=True
        )

    return render_template("mailing/bulk.html", mailing_lists=lists, cfg=cfg)
