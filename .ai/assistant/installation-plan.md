# Alatyr Core Installation Plan

Installation id: `ALATYR-20260821-doctrine-orm`

## Target Repository

- Path: repository root
- New install or upgrade: new install
- Primary stack: PHP 8.1+ Composer library
- Existing AI instructions: none found before installation
- Existing adapter manifest or version record: none found before installation
- Existing CODEOWNERS or equivalent owner map: none found before installation
- Existing adapter owner, backup owner, review cadence, and last review: missing before installation
- Scaffolding helper used: `tools/scaffold_target_structure.py --profile standard --framework-pack standard --write`
- Scaffold profile: standard
- Supported assistants: Codex

## Goal

Install Alatyr Core into this Doctrine ORM fork so future assistant work starts
from repository-owned context, source-of-truth routing, risk gates, validation
evidence, and final-evidence expectations.

## Non-Goals

- No Doctrine ORM runtime code changes.
- No Composer dependency changes.
- No CI workflow changes.
- No public security-policy changes.
- No optional Alatyr module enablement except blueprint-change, which is
  enabled by the accepted `.ai/project/blueprint.md` index.

## Target Facts Collected

- Product purpose: `README.md` describes Doctrine ORM as a PHP 8.1+ object-relational mapper built on DBAL.
- Architecture/module facts: `docs/en/reference/architecture.rst` describes ORM, DBAL, Persistence, Collections, Event Manager, EntityManager, UnitOfWork, and mapping terminology.
- Test strategy and existing test surface: `CONTRIBUTING.md`, `tests/README.markdown`, `phpunit.xml.dist`, `tests/`, and CI workflows use PHPUnit, PHPStan, PHPCS, and docs checks.
- Security policy: `SECURITY.md` and `docs/en/reference/security.rst`; vulnerabilities are reported privately to Doctrine security.
- Existing assistant bridge files: none found before installation.
- Blueprint owner: `.ai/project/blueprint.md`, accepted after installation to
  route Doctrine ORM source-of-truth facts for blueprint operations.
- Business logic layer: `.ai/project/business-logic.md`, accepted after
  installation to route ORM behavior-rule families to canonical Doctrine docs,
  source, tests, and validation.
- Existing team/task backend: none found.
- Diagram sources: none found.

## Framework Core Files

Installed `.ai/framework` from the Alatyr Core standard framework pack. The
pack includes projected rule registry, ownership map, and file inventory.

## Project Adapter Files

Created root entry points, owner map, manifest, project/assistant contours,
source-of-truth registry, context router/profiles, gates, flows, help,
operation catalog/index, module profile, maturity profile, output templates,
and installation evidence under `.ai/`.

## Approval Required

No separate approval is required for this installation because the programmer
explicitly requested installation, no existing target instruction files were
overwritten, and no protected Doctrine runtime, security, dependency, CI, or
validation behavior was changed.

## Validation Plan

| Check | Command or review | Required | Notes |
| --- | --- | --- | --- |
| Placeholder scan | `rg "\{[A-Z0-9_][A-Z0-9_/-]*\}" AGENTS.md AI_ASSISTANTS.md CODEOWNERS .ai` | yes | must be empty after adaptation |
| Bootstrap render | Alatyr source bootstrap renderer with the repository root as target | yes | source checkout tool |
| Adapter structural review | Alatyr source adapter validation with the repository root as target | yes | source checkout tool |
| JSON parse | parse all `.ai/**/*.json` | yes | structural check |
| YAML parse | parse `.ai/alatyr.yaml` | yes | structural check |
| Doctrine runtime tests | `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpunit` and related commands | no for adapter-only install | now attempted with local PHP 8; full suite is blocked by local SQLite missing `SQRT()` in two tests |

## Risks

- No backup owner is recorded.
- Target-local Alatyr checker wrapper is committed at `tools/check_alatyr.py` and delegates to `ALATYR_CORE_SOURCE`.
- Full optional-module support is enabled and recorded in
  `.ai/assistant/module-profile.md`, `.ai/alatyr.yaml`, the operation catalog,
  gates, context router, registry, and consistency map.
- The default local `php` and `composer` binaries are not suitable for this branch; use `/usr/local/bin/php8` and `/usr/local/bin/composer8`.
- Local SQLite lacks `SQRT()`, so the full PHPUnit suite reports errors in `Doctrine\Tests\ORM\Functional\QueryDqlFunctionTest::testFunctionSqrt` and `Doctrine\Tests\ORM\Functional\Ticket\GH7941Test::typesShouldBeConvertedForDQLFunctions` in this environment.
