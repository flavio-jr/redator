FROM php:7.2-apache

# ENV http_proxy=http://flavio.ribeiro:bernardo27@192.168.1.10:3128
# ENV https_proxy=${http_proxy}
# ENV ftp_proxy=${http_proxy}
# ENV no_proxy=localhost,127.0.0.1
ENV APACHE_RUN_DIR /var/run

COPY . /srv/app
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

RUN apt-get update

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

WORKDIR /srv/app