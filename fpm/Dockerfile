FROM markoshust/magento-php:7.2-fpm-5
USER root
#RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
#RUN mv wp-cli.phar /usr/local/bin/wp
#RUN chmod a+x /usr/local/bin/wp
USER app
WORKDIR /var/www/html/source
COPY gitconfig /var/www/.gitconfig



