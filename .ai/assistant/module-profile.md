# Alatyr Module Profile

Use this file in Doctrine ORM to record which Alatyr Core capabilities are
required, enabled, deferred, disabled, not applicable, or blocked.

## Required Core Profile

Core profile state: complete-with-known-gaps
Framework pack: standard
Pack inventory: `.ai/framework/file-inventory.json`
Required pack expansion: none
Last reviewed: 2026-08-21
Reviewed by: Codex installation for @atvardovsky

Core item: `contours`
State: required-enabled
Owner or file: `.ai/README.md`, `.ai/project/contour.md`, `.ai/assistant/contour.md`
Required files:

- `.ai/README.md`
- `.ai/project/contour.md`
- `.ai/assistant/contour.md`

Evidence: target facts separated from portable framework and assistant mechanics
Validation or review: Alatyr source validator plus manual review
Approval needs: no separate approval for fresh adapter-only installation
Residual risk: no backup owner found in target evidence

Core item: `manifest-and-versioning`
State: required-enabled
Owner or file: `.ai/alatyr.yaml`
Required files:

- `.ai/alatyr.yaml`

Evidence: framework version 0.1.0-alpha.15, adapter schema 14, template 15
Validation or review: bootstrap regeneration and target adapter validation
Approval needs: approval required for protected adapter behavior changes or overwrites
Residual risk: no target-local checker committed

Core item: `adapter-ownership`
State: required-enabled-with-gap
Owner or file: `.ai/alatyr.yaml` and `CODEOWNERS`
Required files:

- `.ai/alatyr.yaml`
- `CODEOWNERS`

Evidence: @atvardovsky recorded for adapter files; no previous CODEOWNERS existed
Validation or review: manual review and Git status
Approval needs: none for fresh adapter-only install
Residual risk: backup owner missing

Core item: `context-profiles`
State: required-enabled
Owner or file: `.ai/assistant/context-profiles.md` and `.ai/assistant/context-router.json`
Required files:

- `.ai/assistant/context-profiles.md`
- `.ai/assistant/context-router.json`
- `.ai/assistant/context/profiles/*.json`

Evidence: profiles mapped to Doctrine source, docs, validation, architecture, data, security, and adapter upgrade contexts
Validation or review: target adapter validator context-cost measurements
Approval needs: approval required before weakening gates or approval rules
Residual risk: no consistency map, so cross-area impact closure is manual

Core item: `source-of-truth-registry`
State: required-enabled-with-gap
Owner or file: `.ai/project/source-of-truth-registry.md`
Required files:

- `.ai/project/source-of-truth-registry.md`

Evidence: registry maps product, behavior, architecture, data, validation, security, assistant operation, and process-pattern owners from target files
Validation or review: manual review plus target adapter validator
Approval needs: protected semantic changes require explicit approval
Residual risk: optional consistency map is deferred

Core item: `risk-approval-integrity`
State: required-enabled
Owner or file: `.ai/framework/change-risk-model.md`, `.ai/framework/approval-records.md`, `.ai/framework/logical-integrity.md`, and target gates
Required files:

- `.ai/assistant/gates/core.md`
- `.ai/assistant/gates/semantic-integrity.md`
- `.ai/assistant/gates/security-approval.md`
- `.ai/assistant/gates/final-evidence.md`

Evidence: installed standard framework pack and target gate fragments
Validation or review: manual risk review on each task
Approval needs: required for architecture, accepted behavior, security, dependency, destructive, live, production, or weakened-gate changes
Residual risk: no durable approval-record module enabled

Core item: `validation-and-final-evidence`
State: required-enabled
Owner or file: `.ai/alatyr.yaml`, `.ai/assistant/gates/final-evidence.md`, target validation files
Required files:

- `.ai/assistant/gates/final-evidence.md`
- `.ai/alatyr.yaml`

Evidence: Composer, PHPUnit, PHPStan, PHPCS, docs, and GitHub Actions evidence recorded
Validation or review: run applicable commands when dependencies/extensions are available; otherwise report skip reason
Approval needs: required before weakening validation or gates
Residual risk: local SQLite lacks the SQL `SQRT()` function, so full PHPUnit is environment-blocked unless SQLite/runtime changes

## Optional Modules

Module: `blueprint-change`
State: enabled
Owner or file: `.ai/project/blueprint.md`, `.ai/assistant/flows/project-blueprint-creation.flow.md`, `.ai/assistant/flows/blueprint-driven-change.flow.md`
Reason: target blueprint index accepted and wired to Doctrine ORM source-of-truth docs, source, tests, validation files, and adapter routing
Validation or review: adapter validator plus manual source-of-truth review before accepted behavior changes
Approval needs: approval required for accepted product, architecture, or public-contract changes
Residual risk: no consistency map, so broad product changes require manual impact closure
Next action: keep `.ai/project/blueprint.md`, source registry, context profiles, and flows synchronized when source ownership changes

Module: `architecture-knowledge`
State: deferred
Owner or file: missing Alatyr architecture catalog
Reason: architecture docs exist, but no project-owned architecture catalog was accepted
Validation or review: manual architecture doc review
Approval needs: approval required for accepted architecture changes
Residual risk: architecture discussions rely on selected target docs instead of a compact catalog
Next action: enable with a reviewed catalog if architecture assistance becomes recurring work

Module: `test-first-development`
State: deferred
Owner or file: missing target test-first policy
Reason: tests and CI exist, but no explicit test-first/TDD policy is accepted
Validation or review: use existing PHPUnit/PHPStan/PHPCS evidence
Approval needs: approval required before imposing new merge gates or policy
Residual risk: assistant may recommend tests but must not silently impose TDD
Next action: define target policy if desired

Module: `diagrams`
State: deferred
Owner or file: missing diagram source policy
Reason: no target-owned diagram source or renderer found
Validation or review: manual docs review
Approval needs: approval required before adding persistent diagram policy or artifacts
Residual risk: visual discussions have no accepted repository artifact owner
Next action: record diagram source and review policy before enabling

Module: `team-collaboration`
State: deferred
Owner or file: missing team policy and coordination backend
Reason: no actor, authority, priority, task backend, retention, or privacy policy found
Validation or review: manual review only
Approval needs: separate user decision required before enabling
Residual risk: no team task registry is active
Next action: define team policy and backend before enabling

Module: `ai-infrastructure`
State: deferred
Owner or file: missing AI infrastructure inventory and source-access policy
Reason: no existing target skills, prompts, tools, wrappers, or bridge surfaces were found beyond this adapter
Validation or review: manual review before importing any AI infrastructure
Approval needs: approval required for imported third-party AI infrastructure or permission changes
Residual risk: no inventory or recommendation records are active
Next action: enable only for a concrete AI infrastructure review request
