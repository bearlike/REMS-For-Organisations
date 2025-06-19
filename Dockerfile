# syntax=docker/dockerfile:1

FROM python:3.13-slim

# Metadata
LABEL org.opencontainers.image.version="2.0.0"
LABEL org.opencontainers.image.title="Resources and Event Management System (REMS)"
LABEL org.opencontainers.image.authors="Krishnakanth Alagiri <https://github.com/bearlike>, Mahalakshumi V <https://github.com/mahavisvanathan>"
LABEL org.opencontainers.image.source="https://github.com/bearlike/REMS-For-Organisations"
LABEL org.opencontainers.image.description="Resources and Event Management System for small organisations and clubs. Bulk mailer, certificate generation and much more."

WORKDIR /app

COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

ENV FLASK_APP=src.app
ENV FLASK_RUN_HOST=0.0.0.0
ENV PYTHONUNBUFFERED=1

CMD ["python", "-m", "flask", "run"]
