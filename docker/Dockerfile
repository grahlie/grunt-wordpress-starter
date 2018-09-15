FROM phusion/baseimage

# Keep upstart from complaining
RUN dpkg-divert --local --rename --add /sbin/initctl
RUN ln -sf /bin/true /sbin/initctl

# Let the container know that there is no tty
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
	apt-get install -y --force-yes nano bash less iputils-ping \
	apache2 sendmail \
	php7.0 libapache2-mod-php7.0 php7.0-mysql php7.0-gd php7.0-opcache \
	mysql-client && \
	apt-get clean

# Settings for Apache
RUN rm -rf /var/www/html
ADD apache_config.conf /etc/apache2/sites-enabled/000-default.conf

# Settings for PHP
ADD php_config /php_config
RUN cat /php_config >> /etc/php/7.0/apache2/php.ini
RUN phpenmod opcache

# Get necessary files for WordPress
ADD database.sql database.sql

# Wordpress init script
ADD wordpress.sh /wordpress.sh
RUN chmod +x /wordpress.sh
RUN /wordpress.sh

EXPOSE 80

CMD apachectl -D FOREGROUND