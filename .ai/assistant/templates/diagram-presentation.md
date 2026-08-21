# Diagram Presentation

Use this template to present a diagram during an assistant discussion.

## Identity

- Diagram ID: `<stable-diagram-id>`
- Draft revision: `<positive-integer>`
- Supersedes or parent revision: `<revision-id-or-none>`
- Created or updated: `<iso-8601-timestamp>`
- Decision reference: `<decision-id-or-none>`
- Diagram title: `<diagram-title>`
- Purpose and scope: `<diagram-purpose-and-scope>`
- Diagram type: `<context-container-component-sequence-state-data-or-other>`
- Status: `<draft-accepted-source-or-derived-view>`
- Current assistant surface: `<assistant-surface-id>`

## Evidence

- Fact owners: `<fact-ids-and-canonical-owners-or-missing>`
- Repository revision: `<revision-required-for-accepted-or-derived-otherwise-unknown>`
- Source revision or content hash: `<required-for-accepted-or-derived-otherwise-not-available>`
- Assumptions: `<assumptions-or-none>`
- Unresolved facts: `<unresolved-facts-or-none>`
- Intentionally omitted detail: `<omitted-detail-or-none>`

## Presentation

- Presentation mode: `<ascii-native-inline-or-rendered-artifact>`
- Capability evidence: `<current-assistant-capability-entry-version-time-and-evidence>`
- Editable source format: `<source-format-or-none>`
- Editable source path: `<source-path-or-inline-only>`
- Rendered artifact: `<artifact-path-attachment-unsupported-or-none>`

Portable ASCII presentation:

```text
<ascii-diagram-max-100-columns>
```

- Reading direction: `<left-to-right-or-top-to-bottom>`
- Longest line: `<column-count-max-100>`
- Connector legend: `<legend-or-single-connector-not-required>`
- ASCII readability check: `<pass-or-revised>`

Optional inline or artifact presentation:

`<inline-presentation-artifact-link-or-not-used>`

Editable source when useful:

```text
<editable-source-or-source-path-reference>
```

## Security And Artifact Policy

- Data classification: `<public-internal-confidential-restricted-or-target-equivalent>`
- Redactions applied: `<redactions-or-none>`
- External renderer or network action: `<none-requested-blocked-or-handoff>`
- Approval evidence: `<approval-id-or-not-required>`
- Artifact storage: `<target-path-attachment-or-none>`
- Retention and deletion: `<target-policy-or-not-applicable>`
- Sharing boundary: `<target-allowed-audience-or-not-applicable>`

## Integrity

- Explains current facts, compares alternatives, or proposes change:
  `<current-alternatives-or-proposed-change>`
- Accepted fact change detected: `<yes-no-or-unresolved>`
- Required handoff: `<none-decision-documentation-sync-or-product-change>`
- Validation or manual review: `<validation-result-or-not-run>`
- Stale-view risk: `<risk-and-refresh-trigger>`
- Next action: `<continue-revise-persist-accept-handoff-or-stop>`

The rendered or inline view is not project source of truth unless the target
registry explicitly names it as an accepted owner.

The ASCII view is mandatory even when a native or artifact view is added. It
must follow `.ai/framework/ascii-diagrams.md` and
`.ai/assistant/templates/ascii-diagram.md`.

An `accepted-source` or `derived-view` without repository revision and source
revision or content hash is invalid and must remain `draft`.
