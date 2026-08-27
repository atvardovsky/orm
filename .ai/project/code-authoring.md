# Doctrine ORM Code Authoring Rules

Status: accepted for this Alatyr adapter
Path: `.ai/project/code-authoring.md`
Last reviewed: 2026-08-26
Owner: `@atvardovsky`

Use this file when an assistant writes or reviews Doctrine ORM source, tests,
or code comments. These rules summarize target evidence; they do not replace
`composer.json`, `phpcs.xml.dist`, `phpstan*.neon`, `phpunit.xml.dist`,
`CONTRIBUTING.md`, public docs, source, or tests.

Before deciding/suggesting, read current Doctrine authority: docs,
`CONTRIBUTING.md`, source/tests, validation/security, or owner/maintainer
agreement. Stale/conflicting/gap support is proposal-only.

## Source Evidence

- PHP package and dependency boundaries: `composer.json`
- Coding standard and exclusions: `phpcs.xml.dist`
- Static-analysis contracts and compatibility baselines: `phpstan.neon`,
  `phpstan-dbal3.neon`, and `phpstan-params.neon`
- Test command, groups, bootstrap, and database configuration:
  `phpunit.xml.dist` and `tests/README.markdown`
- Contribution and ticket-regression conventions: `CONTRIBUTING.md`
- Public behavior docs: `docs/en/reference/*.rst`
- Runtime architecture evidence: `src/EntityManager.php`,
  `src/UnitOfWork.php`, `src/Mapping/`, `src/Persisters/`, `src/Query/`,
  `src/Cache/`, `src/Proxy/`, `src/Repository/`, and `src/Tools/`

## Architecture Rules

- Treat Doctrine ORM as a PHP 8.1+ Composer library. Do not add application,
  framework-app, service-container, HTTP, CLI orchestration, or persistence
  behavior that belongs outside the library unless target evidence already
  owns it.
- Keep `EntityManager` as the public facade over configuration, metadata,
  UnitOfWork, query, repository, proxy, event, and cache subsystems. Do not use
  inheritance as an EntityManager extension point; use decorator-style
  extension when needed.
- Keep mapping facts in `src/Mapping/` and the mapping reference docs. Mapping
  changes must preserve metadata validation, association ownership, inheritance
  handling, typed-field mapping, and driver compatibility.
- Keep object identity, lifecycle state, change tracking, cascading, commit
  order, and identity-map behavior in `src/UnitOfWork.php` and directly related
  helpers. Any identity or flush change must be tested with persistence-level
  coverage.
- Keep SQL persistence mechanics in persisters and DBAL-facing utility classes.
  Persisters may use DBAL platform APIs and quote strategies; do not bypass
  parameter binding or platform abstraction with ad hoc SQL behavior.
- Keep DQL grammar, AST, parser, walkers, query execution, and hydration within
  `src/Query/` and `src/Internal/Hydration/`. Query semantics must stay aligned
  with DQL and query-builder docs.
- Keep offset and cursor pagination behavior in `src/Tools/Pagination/` and
  align it with `docs/en/tutorials/pagination.rst` and focused pagination
  tests. Preserve deterministic ordering and DBAL portability.
- Keep `src/Internal/` as an internal implementation area. Do not make new
  public API promises from internal classes or move public extension contracts
  there.
- Keep cache behavior in `src/Cache/` and related persister integrations.
  Cache changes must preserve first-level identity-map semantics and second
  level cache contracts.
- Preserve dependency compatibility ranges recorded in `composer.json`.
  Changes touching DBAL, Persistence, Symfony, or PSR contracts must consider
  both the default PHPStan profile and `phpstan-dbal3.neon`.
- Avoid broad refactors. Change the smallest subsystem that owns the fact, and
  update only directly affected tests/docs/Alatyr surfaces.

## PHP Format Rules

- Every PHP source and test file must start with `<?php`, a blank line,
  `declare(strict_types=1);`, then the namespace.
- Follow `doctrine/coding-standard` through `phpcs.xml.dist`. Do not invent a
  local style or run unrelated whole-repository formatting.
- Use sorted explicit imports and import global functions or constants with
  `use function` and `use const` when the surrounding file follows that style.
- Prefer native type declarations where compatible with inherited signatures
  and supported dependency versions. Use PHPStan docblocks for generics,
  array shapes, templates, and precision that PHP cannot express.
- Do not introduce PHP language features that raise the package runtime above
  the `composer.json` PHP constraint. PHP-version-specific tests need explicit
  PHPUnit requirement attributes.
- Prefer small final value/exception/helper classes only where that matches
  existing subsystem style. Do not mark extension-point classes final merely
  for local neatness.
- Keep public names, exception types, method signatures, constants, and
  documented behavior backward-compatible unless the change is explicitly a
  public contract change and is routed through approval and documentation.
- Use existing exception classes and factory methods where available. New
  exceptions should live in the subsystem exception namespace, be specific, and
  produce actionable messages.
- Do not weaken PHPStan ignores, PHPCS exclusions, PHPUnit strictness, or
  Composer constraints to make a change pass without explicit approval.

## Comment Rules

- Comments should explain non-obvious invariants, extension contracts,
  persistence side effects, failure modes, compatibility constraints, and
  performance-sensitive intent.
- Do not add comments that repeat identifiers, parameter names, return types,
  or straightforward control flow.
- Public behavior belongs in `docs/en/reference/*.rst`; code comments may
  clarify a symbol but must not create undocumented public promises.
- Existing PHPDoc style is accepted for public APIs, complex private state, and
  PHPStan-only precision. Keep `@phpstan-var`, `@phpstan-type`, templates, and
  array shapes when they carry static-analysis meaning.
- Do not add forbidden metadata annotations listed in `phpcs.xml.dist`, such
  as `@author`, `@license`, `@package`, `@since`, old PHPUnit `@test`,
  `@dataProvider`, or `@group` annotations.
- Use PHPUnit attributes instead of old PHPUnit annotations for groups,
  requirements, data providers, dependencies, and coverage declarations.
- Inline comments are acceptable for local invariants or intentional edge
  cases, especially in UnitOfWork, parser, persister, proxy, cache, and
  platform-compatibility code. Keep them short and attached to the reason, not
  the syntax.

## Test Rules

- Bug fixes and reproductions should add focused regression coverage. Ticket
  regressions belong under `tests/Tests/ORM/Functional/Ticket` using the issue
  or ticket identifier in the test name or namespace when that is the local
  pattern.
- Persistence, identity-map, association, inheritance, hydration, query, and
  platform behavior should normally be tested through `OrmFunctionalTestCase`
  or the closest existing subsystem test base.
- Create only the schema and fixtures needed for the focused contract. Use
  existing test helpers and model patterns before inventing new infrastructure.
- Prefer focused PHPUnit filters or paths during development, then broaden to
  PHPStan, PHPCS, and relevant suites according to risk.
- The default local database is in-memory SQLite from `phpunit.xml.dist`.
  Report platform-specific skips or blockers explicitly; do not hide them with
  broad assertions.
- This workspace has a known SQLite `SQRT()` limitation in the full suite.
  Treat it as an environment constraint unless the SQLite runtime or test
  profile changes.

## Validation Rules

Use the PHP 8 and Composer 2.8 binaries available in this workspace:

- `/usr/local/bin/composer8 validate --strict`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpunit`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G`

Run the smallest validation that proves the changed contract, then report
broader checks that were run or skipped. Do not claim an unrun command passed.

## Agent Authoring Checklist

- Identify the owning fact family in `.ai/project/business-logic.md` and
  `.ai/project/source-of-truth-registry.md`.
- Check Doctrine authority before deciding or suggesting; unresolved support is
  a proposal or gap, not accepted direction.
- Load this file for `src/` or `tests/` edits before writing code.
- Name the subsystem owner: mapping, UnitOfWork, query/hydration, persister,
  cache, proxy, repository, tools, or validation.
- Preserve public API, documented behavior, dependency compatibility, and
  existing extension points unless the requested change explicitly crosses
  those boundaries.
- Add or update focused tests for behavior changes and regression fixes.
- Update public docs only when user-facing behavior or examples change.
- Run or report the relevant target validation with exact commands and
  residual risk.
