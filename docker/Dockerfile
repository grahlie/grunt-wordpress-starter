FROM phusion/baseimage

RUN apt-get update && \
	apt-get install -y --force-yes nano bash less \
	mysql-client && \
	apt-get clean

# Get necessary files for WordPress installation
COPY .htaccess /.htaccess
COPY ./start_container.sh /start_container.sh

RUN chmod +x start_container.sh

EXPOSE 80

# CMD [ "php" ]