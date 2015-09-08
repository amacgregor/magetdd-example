FROM amacgregor/base
MAINTAINER Allan MacGregor <amacgregor@allanmacgregor.com>

RUN mkdir -p /srv/www/
COPY config/vhosts/ /etc/apache2/sites-available

RUN a2ensite magetdd.example.com.conf
