"""Database models used by the application."""

from __future__ import annotations

from datetime import date
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy.orm import Mapped, mapped_column

from . import db


class Certificate(db.Model):
    """Represents a generated certificate."""

    __tablename__ = "certificates"

    id: Mapped[int] = mapped_column(primary_key=True)
    name: Mapped[str]
    regno: Mapped[str] | None
    dept: Mapped[str] | None
    year: Mapped[str] | None
    section: Mapped[str] | None
    position: Mapped[str] | None
    cert_link: Mapped[str]
    event_name: Mapped[str]


class Event(db.Model):
    """Represents an event."""

    __tablename__ = "events"

    id: Mapped[int] = mapped_column(primary_key=True)
    event_name: Mapped[str]
    date: Mapped[date]
    isInter: Mapped[bool]
