FROM php:7.2-fpm

# Keep upstart from complaining
# RUN dpkg-divert --local --rename --add /sbin/initctl
# RUN ln -sf /bin/true /sbin/initctl

# Let the container know that there is no tty
# ENV DEBIAN_FRONTEND noninteractive

# RUN apt-get update && \
# 	apt-get install -y --no-install-recommends \
# 	nano bash less iputils-ping \
# 	apache2 mailutils \
# 	php libapache2-mod-php7.0 php7.0-mysql php7.0-gd opcache zip \
# 	mysqli mysql-client && \
# 	apt-get clean

RUN set -ex; \
	savedAptMark="$(apt-mark showmanual)"; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
	nano bash less iputils-ping \
	libjpeg-dev libpng-dev \
	; \
	docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr; \
	docker-php-ext-install gd mysqli opcache zip; \
	\
	# FROM WordPress Dockerfile
	# reset apt-mark's "manual" list so that "purge --auto-remove" will remove all build dependencies
	apt-mark auto '.*' > /dev/null; \
	apt-mark manual $savedAptMark; \
	ldd "$(php -r 'echo ini_get("extension_dir");')"/*.so \
	| awk '/=>/ { print $3 }' | sort -u | xargs -r dpkg-query -S | cut -d: -f1 | sort -u | xargs -rt apt-mark manual; \
	apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
	rm -rf /var/lib/apt/lists/*

# Settings for PHP
ADD php_config /php_config
RUN cat /php_config >> /etc/php/7.2/apache2/php.ini
RUN phpenmod opcache

# Settings for Apache
# RUN rm -rf /var/www/html
# ADD apache_config.conf /etc/apache2/sites-enabled/000-default.conf
# ADD apache_config.conf /etc/apache2/sites-available/000-default.conf


# Get necessary files for WordPress
# ADD database.sql database.sql

# Wordpress init script
# ADD wordpress.sh /wordpress.sh
# RUN chmod +x /wordpress.sh

EXPOSE 80

CMD apachectl -D FOREGROUND