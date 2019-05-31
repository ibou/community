#!/usr/bin/env bash

.PHONY: help

CONSOLE=php bin/console
PHPUNIT=docker exec sf4_php_apache ./vendor/bin/simple-phpunit 
DOCKER_APACHE=docker exec sf4_php_apache
DB=docker exec sf4_php_apache bin/console
## Colors
COLOR_RESET			= \033[0m
COLOR_ERROR			= \033[31m
COLOR_INFO			= \033[32m
COLOR_COMMENT		= \033[33m
COLOR_TITLE_BLOCK	= \033[0;44m\033[37m

## Help
help:
	@printf "${COLOR_TITLE_BLOCK}Makefile help${COLOR_RESET}\n"
	@printf "\n"
	@printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	@printf " make [target]\n\n"
	@printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	@awk '/^[a-zA-Z\-\_0-9\@]+:/ { \
		helpLine = match(lastLine, /^## (.*)/); \
		helpCommand = substr($$1, 0, index($$1, ":")); \
		helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
		printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

test:
	$(PHPUNIT) --coverage-text --coverage-html web/coverage

update-db:
	$(DB) doctrine:schema:update --force 
	$(CONSOLE) cache:clear

reload-data:
	$(DB) doctrine:fixtures:load

reindex:
	$(DB) elastic:reindex

dev-db-reset:
	$(DB) doctrine:database:drop --env=dev --if-exists --force
	$(DB) doctrine:database:create --env=dev --if-not-exists
	$(DB) doctrine:schema:drop --env=dev --force
	$(DB) doctrine:schema:update --env=dev --force
	$(DB) doctrine:fixtures:load --env=dev -n

dev-init: dev-db-reset reindex 
