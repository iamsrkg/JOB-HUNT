#!/bin/sh
# Start MariaDB, create the app user and database, seed demo data, then run Apache on $PORT.
set -e

PORT="${PORT:-10000}"
sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# The DB is private to the container; generate a password if the platform didn't provide one.
if [ -z "$DB_PASS" ]; then
  DB_PASS=$(head -c 24 /dev/urandom | od -An -tx1 | tr -d ' \n')
fi
export DB_PASS

mkdir -p /run/mysqld && chown mysql:mysql /run/mysqld
if [ ! -d /var/lib/mysql/mysql ]; then
  mariadb-install-db --user=mysql --datadir=/var/lib/mysql > /dev/null
fi
mariadbd --user=mysql --bind-address=127.0.0.1 &

i=0
until mariadb-admin ping --silent 2> /dev/null; do
  i=$((i + 1)); [ "$i" -gt 60 ] && { echo "MariaDB did not start" >&2; exit 1; }
  sleep 1
done

mariadb -uroot <<SQL
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASS}';
ALTER USER '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASS}';
GRANT SELECT, INSERT, UPDATE, DELETE ON ${DB_NAME}.* TO '${DB_USER}'@'127.0.0.1';
FLUSH PRIVILEGES;
SQL

tables=$(mariadb -uroot -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}'")
if [ "$tables" = "0" ]; then
  mariadb -uroot "${DB_NAME}" < /var/www/html/job_portal.sql
  echo "Seeded ${DB_NAME} with demo data"
fi

exec apache2-foreground
