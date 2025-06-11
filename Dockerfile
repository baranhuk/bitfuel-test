FROM php:8.3-rc-apache
# Instalação de extensões necessárias
RUN apt-get update \
    && apt-get install -y \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev

# Configuração e instalação das extensões PHP
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Instalação de extensões do banco de dados
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Reinicia o servidor Apache para aplicar as alterações
RUN service apache2 restart