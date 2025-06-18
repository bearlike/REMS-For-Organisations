from __future__ import annotations

"""Helper utilities used across the application."""

from sqlalchemy import func

from .. import db
from ..models import LogEntry, Login


def log_activity(user: str, message: str) -> None:
    """Insert a log entry for the specified user."""
    entry = LogEntry(userid=user, log=message)
    db.session.add(entry)
    db.session.commit()


def is_admin(username: str) -> bool:
    """Return whether the given user has admin privileges."""
    count = db.session.query(func.count(Login.id)).filter(
        Login.LoginName == username, Login.IsAdmin == True
    ).scalar()
    return bool(count)

