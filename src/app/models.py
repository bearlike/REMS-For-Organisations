"""Database models used by the application."""

from __future__ import annotations

from datetime import date, datetime
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
    email: Mapped[str]
    position: Mapped[str] | None
    cert_link: Mapped[str]
    event_name: Mapped[str]
    college: Mapped[str] | None


class Event(db.Model):
    """Represents an event."""

    __tablename__ = "events"

    id: Mapped[int] = mapped_column(primary_key=True)
    event_name: Mapped[str]
    date: Mapped[date]
    isInter: Mapped[bool]


class LogEntry(db.Model):
    """Logs user activity."""

    __tablename__ = "logging"

    id: Mapped[int] = mapped_column(primary_key=True)
    timestamp: Mapped[datetime]
    userid: Mapped[str]
    log: Mapped[str]


class Login(db.Model):
    """User login information."""

    __tablename__ = "login"

    id: Mapped[int] = mapped_column(primary_key=True)
    LoginName: Mapped[str]
    PasswordHash: Mapped[str]
    Email: Mapped[str]
    FullName: Mapped[str] | None
    IsAdmin: Mapped[bool]
    FirstName: Mapped[str] | None
    LastName: Mapped[str] | None
    Address: Mapped[str] | None
    Phno: Mapped[str] | None
    Signature: Mapped[str] | None
    imgsrc: Mapped[str] | None
