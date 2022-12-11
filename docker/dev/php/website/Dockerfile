FROM php:7.0-fpm

WORKDIR /apps/website

RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    libmagickwand-dev \
    libpng-dev \
    libjpeg-dev \
    libssl-dev \
    libfreetype6-dev

RUN pecl install apcu \
    pecl install imagick

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install bcmath exif pdo_mysql mysqli zip gd

RUN docker-php-ext-enable apcu imagick

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN mkdir /.composer && chown -R 1000:1000 /.composer && chmod -R 774 /.composer

RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && architecture=$(uname -m) \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/$architecture/$version \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get ('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8307\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz

RUN PATH=$PATH:/apps/website/vendor/bin:bin
RUN rm -rf /var/lib/apt/lists/*
