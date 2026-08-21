# Alatyr Adapter Output Contracts

Use this file in Doctrine ORM to define minimum final evidence for installed
Alatyr adapter work. Replace angle-bracket placeholders with task-specific
target facts before using a contract.

## Contract: `installation-output`

Use after initial installation or a scoped adapter-only expansion.

- Operation id: `<operation-id>`
- Evidence basis: `<current-state-historical-record-or-mixed>`
- Observed at: `<observation-date-time>`
- Observed repository revision: `<repository-revision-or-not-available>`
- Installation id: `ALATYR-20260821-doctrine-orm`
- Framework source: `https://github.com/atvardovsky/AlatyrCore`
- Framework version: `0.1.0-alpha.15`
- Adapter schema version: `14`
- Template version: `15`
- Manifest path: `.ai/alatyr.yaml`
- Surfaces created or updated: `<surfaces-created-or-updated>`
- Existing files preserved: `<existing-files-preserved>`
- Approval records used: `<approval-records-used-or-not-required>`
- Required core profile result: `<required-core-profile-result>`
- Optional module profile result: `<optional-module-profile-result>`
- Blueprint index result: `<blueprint-index-result>`
- Business logic layer result for `.ai/project/business-logic.md`:
  `<business-logic-layer-result>`
- Context router/profile result: `<context-router-profile-result>`
- Operation catalog/index result: `<operation-catalog-index-result>`
- Gate index result: `<gate-index-result>`
- Source-of-truth registry result: `<source-of-truth-registry-result>`
- Bootstrap index result: `<bootstrap-index-result>`
- Root entry points checked: `AGENTS.md and AI_ASSISTANTS.md`
- Adapter drift checks result: `<adapter-drift-checks-result>`
- Local path leakage result: `<local-path-leakage-result>`
- Target-local checker status: `<target-local-checker-status-or-unresolved>`
- Validation run: `<target-validation-run-or-manual-review>`
- Validation skipped or unresolved: `<validation-skipped-or-unresolved>`
- Final evidence: `<final-evidence>`
- Residual risk: `<residual-risk>`

## Contract: `framework-update-output`

Use after comparing the installed adapter against a newer Alatyr Core baseline.

- Operation id: `<operation-id>`
- Evidence basis: `<current-state-historical-record-or-mixed>`
- Observed at: `<observation-date-time>`
- Observed repository revision: `<repository-revision-or-not-available>`
- Update source or baseline: `<alatyr-update-source-or-baseline>`
- Previous framework version: `<previous-alatyr-core-version>`
- New framework version: `<new-alatyr-core-version>`
- Previous adapter schema version: `<previous-adapter-schema-version>`
- New adapter schema version: `<new-adapter-schema-version>`
- Previous template version: `<previous-template-version>`
- New template version: `<new-template-version>`
- Manifest path: `.ai/alatyr.yaml`
- Migration note path: `.ai/assistant/templates/migration-note.md`
- Migration assessment result: `<migration-assessment-result>`
- Changed rule ids: `<changed-rule-ids-or-none>`
- Added or removed framework files: `<added-or-removed-framework-files>`
- Target adapter actions required: `<target-adapter-actions-required>`
- Target adapter actions optional: `<target-adapter-actions-optional>`
- Approval records used: `<approval-records-used-or-not-required>`
- Context router/profile result: `<context-router-profile-result>`
- Operation catalog/index result: `<operation-catalog-index-result>`
- Gate index result: `<gate-index-result>`
- Source-of-truth registry result: `<source-of-truth-registry-result>`
- Blueprint index result: `<blueprint-index-result>`
- Business logic layer result for `.ai/project/business-logic.md`:
  `<business-logic-layer-result>`
- Bootstrap index result: `<bootstrap-index-result>`
- Adapter drift checks result: `<adapter-drift-checks-result>`
- Target-local checker status: `<target-local-checker-status-or-unresolved>`
- Validation run: `<target-validation-run-or-manual-review>`
- Validation skipped or unresolved: `<validation-skipped-or-unresolved>`
- Final evidence: `<final-evidence>`
- Residual risk: `<residual-risk>`

## Contract: `adapter-recheck-output`

Use after read-only, adapter-only, or maturity-focused rechecks.

- Operation id: `<operation-id>`
- Evidence basis: `<current-state-historical-record-or-mixed>`
- Observed at: `<observation-date-time>`
- Observed repository revision: `<repository-revision-or-not-available>`
- Recheck trigger: `<recheck-trigger>`
- Allowed actions: `<read-only-docs-only-adapter-only-code-and-tests-or-full-with-approval>`
- Manifest path: `.ai/alatyr.yaml`
- Installation note status: `<installation-note-status>`
- Framework version: `0.1.0-alpha.15`
- Adapter schema version: `14`
- Template version: `15`
- Approval records used: `<approval-records-used-or-not-required>`
- Required core profile result: `<required-core-profile-result>`
- Optional module profile result: `<optional-module-profile-result>`
- Context router/profile result: `<context-router-profile-result>`
- Operation catalog/index result: `<operation-catalog-index-result>`
- Gate index result: `<gate-index-result>`
- Source-of-truth registry result: `<source-of-truth-registry-result>`
- Blueprint index result: `<blueprint-index-result>`
- Business logic layer result for `.ai/project/business-logic.md`:
  `<business-logic-layer-result>`
- Bootstrap index result: `<bootstrap-index-result>`
- Task-specific maturity result: `<task-specific-maturity-result>`
- Root entry points checked: `AGENTS.md and AI_ASSISTANTS.md`
- Adapter drift checks result: `<adapter-drift-checks-result>`
- Local path leakage result: `<local-path-leakage-result>`
- Target-local checker status: `<target-local-checker-status-or-unresolved>`
- Validation run: `<target-validation-run-or-manual-review>`
- Validation skipped or unresolved: `<validation-skipped-or-unresolved>`
- Recommended next operation: `<recommended-next-operation-or-none>`
- Final evidence: `<final-evidence>`
- Residual risk: `<residual-risk>`

## Contract: `blueprint-or-product-change-output`

Use after `create-project-blueprint` or `product-change`.

- Operation id: `<operation-id>`
- Selected profile and project areas: `<selected-profile-and-areas>`
- Changed facts: `<changed-facts-or-none>`
- Canonical owners: `<canonical-owners>`
- Blueprint index result: `<blueprint-index-result>`
- Business logic layer result for `.ai/project/business-logic.md`:
  `<business-logic-layer-result>`
- Source-of-truth registry result: `<source-of-truth-registry-result>`
- Synchronized docs/source/tests/adapter surfaces:
  `<synchronized-surfaces-or-none>`
- Re-derived invariants: `<re-derived-invariants>`
- Logical integrity result: `<logical-integrity-result>`
- Approval records used: `<approval-records-used-or-not-required>`
- Validation run: `<target-validation-run-or-manual-review>`
- Validation skipped or unresolved: `<validation-skipped-or-unresolved>`
- Final evidence: `<final-evidence>`
- Residual risk: `<residual-risk>`
