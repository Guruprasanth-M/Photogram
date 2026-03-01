# ============================================================
# Photogram — Dockerfile
# Base: PHP 8.2 + Apache
# ============================================================
FROM php:8.2-apache

# ─── System packages ────────────────────────────────────────
RUN apt-get update && apt-get install -y \
        libexif-dev \
        libpng-dev \
        libjpeg-dev \
        libwebp-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        exif \
        gd \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ─── PHP config ─────────────────────────────────────────────
COPY docker/php.ini /usr/local/etc/php/conf.d/photogram.ini

# ─── Apache VirtualHost ─────────────────────────────────────
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# ─── Application files ──────────────────────────────────────
WORKDIR /var/www/html
COPY . .

# Remove dev/CI only files from the image
RUN rm -rf \
        .git \
        .github \
        .vscode \
        php-class-project \
        project/npm-scripts \
        project/js \
        project/css \
        project/sampleconfig.json \
        README.md

# ─── Uploads directory (writable by www-data) ───────────────
RUN rm -f /var/www/html/htdocs/uploads \
    && mkdir -p /var/www/html/htdocs/uploads \
    && chown -R www-data:www-data /var/www/html/htdocs/uploads

# ─── Expose port ────────────────────────────────────────────
EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1
