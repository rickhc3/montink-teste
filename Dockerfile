FROM php:8.0-apache

# Instala extensões necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Ativa o mod_rewrite do Apache (CI usa)
RUN a2enmod rewrite

# Copia os arquivos pro container
COPY . /var/www/html

# Cria diretório de sessões e define permissões
RUN mkdir -p /tmp/ci_sessions && \
    chown -R www-data:www-data /tmp/ci_sessions && \
    chmod 755 /tmp/ci_sessions

# Define permissões
RUN chown -R www-data:www-data /var/www/html

# Define diretório de trabalho
WORKDIR /var/www/html
