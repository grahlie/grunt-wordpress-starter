FROM phusion/baseimage:latest

RUN apt-get update && \
	apt-get install -y --force-yes apache2 libapache2-mod-php5 php5-mysql php5-curl && \
	apt-get clean && \
	rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY wp-config.php /wp-config.php
COPY .htaccess /.htaccess

RUN a2enmod rewrite proxy_http