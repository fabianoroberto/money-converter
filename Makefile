#!make

include .env
-include .env.local
-include `.env.${APP_ENV}.local`
export

help: ## Show command list
	@awk -F ':|##' '/^[^\t].+?:.*?##/ {printf "\033[36m%-30s\033[0m %s\n", $$1, $$NF}' $(MAKEFILE_LIST)

dump_env:
	symfony composer dump-env ${APP_ENV}

install_dependencies:
	symfony composer install
	yarn install

create_db: ## Create DB
	symfony console doctrine:database:create --env=${APP_ENV}

reset_db: ## Drop db, create db, update schema and load fixtures
	symfony console doctrine:database:drop --force --env=${APP_ENV}
	symfony console doctrine:database:create --env=${APP_ENV}
	symfony console doctrine:migrations:migrate --no-interaction --env=${APP_ENV}
	symfony console doctrine:fixtures:load --no-interaction --env=${APP_ENV}

update_schema: ## Update DB Schema
	symfony console doctrine:migrations:migrate --no-interaction --env=${APP_ENV}

phpunit:
	APP_ENV=test symfony php vendor/phpunit/phpunit/phpunit --configuration phpunit.xml.dist

phpspec:
	symfony php vendor/bin/phpspec run -vvv

build_be: ## Build Backend
	symfony composer install
	symfony console doctrine:migrations:migrate --no-interaction

build_fe: ## Build Frontend
	yarn install
	yarn build

refresh: ## Refresh cache
	symfony console cache:clear

install: ## Initial setup
	bash dev/make/install.sh

all: build_be build_fe #Build Backend and Frontend