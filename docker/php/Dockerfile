FROM php:7.4.9-fpm-alpine

ENV TZ=Asia/Tokyo \
  LANG=ja_JP.UTF-8 \
  LANGUAGE=ja_JP:ja \
  LC_ALL=ja_JP.UTF-8

#pdoインストール
RUN docker-php-ext-install pdo_mysql

#composerインストール
# RUN curl -sS https://getcomposer.org/installer | php
# RUN mv composer.phar /usr/local/bin/composer

#gitインストール
# RUN apt-get update
# RUN apt-get install -y git

# php.iniをコピー
COPY php.ini /usr/local/etc/php/

# 実行ディレクトリを指定
WORKDIR /var/www/html/yakukai