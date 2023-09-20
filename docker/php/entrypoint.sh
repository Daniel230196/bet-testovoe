#!/bin/bash
set -xe
export XDEBUG_MODE=off
export SYMFONY_CONSOLE=symfony-app/bin/console
composer i
if [ -e "$SYMFONY_CONSOLE" ]
  then
    php ${SYMFONY_CONSOLE} doctrine:database:create -n --if-not-exists
    php ${SYMFONY_CONSOLE} doctrine:migrations:migrate -n
fi
export XDEBUG_MODE=debug
php-fpm
