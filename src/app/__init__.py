"""Flask application factory and database setup."""

from flask import Flask
from flask_sqlalchemy import SQLAlchemy
import os
import time

from src.config import docker_secrets


db = SQLAlchemy()


def create_app() -> Flask:
    """Create and configure the Flask application."""
    app = Flask(__name__)
    config = docker_secrets.CONFIG
    app.config.update(
        SQLALCHEMY_DATABASE_URI=(
            f"mysql+pymysql://{config.username}:{config.password}"
            f"@{config.servername}/{config.MainDB}"
        ),
        SQLALCHEMY_BINDS={
            "forms": f"mysql+pymysql://{config.username}:{config.password}@{config.servername}/{config.formDB}",
            "mail": f"mysql+pymysql://{config.username}:{config.password}@{config.servername}/{config.mailerDB}",
        },
        SQLALCHEMY_TRACK_MODIFICATIONS=False,
        SECRET_KEY="change-this-key",
    )

    # Configure default timezone similar to pheader.php
    os.environ.setdefault("TZ", "Asia/Kolkata")
    if hasattr(time, "tzset"):
        time.tzset()

    db.init_app(app)

    from .routes.public import public_bp
    from .routes.forms import forms_bp
    from .routes.logs import logs_bp
    from .routes.certificates import cert_bp
    from .routes.auth import auth_bp
    from .routes.dashboard import dashboard_bp
    from .routes.db import db_bp
    from .routes.mailing import mailing_bp

    app.register_blueprint(public_bp)
    app.register_blueprint(forms_bp)
    app.register_blueprint(logs_bp)
    app.register_blueprint(cert_bp)
    app.register_blueprint(auth_bp)
    app.register_blueprint(dashboard_bp)
    app.register_blueprint(db_bp)
    app.register_blueprint(mailing_bp)

    return app
