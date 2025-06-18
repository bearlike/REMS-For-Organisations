"""Configuration for Docker environment.

This module provides a dataclass for storing configuration
values loaded from environment variables. It mimics the values
previously defined in ``docker/secrets_.php``.
"""

from dataclasses import dataclass
import os

@dataclass
class DockerSecrets:
    """Docker secrets configuration."""

    OrgName: str = "SVCE-ACM"
    servername: str = os.getenv("MYSQL_HOST", "")
    username: str = os.getenv("MYSQL_USER", "")
    password: str = os.getenv("MYSQL_PASSWORD", "")
    MainDB: str = "db_cms"
    formDB: str = "db_forms"
    mailerDB: str = "db_mailer"
    startPath: str = ""

    # Mailer details
    mailerHostname: str = "sample.host.name"
    mailerUname: str = "sample_mail@sample.host.name"
    mailerPassword: str = "sample_mail_password"

    # Default mail template details
    buttonLabel: str = "Click here"
    buttonURL: str = "https://sample.org/"
    logoURL: str = "https://sample.org/logo.png"
    coverURL: str = "https://sample.org/cover.jpg"

    # Forgot Password section details
    hostName: str = "localhost/domain/"
    forgotPwdExtension: str = "members/change-password.php"

    # Forgot Password Mail Template details
    reachEmail: str = "sample@sample.host.name"
    darkLogo: str = "https://sample.logo/path"
    logoHREF: str = "https://sample.org"

    # API Keys
    shortcm_authorization: str = "ABCDEFABCDEFABCDEFABCDEF"
    shortcm_domain: str = "sample.domain"


CONFIG = DockerSecrets()
"""
Access ``CONFIG`` to get pre-loaded configuration values.
"""
