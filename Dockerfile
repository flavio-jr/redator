FROM phpearth/php:7.2-apache

RUN apk add --no-cache php-pdo php-pgsql php-pdo_pgsql

COPY . /var/www/localhost

RUN sed -i '/LoadModule rewrite_module/s/^#//g' /etc/apache2/httpd.conf
RUN sed -i 's/User apache/User www-data/g' /etc/apache2/httpd.conf
RUN sed -i 's/Group apache/Group www-data/g' /etc/apache2/httpd.conf
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/httpd.conf

COPY vhost.conf /etc/apache2/conf.d/000-default.conf

WORKDIR /var/www/localhost

RUN set -x ; \
  addgroup -g 82 -S www-data ; \
  adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

CMD exec /usr/sbin/httpd -DFOREGROUND