from __future__ import annotations

"""Pydantic schemas for request parsing."""

from datetime import date
from pydantic import BaseModel, Field


class LoginForm(BaseModel):
    """Form values for user login."""

    username: str
    password: str
    remember: bool = False


class ChangePasswordForm(BaseModel):
    """Password reset submission."""

    pwd: str
    pwd_confirm: str

    @property
    def is_valid(self) -> bool:
        """Return ``True`` if the passwords match."""
        return self.pwd == self.pwd_confirm


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


class FormGeneratorSchema(BaseModel):
    """Input for creating a registration form."""

    event_name: str
    event_description: str = ""
    event_type: str = "individual"
    number_participants: int = 1
    fields: list[str] = Field(default_factory=list)


class BulkMailForm(BaseModel):
    """Input for sending bulk mail."""

    mailing_list: str
    mail_subject: str
    mail_title: str
    mail_button_label: str
    mail_button_url: str
    mail_logo_url: str
    mail_coverimg_url: str
    mail_body: str

