# Alatyr Core Installation Note

Installation id: `ALATYR-20260821-doctrine-orm`

- Installed from: `https://github.com/atvardovsky/AlatyrCore`
- Framework version: `0.1.0-alpha.27`
- Adapter schema version: `25`
- Template version: `25`
- Adapter manifest: `.ai/alatyr.yaml`
- Installation date: `2026-08-21`
- Last adapter update: `2026-08-24`
- Adapter owner: `@atvardovsky`
- Backup owner: missing; no separate backup owner found in target evidence
- Review cadence: quarterly or after Alatyr framework/template updates
- CODEOWNERS or equivalent owner map: `CODEOWNERS`
- Supported assistants: Codex
- Target validation: /usr/local/bin/composer8 install; /usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpunit; /usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G; /usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G; /usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G; docs validation after the docs script resolves a PHP 8-compatible composer command
- Known adapter gaps: backup owner missing; target-local Alatyr checker wrapper committed at tools/check_alatyr.py; full optional module graph enabled; project-knowledge routing has no accepted entries yet; default php/composer are unsuitable for this branch; local SQLite lacks SQRT for two full PHPUnit tests
- Local deviations from Alatyr Core: full complete pack adapted to Doctrine ORM; CODEOWNERS scoped to adapter and bridge files; `.ai/project/blueprint.md` accepted as the Doctrine ORM blueprint index; `tools/check_alatyr.py` wraps the source validator via `ALATYR_CORE_SOURCE`
- Root assistant entry points checked: `AGENTS.md`, `AI_ASSISTANTS.md`, `CLAUDE.md`, `GEMINI.md`
- Supported bridge files checked: Codex, generic agents, Claude, Cursor, Devin/Cascade, Gemini, GitHub Copilot, and Windsurf bridge files
- Installed-operation request template: `.ai/assistant/templates/operation-request.md`
- Adapter output contracts: `.ai/assistant/templates/adapter-output-contracts.md`
- Operation help: `.ai/assistant/help.md`
- Operation help reference: `.ai/assistant/help-reference.md`
- Compact operation index: `.ai/assistant/operation-index.json`
- Operation catalog: `.ai/assistant/operation-catalog.json`
- Current-scope action authorization policy: `.ai/assistant/policies/action-authorization.json`
- Durable engineering-evidence index: `.ai/project/engineering-evidence/index.json`
- Project-knowledge index: `.ai/project/knowledge/index.json`
- Project-knowledge routing descriptor: `.ai/assistant/context/project-knowledge-routing.json`
- Context router: `.ai/assistant/context-router.json`
- Generated bootstrap index: `.ai/assistant/bootstrap-index.json`
- Routed gate index: `.ai/assistant/gates/index.json`
- Context profiles: `.ai/assistant/context-profiles.md`
- Module profile: `.ai/assistant/module-profile.md`
- Source-of-truth registry: `.ai/project/source-of-truth-registry.md`
- Blueprint index: `.ai/project/blueprint.md`
- Business logic layer: `.ai/project/business-logic.md`
- Maturity profile: `.ai/assistant/maturity-profile.md`
- Operation routing flow: `.ai/assistant/flows/operation-routing.flow.md`
- Adapter health flow: `.ai/assistant/flows/adapter-health.flow.md`
- Pre-change preview: `.ai/assistant/templates/pre-change-preview.md`
- Migration note template: `.ai/assistant/templates/migration-note.md`
- Post-install chat message template: `.ai/assistant/templates/post-install-message.md`
- Post-update chat message template: `.ai/assistant/templates/post-update-message.md`

## Installation Plan Summary

- Target repository: repository root
- New install or upgrade: new install
- Primary stack: PHP 8.1+ Composer library
- Existing AI instructions before install: none found
- Existing CODEOWNERS before install: none found
- Scaffold helper used: `tools/scaffold_target_structure.py --profile full --framework-pack complete --write`
- Framework pack: complete
- Enabled optional modules: full optional capability graph recorded in `.ai/assistant/module-profile.md`
- Deferred modules: none
- Protected target behavior changed: none; adapter-only files added
- Approval required: no separate approval required because the programmer explicitly requested installation, no existing instructions were overwritten, and no runtime/security behavior was changed

## Future Session Bootstrap

Future assistants should not rely on this chat message being visible. Treat
`AGENTS.md` as preloaded, then read `.ai/assistant/bootstrap-index.json`.
Repair a stale index from `.ai/alatyr.yaml`, `.ai/README.md`, and
`.ai/assistant/context-router.json`. Load this note after installation/update
or when adapter state is unclear.

Use `Alatyr` as the single conversational entry, `Alatyr status` for read-only
health, automatic routing for clear requests, and the risk-gated pre-change
preview before applicable edits.

Apply the current-scope action policy before `modify`, `commit`, `publish`, or
`live-external`. A previous task's authorization expires when that task is
complete or the subject changes. Backlog/issue returns, status, discussion,
analysis, reports, plans, and ambiguous continuation default to `inspect`.
