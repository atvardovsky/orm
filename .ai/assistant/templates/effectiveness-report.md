# Alatyr Effectiveness Report

Use this report in `<project-name>` when comparing Alatyr-assisted work across
adapter states or repeated task runs.

Replace placeholders with target facts before accepting the report.

Task: `<task-name>`
Task profile: `<task-profile>`
Adapter mode: `<none-minimal-full-or-other>`
Operation ID: `<operation-id>`
Date: `<date>`

## Metrics

Context files loaded: `<context-files-loaded-or-unknown>`
Approximate context volume: `<context-volume-or-unknown>`
Input tokens: `<input-token-count-or-unknown>`
Output tokens: `<output-token-count-or-unknown>`
Estimated cost and currency: `<cost-and-currency-or-unknown>`
Cost evidence: `<billing-export-host-estimate-or-unknown>`
Context expansions: `<context-expansion-count-or-unknown>`
Context receipt reused: `<yes-no-or-unknown>`
Context budget exceeded: `<yes-no-or-unknown>`
Clarifications: `<clarification-count>`
Approvals requested: `<approval-count>`
Validation: `<validation-run-skipped-or-unresolved>`
Hallucinated commands avoided or produced: `<command-hallucination-result>`
Hallucinated command count: `<count-or-unknown>`
Validation error count: `<count-or-unknown>`
Missed companion updates: `<missed-companion-updates-or-unknown>`
Rework count: `<rework-count-or-unknown>`
Changed facts identified: `<changed-fact-count-or-unknown>`
Consistency relationships reviewed: `<relationships-reviewed-or-unknown>`
Companion surfaces checked: `<companion-surface-count-or-unknown>`
Unresolved consistency gaps: `<unresolved-consistency-gap-count-or-unknown>`
Duration seconds: `<duration-seconds-or-unknown>`
Human active-attention seconds: `<count-or-unknown>`
Human attention evidence state: `<observed-manual-estimated-or-unavailable>`
Human attention evidence or unavailable reason: `<evidence>`
Review cycles: `<count-or-unknown>`
Review-cycle evidence state: `<observed-manual-estimated-or-unavailable>`
Review-cycle evidence or unavailable reason: `<evidence>`
Executor active-time seconds: `<count-or-unknown>`
Executor-time evidence state: `<observed-or-unavailable>`
Executor-time telemetry or unavailable reason: `<evidence>`
Protected changes blocked before approval: `<protected-changes-blocked>`
Residual risks: `<residual-risks>`
Outcome: `<accepted-rework-blocked-or-other>`

Executor active time must be host- or provider-observed. Do not substitute
wall-clock duration, human recollection, or an estimate.

## Classified Interventions

Record a count, evidence state, and source or unavailable reason for each
applicable classification:

- Intervention total: `<count-state-and-evidence>`
- New guidance candidate: `<count-state-and-evidence>`
- Known-guidance routing failure: `<count-state-and-evidence>`
- Known-guidance compliance failure: `<count-state-and-evidence>`
- Task-local input: `<count-state-and-evidence>`
- Scope change: `<count-state-and-evidence>`
- Validation request: `<count-state-and-evidence>`
- Other: `<count-state-and-evidence>`

Do not promote an intervention into project authority from this report. Use
the target's normal ownership and acceptance process.

## Later-Linked Evidence

Delayed outcomes at task completion: `<none-yet-or-existing-record-ids>`
Adapter maintenance record IDs: `<record-ids-or-not-applicable>`

Record a later accepted direction, pull request, merge, rejection, regression,
revert, or follow-up in a new delayed-outcome evidence record. Do not modify
this completed report or a completed Debug record to add the later event.

## Notes

Comparable baseline: `<comparable-baseline-or-none>`
Limitations: `<limitations>`
Next measurement: `<next-measurement>`

Do not calculate precise productivity, output-per-minute, or percentage-saving
claims from these fields alone. Compare only compatible tasks with accepted
outcomes, non-regressing quality evidence, and compatible measurement states.
