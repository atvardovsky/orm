# Extension Lifecycle Record

Record ID: `<extension-lifecycle-record-id>`
Operation ID: `<operation-id>`
Mode: `<install-update-disable-remove-or-review>`
Extension ID: `<extension-id>`
Prior state: `<available-reviewed-planned-active-blocked-disabled-deprecated-removed-or-none>`
Final state: `<reviewed-planned-active-blocked-disabled-deprecated-or-removed>`

## Source And Compatibility

Source type and location: `<source-type-and-location>`
Immutable revision: `<source-commit-or-version>`
Package digest: `<sha256-digest>`
Package version: `<semver-version>`
License result: `<license-result>`
Compatibility result: `<extension-api-framework-schema-template-and-rule-result>`
Review record: `<extension-review-record>`

## Target Adaptation

Resolved bindings: `<binding-ids-target-values-and-owners>`
Unresolved bindings: `<unresolved-bindings-or-none>`
Requested permissions: `<requested-permissions>`
Granted permissions: `<granted-permissions>`
Protected effects: `<protected-effects-or-none>`
Rejected or rewritten source instructions: `<normalization-result>`
Extension-owned files: `<paths-and-hashes>`
Shared integration surfaces: `<paths-and-sync-result>`
Local modifications or ownership conflicts: `<conflicts-or-none>`
Dependents: `<active-dependents-or-none>`

## Control Surfaces

Catalog result: `<catalog-result>`
Lock result: `<lock-result>`
AI infrastructure router result: `<router-result>`
Operation and context route result: `<operation-context-result>`
Gate result: `<gate-result>`
Bridge and wrapper result: `<bridge-wrapper-result>`
Module result: `<module-result>`

## Evidence

Approval records: `<approval-records-or-none>`
Package validation: `<package-validation>`
Adapter validation: `<adapter-validation>`
Target validation: `<target-validation>`
Removed files: `<removed-files-or-none>`
Preserved files and history: `<preserved-files-and-history>`
Skipped checks: `<skipped-checks>`
Context and maintenance cost: `<measured-or-labeled-estimate>`
Residual risk: `<residual-risk>`
