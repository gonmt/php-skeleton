current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
uid := $(shell id -u)

rename-project:
	@make composer ACTION="rename-project -- $(COMPANY_NAME) $(APP_NAME)"

start-local:
	@make composer ACTION=init-local-env
	@make composer ACTION="install --ignore-platform-reqs --no-ansi"
	@make up

start:
	@make composer ACTION=init-public-env
	@make composer ACTION="install --no-dev --ignore-platform-reqs --no-ansi"
	@make up

composer/install: ACTION=install
composer/update: ACTION=update
composer/require: ACTION=require $(module)
composer composer/install composer/update composer/require:
	@docker run --rm $(INTERACTIVE) --volume $(current-dir):/app --user $(uid):$(uid) \
		composer:2.7.6 $(ACTION)

test:
	@docker exec companyname-firstapp-backend ./vendor/bin/phpunit --testsuite unit -c tools/phpunit.xml
	@docker exec companyname-firstapp-backend ./vendor/bin/behat -p firstapp_backend --format=progress -v -c tools/behat.yml
	@docker exec companyname-firstapp-backend ash -c "cd tools && ../vendor/bin/infection -s --min-msi=1 --min-covered-msi=1"

refactor-suggestions:
	@docker exec companyname-firstapp-backend ./vendor/bin/rector process --dry-run --config tools/rector.php

refactor:
	@docker exec companyname-firstapp-backend ./vendor/bin/rector process --config tools/rector.php

static-analysis:
	@docker exec companyname-firstapp-backend ./vendor/bin/psalm -c tools/psalm.xml --show-info=true --output-format=github

lint:
	@docker exec companyname-firstapp-backend ./vendor/bin/ecs check -c tools/ecs.php

lint-fix:
	@docker exec companyname-firstapp-backend ./vendor/bin/ecs check -c tools/ecs.php --fix

test-architecture:
	@docker exec companyname-firstapp-backend php -d memory_limit=2G ./vendor/bin/phpstan analyse -c tools/phpstan.neon --error-format=github

mess-detector:
	@docker exec companyname-firstapp-backend ./vendor/bin/phpmd apps,src,tests github tools/phpmd.xml

up:
	@UID=$(uid) docker compose up --build -d

restart:
	@docker compose restart

stop:
	@docker compose stop

destroy:
	@docker compose down

clean-cache:
	@rm -rf ./apps/firstapp/backend/var
	@docker exec companyname-firstapp-backend ./apps/firstapp/backend/bin/console cache:warmup

pre-commit-hook:
	@make test
	@make test-architecture
	@make static-analysis
	@make lint
	@make mess-detector

generate-docs:
	@docker exec companyname-firstapp-backend php ./tools/swagger-yaml-to-html.php > apps/firstapp/backend/public/openapi.html
