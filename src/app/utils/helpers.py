"""Helper utilities used across the application."""

from __future__ import annotations

import re
from sqlalchemy import func
import re

# pylint: disable=relative-beyond-top-level
from .. import db
from ..models import LogEntry, Login
from .logger import logger


def log_activity(user: str, message: str) -> None:
    """Insert a log entry for the specified user."""
    logger.debug(f"Activity log: {user} - {message}")
    entry = LogEntry(userid=user, log=message)
    db.session.add(entry)
    db.session.commit()


def is_admin(username: str) -> bool:
    """Return whether the given user has admin privileges."""
    count = (
        db.session.query(func.count(Login.id))
        .filter(Login.LoginName == username, Login.IsAdmin == True)
        .scalar()
    )
    is_admin_user = bool(count)
    logger.trace(f"Admin check for {username}: {is_admin_user}")
    return is_admin_user


def sanitize_identifier(name: str) -> str:
    """Return a safe SQL identifier with only alphanumerics and underscores."""
    sanitized = re.sub(r"[^0-9a-zA-Z_]+", "_", name).strip("_")
    logger.debug(f"Sanitized identifier {name} -> {sanitized}")
    return sanitized
