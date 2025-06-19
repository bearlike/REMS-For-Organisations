# REMS - Resources and Event Management System

<p align="center">
  <img src="https://cdn.thekrishna.in/img/common/rems.png" alt="CMS For Organisations" height="250px">
</p>
<p align="center">All-in-one toolkit for generating forms, mailing lists and certificates—ideal for clubs and small organizations.</p>

<p align="center">
  <img src="https://img.shields.io/github/last-commit/bearlike/REMS-For-Organisations?color=blue&style=flat-square">
  <a href="/LICENSE"><img src="https://img.shields.io/github/license/bearlike/REMS-For-Organisations.svg?style=flat-square"></a>
  <a href="https://hub.docker.com/r/krishnaalagiri/rems"><img src="https://img.shields.io/docker/image-size/krishnaalagiri/rems/latest?label=Image%20Size&logo=docker&style=flat-square" /></a>
  <a href="https://github.com/bearlike/REMS-For-Organisations/issues"><img src="https://img.shields.io/github/issues-raw/bearlike/REMS-For-Organisations?color=red&style=flat-square"/></a>
  <a href="https://github.com/bearlike/REMS-For-Organisations/releases"><img src="https://img.shields.io/github/v/tag/bearlike/REMS-For-Organisations?label=stable&style=flat-square"/></a>
</p>


> [!NOTE]
> Prior to [version 1.1.5](https://github.com/bearlike/REMS-For-Organisations/tree/release/v1.1.5?tab=readme-ov-file), this project was developed in PHP and has since been fully migrated to Python.

## Overview

REMS began as a fun PHP project in college and has since been completely migrated to Python with Flask. It automates certificate generation, form creation, bulk mailing and more. Over 1500 participants across 20+ events have used it, and you can easily adapt it for your own organization.

## Getting Started

### Prerequisites

- Python 3.11 or newer
- A MySQL-compatible database (tested with MariaDB 10.7)
- `pip` for installing dependencies
- Clone this repository.

> [!WARNING]
> A default user with the username `admin` and password `admin` will be created during initialization. Please change these credentials after project setup and deploying to production.

### Deploy with Docker (Recommended)

- Install Docker and Docker Compose.
- Adjust credentials in [`docker-compose.yml`](docker-compose.yml) if needed.
- Launch with:

```bash
# Build the Docker image, if not already built:
# docker-compose build

docker-compose up -d
```

### Manual Installation

1. Install Python packages with `pip install -r requirements.txt` or using Poetry: `poetry install`.
2. Import the sample database from [`docs/files/Sample_REMS_Database.sql`](docs/files/Sample_REMS_Database.sql).
3. Export `MYSQL_HOST`, `MYSQL_USER` and `MYSQL_PASSWORD` or update `src/config/docker_secrets.py`.
4. Start the app with `flask --app src.app run`.


## Features and Screenshots

Tested on Python 3.11 with MariaDB 10.7

### Certificate Generation and Distribution System (CGDS)

Automatically generate certificates from a template and a CSV file. The certificates are then available for download.

| CDS Public (Collection) | CDS Admin (Generation) | Generated Certificates |
| --- | --- | --- |
| <img src="https://i.imgur.com/gaMfSOr.png" width="650"/> | <img src="https://i.imgur.com/APt8LDL.png" width="650"/> | <img src="https://i.imgur.com/av6OHek.png" width="650"/> |

### Database Management

Browse, insert, update and delete records across all databases through a simple interface.

| Database Manager | Modify Tuples | If Error Occurs |
| --- | --- | --- |
| <img src="https://i.imgur.com/lRIwQna.png" width="650"/> | <img src="https://i.imgur.com/696Ipzy.png" width="650"/> | <img src="https://i.imgur.com/li7c2zi.png" width="650"/> |

### Form Generation

Create event registration forms with built-in validation and Markdown support. A table is automatically created in `forms-db` to store responses.

| Form Generator | Sample Generated Form |
| --- | --- |
| <img src="https://i.imgur.com/F7Dci7Z.png" width="650"/> | <img src="https://i.imgur.com/RnTQ3Jq.png" width="650"/> |

### Bulk Mailer

Send HTML emails to a mailing list using a customizable template. Upload a CSV of recipients to build your list.

| Bulk Mailer Interface | Mailing List Generator | Sample Sent Mail |
| --- | --- | --- |
| <img src="https://i.imgur.com/R2RJOvu.png" width="650"/> | <img src="https://i.imgur.com/NOEJiH4.png" width="650"/> | <img src="https://i.imgur.com/OvPolMF.png" width="650"/> |

### View Responses

View submissions for any generated form and download them as CSV.

| View Form Responses |
| --- |
| <img src="https://i.imgur.com/8HfJ5rF.png" width="500"/> |

### Link Shortener

Create short URLs using the `short.io` API with either custom or auto-generated slugs.

| Link Shortener with short.io API |
| --- |
| <img src="https://i.imgur.com//LB4QqoL.png" width="550"/> |

### Dark Mode 🌙

Toggle between light and dark themes to save your eyes at night.

| Dark Mode Preview |
| :---------------: |
| <img src="https://i.imgur.com/itX7cW9.gif" width="550"/> |

## Need Help?

If you encounter any issues, please open a ticket using the appropriate template.

## Contributors

| Krishnakanth Alagiri 👨‍💻 | Mahalakshumi V 👩‍💻 | Dhiraj V |
| --- | --- | --- |
| [![f](https://avatars1.githubusercontent.com/u/39209037?s=86)](https://kanth.tech/github/) | [![f](https://avatars2.githubusercontent.com/u/40058339?s=86)](https://github.com/mahavisvanathan) | [![f](https://i.imgur.com/KqeCBQX.jpg)](https://github.com/dhirajv2000) |
| [@bearlike](https://kanth.tech/github/) | [@mahavisvanathan](https://github.com/mahavisvanathan) | [@dhirajv2000](https://github.com/dhirajv2000) |

## Acknowledgments

*Hat tip to everyone whose code was used.*

![wave](http://cdn.thekrishna.in/img/common/border.png)
