#-----------------------------------------------------------
##@ # Stack commands
#-----------------------------------------------------------

up: ## Start the project stack
	@docker-compose up -d
up-force: ## Start the project stack with recreate step
	@docker-compose up -d --force-recreate
up-build: ## Start the project stack with recreate & build steps
	@docker-compose up -d --build --force-recreate
down: ## Stop the project stack
	@docker-compose down
restart: down up ### Restart the project stack

status: ## Stack status
	@docker-compose ps

#-----------------------------------------------------------
##@ # Legacy stack commands
#-----------------------------------------------------------

legacy-up: ## Start the project stack
	@docker-compose -f docker-compose.legacy.yaml up -d
legacy-up-force: ## Start the project stack with recreate step
	@docker-compose -f docker-compose.legacy.yaml up -d --force-recreate
legacy-up-build: ## Start the project stack with recreate & build steps
	@docker-compose -f docker-compose.legacy.yaml up -d --build --force-recreate
legacy-down: ## Stop the project stack
	@docker-compose -f docker-compose.legacy.yaml down
legacy-restart: down up ### Restart the project stack

legacy-status: ## Stack status
	@docker-compose -f docker-compose.legacy.yaml ps

#-----------------------------------------------------------
##@ # Test commands
#-----------------------------------------------------------

format: ## Lint the code
	docker-compose exec website-rewrite-php vendor/bin/php-cs-fixer fix --allow-risky=yes

analyse: ## Static analysis
	docker-compose exec website-rewrite-php ./vendor/bin/phpstan analyse --memory-limit=2G

test: ## Start the whole test suite
	docker-compose exec website-rewrite-php php vendor/bin/phpunit

prepare: format analyse test

#-----------------------------------------------------------
##@ # Nginx commands
#-----------------------------------------------------------

nginx: ## Opens a shell inside the Nginx container
	@docker-compose exec nginx bash
nginx-restart: ## Restart the Nginx service
	@docker-compose restart nginx
nginx-logs: ## Read Nginx service logs
	@docker-compose logs -f nginx

#-----------------------------------------------------------
##@ # PHP commands
#-----------------------------------------------------------
php-legacy: ## Opens a shell inside the legacy PHP container
	@docker-compose exec website-php bash

php: ## Opens a shell inside the PHP container
	@docker-compose exec website-rewrite-php bash
php-restart: ## Restart the PHP service
	@docker-compose restart website-rewrite-php
php-logs: ## Read PHP service logs
	@docker-compose logs -f website-rewrite-php

composer: # Alias for "make php" Opens a shell inside the PHP container
	@docker-compose exec website-rewrite-php bash
composer-install:
	@docker-compose exec website-rewrite-php composer install
composer-dump:
	@docker-compose exec website-rewrite-php composer dump-autoload

#-----------------------------------------------------------
##@ # Mysql commands
#-----------------------------------------------------------

mysql: ## Opens a shell inside the MySQL container
	@docker-compose exec mysql bash
mysql-restart: ## Restart the MySQL service
	@docker-compose restart mysql
mysql-logs: ## Read MySQL service logs
	@docker-compose logs -f mysql

#-----------------------------------------------------------
# Other commands
#-----------------------------------------------------------

.DEFAULT_GOAL := help
.PHONY: help
help: ## Display help
	@awk 'BEGIN {FS = ":.*##"; printf "Usage:\n  make <command> \033[36m\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)
