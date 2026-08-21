# Alatyr Adapter Maturity Profile

Use this file in Doctrine ORM to report adapter readiness by task type.

Overall adapter state: minimal-usable-with-gaps
Last reviewed: 2026-08-21
Reviewed by: Codex installation for @atvardovsky

Blocking gaps:

- no separate backup owner recorded
- no target-local Alatyr checker committed
- non-blueprint optional modules deferred until target owners and policies are accepted
- default `php` and `composer` binaries are not suitable for this branch in this workspace
- local SQLite lacks the SQL `SQRT()` function required by part of the PHPUnit suite

## Documentation Work

Maturity: minimal-usable
Supported work: README, docs, and non-semantic documentation edits
Required context:

- `.ai/project/blueprint.md`
- `README.md`
- `docs/README.md`
- selected `docs/en/**/*.rst`
- `.ai/assistant/context/profiles/docs-local.json`

Required owners present: yes for public docs; no diagram policy found
Validation or manual review: docs validation after the docs script resolves a PHP 8-compatible composer command; otherwise manual docs review
Approval needs: approval required for accepted behavior, security posture, public contract, or approval-rule changes
Blocking criteria: semantic documentation change without source-of-truth owner or approval
Residual risks: docs tooling dependencies may be missing locally
Final evidence: changed docs, source of truth, validation or skip reason, residual risk

## Blueprint Work

Maturity: usable-with-manual-closure
Supported work: create, repair, or recheck blueprint-equivalent source routing and carry accepted product changes across docs, source, tests, and adapter evidence
Required context:

- `.ai/project/blueprint.md`
- `.ai/project/business-logic.md`
- `.ai/project/source-of-truth-registry.md`
- `.ai/assistant/flows/project-blueprint-creation.flow.md`
- `.ai/assistant/flows/blueprint-driven-change.flow.md`
- selected canonical Doctrine docs, source, tests, and validation files

Required owners present: yes for blueprint routing; no consistency map
Validation or manual review: adapter validator, logical-integrity review, relevant Doctrine validation, and manual owner-evidence review
Approval needs: approval required for accepted behavior, architecture, public contract, security posture, dependency, CI, or weakened-gate changes
Blocking criteria: accepted semantic change without canonical owner, approval, validation plan, or source/docs/tests sync
Residual risks: cross-surface impact closure is manual until a consistency map is accepted
Final evidence: changed facts, owners, synchronized surfaces, validation, skipped checks, residual risk

## Business Logic Work

Maturity: usable-with-manual-closure
Supported work: read-only behavior explanation, business-rule routing review, and accepted ORM behavior changes through blueprint-driven change
Required context:

- `.ai/project/business-logic.md`
- `.ai/project/blueprint.md`
- `.ai/project/source-of-truth-registry.md`
- `.ai/assistant/context/profiles/business-change.json`
- selected canonical Doctrine docs, source, tests, and validation files

Required owners present: yes for Alatyr business-rule routing; canonical behavior remains in Doctrine docs/source/tests
Validation or manual review: manual owner-evidence review plus relevant PHPUnit/PHPStan/PHPCS checks when implementation changes
Approval needs: approval required for accepted behavior, public contract, persistence rule, query behavior, security posture, dependency, CI, or weakened-gate changes
Blocking criteria: accepted business-rule change without canonical Doctrine owner evidence, business-logic layer sync, docs/source/tests sync, approval when required, or validation plan
Residual risks: no consistency map, so cross-rule and cross-surface impact closure is manual
Final evidence: business rule family, canonical owners, changed facts, synchronized surfaces, validation, skipped checks, residual risk

## Code Changes

Maturity: minimal-usable
Supported work: bounded implementation and test changes in `src/` and `tests/`
Required context:

- `composer.json`
- selected source/test files
- `CONTRIBUTING.md`
- `.ai/project/business-logic.md`
- `.ai/project/blueprint.md`
- `.ai/project/source-of-truth-registry.md`

Required owners present: yes for code/test surfaces; manual impact closure required
Validation or manual review: `/usr/local/bin/php8 vendor/bin/phpunit`, `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`, `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G`, `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G` as applicable
Approval needs: approval required for protected categories or weakened validation
Blocking criteria: unavailable source-of-truth owner for semantic behavior changes
Residual risks: no consistency map; database extension availability varies by test profile
Final evidence: changed files, invariant review, validation run/skipped, docs sync, residual risk

## Architecture Work

Maturity: minimal-usable
Supported work: read-only architecture explanation and bounded implementation review
Required context:

- `docs/en/reference/architecture.rst`
- `.ai/project/blueprint.md`
- `composer.json`
- relevant source and test files

Required owners present: architecture docs exist; no Alatyr architecture catalog owner accepted
Validation or manual review: architecture manual review plus PHPStan/PHPUnit when implementation changes
Approval needs: approval required for architecture boundary, dependency direction, public API, or runtime responsibility changes
Blocking criteria: accepting architecture decisions without owner evidence or approval
Residual risks: no compact catalog or decision authority beyond target docs/review
Final evidence: affected areas, selected evidence, validation, residual risk

## Data And Persistence Work

Maturity: minimal-usable
Supported work: mapping, UnitOfWork, identity, query, and persistence changes with manual closure
Required context:

- `docs/en/reference/basic-mapping.rst`
- `docs/en/reference/unitofwork.rst`
- `.ai/project/blueprint.md`
- selected `src/Mapping/`, `src/UnitOfWork.php`, query, persister, and test files

Required owners present: yes in docs/source/tests; no consistency map
Validation or manual review: PHPUnit with relevant SQLite/database configuration; PHPStan as applicable
Approval needs: approval required for persistence behavior, data-loss, destructive, migration, or external data-contract changes
Blocking criteria: destructive or data-loss risk without explicit approval
Residual risks: database-specific CI matrix may not be locally reproducible
Final evidence: data facts, invariant review, validation, rollback/residual risk

## Security-Sensitive Work

Maturity: guarded
Supported work: read-only security review and approved security-sensitive changes
Required context:

- `SECURITY.md`
- `docs/en/reference/security.rst`
- `.ai/assistant/gates/security-approval.md`

Required owners present: reporting policy exists; security owner is Doctrine security contact from `SECURITY.md`
Validation or manual review: policy review plus applicable tests/static analysis
Approval needs: required for secrets, credentials, permissions, dependency trust, privacy, destructive work, live services, or public vulnerability handling
Blocking criteria: security change without explicit approval or private-reporting compliance
Residual risks: no target-specific secret scanner or live-service policy beyond documentation
Final evidence: security evidence, approvals, validation, residual risk

## AI Adapter And Framework Work

Maturity: minimal-usable
Supported work: adapter health, standard Alatyr recheck, and framework update review
Required context:

- `.ai/alatyr.yaml`
- `.ai/README.md`
- `.ai/assistant/context-router.json`
- `.ai/assistant/module-profile.md`
- `.ai/assistant/templates/installation-note.md`

Required owners present: @atvardovsky for adapter files; backup owner missing
Validation or manual review: Alatyr source validator from installation source; manual review if unavailable
Approval needs: approval required before overwriting existing instructions, weakening gates, importing AI infrastructure, or changing protected adapter behavior
Blocking criteria: unresolved placeholders, stale bootstrap, or unreviewed protected adapter changes
Residual risks: no target-local checker committed
Final evidence: validator findings, framework version/schema/template state, gaps, local deviations

## Team Collaboration

Maturity: not-enabled
Supported work: none beyond ordinary Git workflow
Required context:

- no team policy installed

Required owners present: no
Validation or manual review: manual review only
Approval needs: separate user decision required before enabling
Blocking criteria: attempts to record actor authority, claims, priorities, or team decisions without policy
Residual risks: no team task registry or backend
Final evidence: module deferred
