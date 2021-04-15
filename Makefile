SHELL := /bin/bash
COMPOSE=docker-compose -f dockers/docker-compose.yml

build:
	@$(COMPOSE) build

install:
	@$(COMPOSE) build
	@$(COMPOSE) up -d  --remove-orphans
	@make composer-install

stop:
	@$(COMPOSE) stop

up:
	@$(COMPOSE) up -d

composer-install:
	@$(COMPOSE) exec tic_tac_php composer install

bash:
	@$(COMPOSE) exec tic_tac_php bash

app:
	@$(COMPOSE) exec tic_tac_php apps/tic-tac-toe/bin/console app:tictactoe

test:
	@$(COMPOSE) exec tic_tac_php php vendor/bin/codecept run tests