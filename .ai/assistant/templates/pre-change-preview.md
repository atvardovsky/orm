# Alatyr Pre-Change Preview

Use this bounded artifact before edits when operation routing identifies a
semantic or protected change, cross-boundary scope, external or destructive
effect, or unclear allowed-action surface.

The preview is not approval. Refresh it when changed facts, risk, expected
surfaces, allowed actions, or approval scope changes materially.

## Preview

- Operation ID: `<operation-id>`
- Goal: `<goal>`
- Selected context profile and overlays: `<profile-and-overlays>`
- Changed facts or suspected facts: `<changed-fact-ids-and-summaries>`
- Canonical owners: `<source-of-truth-owners>`
- Affected contours, project areas, and external surfaces:
  `<affected-surfaces>`
- Risk classes: `<risk-classes>`
- Preview trigger: `<semantic-protected-cross-boundary-external-or-unclear-scope>`
- Expected files or bounded surface patterns: `<expected-change-scope>`
- Allowed actions: `<allowed-actions>`
- Approval needs and selected records: `<approval-needs-and-records>`
- Planned validation: `<validation-or-manual-review>`
- Unresolved questions: `<unresolved-questions-or-none>`
- Evidence basis: `<files-records-and-revision>`
- Decision: `<proceed-ask-or-blocked>`

## Skip Evidence

For routine read-only or local non-semantic work, do not create a full preview.
Record only:

```text
Pre-change preview: skipped
Reason: <no semantic or protected effect, no boundary crossing, and allowed
scope is clear>
```
