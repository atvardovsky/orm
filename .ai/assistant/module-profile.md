# Alatyr Module Profile

Use this file in Doctrine ORM to record which Alatyr Core capabilities are required, enabled, deferred, disabled, not applicable, or blocked.

## Shared Capability Surfaces

Use `.ai/framework/capabilities.json` as the lifecycle owner for target paths
produced by multiple modules. Merge all enabled producers according to the
declared strategy. Disabling one module must not remove a surface required by
another enabled producer. Preserve a target-owned shared surface when
`preserve_on_disable` is true; any later cleanup requires explicit scope and
evidence that no target facts or active capability output will be lost.

## Required Core Profile

Core profile state: complete-with-known-gaps
Framework pack: complete
Pack inventory: `.ai/framework/file-inventory.json`
Required pack expansion: none
Last reviewed: 2026-08-26
Reviewed by: Codex alpha.31 adapter migration for @atvardovsky

Core item: `contours`
State: required-enabled
Owner or file: .ai/README.md, .ai/project/contour.md, .ai/assistant/contour.md
Required files:
- `.ai/README.md`
- `.ai/project/contour.md`
- `.ai/assistant/contour.md`
Evidence: target facts separated from portable framework and assistant mechanics
Validation or review: adapter validator plus manual owner-evidence review
Approval needs: approval required before weakening gates, protected approvals, validation, or source-of-truth ownership
Residual risk: backup owner is still unresolved; full runtime tests remain subject to local SQLite SQRT blocker

Core item: `manifest-and-versioning`
State: required-enabled
Owner or file: .ai/alatyr.yaml
Required files:
- `.ai/alatyr.yaml`
Evidence: framework version, adapter schema, template version, complete pack, and full enabled module graph recorded
Validation or review: adapter validator plus manual owner-evidence review
Approval needs: approval required before weakening gates, protected approvals, validation, or source-of-truth ownership
Residual risk: backup owner is still unresolved; full runtime tests remain subject to local SQLite SQRT blocker

Core item: `adapter-ownership`
State: required-enabled-with-gap
Owner or file: .ai/alatyr.yaml and CODEOWNERS
Required files:
- `.ai/alatyr.yaml`
- `CODEOWNERS`
Evidence: @atvardovsky recorded for adapter and bridge files
Validation or review: adapter validator plus manual owner-evidence review
Approval needs: approval required before weakening gates, protected approvals, validation, or source-of-truth ownership
Residual risk: backup owner is still unresolved; full runtime tests remain subject to local SQLite SQRT blocker

Core item: `context-profiles`
State: required-enabled
Owner or file: .ai/assistant/context-profiles.md and .ai/assistant/context-router.json
Required files:
- `.ai/assistant/context-profiles.md`
- `.ai/assistant/context-router.json`
- `.ai/assistant/context/profiles/*.json`
Evidence: profiles, intent overlays, task-scale overlays, workspace mode routing, and area overlays installed
Validation or review: adapter validator plus manual owner-evidence review
Approval needs: approval required before weakening gates, protected approvals, validation, or source-of-truth ownership
Residual risk: backup owner is still unresolved; full runtime tests remain subject to local SQLite SQRT blocker

Core item: `source-of-truth-registry`
State: required-enabled
Owner or file: .ai/project/source-of-truth-registry.md
Required files:
- `.ai/project/source-of-truth-registry.md`
- `.ai/project/consistency-map.json`
Evidence: registry plus consistency map route product, behavior, architecture, data, validation, security, assistant, process, and commit facts
Validation or review: adapter validator plus manual owner-evidence review
Approval needs: approval required before weakening gates, protected approvals, validation, or source-of-truth ownership
Residual risk: backup owner is still unresolved; full runtime tests remain subject to local SQLite SQRT blocker

Core item: `risk-approval-integrity`
State: required-enabled
Owner or file: .ai/framework/change-risk-model.md, .ai/framework/approval-records.md, .ai/framework/logical-integrity.md, and target gates
Required files:
- `.ai/assistant/gates/core.md`
- `.ai/assistant/gates/semantic-integrity.md`
- `.ai/assistant/gates/security-approval.md`
- `.ai/assistant/gates/final-evidence.md`
- `.ai/assistant/approvals/approval-template.md`
Evidence: risk model, durable approvals, semantic integrity, and final evidence gates installed
Validation or review: adapter validator plus manual owner-evidence review
Approval needs: approval required before weakening gates, protected approvals, validation, or source-of-truth ownership
Residual risk: backup owner is still unresolved; full runtime tests remain subject to local SQLite SQRT blocker

Core item: `current-scope-action-authorization`
State: required-enabled
Owner or file: .ai/assistant/policies/action-authorization.json
Required files:
- `.ai/assistant/policies/action-authorization.json`
Evidence: current-scope phase policy separates inspect, modify, commit, publish, and live-external authorization
Validation or review: adapter validator plus manual owner-evidence review
Approval needs: approval required before changing authorization phases, weakening gates, or broadening protected action scope
Residual risk: human requests can remain ambiguous; unresolved phase authorization defaults to inspect only

Core item: `validation-and-final-evidence`
State: required-enabled-with-gap
Owner or file: .ai/alatyr.yaml, .ai/assistant/gates/final-evidence.md, target validation files
Required files:
- `.ai/assistant/gates/final-evidence.md`
- `.ai/alatyr.yaml`
Evidence: Composer, PHPUnit, PHPStan, PHPCS, docs, adapter validation, and known local blockers recorded
Validation or review: adapter validator plus manual owner-evidence review
Approval needs: approval required before weakening gates, protected approvals, validation, or source-of-truth ownership
Residual risk: backup owner is still unresolved; full runtime tests remain subject to local SQLite SQRT blocker

Core item: `durable-engineering-evidence`
State: required-enabled
Owner or file: .ai/project/engineering-evidence/README.md
Required files:
- `.ai/project/engineering-evidence/README.md`
- `.ai/project/engineering-evidence/index.json`
- `.ai/assistant/context/task-scales/engineering-evidence.json`
- `.ai/assistant/flows/engineering-evidence-capture.flow.md`
- `.ai/assistant/gates/engineering-evidence.md`
- `.ai/assistant/templates/engineering-evidence-record.json`
Evidence: compact reviewed evidence records are repository-internal and exclude raw private chat, secrets, credentials, and external-only logs
Validation or review: adapter validator plus manual evidence-policy review
Approval needs: approval required before changing retention, privacy, or external patch inclusion policy
Residual risk: capture quality still depends on explicit finalization and human review

Core item: `project-knowledge-delivery`
State: required-enabled
Adoption state: enabled-empty
Owner or file: .ai/project/knowledge/README.md
Required files:
- `.ai/project/knowledge/README.md`
- `.ai/project/knowledge/index.json`
- `.ai/project/knowledge/routes/README.md`
- `.ai/project/knowledge/promotions/README.md`
- `.ai/assistant/context/project-knowledge-routing.json`
- `.ai/assistant/flows/project-knowledge.flow.md`
- `.ai/assistant/gates/project-knowledge.md`
- `.ai/assistant/templates/project-knowledge-promotion.json`
- `.ai/assistant/templates/project-knowledge-route-shard.json`
Evidence: empty target-owned routing index installed with explicit owner, guidance origin, coverage, exception, review, retention, and redaction policy; no guidance item is promoted without decision-owner review and canonical-owner validation
Validation or review: adapter validator plus manual project-knowledge policy review
Approval needs: approval required before accepting a promotion, changing a canonical owner, or altering project-knowledge retention and privacy policy
Residual risk: no accepted route entries exist yet; useful reusable conclusions must still be proposed, reviewed, and promoted before routine delivery

## Optional Modules

Module: `blueprint-change`
State: enabled
Owner or file: .ai/project/blueprint.md
Required files:
- `.ai/assistant/flows/blueprint-driven-change.flow.md`
- `.ai/assistant/flows/project-blueprint-creation.flow.md`
Reason: target blueprint index accepted and wired to Doctrine source-of-truth files
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: keep blueprint, business logic, registry, and flows synchronized

Module: `consistency-map`
State: enabled
Owner or file: .ai/project/consistency-map.json
Required files:
- `.ai/project/source-of-truth-registry.md`
- `.ai/project/consistency-map.json`
Reason: relationship map accepted for manual impact closure across facts, owners, surfaces, and validation
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: update map when new fact families or derived surfaces are accepted

Module: `architecture-knowledge`
State: enabled
Owner or file: .ai/project/architecture/catalog.json
Required files:
- `.ai/project/architecture/README.md`
- `.ai/project/architecture/catalog.json`
- `.ai/assistant/flows/architecture-assistance.flow.md`
- `.ai/assistant/templates/architecture-pattern.md`
- `.ai/assistant/templates/architecture-area.md`
- `.ai/assistant/templates/architecture-discussion-result.md`
Reason: architecture catalog accepted from Doctrine architecture docs, composer metadata, source areas, and validation files
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: keep catalog in sync with architecture docs/source/tests

Module: `code-documentation`
State: enabled
Owner or file: .ai/project/documentation/catalog.json
Required files:
- `.ai/project/documentation/README.md`
- `.ai/project/documentation/catalog.json`
- `.ai/project/documentation/profiles.json`
- `.ai/assistant/context/intents/code-documentation.json`
- `.ai/assistant/flows/documentation-sync.flow.md`
- `.ai/assistant/templates/code-documentation-profile-review.md`
- `.ai/assistant/skills/code-documentation/SKILL.md`
Reason: code documentation profiles accepted for PHP source, tests, docs, and adapter files
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: use documentation-sync before broad comment or docs policy changes

Module: `project-vocabulary`
State: enabled
Owner or file: .ai/project/vocabulary/catalog.json
Required files:
- `.ai/project/vocabulary/README.md`
- `.ai/project/vocabulary/catalog.json`
- `.ai/project/vocabulary/terms.json`
- `.ai/project/vocabulary/data-dictionary-links.json`
- `.ai/assistant/context/intents/vocabulary-request.json`
- `.ai/assistant/flows/project-vocabulary.flow.md`
- `.ai/assistant/templates/vocabulary-term-review.md`
- `.ai/assistant/skills/project-vocabulary/SKILL.md`
Reason: selective vocabulary catalog accepted for ORM and Alatyr routing terminology
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: add terms only from canonical docs/source evidence

Module: `test-first-development`
State: enabled
Owner or file: .ai/project/testing/test-first-policy.json
Required files:
- `.ai/project/testing/README.md`
- `.ai/project/testing/test-first-policy.json`
- `.ai/assistant/context/intents/test-first-request.json`
- `.ai/assistant/flows/test-first-configuration.flow.md`
- `.ai/assistant/flows/test-first-change.flow.md`
- `.ai/assistant/gates/test-first-development.md`
- `.ai/assistant/templates/test-first-evidence.md`
- `.ai/assistant/skills/test-first-development/SKILL.md`
Reason: advisory regression-first, characterization-first, and contract-first policy accepted for behavior-changing work
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: record trigger result and validation evidence on code changes

Module: `extensions`
State: enabled
Owner or file: .ai/assistant/extensions/catalog.json
Required files:
- `.ai/assistant/extensions/README.md`
- `.ai/assistant/extensions/catalog.json`
- `.ai/assistant/extensions/lock.json`
- `.ai/assistant/context/intents/extension-request.json`
- `.ai/assistant/flows/extension-lifecycle.flow.md`
- `.ai/assistant/gates/extensions.md`
- `.ai/assistant/templates/extension-review.md`
- `.ai/assistant/templates/extension-lifecycle-record.md`
Reason: extension lifecycle surfaces installed with empty catalog and approval-first policy
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: review provenance, permissions, prompt-injection, and approval before installing extensions

Module: `dependency-knowledge`
State: enabled
Owner or file: .ai/project/dependencies/policy.json
Required files:
- `.ai/project/dependencies/README.md`
- `.ai/project/dependencies/policy.json`
- `.ai/project/dependencies/catalog.json`
- `.ai/project/dependencies/knowledge-lock.json`
- `.ai/project/dependencies/deviations.json`
- `.ai/project/dependencies/snapshots/README.md`
- `.ai/assistant/context/intents/dependency-knowledge-request.json`
- `.ai/assistant/flows/dependency-knowledge-sync.flow.md`
- `.ai/assistant/gates/dependency-knowledge.md`
- `.ai/assistant/templates/dependency-knowledge-sync-report.md`
Reason: Composer dependency knowledge policy and catalog accepted from composer.json and lock fingerprint evidence
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: refresh catalog when composer metadata or lockfile changes

Module: `workspace-modes`
State: enabled
Owner or file: .ai/project/workspace-modes/catalog.json
Required files:
- `.ai/project/workspace-modes/README.md`
- `.ai/project/workspace-modes/catalog.json`
- `.ai/project/workspace-modes/root/README.md`
- `.ai/project/workspace-modes/root/context.json`
- `.ai/project/workspace-modes/modes/_template/README.md`
- `.ai/project/workspace-modes/modes/_template/mode.json`
- `.ai/assistant/context/intents/workspace-mode-request.json`
- `.ai/assistant/flows/workspace-mode.flow.md`
- `.ai/assistant/gates/workspace-mode.md`
- `.ai/assistant/templates/workspace-mode-suggestion.md`
- `.ai/assistant/templates/workspace-mode-preflight.md`
Reason: Doctrine ORM library workspace mode accepted for this repository root
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: add new modes only with reviewed mode records

Module: `diagrams`
State: enabled
Owner or file: .ai/assistant/flows/diagram-discussion.flow.md
Required files:
- `.ai/assistant/flows/diagram-discussion.flow.md`
- `.ai/assistant/templates/diagram-presentation.md`
- `.ai/assistant/templates/ascii-diagram.md`
- `.ai/assistant/assistant-capabilities.json`
- `.ai/assistant/bridge-capability-matrix.md`
Reason: diagram discussion and ASCII presentation templates enabled for explanation and docs sync
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: keep diagrams evidence-bound; no generated artifact is canonical without review

Module: `ai-infrastructure`
State: enabled
Owner or file: .ai/assistant/ai-infrastructure-router.json
Required files:
- `.ai/assistant/ai-infrastructure-router.json`
- `.ai/assistant/bridge-capability-matrix.md`
- `.ai/assistant/context/profiles/ai-infrastructure.json`
- `.ai/assistant/flows/ai-infrastructure-inventory.flow.md`
- `.ai/assistant/flows/ai-infrastructure-recommendation.flow.md`
- `.ai/assistant/flows/development-evidence-capture.flow.md`
- `.ai/assistant/flows/skill-adaptation.flow.md`
- `.ai/assistant/policies/ai-infrastructure-source-access.md`
- `.ai/assistant/policies/prompt-injection.md`
- `.ai/assistant/templates/ai-infrastructure-inventory.md`
- `.ai/assistant/templates/ai-infrastructure-recommendation.md`
- `.ai/assistant/templates/ai-infrastructure-adaptation-record.md`
- `.ai/project/development-evidence.json`
Reason: AI infrastructure inventory, recommendation, adaptation, and prompt-injection policies enabled
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: verify runtime capabilities before relying on them

Module: `multi-assistant-bridges`
State: enabled
Owner or file: .ai/assistant/assistant-capabilities.json
Required files:
- `.ai/assistant/bridge-capability-matrix.md`
- `.ai/assistant/assistant-capabilities.json`
Reason: multi-assistant bridge files and capability records installed
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: keep bridge files as pointers and update capability evidence when clients change

Module: `installed-operations`
State: enabled
Owner or file: .ai/assistant/operation-catalog.json
Required files:
- `.ai/assistant/operation-index.json`
- `.ai/assistant/operation-catalog.json`
- `.ai/assistant/help.md`
- `.ai/assistant/help-reference.md`
- `.ai/assistant/flows/operation-routing.flow.md`
- `.ai/assistant/flows/adapter-health.flow.md`
- `.ai/assistant/templates/pre-change-preview.md`
Reason: full operation catalog and compact index installed
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: regenerate operation index when catalog changes

Module: `large-task-orchestration`
State: enabled
Owner or file: .ai/assistant/flows/large-task-orchestration.flow.md
Required files:
- `.ai/assistant/flows/large-task-orchestration.flow.md`
- `.ai/assistant/templates/large-task-operation-packet.md`
Reason: large task packets and task-scale routing enabled for resumable/cross-boundary work
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: use only when scope warrants workstreams/checkpoints

Module: `subagent-delegation`
State: enabled
Owner or file: .ai/assistant/delegation-policy.json
Required files:
- `.ai/assistant/delegation-policy.json`
- `.ai/assistant/context/task-scales/delegated-execution.json`
- `.ai/assistant/flows/subagent-delegation.flow.md`
- `.ai/assistant/templates/subagent-task-packet.md`
- `.ai/assistant/assistant-capabilities.json`
- `.ai/assistant/bridge-capability-matrix.md`
Reason: suggest-only delegation policy enabled with primary convergence and disjoint-scope requirements
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: verify tool/model availability per task

Module: `change-packages`
State: enabled
Owner or file: .ai/assistant/change-packages/index.json
Required files:
- `.ai/assistant/change-packages/index.json`
- `.ai/assistant/context/task-scales/change-package.json`
- `.ai/assistant/flows/change-package.flow.md`
- `.ai/assistant/templates/change-package-record.json`
- `.ai/assistant/templates/change-package-report.md`
Reason: change package index and templates enabled for material multi-surface outcomes
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: create packages only for tasks that need audit-grade before/after records

Module: `team-collaboration`
State: enabled
Owner or file: .ai/project/team-policy.json
Required files:
- `.ai/project/team-policy.json`
- `.ai/project/team-operating-model.md`
- `.ai/assistant/team/context-overlay.json`
- `.ai/assistant/team/work-registry.json`
- `.ai/assistant/team/active-work-index.json`
- `.ai/assistant/team/backend-contract.json`
- `.ai/assistant/team/task-record-template.json`
- `.ai/assistant/flows/team-identity.flow.md`
- `.ai/assistant/flows/team-task-coordination.flow.md`
- `.ai/assistant/flows/team-handoff.flow.md`
- `.ai/assistant/flows/team-decision.flow.md`
- `.ai/assistant/flows/team-review.flow.md`
- `.ai/assistant/gates/team-collaboration.md`
- `.ai/assistant/skills/team-collaboration/SKILL.md`
Reason: repository-local team policy, work registry, and flows enabled
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: avoid external tracker writes unless separately approved

Module: `durable-approvals`
State: enabled
Owner or file: .ai/assistant/approvals/approval-template.md
Required files:
- `.ai/assistant/approvals/approval-template.md`
- `.ai/assistant/approvals/approval-record-template.json`
Reason: durable approval templates installed for protected scope records
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: use explicit approval records for protected changes

Module: `migration-diff`
State: enabled
Owner or file: .ai/assistant/templates/migration-note.md
Required files:
- `.ai/assistant/context/migration-routing.json`
- `.ai/assistant/templates/migration-note.md`
Reason: migration routing and note template already installed and now part of full profile
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: use before framework updates or schema migrations

Module: `effectiveness-metrics`
State: enabled
Owner or file: .ai/assistant/templates/effectiveness-report.md
Required files:
- `.ai/assistant/templates/effectiveness-report.md`
- `.ai/assistant/templates/delayed-outcome-evidence.json`
- `.ai/assistant/templates/adapter-maintenance-evidence.json`
Reason: effectiveness report template enabled for measuring Alatyr outcomes
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: record only summarized metrics without private raw conversation data

Module: `debug-mode`
State: enabled
Owner or file: .ai/project/debug/README.md
Dependencies: effectiveness-metrics, installed-operations
Required files:
- `.ai/framework/debug-mode.md`
- `.ai/project/debug/README.md`
- `.ai/project/debug/index.json`
- `.ai/project/debug/records/README.md`
- `.ai/assistant/context/task-scales/debug-mode.json`
- `.ai/assistant/flows/debug-mode.flow.md`
- `.ai/assistant/gates/debug-mode.md`
- `.ai/assistant/templates/debug-session-record.json`
- `.ai/assistant/templates/debug-summary.md`
Reason: optional non-canonical observability records are enabled for explicitly selected Doctrine ORM Alatyr tasks
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit current-scope adapter-only authorization before creating or updating debug records; approval required before changing privacy, retention, or external patch inclusion policy
Residual risk: debug capture is inactive unless explicitly enabled per task or session, and record quality depends on honest event attribution
Next action: keep Debug Mode inactive by default and create records only after explicit per-scope activation

Module: `scaffolding`
State: enabled
Owner or file: .ai/framework/scaffolding.md
Required files:
- `.ai/framework/scaffolding.md`
Reason: scaffolding rules and complete pack files installed
Validation or review: adapter validator, module-specific checker when available, JSON/YAML parse, local path/placeholder scan, and manual review
Approval needs: explicit approval before protected, external, dependency, permission, live-service, destructive, or weakened-gate changes
Residual risk: assistant/client runtime features are evidence-bound; unverified bridge capabilities must be reported before use
Next action: use scaffold helpers only with review and no overwrite unless approved

## Evidence

Report enabled modules, deferred modules, blocked modules, files created or
skipped, shared surfaces retained or merged, validation, approvals, and
residual risk before claiming adapter maturity.
