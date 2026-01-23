# Prepare project
## Main target
.PHONY: all
all: vendor public/build/app.js public/build/app.css

## Back end
vendor: composer.lock
	composer install

## Front end
assets = public/build/app.js public/build/app.css
$(assets): node_modules
	npm run build

node_modules: package-lock.json
	npm ci

#t Refresh project
.PHONY: clean
clean:
	rm -rf vendor var node_modules public/build

## Enter container shell
.PHONY: sh
sh:
	docker compose exec -u dev web bash

# Testing application
.PHONY: test
test: lint unit-tests integration-tests application-tests

lint: vendor
	vendor/bin/phpstan analyse -c phpstan.neon

unit-tests: vendor
	vendor/bin/phpunit --testsuite=unit

integration-tests: vendor
	vendor/bin/phpunit --testsuite=integration

application-tests: vendor $(assets)
	vendor/bin/phpunit --testsuite=application
