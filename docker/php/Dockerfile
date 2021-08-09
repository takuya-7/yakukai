FROM php:7.4.9-fpm-alpine
# FROM circleci/php:7.4.9-fpm-node-browsers


ENV TZ=Asia/Tokyo \
  LANG=ja_JP.UTF-8 \
  LANGUAGE=ja_JP:ja \
  LC_ALL=ja_JP.UTF-8


#composerインストール
# RUN curl -sS https://getcomposer.org/installer | php
# RUN mv composer.phar /usr/local/bin/composer

#gitインストール
# RUN apt-get update
# RUN apt-get install -y git

# php.iniをコピー（コピーもとの起点はdocker-compose.ymlで指定したbuild contextになる。今の場合yakukaiディレクトリ）
COPY ./docker/php/php.ini /usr/local/etc/php/

# 実行ディレクトリを指定
WORKDIR /var/www/html/yakukai

#pdoインストール、phpunitインストール
RUN docker-php-ext-install pdo_mysql
RUN wget https://phar.phpunit.de/phpunit-9.5.6.phar
RUN chmod +x phpunit-9.5.6.phar
RUN mv phpunit-9.5.6.phar /usr/local/bin/phpunit