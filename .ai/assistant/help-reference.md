# Alatyr Help Reference

Use this file in Doctrine ORM for expanded help about the installed Alatyr
adapter. The compact default help is `.ai/assistant/help.md`. Canonical
operation metadata lives in `.ai/assistant/operation-catalog.json`; exact alias
routing lives in `.ai/assistant/operation-index.json`.

Alatyr is an assistant request layer, not a shell command. Local validation in
this workspace uses `/usr/local/bin/php8` and `/usr/local/bin/composer8`.

## Installed Operations

### `help`

Aliases: `Alatyr`, `Alatyr help`.

Use when the user asks what Alatyr can do or the request is unclear. This is a
read-only route through `.ai/assistant/flows/operation-routing.flow.md`.

### `adapter-health`

Aliases: `Alatyr status`, `Alatyr doctor`.

Use for read-only adapter health checks. The flow is
`.ai/assistant/flows/adapter-health.flow.md`.

### `create-project-blueprint`

Aliases: `create blueprint`, `repair blueprint`, `recheck blueprint`.

Use when creating, repairing, or rechecking `.ai/project/blueprint.md` and
equivalent Doctrine source-of-truth docs from repository evidence. The flow is
`.ai/assistant/flows/project-blueprint-creation.flow.md`.

Allowed actions: `read-only`, `docs-only`, or `full-with-approval`.

### `recheck-after-installation`

Aliases: `check Alatyr after installation`, `recheck installation`.

Use after initial installation to verify the adapter, report drift, or repair
adapter-only support files. The flow is
`.ai/assistant/flows/adapter-recheck.flow.md`.

### `recheck-after-framework-update`

Aliases: `update Alatyr`, `recheck Alatyr update`.

Use when comparing the installed adapter against a newer Alatyr Core baseline.
The flow is `.ai/assistant/flows/adapter-recheck.flow.md`.

### `product-change`

Aliases: `change business rule`, `product change`.

Use when accepted behavior, architecture, data, runtime, or public-contract
facts may change. The flow is
`.ai/assistant/flows/blueprint-driven-change.flow.md`.

Allowed actions: `read-only`, `docs-only`, `code-and-tests`, or
`full-with-approval`. Protected changes require explicit approval before edits.

### `logical-integrity-review`

Aliases: `check integrity`, `logical integrity review`.

Use for changed facts, invariants, owner conflicts, or companion surface
review. The flow is `.ai/assistant/flows/logical-integrity-review.flow.md`.

### `drift-review`

Aliases: `review drift`, `find stale Alatyr facts`.

Use to find stale source-of-truth, documentation, or adapter claims without
assuming repair scope. The flow is
`.ai/assistant/flows/logical-integrity-review.flow.md`.

### `documentation-sync`

Aliases: `sync documentation`, `sync diagrams`, `document code`,
`propose comment style`, `generate code docs`, `review code documentation`.

Use for synchronized docs, comments, generated reference material, or companion
assistant surfaces after a changed fact. The flow is
`.ai/assistant/flows/documentation-sync.flow.md`. Optional code-documentation
profiles are not installed; use manual owner review unless a future adapter
expansion enables them.

### `adapter-maturity-review`

Aliases: `check Alatyr maturity`, `review adapter readiness`.

Use for task-specific adapter readiness and blocker review. The flow is
`.ai/assistant/flows/adapter-recheck.flow.md`.

## Routing Rules

- Start from `AGENTS.md` and `.ai/assistant/bootstrap-index.json`.
- Use `.ai/assistant/context-router.json` to choose exactly one smallest
  matching profile, then add project-area overlays only when affected.
- Use `.ai/assistant/module-profile.md` before relying on optional modules.
- Use `.ai/project/business-logic.md`, `.ai/project/blueprint.md`, and
  `.ai/project/source-of-truth-registry.md` for business-rule routing, fact
  ownership, and blueprint routing.
- Use `.ai/assistant/gates/index.json` to select installed gate fragments.
- Show `.ai/assistant/templates/pre-change-preview.md` before semantic,
  protected, cross-boundary, external-effect, or unclear-scope edits.

## Validation

Target validation commands recorded for this workspace:

- `/usr/local/bin/composer8 install`
- `/usr/local/bin/php8 vendor/bin/phpunit`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G`
- docs validation after the docs script resolves a PHP 8-compatible composer
  command

Known local test constraint: SQLite 3.31.1 here does not provide SQL `SQRT()`,
so the full PHPUnit suite stops at
`Doctrine\Tests\ORM\Functional\QueryDqlFunctionTest::testFunctionSqrt` unless
the SQLite runtime or test profile changes.

## Deferred Modules

`blueprint-change` is enabled. Non-blueprint optional modules are deferred and
must not be routed as installed operations:

- `architecture-knowledge`
- `test-first-development`
- `diagrams`
- `team-collaboration`
- `ai-infrastructure`

Other Alatyr Core optional capabilities are unavailable until a future adapter
expansion records their owners, files, operation catalog entries, gates, and
validation.

## Final Evidence

Every completed Alatyr-routed task should report the selected operation and
profile, changed facts/files, source-of-truth owners, integrity result,
validation run or skipped with reason, approvals used, and residual risk.
