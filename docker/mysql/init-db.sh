#!/usr/bin/env bash
set -e
echo "docker-entrypoint-initdb reached...."

mysql -u root -p"$MYSQL_ROOT_PASSWORD" <<'EOSQL'
CREATE DATABASE IF NOT EXISTS forms_db;
CREATE DATABASE IF NOT EXISTS mail_db;
GRANT ALL PRIVILEGES ON forms_db.* TO '$MYSQL_USER'@'%';
GRANT ALL PRIVILEGES ON mail_db.* TO '$MYSQL_USER'@'%';
EOSQL
