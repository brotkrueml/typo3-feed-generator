.PHONY: qa
qa: cs tests yaml-lint

.PHONY: cs
cs:
	.Build/bin/ecs check --fix

.PHONY: tests
tests:
	.Build/bin/phpunit -c Tests/phpunit.xml.dist

.PHONY: yaml-lint
yaml-lint:
	find -regex '.*\.ya?ml' ! -path "./.Build/*" -exec .Build/bin/yaml-lint --parse-tags -v {} \;
