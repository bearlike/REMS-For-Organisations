from __future__ import annotations

"""Simple email sending helper."""

import smtplib
from email.mime.text import MIMEText

from typing import Any

from flask import render_template

from ..config import docker_secrets


CONFIG = docker_secrets.CONFIG


def send_mail(recipient: str, subject: str, body: str) -> None:
    """Send an email using the configured SMTP server."""
    msg = MIMEText(body, "html")
    msg["Subject"] = subject
    msg["From"] = CONFIG.mailerUname
    msg["To"] = recipient

    with smtplib.SMTP_SSL(CONFIG.mailerHostname, 465) as server:
        server.login(CONFIG.mailerUname, CONFIG.mailerPassword)
        server.sendmail(CONFIG.mailerUname, [recipient], msg.as_string())


def build_mail(template: str, **context: Any) -> str:
    """Render an email template and return the resulting HTML."""
    return render_template(template, **context)
