# Adapter Recheck Flow

Use this flow after installing or updating Alatyr Core in `Doctrine ORM`, or
when the programmer asks whether the installed adapter is still coherent.

## Target Sources

- Framework baseline/source: `https://github.com/atvardovsky/AlatyrCore`
- Adapter manifest: `.ai/alatyr.yaml`
- Installation or update note: `.ai/assistant/templates/installation-note.md`
- Project blueprint index: `.ai/project/blueprint.md`
- Business logic layer: `.ai/project/business-logic.md`
- Project source of truth: `README.md, docs/en/reference/*.rst, SECURITY.md, CONTRIBUTING.md, composer.json, tests/README.markdown, and CI workflows`
- Source-of-truth registry: `.ai/project/source-of-truth-registry.md`
- Context router: `.ai/assistant/context-router.json`
- Context profiles: `.ai/assistant/context-profiles.md`
- Module profile: `.ai/assistant/module-profile.md`
- Maturity profile: `.ai/assistant/maturity-profile.md`
- Target validation: `/usr/local/bin/composer8 install`; `/usr/local/bin/php8 vendor/bin/phpunit`; `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`; `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G`; `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G`; docs validation only after the docs script resolves a PHP 8-compatible composer command
- Supported assistants: `Codex`
- Operation index, catalog, help, routing, health, and preview:
  `.ai/assistant/operation-index.json`,
  `.ai/assistant/operation-catalog.json`, `.ai/assistant/help.md`,
  `.ai/assistant/flows/operation-routing.flow.md`,
  `.ai/assistant/flows/adapter-health.flow.md`,
  `.ai/assistant/templates/pre-change-preview.md`
- Chat-message templates: `.ai/assistant/templates/post-install-message.md`,
  `.ai/assistant/templates/post-update-message.md`
- Migration note template: `.ai/assistant/templates/migration-note.md`
- Optional module files: check only when the corresponding module is enabled
  in `.ai/assistant/module-profile.md` or `.ai/alatyr.yaml`; missing optional
  files for deferred modules are not health failures.
- Known adapter gaps: `backup owner missing; no target-local checker; non-blueprint optional modules deferred; local SQLite lacks SQRT`

## Steps

1. Treat `AGENTS.md` as preloaded, load the compact bootstrap, and select the
   `framework-upgrade` profile plus only affected adapter areas. Do not load
   all `.ai/project` or `.ai/assistant` files before identifying the recheck
   scope.
2. Identify whether this is a post-installation recheck, framework update
   recheck, bridge compatibility review, or maturity audit.
3. Prepare or review migration assessment evidence before target changes.
   Compare rule registries, installed and next framework files, framework
   version, adapter schema version, template version, and current structural
   validator findings.
4. Use affected canonical sources, rule categories, task profiles, template
   surfaces, bridge capabilities, and local deviations from that assessment to
   select additional context. Do not load the full framework corpus by default.
5. Compare installed framework files against the recorded framework baseline or
   update source.
6. Compare framework version, adapter schema version, template version, module
   states, known gaps, local deviations, and owner facts in `.ai/alatyr.yaml`.
7. Check required core and optional module state in
   `.ai/assistant/module-profile.md`.
8. Check target adapter references to installed framework files, operation
   help, routing flows, gates, checker rules, operation catalog/index, health
   and preview contracts, root bridge files, chat-message templates, and
   final-evidence expectations. Check optional-module paths only when the
   module is enabled or the file is present.
9. Check adapter drift hazards: hard-coded local machine paths in `.ai/*`,
   root assistant entry points, bridge files, templates, and policies; stale
   statements about whether local Alatyr or adapter checkers exist; duplicate
   required-context references inside context profiles or router entries;
   missing `.ai/assistant/context-router.json` references where bootstrap
   routing is described; unresolved owner placeholders that are not recorded as
   known gaps; and target-local adapter checker evidence that no longer matches
   repository files.
10. Check project blueprint/source-of-truth ownership, registry entries,
   consistency-map nodes and edges when enabled, missing facts, stale
   relationships, and drift.
    When architecture knowledge is enabled, check catalog ownership, decision
    authority, item states, selected evidence, revision freshness,
    documentation routes, contradictions, and accepted-decision handoff.
11. Check security, live-service, destructive-operation, dependency, credential,
   diagram, generated-artifact, validation, and lifecycle policies.
12. Check task-specific maturity using `.ai/assistant/maturity-profile.md` when
   it exists.
13. Check bridge behavior through `AGENTS.md`, `AI_ASSISTANTS.md`, and any
    installed bridge capability matrix. When diagrams are enabled, verify discussion routing, source/visual
    ownership, inline or artifact capability claims, portable ASCII, and
    stale-view evidence for every supported assistant.
14. When team collaboration is enabled, preserve active task IDs and external
    references; check coordination backend direction, actors and authority,
    stale claims, evidence revisions, concurrent changed-fact overlaps,
    checkpoints, handoffs, decisions, review state, and merge-readiness
    invalidation.
    When change packages are enabled, preserve historical target records and
    check semantic approval fields, companion decisions, correction impact,
    provenance quality, and target validator support.
    When test-first development is enabled, preserve target commands, trigger
    severity, modes, levels, isolation, exceptions, policy revision, and
    historical evidence; recheck recommendation and RED/GREEN routing without
    silently enabling new CI or merge requirements.
    When extensions are enabled, preserve catalog and lock IDs, immutable
    source revisions/digests, target bindings, permissions, approvals, local
    deviations, installed-file ownership, and historical lifecycle evidence;
    recheck compatibility and drift without automatic updates.
15. Identify required migrations, approvals, unresolved facts, and skipped
   checks.
16. Use `.ai/assistant/templates/migration-note.md` when a framework update
    requires target migration evidence.
17. Report effectiveness inline when comparing adapter effectiveness across
    comparable tasks or adapter states, unless a future adapter expansion
    installs a dedicated effectiveness-report template.
18. Run target validation that exists. Do not invent commands.
19. Classify final evidence as `current-state`, `historical-record`, or `mixed`.
    Current files prove current structure only; name dated operation, approval,
    validation, or migration records before making historical claims.
20. Report final evidence and residual risk.

## Final Evidence

Report:

- recheck type
- framework baseline or update source
- migration assessment and affected canonical sources selected from it
- candidate context intentionally omitted with reasons
- framework version, adapter schema version, and template version
- files inspected
- evidence basis, observation time, and repository revision when available
- historical records used and historical claims that remain unverifiable
- adapter references changed or still current
- adapter drift checks result, including local path leakage, stale checker
  statements, duplicate profile references, context-router references, owner
  placeholders, and target-local checker evidence
- blueprint/source-of-truth registry status
- consistency-map relationship coverage and staleness status
- architecture catalog owner, decision authority, states, selected evidence,
  validation, contradictions, and revision status
- context router and context profile status
- module profile status
- code-documentation profile ownership, source-set match ambiguity, accepted
  state, canonical fact boundaries, generator/output policy, direct-edit rule,
  adapted skill, validation, and evidence revision
- project-vocabulary ownership, term decision authority, scoped state and
  ambiguity, alias/acronym lookup, canonical sources, data links,
  normalization boundaries, adapted skill, validation, and evidence revision
- test-first policy owner, authority, state, trigger severity, modes, levels,
  commands, isolation, exceptions, recommendation behavior, adapted skill,
  RED/GREEN evidence contract, validation, and evidence revision
- extension state owner, catalog/lock consistency, source revision/digest,
  license and compatibility, target bindings, permissions, approval,
  installed-file hashes/ownership, dependents, update/removal safety, and
  lifecycle evidence
- catalog, automatic routing, health, preview, help, AI infrastructure
  inventory/recommendation, bridge, prompt,
  skill, gate, checker, item router, recommendation/adaptation records,
  large-task orchestration, diagram, chat-message, and lifecycle status
- team module owner/backend, active registry schema and references, stale
  claims, overlaps, handoffs, decision destinations, review evidence, and
  revision-bound merge readiness
- change-package index, record schema, target record preservation, semantic
  approval scope, provenance policy, and validator support
- development-pattern index schema, owner, retention/privacy policy, evidence
  references, and target-only optimization boundary
- bridge capability matrix status
- diagram discussion presentation, ASCII readability, and source-revision status
- target validation run or unresolved
- approvals needed
- task-specific maturity level and gaps
- migration note created or not needed
- residual risk

## Rejection Criteria

Reject or revise recheck work that:

- claims success without inspecting the installed target adapter
- overwrites target facts just because the source framework changed
- copies Alatyr Core source-repository commands into the target
- ignores supported assistant bridge drift
- hides missing validation, missing approval, or maturity gaps
