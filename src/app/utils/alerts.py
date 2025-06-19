from __future__ import annotations

"""Helper functions for dashboard alerts."""

from typing import List, Dict
from sqlalchemy import text
from sqlalchemy.exc import SQLAlchemyError

from .. import db
from ..routes.db import _get_bind


def get_recent_alerts(limit: int = 5) -> List[Dict[str, str]]:
    """Return recent dashboard alerts from the database."""
    bind_key, _ = _get_bind("1")
    engine = db.get_engine(bind=bind_key)
    sql = text(
        "SELECT n.user, n.message, n.type, n.clickURL, n.timestamp, l.imgsrc "
        "FROM notification n LEFT JOIN login l ON n.user = l.LoginName "
        "ORDER BY n.timestamp DESC LIMIT :limit"
    )
    alerts: List[Dict[str, str]] = []
    try:
        with engine.begin() as conn:
            for row in conn.execute(sql, {"limit": limit}).mappings():
                alerts.append(
                    {
                        "user": row["user"],
                        "message": row["message"],
                        "type": row["type"],
                        "clickURL": row["clickURL"],
                        "timestamp": str(row["timestamp"]),
                        "imgsrc": row["imgsrc"],
                    }
                )
    except SQLAlchemyError:
        return []
    return alerts
