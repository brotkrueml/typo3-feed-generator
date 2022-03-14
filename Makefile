.PHONY: qa
qa: cs yaml-lint

.PHONY: cs
cs:
	.Build/bin/ecs check --fix

.PHONY: yaml-lint
yaml-lint:
	find -regex '.*\.ya?ml' ! -path "./.Build/*" -exec .Build/bin/yaml-lint -v {} \;
