#!make

include .env
-include .env.local
-include `.env.${APP_ENV}.local`
export

help: ## Show command list
	@awk -F ':|##' '/^[^\t].+?:.*?##/ {printf "\033[36m%-30s\033[0m %s\n", $$1, $$NF}' $(MAKEFILE_LIST)

dump_env:
	symfony composer dump-env ${APP_ENV}

create_db: ## Create DB
	symfony console doctrine:database:create

reset_db: ## drop db, create db, update schema and load fixtures
	symfony console doctrine:database:drop --force
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate --no-interaction --env=${APP_ENV}
	symfony console doctrine:fixtures:load --no-interaction

update_schema: ## Update DB Schema
	symfony console doctrine:migrations:migrate --no-interaction

phpspec:
	symfony php vendor/bin/phpspec run -vvv

refresh: ## Refresh cache
	symfony console cache:clear