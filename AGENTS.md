# Agent Instructions

This repository uses Alatyr Core for project-owned assistant guidance.

## Compact Bootstrap

Treat this file as host-preloaded context; do not reread it. Load only:

- `.ai/assistant/bootstrap-index.json`

The bootstrap index is a hash-bound projection of `.ai/alatyr.yaml`,
`.ai/README.md`, and `.ai/assistant/context-router.json`. If it is missing or
stale, load those owners and repair the projection before routine routing.
Select the smallest task profile and affected project areas. Load
`.ai/assistant/context-profiles.md` only for ambiguity, conflict, or repair.
Record context receipt on expansion.

Route IDs and aliases through `.ai/assistant/operation-index.json`; use profile
candidates for requests. Load `.ai/assistant/operation-catalog.json` only for
ambiguity or repair. Status operations are read-only.

## Session Recovery

For installation, update, or uncertainty, use `.ai/assistant/templates/installation-note.md`
and `.ai/README.md` as recovery references.

## Target Evidence

- Project: Doctrine ORM, a PHP 8.1+ Composer library for object-relational mapping.
- Areas: `src/` runtime ORM code, `tests/` PHPUnit/static-analysis/performance tests, `docs/` public documentation, `ci/github/phpunit/` database test configs, `.github/workflows/` CI.
- Blueprint index: `.ai/project/blueprint.md`.
- Business logic layer: `.ai/project/business-logic.md`.
- Code authoring rules: `.ai/project/code-authoring.md`.
- Commit policy: `.ai/project/commit-policy.md`; every commit must have one
  logical scope and a detailed commit message written in English.
- Fact registry: `.ai/project/source-of-truth-registry.md`.
- Doctrine authority rule: decisions/suggestions require current Doctrine
  docs, `CONTRIBUTING.md`, source/tests, validation/CI/security, or
  owner/maintainer agreement; stale/conflicting/gap support is proposal-only.
- Checks: `/usr/local/bin/composer8 install`; `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpunit`; `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`; `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G`; `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G`; docs validation only after the docs script resolves a PHP 8-compatible composer command.
- Security/live services: SECURITY.md and docs/en/reference/security.rst; report security vulnerabilities to security@doctrine-project.org, not public GitHub issues. Do not perform destructive, credential, live-service, production, or security-sensitive actions without explicit approval.
- Diagrams/artifacts: no target-owned diagram source was found during installation; treat diagram changes as documentation/manual-review work until the target records a diagram policy.

## Canonical Installed Rules

Use installed owners for `ALATYR-CONTEXT-001`, `ALATYR-SOURCE-001`,
`ALATYR-RISK-001`, `ALATYR-APPROVAL-001`, `ALATYR-SAFETY-001`,
`ALATYR-AUTHORIZATION-001`, `ALATYR-SAFETY-002`, `ALATYR-INTEGRITY-001`, `ALATYR-CHANGE-001`,
`ALATYR-ADAPTER-001`, `ALATYR-MODULE-001`, `ALATYR-OPERATION-001`,
`ALATYR-BRIDGE-001`, `ALATYR-LIFECYCLE-001`, `ALATYR-PACKAGE-001`,
`ALATYR-ENGINEERING-EVIDENCE-001`, `ALATYR-DEBUG-001`,
`ALATYR-CODEDOC-001`, `ALATYR-VOCABULARY-001`, `ALATYR-TDD-001`,
`ALATYR-EXTENSION-001`, `ALATYR-DEPENDENCY-001`, `ALATYR-MODE-001`,
`ALATYR-DIAGRAM-001`, `ALATYR-TEAM-001`, `ALATYR-DELEGATION-001`, and
`ALATYR-EVIDENCE-001`.
Project facts belong to project contour; local assistant mechanics belong to
assistant contour. Do not invent facts or copy policy into bridges.

For `src/` or `tests/` changes, load `.ai/project/code-authoring.md` and follow
the target architecture, PHP format, comment, test, and validation rules before
editing. For semantic changes, re-derive invariants and reconcile reviews
sharing a fact or contract. Use `.ai/project/source-of-truth-registry.md` for
owners and selected target files for evidence. Run only validation that exists.

Select routine acceptance gates through `.ai/assistant/gates/index.json` and
load only the routed fragments. Load the complete gate checklist only for
adapter repair, ambiguity, or a full acceptance audit.

Routing selects a flow; it does not grant approval or broaden allowed actions.
A preview is not approval and becomes stale when material risk or scope
changes.

Before state changes, apply `.ai/assistant/policies/action-authorization.json`
to the newest request and current scope. Issue/backlog returns and ambiguous
informational requests are `inspect` only. Implementation does not imply
commit; commit does not imply push. Prior-scope authorization and other gates
cannot grant a missing phase.
Implementation does not imply commit; commit does not imply push.

## Protected Changes

Apply target approval policy before architecture, accepted behavior, security,
permission, dependency, destructive, live, spend, production, imported AI
infrastructure, or weakened-gate changes. When path scope matters, use an
explicit approval record bound to the Git diff base and reject uncovered or
excluded paths.

## Final Evidence

Report selected profile and areas, changed facts/files, invariant/integrity
result, synchronized surfaces, validation run or skipped with reason, approval
scope, context expansion, commit-policy check when committing, and residual
risk.

## Full Alatyr Branch Mode

This branch enables the complete Alatyr capability graph. For architecture, diagrams, dependency knowledge, vocabulary, code documentation, test-first, workspace modes, team coordination, AI infrastructure, extensions, delegation, durable approvals, change packages, and large-task work, load the routed owner files from `.ai/assistant/context-router.json` and `.ai/assistant/module-profile.md` before acting.

Runtime-specific assistant and delegation capabilities must be verified from `.ai/assistant/assistant-capabilities.json` and `.ai/assistant/bridge-capability-matrix.md` before use.
