# Team Handoff

- Handoff ID: `<handoff-id>`
- Task ID: `<task-id>`
- Source actor: `<source-actor-id>`
- Destination actor or role: `<destination-actor-id-or-role>`
- Assistant actor: `<assistant-actor-id-or-none>`
- Reason: `<handoff-reason>`
- State: `<pending-accepted-rejected-or-stale>`
- Created at: `<observation-time>`
- Accepted or rejected at: `<acceptance-time-or-none>`
- Repository revision: `<head-revision-or-unavailable>`
- Task record revision: `<task-record-revision>`
- Backend revision: `<backend-revision-or-unavailable>`
- Checkpoint reference: `<checkpoint-path-or-id>`

## Scope And Evidence

- Goal and accepted scope: `<goal-and-scope>`
- Completed work: `<completed-work>`
- Changed fact IDs: `<fact-ids>`
- Canonical owner references: `<canonical-owner-refs>`
- Decisions and decision records: `<decisions-and-records>`
- Current diff or change reference: `<diff-or-change-reference>`
- Validation state: `<validation-result-or-unresolved>`
- Review state and evidence: `<review-state-and-evidence-or-unresolved>`
- Approval state: `<approval-records-or-not-required-or-missing>`
- Concurrent overlap state: `<overlap-result>`

## Receiver Context

- Required context: `<minimum-required-context>`
- Intentionally omitted context: `<intentionally-omitted-context>`
- Invalidated assumptions: `<invalidated-assumptions-or-none>`
- Unresolved questions: `<unresolved-questions-or-none>`
- Blockers: `<blockers-or-none>`
- Residual risks: `<residual-risks-or-none>`
- Exact next action: `<next-action>`

The destination actor must compare this handoff with current evidence before
acceptance. Acceptance does not grant protected-change approval.
