# One container for the demo: Apache + PHP 8.3 serving the app, and a small MariaDB
# seeded from job_portal.sql on start (so the public demo resets to clean data on restart).
FROM php:8.3-apache

RUN apt-get update \
 && apt-get install -y --no-install-recommends mariadb-server \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-install mysqli \
 && a2enmod headers \
 # the app ships .htaccess rules (deny code/upload folders), so let Apache read them
 && sed -ri 's#AllowOverride None#AllowOverride All#g' /etc/apache2/apache2.conf \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/mariadb.cnf /etc/mysql/mariadb.conf.d/99-small.cnf
COPY docker/apache-env.conf /etc/apache2/conf-enabled/app-env.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY . /var/www/html/

RUN chmod +x /usr/local/bin/entrypoint.sh \
 && rm -rf /var/www/html/docker /var/www/html/tests /var/www/html/.git /var/www/html/.github \
 && chown -R www-data:www-data /var/www/html/files /var/www/html/profile_img

ENV DB_HOST=127.0.0.1 DB_PORT=3306 DB_USER=jobhunt DB_NAME=job_portal PORT=10000
EXPOSE 10000
ENTRYPOINT ["entrypoint.sh"]
