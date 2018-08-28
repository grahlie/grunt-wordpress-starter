FROM phusion/baseimage

# Keep upstart from complaining
RUN dpkg-divert --local --rename --add /sbin/initctl
RUN ln -sf /bin/true /sbin/initctl

# Let the container know that there is no tty
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
	apt-get install -y --force-yes nano bash less iputils-ping \
	apache2 \
	php7.0 libapache2-mod-php7.0 php7.0-mysql php7.0-gd php7.0-opcache \
	mysql-client && \
	apt-get clean

# Get necessary files for WordPress installation
ADD .htaccess /.htaccess
ADD start_container.sh /start_container.sh
ADD wordpress.sql wordpress.sql
ADD apache_config.conf /etc/apache2/sites-enabled/000-default.conf

# Wordpress Initialization and Startup Script
RUN "/start_container.sh"

EXPOSE 80

CMD apachectl -D FOREGROUND