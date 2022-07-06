# syntax=docker/dockerfile:1

FROM php:7.2-apache

# Lablelling
LABEL com.rems.title="Resources and Event Management System (REMS)"
LABEL com.rems.version="1.1.6"
LABEL com.rems.authors="Krishnakanth, Mahalakshumi"
LABEL com.rems.repository="https://github.com/bearlike/REMS-For-Organisations"
LABEL com.rems.description="Resources and Event Management System for small organisations and clubs. Bulk mailer, certificate generation and much more."

# Installing dependencies
RUN rm /etc/apt/preferences.d/no-debian-php
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions gd xdebug
RUN install-php-extensions pdo_mysql

# Copying project and secrets file
COPY . /var/www/html/
COPY docker/secrets_.php /var/www/html/members