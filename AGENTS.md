# Developer Overview

This document describes the Python code base for the Resources and Event Management System (REMS). The application is mainly built with **Flask**, **SQLAlchemy** and **Jinja2**. You must constantly monitor and update this file as any commits, modifications, or new features are added. You may remove or consolidate entries as needed.

## Directory Layout

```
src/
  app/             # Flask application package
    __init__.py    # application factory and blueprint registration
    models.py      # SQLAlchemy models
    schemas.py     # Pydantic data models for form validation
    routes/        # feature blueprints (auth, dashboard, forms, etc.)
    utils/         # helper modules (auth decorators, email, logging)
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

Forms are stored in the `forms` database. The form generator and submission routes now use `db.get_engine(bind="forms")` and sanitize event names with `sanitize_identifier` from `helpers.py`.

The template `forms/generator.html` lists existing form tables below the creation form. Each entry shows a public registration link with a copy-to-clipboard button so admins can easily share it.

`forms/register_form.html` renders registration pages. It groups fields per participant when the table columns end with numbers and provides dropdowns for `dept` and `year` with preset options.

Mailing lists live in the `mail` database. The list manager creates tables using `db.get_engine(bind="mail")` and sanitizes the list name with the same helper to avoid unsafe characters.

Keep this document up to date as modules evolve. It should help new contributors navigate the repository quickly and start coding in Python from day one.
