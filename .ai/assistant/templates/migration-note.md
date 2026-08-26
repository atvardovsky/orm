# Alatyr Migration Note

Use this note in `Doctrine ORM` when installing, upgrading, or rechecking
Alatyr Core.

Replace placeholders with target facts before accepting the migration result.

Migration ID: `<migration-id>`
Operation ID: `<operation-id>`
From framework version: `<from-alatyr-core-version>`
To framework version: `<to-alatyr-core-version>`
From adapter schema version: `<from-adapter-schema-version>`
To adapter schema version: `<to-adapter-schema-version>`
From template version: `<from-template-version>`
To template version: `<to-template-version>`
Prepared by: `<prepared-by>`
Prepared at: `<prepared-at>`
Evidence basis: `<current-state-historical-record-or-mixed>`
Observed target revision: `<target-revision-or-not-available>`
Migration assessment: `.ai/assistant/templates/migration-note.md or manual review`

## Routed Context

Affected canonical framework sources:

- `<affected-canonical-source-or-none>`

Affected task profiles or rule categories:

- `<affected-profile-or-rule-category-or-none>`

Candidate context intentionally omitted:

- `<omitted-candidate-context-and-reason-or-none>`

## Changed Framework Rules

Added rules:

- `<added-rule-id-or-none>`

Changed rules:

- `<changed-rule-id-or-none>`

Removed or deprecated rules:

- `<removed-or-deprecated-rule-id-or-none>`

## Required Target Actions

- `<required-target-action>`

## Optional Target Actions

- `<optional-target-action>`

## Local Deviations

- `<local-deviation-to-keep-or-repair>`

## Affected Target Surfaces

- `.ai/alatyr.yaml`: `<affected-or-not>`
- `.ai/framework`: `<affected-or-not>`
- `.ai/project`: `<affected-or-not>`
- `.ai/assistant`: `<affected-or-not>`
- `.ai/assistant/module-profile.md`: `<affected-or-not>`
- bridge files: `<affected-or-not>`
- validation or manual review: `<affected-or-not>`

## Stateful Evidence Migration

Debug record/index versions and preservation result:

- `<debug-versions-preserved-new-authoring-version-and-lineage-actions-or-not-enabled>`

Engineering-evidence record/index versions and reciprocal Debug-link result:

- `<engineering-evidence-versions-preserved-new-authoring-version-and-link-actions>`

Project-knowledge index version, adoption state, and reuse-evidence result:

- `<project-knowledge-version-adoption-state-and-evidence-actions>`

Historical records remain immutable: `<yes-or-blocker>`

## Approval And Validation

Approval needed: `<yes-no-reason>`
Approval record: `<approval-record-or-not-required>`
Assessment completed before target changes: `<yes-no-and-reason>`
Validation run: `<validation-run-or-skipped-with-reason>`

## Final Evidence

Migration result: `<complete-partial-or-blocked>`
Remaining gaps: `<remaining-gaps-or-none>`
Residual risk: `<residual-risk>`
