.PHONY: qa
qa: cs tests phpstan rector-dry yaml-lint changelog

# See: https://github.com/crossnox/m2r2
.PHONY: changelog
changelog:
	m2r2 CHANGELOG.md && \
	echo ".. _changelog:" | cat - CHANGELOG.rst > /tmp/CHANGELOG.rst && \
	mv /tmp/CHANGELOG.rst Documentation/Changelog/Index.rst && \
	rm CHANGELOG.rst

.PHONY: cs
cs: vendor
	.Build/bin/ecs check --fix

.PHONY: phpstan
phpstan: vendor
	.Build/bin/phpstan analyse

.PHONY: rector
rector: vendor
	.Build/bin/rector

.PHONY: rector-dry
rector-dry: vendor
	.Build/bin/rector --dry-run

.PHONY: tests
tests: vendor
	.Build/bin/phpunit -c Tests/phpunit.xml.dist

vendor: composer.json composer.lock
	composer validate
	composer install

.PHONY: yaml-lint
yaml-lint: vendor
	find -regex '.*\.ya?ml' ! -path "./.Build/*" -exec .Build/bin/yaml-lint --parse-tags -v {} \;
