# Prepare project

## Main target
.PHONY: all
all: vendor

## Back end
vendor: composer.lock
	composer install

## Refresh project
.PHONY: clean
clean:
	rm -rf vendor

# Testing application
.PHONY: test
test: lint unit-tests integration-tests application-tests

lint: vendor
	vendor/bin/phpstan analyse -c phpstan.neon

unit-tests: vendor
	vendor/bin/phpunit --testsuite=unit

integration-tests: vendor
	vendor/bin/phpunit --testsuite=integration

application-tests: vendor
	vendor/bin/phpunit --testsuite=application
