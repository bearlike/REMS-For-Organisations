#!/usr/bin/env python3
"""Pydantic schemas for request parsing."""
from __future__ import annotations

from typing import Optional
from datetime import date as date_type
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
    regno: Optional[str] = None
    dept: Optional[str] = None
    year: Optional[int] = None
    section: Optional[str] = None
    email: str
    position: Optional[str] = None
    event_name: str
    college: Optional[str] = None


class EventMetadata(BaseModel):
    """Metadata for a certificate generation event."""

    event_name: str
    date: date_type = Field(..., alias="event_date")
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


class ShortenURLForm(BaseModel):
    """Form input for the URL shortener."""

    url: str


class ProfileUpdateForm(BaseModel):
    """Form data for updating a user profile."""

    email: Optional[str] = None
    first_name: Optional[str] = None
    last_name: Optional[str] = None
    address: Optional[str] = None
    phno: Optional[str] = None
    signature: Optional[str] = None

