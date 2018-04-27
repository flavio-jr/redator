FROM phpearth/php:7.2-apache

RUN set -x ; \
  addgroup -g 82 -S www-data ; \
  adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

RUN apk add --no-cache \
    php-pdo \
    php-pgsql \
    php-pdo_pgsql \
    php-sqlite3 \
    php-pdo_sqlite \
    php-fpm \
    supervisor

ENV FPM_SOCKET /run/php/php-fpm7.2.sock

COPY . /var/www/localhost/htdocs
COPY docker/vhost.conf /etc/apache2/conf.d/000-default.conf
COPY docker/supervisord.conf /etc/supervisord.conf

RUN sed -i '/LoadModule rewrite_module/s/^#//g' /etc/apache2/httpd.conf
RUN sed -i 's/User apache/User www-data/g' /etc/apache2/httpd.conf
RUN sed -i 's/Group apache/Group www-data/g' /etc/apache2/httpd.conf
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/httpd.conf

RUN curl -sS https://getcomposer.org/installer | \
    php -- --filename=composer --install-dir=/usr/sbin

WORKDIR /var/www/localhost/htdocs

RUN composer self-update && \
    composer install --no-interaction

RUN mkdir -p /var/log/supervisor

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]