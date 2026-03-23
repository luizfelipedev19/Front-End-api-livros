FROM php:8.2-apache

# Instala extensões necessárias
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    && docker-php-ext-install curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Habilita mod_rewrite do Apache
RUN a2enmod rewrite

# Configura o DocumentRoot para /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Copia os arquivos do projeto para o container
COPY . /var/www/html/

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala as dependências do projeto
RUN composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Permissões corretas
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configuração do Apache para permitir .htaccess
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/allow-override.conf \
    && a2enconf allow-override

RUN echo 'Alias /Front-Biblioteca /var/www/html' >> /etc/apache2/apache2.conf

EXPOSE 80