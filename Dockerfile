FROM php:7.3-apache

RUN apt-get update

# 1. development packages

RUN apt-get update && apt-get -y install git && apt-get -y install vim

# 2. apache configs + document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# 4. start with base php config, then add extensions
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"


# 5. composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage && chown -R www-data:www-data /var/www/html/bootstrap/cache
		
RUN cd /var/www/html && composer install && php artisan key:generate && php artisan storage:link && php artisan cache:clear


