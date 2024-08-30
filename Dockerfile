# Usa a imagem oficial do PHP 8.2 com Apache
FROM php:8.2-apache

# Instala extensões necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip exif pcntl

# Habilita mod_rewrite do Apache
RUN a2enmod rewrite

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o script de inicialização e configurações do Supervisor
COPY docker/startup.sh /usr/local/bin/startup.sh
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Ajusta permissões e executa o script de inicialização
RUN chmod +x /usr/local/bin/startup.sh

# Define o ponto de entrada
ENTRYPOINT ["startup.sh"]

# Expõe a porta 80
EXPOSE 80
