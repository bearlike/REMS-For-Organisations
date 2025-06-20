"""Flask application factory and database setup."""

import os
from flask import Flask, session, url_for

from dotenv import load_dotenv
from flask_sqlalchemy import SQLAlchemy
from loguru import logger


load_dotenv(os.path.join(os.path.dirname(__file__), ".env"))
db = SQLAlchemy()


def create_app() -> Flask:
    """Create and configure the Flask application."""
    app = Flask(__name__)
    app.config.update(
        SQLALCHEMY_DATABASE_URI=os.getenv("MAIN_DB_URI"),
        SQLALCHEMY_BINDS={
            "forms": os.getenv("FORMS_DB_URI"),
            "mail": os.getenv("MAIL_DB_URI"),
        },
        SQLALCHEMY_TRACK_MODIFICATIONS=False,
        SECRET_KEY="change-this-key",
    )

    db.init_app(app)

    @app.context_processor
    def inject_user_data():
        """Inject current user data into all templates."""
        # pylint: disable=import-outside-toplevel, relative-beyond-top-level
        from .models import Login
        from ..config.docker_secrets import CONFIG

        user = session.get("user")
        current_user = None
        profile_pic = url_for("static", filename="assets/img/avatars/image2.png")

        if user:
            try:
                db_user = Login.query.filter_by(LoginName=user).first()
                if db_user:
                    current_user = db_user
                    # Build profile path similar to PHP retProfilePic
                    if db_user.imgsrc:
                        if db_user.imgsrc.startswith("data:"):
                            # Base64 image data
                            profile_pic = db_user.imgsrc
                        else:
                            # Legacy file-based image
                            profile_pic = url_for(
                                "static",
                                filename=(f"assets/img/avatars/users/{db_user.imgsrc}"),
                            )
                            logger.warning(
                                f"Using legacy profile picture for user {user}. Need to update to base64 format."
                            )
                    else:
                        profile_pic = url_for(
                            "static", filename="assets/img/avatars/image2.png"
                        )
            except Exception:
                # If there's any database error, return default values
                pass

        from .utils.alerts import get_recent_alerts

        return {
            "current_user": current_user,
            "profile_pic": profile_pic,
            "org_name": CONFIG.OrgName,
            "alerts": get_recent_alerts(),
        }

    # pylint: disable=import-outside-toplevel
    from .routes.public import public_bp
    from .routes.forms import forms_bp
    from .routes.logs import logs_bp
    from .routes.certificates import cert_bp
    from .routes.auth import auth_bp
    from .routes.dashboard import dashboard_bp
    from .routes.db import db_bp
    from .routes.mailing import mailing_bp
    from .routes.link_short import link_short_bp
    from .routes.profile import profile_bp
    from .routes.settings import settings_bp
    from .routes.errors import errors_bp

    app.register_blueprint(public_bp)
    app.register_blueprint(forms_bp)
    app.register_blueprint(logs_bp)
    app.register_blueprint(cert_bp)
    app.register_blueprint(auth_bp)
    app.register_blueprint(dashboard_bp)
    app.register_blueprint(db_bp)
    app.register_blueprint(mailing_bp)
    app.register_blueprint(link_short_bp)
    app.register_blueprint(profile_bp)
    app.register_blueprint(settings_bp)
    app.register_blueprint(errors_bp)

    return app
