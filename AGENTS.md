# Migration Tracker

This file tracks the migration of PHP files to the Python + Jinja2 stack. When migrating a PHP file, create a corresponding Python implementation using SQLAlchemy for database operations. Update the status of each file to **Completed** once the migration is done. If a file has not been migrated yet, keep the status as **Incomplete**.

## PHP Files Migration Status

| PHP File | Status |
|---------|-------|
| docker/secrets_.php | Completed |
| index.php | Completed |
| members/activity-log.php | Completed |
| members/cds-admin.php | Completed |
| members/change-password.php | Completed |
| members/dashboard.php | Completed |
| members/db-manage.php | Completed |
| members/db-ops/delete.php | Completed |
| members/db-ops/insert.php | Completed |
| members/db-ops/modify.php | Completed |
| members/db-ops/pushAlert.php | Completed |
| members/forgot-password.php | Completed |
| members/form-gen/index.php | Completed |
| members/form-gen/toCSV.php | Completed |
| members/form-gen/view-reg.php | Completed |
| members/header.php | Completed |
| members/link-short.php | Completed |
| members/logout.php | Completed |
| members/mail-list.php | Completed |
| members/mailer-templates/forgot_pwd_temp.php | Completed |
| members/mailer-templates/make_mail.php | Completed |
| members/mailer.php | Completed |
| members/mainFunction.php | Completed |
| members/member-login.php | Completed |
| members/navigation.php | Completed |
| members/pages/404.php | Completed |
| members/pages/error.php | Completed |
| members/profile.php | Completed |
| members/settings.php | Completed |
| members/validate.php | Completed |
| public/cds-public.php | Completed |
| public/entry.php | Completed |
| public/pheader.php | Completed |
| src/PHPMailer/Exception.php | Completed |
| src/PHPMailer/PHPMailer.php | Completed |
| src/PHPMailer/SMTP.php | Completed |

## Project Overview

This repository contains a certificate distribution and resource management platform used by organizations. It includes modules for member authentication, certificate generation, form creation, bulk mailing and generic database maintenance. The system relies on three MariaDB/MySQL databases for CMS, form data and mail templates. Public users can search for certificates and submit event registration forms, while members access administrative functions after logging in.

## Execution Flow

1. **Public access** starts at `index.php` which lets visitors search for certificates. This page links to `public/cds-public.php` where certificates and event information are displayed.
2. **Form submissions** are handled by `public/entry.php` which inserts registration data into the forms database.
3. **Member login** occurs via `members/member-login.php` and is processed by `members/validate.php`. Once authenticated a session is created.
4. Most member pages include `members/header.php` which verifies the session and sets up navigation via `members/navigation.php`.
5. The member dashboard (`members/dashboard.php`) provides quick statistics and links to other tools such as certificate generation (`members/cds-admin.php`), form generation, mailing list management and database maintenance.
6. Utility functions used across the system are defined in `members/mainFunction.php`. This file logs user actions, checks admin privileges and fetches profile pictures.
7. Additional pages allow password resets (`forgot-password.php` and `change-password.php`), log viewing, profile editing and other maintenance tasks.

## Python Migration Strategy

The PHP code will be migrated to a Flask application. Each major feature will become a Flask blueprint with SQLAlchemy models for database access. Jinja2 templates will replace the existing PHP HTML. The configuration already lives in `src/config/docker_secrets.py`. The proposed package layout:

```text
src/
  app/
    __init__.py
    routes/
    templates/
    utils/
```

### File Mapping

| PHP File | Planned Python Component | Purpose |
|---------|---------------------------|---------|
| index.php | `routes/public.py:home` | Landing page to search events |
| public/cds-public.php | `routes/public.py:cds_public` | Display certificates and events |
| public/entry.php | `routes/forms.py:submit_entry` | Record form submissions |
| public/pheader.php | handled by config module | Load timezone and common settings |
| members/member-login.php | `routes/auth.py:login` | Member login form |
| members/validate.php | `routes/auth.py:validate_login` | Process login credentials |
| members/logout.php | `routes/auth.py:logout` | End session |
| members/header.php | auth decorator in `utils/auth.py` | Session check helper |
| members/navigation.php | Jinja2 template `partials/navigation.html` | Sidebar and alerts |
| members/dashboard.php | `routes/dashboard.py:index` | Member dashboard |
| members/cds-admin.php | `routes/certificates.py:generate` | Certificate generation via Pillow |
| members/form-gen/index.php | `routes/forms.py:generator` | Build event registration forms |
| members/form-gen/view-reg.php | `routes/forms.py:view_registrations` | Display responses |
| members/form-gen/toCSV.php | `routes/forms.py:download_csv` | Export responses to CSV |
| members/activity-log.php | `routes/logs.py:list_logs` | View activity logs |
| members/mail-list.php | `routes/mailing.py:list_manager` | Manage mailing lists |
| members/mailer.php | `routes/mailing.py:bulk_mail` | Send emails using smtplib |
| members/mailer-templates/forgot_pwd_temp.php | Jinja2 template `email/forgot_password.html` | Password reset email |
| members/mailer-templates/make_mail.php | `utils/email.py:build_mail` | Common mail builder |
| members/mainFunction.php | `utils/helpers.py` | Logging and admin checks |
| members/profile.php | `routes/profile.py:view_profile` | Profile display and picture upload |
| members/change-password.php | `routes/auth.py:change_password` | Verify reset token and update password |
| members/forgot-password.php | `routes/auth.py:forgot_password` | Send password reset link |
| members/db-manage.php | `routes/db.py:manage` | Generic DB browser |
| members/db-ops/insert.php | `routes/db.py:insert_row` | Insert record |
| members/db-ops/delete.php | `routes/db.py:delete_row` | Delete record |
| members/db-ops/modify.php | `routes/db.py:modify_row` | Update record |
| members/db-ops/pushAlert.php | `routes/db.py:push_alert` | Insert alert notification |
| members/link-short.php | `routes/link_short.py:create_short_url` | Shorten URLs using external API |
| members/pages/404.php | Jinja2 template `errors/404.html` | Not found page |
| members/pages/error.php | Jinja2 template `errors/db_error.html` | Display database errors |
| members/settings.php | `routes/settings.py:page_not_found` | Placeholder settings/404 |
| src/PHPMailer/Exception.php | replaced by `utils/email.py` using smtplib | Email helper classes |
| src/PHPMailer/PHPMailer.php | replaced by `utils/email.py` using smtplib | Email helper classes |
| src/PHPMailer/SMTP.php | replaced by `utils/email.py` using smtplib | Email helper classes |

These notes serve as the guiding strategy for the full migration to Python. Update this file as features are implemented.

## Migration Notes

- Converted dashboard and password reset pages to Flask.
- Introduced `auth` and `dashboard` blueprints with session-based login and statistics view.
- Configured multiple database binds for forms and mail data in the application factory.
- Added Pydantic schemas for login and password reset forms for better validation.
- Added form generation blueprint routes for creating tables, viewing registrations and downloading CSV exports.
- Implemented password reset request flow with email sending using `smtplib`.
- Added mailing list generator and bulk mailer routes with new templates.
- Added database management blueprint with routes to browse, insert, modify and delete rows.
- Introduced generic email builder and forgot-password email template.

- Added link shortener blueprint with TinyURL API integration.
- Implemented user profile editor with image uploads and detail updates.
- Added navigation template and placeholder settings route.
- Created generic error handlers with 404 and database error pages.
- All legacy PHP modules migrated to Flask with updated Docker configuration and documentation.
- Ensured login authentication hashes passwords using SHA1 before querying the database.
- Adjusted certificate model to store the `year` column as an integer to match the MySQL schema.
- Sanitized legacy PHP templates (`index.php` and `members/member-login.php`) to remove PHP code and use Jinja2 placeholders.
