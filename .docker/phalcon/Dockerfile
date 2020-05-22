FROM php:7.4-fpm

ARG PHALCON_VERSION=4.0.5
ARG PHALCON_EXT_PATH=php7/64bits

RUN set -xe && \

        # Compile Phalcon
        curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && \
        tar xzf ${PWD}/v${PHALCON_VERSION}.tar.gz && \ 
        rm -r ${PWD}/v${PHALCON_VERSION}.tar.gz  && \
        cd ${PWD}/cphalcon-${PHALCON_VERSION}/build/ && \
        ./install && \
        echo "extension=phalcon.so" > $PHP_INI_DIR/conf.d/phalcon.ini

RUN apt-get update
RUN apt-get install libzip-dev -y
RUN apt-get install git -y
# Phalcon requires PSR
RUN cd && git clone https://github.com/jbboehr/php-psr.git && \
    cd php-psr && \
    phpize && \
    ./configure && \
    make && \
    make test && \
    make install && \
    echo "extension=psr.so" > $PHP_INI_DIR/conf.d/php-psr.ini && \
    cd /var/www/html/

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN echo "extension=psr.so" > $PHP_INI_DIR/conf.d/xdebug.ini && \
    echo "xdebug.remote_enable=on" >> $PHP_INI_DIR/conf.d/xdebug.ini && \
    echo "xdebug.remote_autostart=off" >> $PHP_INI_DIR/conf.d/xdebug.ini && \
    echo "xdebug.remote_port=9001" >> $PHP_INI_DIR/conf.d/xdebug.ini && \
    echo "xdebug.remote_handler=dbgp" >> $PHP_INI_DIR/conf.d/xdebug.ini

# COPY docker-phalcon-* /usr/local/bin/
# Required for GD
RUN apt-get install -qq -y libpng-dev libjpeg-dev unzip
RUN docker-php-ext-install pdo pdo_mysql gd zip
RUN curl -sS https://getcomposer.org/installer | php \
    && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer
RUN mkdir /usr/share/nginx
RUN mkdir /usr/share/nginx/api
WORKDIR /usr/share/nginx

COPY phinx.yml composer.json ./
RUN composer install --no-scripts --no-autoloader