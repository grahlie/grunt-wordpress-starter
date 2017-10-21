FROM php:7.0-apache

RUN apt-get update && \
	apt-get install -y --force-yes nano bash less \
	mysql-client && \
	apt-get clean

# Get necessary files for WordPress installation
COPY .htaccess /.htaccess
COPY start_container.sh /start.container.sh

# RUN a2enmod rewrite proxy_http

ENTRYPOINT start_container.sh