# Large-Task Operation Packet

Use this template in `<project-name>` only for large, cross-boundary,
multi-workstream, or resumable operations. The completed packet is coordination
evidence, not a source of truth for project facts.

## Operation

- Operation ID: `<operation-id>`
- Parent request or issue: `<parent-request-or-issue>`
- Goal: `<goal>`
- Non-goals: `<non-goals>`
- Allowed actions: `<allowed-actions>`
- Activation reason: `<large-task-activation-reason>`
- Current phase: `<discovery-planning-execution-convergence-or-complete>`
- Packet status: `<active-blocked-complete-or-archived>`
- Packet owner: `<target-operation-owner>`
- Storage and retention policy: `<target-operation-packet-policy>`
- Change package: `<change-package-id-or-not-active>`

## Routed Context

- Selected task profile: `<task-profile>`
- Task-scale overlay: `large-or-resumable`
- Selected project areas: `<project-area-overlays>`
- Context budget: `<context-budget>`
- Loaded files and reasons: `<loaded-files-and-reasons>`
- Approximate context volume: `<approximate-context-volume>`
- Expansion triggers: `<context-expansion-triggers>`
- Intentionally omitted context: `<intentionally-omitted-context>`
- Residual context risk: `<residual-context-risk>`

## Changed Facts

Repeat this block for each changed or disputed fact.

### Fact `<fact-id>`

- Statement: `<changed-fact>`
- Canonical owner: `<canonical-source-of-truth-or-missing>`
- Consistency map node: `<consistency-map-node-or-not-enabled-or-missing>`
- Selected relationship edges: `<selected-edges-and-reasons>`
- Skipped or missing edges: `<skipped-or-missing-edges-and-reasons>`
- Risk class: `<risk-class>`
- Affected surfaces: `<affected-surfaces>`
- Approval state: `<approval-record-or-not-required-or-missing>`
- Approved diff base and explicit machine records: `<diff-base-and-approval-json-paths-or-not-required>`
- Owning workstream: `<workstream-id>`
- Reconciliation state: `<pending-consistent-conflict-or-unresolved>`

## Workstreams

Repeat this block for each coherent workstream.

### Workstream `<workstream-id>`

- Goal: `<workstream-goal>`
- Project area: `<project-area>`
- Changed facts: `<fact-ids>`
- Re-derived invariants: `<invariant-statements>`
- Related review-item clusters: `<review-item-clusters-or-none>`
- Dependencies: `<workstream-dependencies-or-none>`
- Blocking decisions: `<blocking-decisions-or-none>`
- Required context: `<minimum-required-context>`
- Deferred context: `<intentionally-deferred-context>`
- Allowed surfaces: `<allowed-files-or-surfaces>`
- Expected outputs: `<expected-outputs>`
- Validation: `<target-validation-or-manual-review>`
- Delegation packet: `<subagent-packet-path-or-none>`
- Delegation role/model evidence: `<role-model-capability-evidence-or-none>`
- Concurrent write isolation: `<disjoint-scope-read-only-or-not-delegated>`
- Delegation fallback: `<fallback-or-none>`
- Status: `<ready-active-blocked-locally-validated-or-complete>`
- Evidence: `<workstream-evidence>`
- Unresolved risk: `<workstream-residual-risk>`
- Handoff state: `<next-action-and-required-context>`

## Checkpoints

Repeat after local validation, an approval boundary, a handoff, or before a
context reset.

### Checkpoint `<checkpoint-id>`

- Recorded at: `<checkpoint-time-or-commit>`
- Completed work: `<completed-work>`
- Decisions and evidence: `<decisions-and-evidence>`
- Approval state: `<approval-state>`
- Validation state: `<validation-state>`
- Invalidated assumptions: `<invalidated-assumptions-or-none>`
- Context receipt delta: `<new-files-reasons-and-volume>`
- Unresolved items: `<unresolved-items>`
- Next ready action: `<next-ready-action>`
- Resume context: `<minimum-resume-context>`

## Final Convergence

- Completed workstreams: `<completed-workstreams>`
- Unresolved workstreams: `<unresolved-workstreams-or-none>`
- Changed-fact reconciliation: `<fact-reconciliation-result>`
- Relationship impact closure: `<levels-areas-edges-and-missing-links>`
- Source-of-truth synchronization: `<source-of-truth-sync-result>`
- Cross-workstream conflicts: `<conflicts-and-repairs-or-none>`
- Approval scope versus applied changes: `<approval-coverage-result>`
- Complete changed-path scope enforcement: `<committed-staged-unstaged-renamed-deleted-untracked-result>`
- Combined validation: `<target-validation-result-or-unresolved>`
- Global logical integrity review: `<global-logical-integrity-result>`
- Delegated packet reconciliation: `<result-rejected-output-fallback-or-not-used>`
- Skipped checks: `<skipped-checks>`
- Final residual risk: `<final-residual-risk>`
- Packet disposition: `<close-archive-retain-or-delete-per-target-policy>`
- Change-package convergence: `<semantic-scope-companion-correction-and-provenance-result-or-not-active>`

## Resume Rule

On resume, load the compact adapter bootstrap, this packet, the active
workstream's minimum context, its changed-fact owners, and dependencies. Check
packet claims against current repository evidence before continuing. Do not
load completed workstream context again unless evidence changed.
