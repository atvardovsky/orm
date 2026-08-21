# Alatyr Context Profiles

Use this file as the human explanation for the Doctrine ORM context router.
The machine owner is `.ai/assistant/context-router.json`; profile descriptors
under `.ai/assistant/context/profiles/` are the exact load lists.

## Bootstrap

Treat `AGENTS.md` as preloaded. Routine sessions should read only
`.ai/assistant/bootstrap-index.json` first. If the generated index is stale or
routing is ambiguous, reload `.ai/alatyr.yaml`, `.ai/README.md`, and
`.ai/assistant/context-router.json`, then regenerate the bootstrap index.

After bootstrap, choose the smallest matching profile and only the affected
project-area overlays. Record context expansion when the task crosses multiple
areas, exceeds the default budget, or needs protected owner evidence.

## Profile: `docs-local`

Use for local documentation wording, README edits, and non-semantic docs work.
Required descriptor: `.ai/assistant/context/profiles/docs-local.json`.

Core owners: `README.md`, selected `docs/en/**/*.rst`,
`.ai/assistant/gates/documentation.md`, and
`.ai/assistant/gates/final-evidence.md`.

Validation: docs validation after the docs script resolves a PHP 8-compatible
composer command, or manual documentation review when docs dependencies are
unavailable.

## Profile: `code-local`

Use for bounded implementation or test changes that do not accept a new public
behavior, architecture, or data contract.
Required descriptor: `.ai/assistant/context/profiles/code-local.json`.

Core owners: `composer.json`, selected `src/` and `tests/` files,
`CONTRIBUTING.md`, `.ai/project/blueprint.md`, and
`.ai/project/source-of-truth-registry.md`.

Validation: `/usr/local/bin/php8 vendor/bin/phpunit`,
`/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`,
`/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G`,
and `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G`
as applicable.

## Profile: `business-change`

Use for accepted behavior, domain rule, workflow, or public-contract changes.
Required descriptor: `.ai/assistant/context/profiles/business-change.json`.

Core owners: `.ai/project/business-logic.md`,
`.ai/project/blueprint.md`, `README.md`, `docs/en/reference/*.rst`, selected
source/tests, semantic-integrity gate, and final-evidence gate.

Validation: `/usr/local/bin/php8 vendor/bin/phpunit` plus relevant docs/manual
review. Approval is required before accepted behavior or public contract
changes.

## Profile: `architecture-change`

Use for module boundary, dependency direction, runtime responsibility, public
API, or cross-component contract changes.
Required descriptor: `.ai/assistant/context/profiles/architecture-change.json`.

Core owners: `.ai/project/blueprint.md`,
`docs/en/reference/architecture.rst`, `composer.json`, selected source/tests,
semantic-integrity gate, and final-evidence gate.

Validation: manual architecture review plus
`/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`
when implementation changes. Approval is required before accepted architecture
changes.

## Profile: `data-change`

Use for mapping, persistence, UnitOfWork, identity, query, migration, or
data-loss-risk work.
Required descriptor: `.ai/assistant/context/profiles/data-change.json`.

Core owners: `.ai/project/blueprint.md`,
`docs/en/reference/basic-mapping.rst`,
`docs/en/reference/unitofwork.rst`, selected persistence source/tests,
semantic-integrity gate, code-and-tests gate, and final-evidence gate.

Validation: `/usr/local/bin/php8 vendor/bin/phpunit` with relevant database
configuration and manual persistence review. Destructive or data-loss risk
requires explicit approval.

## Profile: `security-sensitive`

Use for secrets, credentials, permissions, network/external services,
destructive actions, production boundaries, security posture, or privacy risk.
Required descriptor: `.ai/assistant/context/profiles/security-sensitive.json`.

Core owners: `SECURITY.md`, `docs/en/reference/security.rst`,
`.ai/assistant/gates/security-approval.md`, and final-evidence gate.

Validation: policy review plus applicable tests/static analysis. Public
security vulnerability handling must follow Doctrine security reporting
instructions.

## Profile: `framework-upgrade`

Use for installing Alatyr, updating Alatyr Core, adapter rechecks, adapter
maturity review, and framework drift repair.
Required descriptor: `.ai/assistant/context/profiles/framework-upgrade.json`.

Core owners: `.ai/alatyr.yaml`, `.ai/README.md`,
`.ai/assistant/context-router.json`, `.ai/assistant/module-profile.md`,
`.ai/assistant/templates/installation-note.md`, and generated bootstrap state.

Validation: bootstrap regeneration, JSON/YAML parse, operation flow existence,
target adapter validator, local path/placeholder scans, and `git diff --check`.

## Project Area Overlays

The router has overlays for `src`, `tests`, `docs`, `business-logic`, `ci`,
and `commits`. Use them only when that area is affected. Each overlay adds the
target files needed to close source-of-truth, validation, behavior-rule, CI, or
commit-policy impact for the selected profile.

The `commits` overlay loads `.ai/project/commit-policy.md` before creating,
reviewing, or amending a commit.

## Installed Operations

Installed operations come only from `.ai/assistant/operation-catalog.json` and
`.ai/assistant/operation-index.json`. The current installed set is:

- `help`
- `adapter-health`
- `create-project-blueprint`
- `recheck-after-installation`
- `recheck-after-framework-update`
- `product-change`
- `logical-integrity-review`
- `drift-review`
- `documentation-sync`
- `adapter-maturity-review`

Do not route to optional-module operations unless the module is enabled in
`.ai/assistant/module-profile.md`, the operation is added to the catalog and
index, and the referenced flow exists.

## Deferred Optional Modules

`blueprint-change` is enabled. Non-blueprint optional modules are deferred
until target owners, policies, files, and validation are accepted. Deferred
modules must not create routing obligations, missing-file failures, or approval
bypasses.

Current deferred optional modules recorded by this adapter:

- `architecture-knowledge`
- `test-first-development`
- `diagrams`
- `team-collaboration`
- `ai-infrastructure`

Other Alatyr Core optional capabilities remain unavailable unless a future
adapter expansion records them in `.ai/alatyr.yaml`,
`.ai/assistant/module-profile.md`, the operation catalog, and the relevant
target files.

## Final Evidence

For every routed task, report selected profile and project areas, changed
facts/files, source-of-truth owners, invariant/integrity result, validation run
or skipped with reason, approval scope when used, context expansion, and
residual risk.
