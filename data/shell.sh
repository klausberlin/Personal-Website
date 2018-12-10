#!/usr/bin/env bash

'PHP'
#INSTALL PHP


'MYSQL server'
#INSTALL MariaDb
sudo apt-get install php-mysql

'Composer'
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '55d6ead61b29c7bdee5cccfb50076874187bd9f21f65d8991d46ec5cc90518f447387fb9f76ebae1fbbacf329e583e30') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php --install-dir=bin
php -r "unlink('composer-setup.php');"

'composer-development'
composer development-enable

#;extension=php_curl.dll
apt-get install php-curl

sudo /etc/init.d/php7.0-fpm restart
