# Load .env file if it exists
include .env

CURRENT_DIRECTORY := $(shell pwd)

start up:
	@docker-compose up -d

stop:
	@docker-compose stop

down:
	@docker-compose down --remove-orphans

start-db:
	@docker-compose up -d database

start-php:
	@docker-compose up -d php-fpm

start-cache:
	@docker-compose up -d cache

status:
	@docker-compose ps

restart: down start

clean: stop
	@docker-compose rm --force
	@find . -name \*.pyc -delete

build: start
	@docker-compose up -d --build

tail: start-php
	@docker-compose logs -f

php: start-php
	@docker-compose exec php-fpm bash

test: start-php
	docker exec -ti php-fpm ./vendor/phpunit/phpunit/phpunit tests

mysql: start-db
	docker exec -ti hp-database mysql --user=${DB_USERNAME} --password=${DB_PASSWORD} --database=${DB_DATABASE}

.PHONY: start up stop down start-db start-php start-cache status restart clean build tail php test coverage mysql redis
