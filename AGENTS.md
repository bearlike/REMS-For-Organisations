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
    static/        # Bootstrap, fonts and JavaScript assets
  config/          # configuration loaded from Docker secrets
public/            # static landing pages served by Flask
members/           # legacy templates now rendered via Jinja2
docker/            # Docker and database setup scripts
  app-entrypoint.sh  # runs Alembic migrations then starts the app
docs/              # images and sample SQL
```

## Programming Principles

- **Blueprint modularity** – each major feature lives in its own module inside `src/app/routes`.
- **SQLAlchemy models** – database tables are represented in `models.py` and reused across routes.
- **Pydantic validation** – forms and CSV uploads use schemas from `schemas.py`.
- **Jinja2 templates** – HTML pages are rendered from files under `src/app/templates`.
- **Docker first** – use `Dockerfile` and `docker-compose.yml` for local development and deployment.
- **Helper utilities** – common logic for authentication and logging lives under `src/app/utils`. The module `src/app/utils/logger.py` configures the shared Loguru instance. Always use f-strings when passing values to the logger.
- **Image generation** – certificate images are created using the Pillow library.

## Jinja2 Template Guidelines

Most pages share a common structure:

1. **Static assets** – include Bootstrap, FontAwesome and custom CSS in the `<head>` using `url_for('static', ...)`.
2. **Navigation** – import `partials/navigation.html` to display the sidebar and topbar.

This ensures consistent styling and keeps navigation identical across views.

## Key Modules
| Module | Purpose |
|-------|---------|
| `src/app/__init__.py` | Application factory and blueprint registration |
| `src/app/models.py` | SQLAlchemy models for certificates, events, logs and users |
| `src/app/schemas.py` | Pydantic schemas used for form and CSV validation |
| `src/app/routes/auth.py` | Login, logout and password management |
| `src/app/routes/certificates.py` | Certificate generation from CSV uploads |
| `src/app/routes/forms.py` | Registration form creation and CSV export |
| `src/app/routes/mailing.py` | Mailing list creation and bulk email sending |
| `src/app/routes/db.py` | Generic database browser and edit interface |
| `src/app/routes/public.py` | Public certificate search and event listing |
| `src/app/routes/profile.py` | User profile view and picture upload |
| `src/app/routes/logs.py` | Activity log listing for users |
| `src/app/routes/link_short.py` | Short URL generator using an external API |
| `src/app/routes/errors.py` | Custom 404 and 500 error handlers |
| `src/app/utils/auth.py` | `login_required` decorator for protecting routes |
| `src/app/utils/email.py` | Utilities for building and sending emails |
| `src/app/utils/logger.py` | Loguru configuration and shared logger |
| `src/app/utils/helpers.py` | Logging, admin checks and identifier sanitation |
| `src/app/utils/pagination.py` | Simple pagination helper class |
| `src/app/utils/sql.py` | Cross-database table and column helpers |


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
