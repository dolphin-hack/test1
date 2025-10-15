FROM ubuntu:latest

RUN apt update -y && apt upgrade -y
RUN apt install -y vim

# Apache / PHP
#RUN DEBIAN_FRONTEND=noninteractive apt -y install apache2 php-fpm php-mbstring php-mysql php-gd php-pdo
RUN DEBIAN_FRONTEND=noninteractive apt -y install apache2 php php-mbstring php-mysql php-gd php-pdo php-curl
RUN phpenmod -v 8.3 -s apache2 curl
RUN a2enmod php8.3
RUN rm -rf /var/lib/apt/lists/*
#COPY ./php.ini /etc/php/php.ini

#RUN a2enmod ssl
#RUN a2enmod http2 && a2enmod proxy_fcgi setenvif && a2enconf php8.1-fpm && a2dismod mpm_prefork && a2enmod mpm_event
#RUN a2enmod http2 && a2enmod proxy_fcgi setenvif && a2enmod mpm_event
#COPY http2.conf /etc/apache2/mods-enabled/http2.conf
#COPY ssl.conf /etc/apache2/sites-enabled/ssl.conf
#COPY ./certs /etc/ssl/certs
#COPY server.pem /etc/ssl/certs/server.pem
#COPY server.key /etc/ssl/certs/server.key
#COPY ca.crt /etc/ssl/certs/ca.crt

#RUN mkdir -p /data/csv && chmod -R 777 /data/csv

CMD ["apachectl", "-D", "FOREGROUND"]