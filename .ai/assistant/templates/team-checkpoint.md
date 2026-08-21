# Team Checkpoint

- Checkpoint ID: `<checkpoint-id>`
- Task ID: `<task-id>`
- Recorded by actor: `<actor-id>`
- Assistant actor: `<assistant-actor-id-or-none>`
- Recorded at: `<observation-time>`
- Repository revision: `<head-revision-or-unavailable>`
- Base revision: `<base-revision-or-unavailable>`
- Branch or worktree: `<target-branch-worktree-or-none>`
- Task status: `<task-status>`
- Task record revision: `<task-record-revision>`
- Backend revision: `<backend-revision-or-unavailable>`
- Claim state: `<claim-state>`

## Work State

- Completed work: `<completed-work>`
- Current diff or change reference: `<diff-or-change-reference>`
- Changed fact IDs: `<fact-ids>`
- Canonical owner references: `<canonical-owner-refs>`
- Decisions and decision records: `<decisions-and-records>`
- Active-task overlap result: `<overlap-result>`
- Validation state: `<validation-result-or-unresolved>`
- Review state and evidence: `<review-state-and-evidence-or-unresolved>`
- Approval state: `<approval-records-or-not-required-or-missing>`

## Resume Evidence

- Invalidated assumptions: `<invalidated-assumptions-or-none>`
- Blockers: `<blockers-or-none>`
- Unresolved questions: `<unresolved-questions-or-none>`
- Residual risks: `<residual-risks-or-none>`
- Minimum resume context: `<minimum-resume-context>`
- Exact next action: `<next-action>`
- Next responsible actor or role: `<next-actor-id-or-role>`

This checkpoint is coordination evidence. Compare it with current repository,
registry, backend, approval, and source-of-truth evidence before resuming.
