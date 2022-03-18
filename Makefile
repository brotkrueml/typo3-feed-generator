.PHONY: qa
qa: cs tests phpstan yaml-lint

.PHONY: cs
cs: vendor
	.Build/bin/ecs check --fix

.PHONY: phpstan
phpstan: vendor
	.Build/bin/phpstan analyse

.PHONY: tests
tests: vendor
	.Build/bin/phpunit -c Tests/phpunit.xml.dist

vendor: composer.json composer.lock
	composer validate
	composer install

.PHONY: yaml-lint
yaml-lint: vendor
	find -regex '.*\.ya?ml' ! -path "./.Build/*" -exec .Build/bin/yaml-lint --parse-tags -v {} \;
