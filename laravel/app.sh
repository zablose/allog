#!/usr/bin/env bash

set -e

cd ${DAMP_WEB_DIR} && php /home/${DAMP_USER_NAME}/bin/composer install

cd ${DAMP_WEB_APP} && php artisan migrate:fresh && php artisan db:seed

cd ${DAMP_WEB_DIR} && php vendor/bin/phpunit
