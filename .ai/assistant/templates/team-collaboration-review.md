# Team Collaboration Review

- Review ID: `<team-review-id>`
- Period or operation scope: `<bounded-review-scope>`
- Policy revision: `<target-team-policy-revision>`
- Registry/backend evidence revision: `<team-evidence-revision>`
- Reviewed by actor: `<actor-id>`
- Evidence quality: `<measured-observed-anecdotal-conflicting-or-unresolved>`

## Aggregate Signals

- Active and stale claim counts: `<active-and-stale-claim-counts>`
- Revision conflicts rejected before overwrite:
  `<rejected-concurrent-write-count-or-unknown>`
- Logical overlaps found before edits and after edits:
  `<prechange-and-late-overlap-counts-or-unknown>`
- Handoffs pending, accepted, rejected, or stale:
  `<handoff-state-counts>`
- Review or merge evidence invalidated by later changes:
  `<review-invalidation-count-or-unknown>`
- Repeated missing actor, owner, authority, or backend evidence:
  `<missing-coordination-evidence-patterns-or-none>`
- Team context files and approximate volume:
  `<team-context-cost-evidence-or-unknown>`

Do not rank individuals or infer productivity from these signals. Use bounded
team-process evidence and target privacy/retention policy.

## Improvement Candidates

For each repeated or high-impact pattern, record the project owner, evidence
references, current workflow or AI item, proposed gate/checker/skill/flow/tool
change, expected quality and context-cost effect, acceptance criteria,
maintenance owner, and next read-only recommendation operation.

## Decisions

- Keep: `<supported-existing-items-or-process>`
- Improve: `<accepted-improvement-candidates-or-none>`
- Add: `<accepted-new-item-candidates-or-none>`
- Retire: `<retirement-candidates-or-none>`
- Unresolved: `<unresolved-evidence-or-authority>`

This review does not modify project facts, AI infrastructure, permissions,
framework files, or team policy. Accepted changes use their normal target
operation, authority, approval, validation, and rollback path.
