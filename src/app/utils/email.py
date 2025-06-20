#!/usr/bin/env python3
"""Simple email sending helper."""
from __future__ import annotations

from typing import Any

import smtplib
from email.mime.text import MIMEText


from flask import render_template

from ...config import docker_secrets
from .logger import logger


CONFIG = docker_secrets.CONFIG


def send_mail(recipient: str, subject: str, body: str) -> None:
    """Send an email using the configured SMTP server."""
    logger.debug(f"Sending mail to {recipient}")
    msg = MIMEText(body, "html")
    msg["Subject"] = subject
    msg["From"] = CONFIG.mailerUname
    msg["To"] = recipient

    with smtplib.SMTP_SSL(CONFIG.mailerHostname, 465) as server:
        server.login(CONFIG.mailerUname, CONFIG.mailerPassword)
        server.sendmail(CONFIG.mailerUname, [recipient], msg.as_string())
    logger.info(f"Mail sent to {recipient}")


def build_mail(template: str, **context: Any) -> str:
    """Render an email template and return the resulting HTML."""
    logger.trace(f"Building email from template {template}")
    return render_template(template, **context)
