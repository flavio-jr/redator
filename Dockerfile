FROM phpearth/php:7.2-apache

RUN apk add --no-cache \
    php-pdo \
    php-pgsql \
    php-pdo_pgsql \
    php-sqlite3 \
    php-pdo_sqlite

COPY . /var/www/localhost/htdocs

RUN sed -i '/LoadModule rewrite_module/s/^#//g' /etc/apache2/httpd.conf
RUN sed -i 's/User apache/User www-data/g' /etc/apache2/httpd.conf
RUN sed -i 's/Group apache/Group www-data/g' /etc/apache2/httpd.conf
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/httpd.conf

COPY docker/vhost.conf /etc/apache2/conf.d/000-default.conf

RUN curl -sS https://getcomposer.org/installer | \
    php -- --filename=composer --install-dir=/usr/sbin

WORKDIR /var/www/localhost/htdocs

RUN composer self-update && \
    composer install --no-interaction

RUN set -x ; \
  addgroup -g 82 -S www-data ; \
  adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

COPY docker/docker-entrypoint.sh /usr/sbin
RUN chmod +x /usr/sbin/docker-entrypoint.sh

CMD [ "/usr/sbin/docker-entrypoint.sh" ]
