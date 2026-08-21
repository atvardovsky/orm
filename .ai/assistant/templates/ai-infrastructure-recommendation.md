# AI Infrastructure Recommendation

Use this template in `<project-name>` to record evidence-based recommendations
for new AI infrastructure or changes to existing items.

Recommendation is read-only decision evidence. It does not approve fetching,
installation, execution, canonical edits, removal, permission changes, or item
activation.

## Recommendation Scope

- Operation ID: `<operation-id>`
- Recommendation record path: `<target-record-path-or-report-only>`
- Recommendation date: `<recommendation-date>`
- Requested by: `<requester-or-role>`
- Allowed actions: `<read-only-or-adapter-only>`
- Recommendation scope: `<project-area-problem-or-item-scope>`
- Target assistant surfaces: `<target-assistant-surfaces>`
- Project contour: `.ai/project/contour.md`
- Development evidence index: `.ai/project/development-evidence.json`
- Project area and canonical owner: `<project-area-and-canonical-owner>`
- Project-contour evidence: `<project-facts-constraints-or-outcomes>`
- Selected development pattern IDs: `<development-pattern-ids-or-none>`
- Pattern occurrence and evidence references:
  `<pattern-occurrence-counts-and-bounded-evidence-refs-or-none>`
- Evidence sources inspected: `<task-review-incident-validation-rework-cost-or-maturity-evidence>`
- Evidence quality: `<measured-observed-anecdotal-missing-or-conflicting>`
- Inventory source and freshness: `<inventory-path-date-or-bounded-inspection>`
- Router: `.ai/assistant/ai-infrastructure-router.json`

## Candidate Record

Repeat this block for each bounded candidate.

- Recommendation ID: `<recommendation-id>`
- Recommendation kind:
  `<add-new-improve-existing-consolidate-replace-retire-keep-or-unresolved>`
- Status: `<proposed-deferred-rejected-or-unresolved>`
- Priority: `<target-defined-priority-or-not-ranked>`
- Existing item IDs: `<ai-infrastructure-item-ids-or-none>`
- Development pattern IDs: `<development-pattern-ids-or-none>`
- Proposed item type:
  `<skill-prompt-gate-checker-flow-tool-mcp-bridge-wrapper-template-or-other>`
- Observed problem: `<observed-problem>`
- Recurrence or high-impact exception: `<recurrence-frequency-or-impact-evidence>`
- Existing coverage and why keep is insufficient:
  `<current-coverage-overlap-and-gap-or-keep-justification>`
- Proposed contract change:
  `<purpose-triggers-context-permissions-gates-validation-output-and-surfaces>`
- Non-goals: `<non-goals>`
- Expected quality or consistency effect:
  `<measured-effect-or-labeled-estimate>`
- Acceptance criteria: `<measurable-acceptance-criteria>`
- Expected context-load effect: `<measured-effect-or-labeled-estimate>`
- Implementation cost: `<measured-cost-or-labeled-estimate>`
- Ongoing maintenance cost and owner:
  `<maintenance-cost-and-target-owner-or-unresolved>`
- Permission, safety, dependency, and compatibility impact:
  `<permission-safety-dependency-and-assistant-surface-impact>`
- Build, adapt, or source strategy:
  `<build-target-owned-adapt-known-source-search-later-or-not-applicable>`
- Validation plan: `<target-validation-or-manual-review>`
- Rollback, retirement, or supersession path:
  `<rollback-retirement-or-supersession-plan>`
- Approval needed for later operation:
  `<approval-trigger-or-not-required-for-recommendation>`
- Next route and operation:
  `<adapt-import-gate-checker-change-tool-mcp-change-bridge-wrapper-change-or-none>`
- Residual risk: `<residual-risk>`

## Existing Item Review Summary

- Items kept without change: `<kept-item-ids-and-reason-or-none>`
- Items proposed for improvement: `<improve-item-ids-and-reason-or-none>`
- Items proposed for consolidation or replacement:
  `<consolidate-or-replace-item-ids-and-reason-or-none>`
- Items proposed for retirement: `<retire-item-ids-and-reason-or-none>`
- New items proposed: `<new-item-ids-and-reason-or-none>`

## Decision Summary

- Candidates proposed: `<proposed-count>`
- Candidates deferred: `<deferred-count>`
- Candidates rejected: `<rejected-count>`
- Candidates unresolved: `<unresolved-count>`
- Actions explicitly not taken:
  `<no-fetch-install-execute-edit-remove-permission-or-activation-actions>`
- Recommended next operation: `<next-operation-or-none>`
- Final evidence: `<final-evidence>`
- Residual risk: `<residual-risk>`
