#!/usr/bin/env sh
set -e

# wait for database to become available
if [ -n "$MAIN_DB_URI" ]; then
  until alembic upgrade head; do
    echo "Waiting for database..."
    sleep 2
  done
fi

exec python -m flask run
