"""Database models used by the application."""

from datetime import date, datetime
from typing import Optional
from sqlalchemy.orm import Mapped, mapped_column

from . import db


class Certificate(db.Model):
    """Represents a generated certificate."""

    __tablename__ = "certificates"

    id: Mapped[int] = mapped_column(primary_key=True)
    name: Mapped[str]
    regno: Mapped[Optional[str]]
    dept: Mapped[Optional[str]]
    year: Mapped[Optional[int]]
    section: Mapped[Optional[str]]
    email: Mapped[str]
    position: Mapped[Optional[str]]
    cert_link: Mapped[str]
    event_name: Mapped[str]
    college: Mapped[Optional[str]]


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
    timestamp: Mapped[datetime] = mapped_column(
        server_default=db.func.current_timestamp(),
        server_onupdate=db.func.current_timestamp(),
    )
    userid: Mapped[str]
    log: Mapped[str]


class Login(db.Model):
    """User login information."""

    __tablename__ = "login"

    id: Mapped[int] = mapped_column(primary_key=True)
    LoginName: Mapped[str]
    PasswordHash: Mapped[str]
    Email: Mapped[str]
    FullName: Mapped[Optional[str]]
    IsAdmin: Mapped[bool]
    FirstName: Mapped[Optional[str]]
    LastName: Mapped[Optional[str]]
    Address: Mapped[Optional[str]]
    Phno: Mapped[Optional[str]]
    Signature: Mapped[Optional[str]]
    imgsrc: Mapped[Optional[str]]


class Notification(db.Model):
    """Dashboard alert notification."""

    __tablename__ = "notification"

    id: Mapped[int] = mapped_column(primary_key=True)
    timestamp: Mapped[datetime] = mapped_column(
        server_default=db.func.current_timestamp()
    )
    user: Mapped[str]
    message: Mapped[str]
    type: Mapped[str]
    clickURL: Mapped[str]
