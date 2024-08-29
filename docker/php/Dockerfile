FROM php:8.2-fpm

# Instala as dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libzip-dev \
    unzip \
    curl \
    git \
    libonig-dev \
    supervisor \
    gnupg2 \
    nodejs \
    npm \
    libicu-dev \
    libjpeg62-turbo-dev \
    cron

# Instala o Yarn
RUN npm install --global yarn && yarn -v

# Configura as extensões do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo pdo_mysql zip mbstring exif intl

# Atualiza os pacotes PECL
RUN pecl channel-update pecl.php.net

# Limpa cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configura o diretório de trabalho
WORKDIR /var/www

# Copia e instala as dependências do projeto
COPY package.json yarn.lock ./
RUN yarn install

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia a configuração do Supervisor e o script de inicialização
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY startup.sh /startup.sh
RUN chmod +x /startup.sh

# Define o comando de inicialização
CMD ["/startup.sh"]
