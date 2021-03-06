#!/usr/bin/env bash

# Set php version through phpenv. 5.3, 5.4 and 5.5 available
phpenv local 7.1

# php memory limit
sed -i'' 's/^memory_limit = .*/memory_limit = -1/g' ${HOME}/.phpenv/versions/$(phpenv version-name)/etc/php.ini

# Remove xdebug
rm -f /home/rof/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini

# Launch elasticsearch
sudo /etc/init.d/elasticsearch start

# Install dependencies through Composer
composer install --prefer-dist --no-interaction

# MySQL
sed -i 's/database_host.*/database_host: 127.0.0.1/' app/config/parameters.yml
sed -i "s/database_user.*/database_user: ${MYSQL_USER}/" app/config/parameters.yml
sed -i "s/database_password.*/database_password: ${MYSQL_PASSWORD}/" app/config/parameters.yml
sed -i "s/dbname.*/dbname: test/" app/config/config_test.yml

# Elastic search
sed -i "s/fos_elastica\.host.*/fos_elastica\.host: 127.0.0.1/" app/config/parameters.yml

# database creation
php bin/console doctrine:migrations:migrate --env=test -n
php bin/console cache:clear --no-warmup --env=test
php bin/console doctrine:phpcr:repository:init --env=test

# Install Yarn globally
apt-key adv --fetch-keys http://dl.yarnpkg.com/debian/pubkey.gpg
echo \"deb http://dl.yarnpkg.com/debian/ stable main\" | tee /etc/apt/sources.list.d/yarn.list
apt-get update -qq
apt-get install -y -qq yarn=0.21.3-1

yarn run gulp
