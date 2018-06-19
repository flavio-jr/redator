#!/bin/sh
vendor/bin/doctrine orm:schema-tool:update --force
bin/console user:create-master

/usr/sbin/httpd -DFOREGROUND