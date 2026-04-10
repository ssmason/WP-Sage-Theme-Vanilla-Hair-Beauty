FROM php:8.2-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev default-mysql-client libfcgi-bin \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
  && docker-php-ext-configure gd \
      --with-freetype \
      --with-jpeg \
      --with-webp \
  && docker-php-ext-install pdo_mysql mysqli zip gd \
  && rm -rf /var/lib/apt/lists/*

RUN { \
    echo "ping.path = /ping"; \
    echo "pm.status_path = /status"; \
  } >> /usr/local/etc/php-fpm.d/zz-healthcheck.conf

RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

RUN groupadd -g 1000 appuser \
  && useradd -u 1000 -g appuser -m appuser

WORKDIR /var/www/html

RUN chown -R appuser:appuser /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

USER appuser

HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
  CMD SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET \
      cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1
