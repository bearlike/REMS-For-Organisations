# Maintainer Guide

This document explains the current structure of REMS (Resources and Event Management System) and how to contribute effectively. Keep it up to date whenever new modules or features are introduced.

## Repository Layout
```
src/               # application source code
  app/             # Flask package
    __init__.py    # application factory
    models.py      # SQLAlchemy models
    schemas.py     # Pydantic validation models
    routes/        # feature blueprints
    utils/         # shared helpers and logger
    templates/     # Jinja2 templates
    static/        # CSS, JS, fonts and generated certificates
  config/          # configuration via docker secrets
  migrations/      # Alembic migration scripts
members/           # certificate templates and fonts
docker/            # container setup files
  app-entrypoint.sh  # runs migrations then starts Flask
```
The old `public/` folder was removed during the Python migration.

## Development Setup
1. Install Python 3.13 or use Docker.
2. Create `src/app/.env` and define at minimum `MAIN_DB_URI`.
   Optional databases for forms and mail use `FORMS_DB_URI` and `MAIL_DB_URI`.
3. Apply migrations with `alembic upgrade head` or let `docker-compose up` run them automatically via the entrypoint script.
4. Start the server with `flask --app src.app run` (or use Docker Compose).

## Coding Principles
- **Blueprint modularity** – each feature lives in its own module under `src/app/routes`.
- **Single models file** – all database tables live in `models.py` and are shared between routes.
- **Pydantic schemas** – forms and API inputs are validated in `schemas.py`.
- **Jinja2 templates** – HTML resides in `src/app/templates` with `partials/navigation.html` providing the sidebar and menu.
- **Loguru for logging** – `src/app/utils/logger.py` exposes a configured logger. Format messages with f-strings.
- **Helper utilities** – authentication, mailing and pagination helpers live in `src/app/utils`.
- **Database binds** – additional databases are accessed using `db.get_engine(bind="forms")` or `bind="mail"`. Table names must be sanitized with `sanitize_identifier`.

## Key Modules
| Module | Purpose |
|-------|---------|
| `src/app/__init__.py` | Creates the Flask app and registers blueprints |
| `src/app/models.py` | SQLAlchemy models for certificates, events, logs and logins |
| `src/app/schemas.py` | Pydantic schemas for form validation and CSV parsing |
| `src/app/routes/auth.py` | Login, logout and password reset |
| `src/app/routes/certificates.py` | Generate certificates from uploaded CSV files |
| `src/app/routes/forms.py` | Create and view event registration forms |
| `src/app/routes/mailing.py` | Upload mailing lists and send bulk mail |
| `src/app/routes/db.py` | Browse and edit database tables |
| `src/app/routes/public.py` | Certificate search and event listing |
| `src/app/routes/profile.py` | User profile and picture upload |
| `src/app/routes/logs.py` | View user activity logs |
| `src/app/routes/link_short.py` | Shorten URLs via the Short.io API |
| `src/app/utils/helpers.py` | Logging, admin checks and identifier sanitation |
| `src/app/utils/email.py` | Build and send HTML emails |
| `src/app/utils/pagination.py` | Simple pagination class |

## Template Conventions
The front‑end uses assets from the *Start Bootstrap* SB Admin 2 theme, compiled in Bootstrap Studio. All CSS, JavaScript and images live under `src/app/static/assets`. Generated certificate images are stored in `src/app/static/certificates`. Always reference files with `url_for('static', filename='...')`.

Templates include `partials/navigation.html` for navigation and `partials/footer.html` for a common footer. Most pages follow Bootstrap's **card layout** to stay consistent with the theme. When adding a new template:

1. Copy an existing page such as `dashboard.html` as a starting point.
2. Include the navigation and footer partials at the top and bottom of the file.
3. Place custom assets in `static/assets/` and link them with `url_for`.
4. Wrap page sections in `<div class="card shadow mb-4">` blocks to match the existing layout.
5. Save the file under `src/app/templates` and register a new route if needed.

Certificate templates and fonts for Pillow are located in `members/` and loaded by `certificates.py` when generating images.

Maintain this guide as the project evolves so new contributors can navigate the repository with ease.
