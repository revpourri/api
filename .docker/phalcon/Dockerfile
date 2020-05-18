FROM php:7.2-fpm

ARG PHALCON_VERSION=3.4.5
ARG PHALCON_EXT_PATH=php7/64bits

RUN set -xe && \
        # Compile Phalcon
        curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && \
        tar xzf ${PWD}/v${PHALCON_VERSION}.tar.gz && \ 
        rm -r ${PWD}/v${PHALCON_VERSION}.tar.gz  && \
        cd ${PWD}/cphalcon-${PHALCON_VERSION}/build/ && \
        ./install && \
        echo "extension=phalcon.so" > $PHP_INI_DIR/conf.d/phalcon.ini && \
        cd /var/www/html/ 
 
# COPY docker-phalcon-* /usr/local/bin/
RUN apt-get update
# Required for GD
RUN apt-get install -qq -y libpng-dev libjpeg-dev unzip
RUN docker-php-ext-configure gd --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install pdo pdo_mysql gd zip
RUN curl -sS https://getcomposer.org/installer | php \
    && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer
RUN mkdir /usr/share/nginx
RUN mkdir /usr/share/nginx/api
WORKDIR /usr/share/nginx
COPY phinx.yml composer.json ./
RUN composer install --no-scripts --no-autoloader