# Prepare project
DOCKER_EXEC = docker compose exec web

## Main target
.PHONY: all
all: vendor

## Back end
vendor: composer.lock
	$(DOCKER_EXEC) composer install

## Refresh project
.PHONY: clean
clean:
	rm -rf vendor var

## Run arbitrary command in container
.PHONY: exec
exec:
	$(DOCKER_EXEC) $(COMMAND)

# Testing application
.PHONY: test
test: lint unit-tests integration-tests application-tests

lint: vendor
	$(DOCKER_EXEC) vendor/bin/phpstan analyse -c phpstan.neon

unit-tests: vendor
	$(DOCKER_EXEC) vendor/bin/phpunit --testsuite=unit

integration-tests: vendor
	$(DOCKER_EXEC) vendor/bin/phpunit --testsuite=integration

application-tests: vendor
	$(DOCKER_EXEC) vendor/bin/phpunit --testsuite=application
