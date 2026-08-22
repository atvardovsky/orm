# Core Task Gate

Canonical owners: `ALATYR-CONTEXT-001`, `ALATYR-SOURCE-001`,
`ALATYR-RISK-001`, and `ALATYR-AUTHORIZATION-001`. Use the full gate checklist only for ambiguity, adapter
repair, or a boundary named by another gate.

- Select the smallest task profile, intent overlay, task-scale overlay, and
  project areas that cover the request.
- Name the target source of truth and allowed actions before editing.
- Before deciding/suggesting, read current Doctrine authority
  (docs/`CONTRIBUTING.md`/source/tests/validation/CI/security/agreements).
  Alatyr summaries never override it; stale/conflicting/gap support is
  proposal-only.
- Do not let routing grant approval or broaden the requested scope.
- Before `modify`, `commit`, `publish`, or `live-external`, verify the newest
  request authorizes that phase for the current logical scope. Issue/backlog
  returns, status, reports, discussion, analysis, plans, and ambiguous
  continuation are `inspect` only.
- Issue/backlog returns are inspect-only unless the newest request explicitly
  authorizes a state-changing phase.
- Do not infer commit from implementation, publish from commit, or any phase
  from prior completed-task authorization, allowed actions, protected approval,
  tool permission, assignment, mode selection, delegation, or validator success.
- Expand context for conflicting evidence, missing ownership, or a crossed
  business, architecture, data, security, lifecycle, or AI-infrastructure
  boundary.
