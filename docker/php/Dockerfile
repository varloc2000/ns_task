FROM php:7.4-fpm

RUN apt-get update -yqq && \
    apt-get install -yqq \
    git \
    curl \
    zip \
    unzip \
    gzip \
    libzip-dev \
    libicu-dev \
    nano \
    acl \
    procps

RUN docker-php-ext-configure intl 

RUN docker-php-ext-install intl pdo pdo_mysql zip opcache bcmath sockets

RUN pecl install xdebug && docker-php-ext-enable xdebug opcache

RUN echo "opcache.max_accelerated_files = 20000" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
RUN echo "opcache.memory_consumption = 256" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
# RUN echo "opcache.preload = /app/config/preload.php" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
RUN echo "opcache.validate_timestamps = 0" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

WORKDIR /app

# Fix var directory permission issue
# @see https://symfony.com/doc/current/setup/file_permissions.html#using-acl-on-a-system-that-supports-setfacl-linux-bsd
RUN mkdir var && \
    HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1) && \
    setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var && \
    setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var
