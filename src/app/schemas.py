from __future__ import annotations

"""Pydantic schemas for request parsing."""

from datetime import date
from pydantic import BaseModel, Field


class CertificateCSVRow(BaseModel):
    """Represents a row from the uploaded certificate CSV."""

    name: str
    regno: str | None = None
    dept: str | None = None
    year: int | None = None
    section: str | None = None
    email: str
    position: str | None = None
    event_name: str
    college: str | None = None


class EventMetadata(BaseModel):
    """Metadata for a certificate generation event."""

    event_name: str
    date: date = Field(..., alias="event_date")
    is_inter: bool = False

