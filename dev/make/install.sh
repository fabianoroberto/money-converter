#!/bin/bash

symfony composer install
yarn install

symfony console doctrine:database:create --env=${APP_ENV}
symfony console doctrine:migrations:migrate --no-interaction --env=${APP_ENV}
symfony console doctrine:fixtures:load --no-interaction --env=${APP_ENV}