from flask import Flask
from flask_sqlalchemy import SQLAlchemy

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
        SQLALCHEMY_TRACK_MODIFICATIONS=False,
        SECRET_KEY="change-this-key",
    )

    db.init_app(app)

    from .routes.public import public_bp
    app.register_blueprint(public_bp)

    return app
