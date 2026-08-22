# Subagent Task Packet

Packet ID: `<packet-id>`
Task ID: `<task-id>`
Execution plan ID: `<plan-id>`
Parent operation ID: `<operation-id>`
Parent workstream ID: `<workstream-id-or-none>`
Primary assistant/session reference: `<primary-assistant-reference>`
Status: `<planned-dispatched-returned-accepted-rejected-blocked-or-cancelled>`

## Goal And Scope

Goal: `<one-bounded-goal>`
Non-goals: `<explicit-non-goals>`
Expected output: `<patch-evidence-findings-or-other>`
Changed fact IDs: `<changed-fact-ids-or-none>`
Semantic fact owner: `<primary-owned-owner-or-none>`
Local acceptance criteria: `<objective-acceptance-criteria>`
Dependency state: `<ready-dependencies-or-blocker>`
Base revision: `<base-revision>`

## Context Boundary

Required context:

- `<required-context-path-and-reason>`

Excluded context:

- `<excluded-context-or-not-needed>`

Context budget: `<target-packet-context-budget>`

## Authority Boundary

Allowed actions: `<read-only-docs-only-adapter-only-or-code-and-tests>`
Current logical scope: `<parent-current-logical-scope>`
Inherited action phases: `<parent-authorized-phases>`
Prohibited phase escalation: `commit, publish, and live-external unless already authorized for the parent and rechecked by the primary`
Allowed files or surfaces:

- `<allowed-path-or-surface>`

Allowed tools:

- `<allowed-tool-or-none>`

Prohibited actions:

- approval or project decision authority
- permission, network, destructive, production, spend, migration, or external
  actions unless the packet explicitly references valid target approval
- files, facts, tools, or surfaces outside this packet
- `<target-additional-prohibited-action>`

Concurrent packets and write-isolation decision:
`<packet-ids-and-disjoint-scope-evidence-or-read-only>`

## Delegation Selection

Assistant surface: `<assistant-surface>`
Dispatch backend: `<native-external-suggestion-only-or-unsupported>`
External dispatcher item: `<target-ai-infrastructure-item-id-none-or-unknown>`
Role: `<target-delegation-role>`
Role prompt: `<target-role-prompt-path>`
Requested model or selection mode: `<model-id-inherit-or-client-default>`
Capability evidence: `<capability-record-path-and-freshness>`
Selection rationale: `<latency-context-or-parallelism-reason>`
Fallback: `<continue-primary-use-stronger-verified-model-or-stop>`

## Validation And Return

Delegate validation:

- `<target-focused-validation-or-manual-review>`

Return format:

- `.ai/assistant/templates/worker-result.md`

## Returned Result

Normalized result ID: `<worker-result-id>`
Actual assistant surface: `<actual-surface-or-unverified>`
Actual role/model: `<actual-role-and-model-or-unverified>`
Files or surfaces touched: `<touched-surfaces-or-none>`
Validation result: `<result-or-not-run-with-reason>`
Acceptance result: `<pass-fail-or-blocked>`
Unexpected scope or conflicts: `<details-or-none>`
Residual risk: `<residual-risk>`

## Primary Review

Scope review: `<accepted-rejected-or-rework-required>`
Patch/evidence review: `<primary-review-result>`
Repeated or combined validation: `<result-or-not-run-with-reason>`
Changed-fact and approval reconciliation: `<result-or-not-applicable>`
Final disposition: `<integrated-reworked-discarded-or-blocked>`
Measured latency or cost evidence: `<measurement-or-not-captured>`
