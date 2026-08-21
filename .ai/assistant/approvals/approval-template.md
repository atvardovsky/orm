# Approval Record

Replace placeholders with `<project-name>` approval evidence before using this
record.

Approval ID: `<approval-id>`
Operation ID: `<operation-id>`
Operation type: `<operation-type>`
Evidence classification: `historical-record`
Plan version: `<plan-version>`
Plan hash: `<plan-hash-or-not-available-with-reason>`
Approved plan file: `<approved-plan-file-or-not-available-with-reason>`
Approved diff base: `<approved-diff-base-or-not-available-with-reason>`
Patch hash: `<patch-hash-or-not-available-with-reason>`
Requested by: `<requested-by>`
Approved by: `<approved-by>`
Approved at: `<approved-at>`
Repository revision at approval: `<repository-revision-or-not-available-with-reason>`
Approval source/message: `<approval-source-or-message-reference>`
Expires at or reuse policy: `<expiration-or-reuse-policy>`
Scope invalidation rule: `<approval-invalidation-rule>`
Machine-readable record: `<target-relative-approval-record-json>`

Use `approval-record-template.json` for deterministic diff enforcement. This
Markdown record remains the human review and evidence surface; narrative path
mentions do not expand the machine-readable scope.

## Approved Scope

Allowed protected changes:

- `<allowed-protected-change>`

Allowed changed-fact IDs:

- `<allowed-changed-fact-id>`

Allowed architecture areas:

- `<allowed-architecture-area-or-none>`

Allowed behavior categories:

- `<allowed-behavior-category>`

Excluded semantic effects:

- `<excluded-semantic-effect-or-none>`

Permitted external effects:

- `<permitted-external-effect-or-none>`

Allowed files or surfaces:

- `<allowed-file-or-surface>`

Excluded files or surfaces:

- `<excluded-file-or-surface-or-none>`

Excluded actions:

- `<excluded-action>`

Allowed actions mode: `<read-only-docs-only-adapter-only-code-and-tests-or-full-with-approval>`

## Plan Evidence

Approved plan summary:

```text
<approved-plan-summary>
```

Approved validation or manual review:

- `<approved-validation-or-review>`

## Use Result

Used by operation/change: `<task-operation-or-change-reference>`
Patch changed after approval: `<yes-no-and-reason>`
Implementation stayed within approved scope: `<yes-no-and-reason>`
Declared semantic scope stayed within approval: `<yes-no-and-reason>`
Validation run: `<validation-run-or-skipped-with-reason>`
Result/evidence: `<result-or-evidence-reference>`
Residual risk: `<residual-risk>`
