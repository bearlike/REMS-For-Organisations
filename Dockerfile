FROM php:7.2-apache
LABEL in.thekrishna.rems.email="krishna.alagiri03@gmail.com"
LABEL in.thekrishna.rems.authors="Krishnakanth, Mahalakshumi"
LABEL in.thekrishna.rems.link="https://github.com/bearlike/REMS-For-Organisations"
LABEL in.thekrishna.rems.title="Resources and Event Management System (REMS)"

RUN rm /etc/apt/preferences.d/no-debian-php
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions gd xdebug
RUN install-php-extensions pdo_mysql

COPY . /var/www/html/
COPY docker/secrets_.php /var/www/html/members
RUN rm -r /var/www/html/docs /var/www/html/.git
