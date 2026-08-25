# Alatyr Help

Use this file in `Doctrine ORM` when a programmer asks for Alatyr help,
available actions, commands, or gives an unclear request.

Alatyr is used here through assistant requests over the installed Markdown
adapter. It is not a universal CLI command. Local validation in this workspace
uses `/usr/local/bin/php8` and `/usr/local/bin/composer8`; the plain `php` and
`composer` commands are older and should not be used for this branch.

These aliases are chat/request shortcuts, not shell commands.

Full operation reference: `.ai/assistant/help-reference.md`.
Compact operation index: `.ai/assistant/operation-index.json`.
Canonical operation catalog: `.ai/assistant/operation-catalog.json`.

Send `Alatyr` by itself for a compact adapter state and up to three relevant
actions. Send `Alatyr status` or `Alatyr doctor` for a read-only adapter health
check. A clear ordinary task is routed automatically; an operation ID is not
required.

Default routing:

- If the operation is clear and low risk, choose the matching operation and
  report the chosen route.
- If the request is `Alatyr` alone, do not edit files. Report whether health
  evidence is fresh or unchecked and show at most three available actions.
- If the request asks for status or doctor, route to `adapter-health` and keep
  allowed actions `read-only`.
- If the request is unclear, show only the two or three closest operations and
  ask for the smallest missing decision.
- If the request only returns to an issue, backlog item, report, or discussion,
  or asks for status, analysis, a plan, or what comes next, keep the operation
  read-only. Do not reuse implementation, commit, or push authorization from a
  completed task.
- Use `.ai/assistant/context-router.json` to choose task context before
  expanding the reading set, and use `.ai/assistant/context-profiles.md` when
  human rationale or conflict resolution is needed.
- Use `.ai/assistant/module-profile.md` to avoid routing to blocked or
  disabled optional modules.
- When `workspace-modes` is enabled, read its compact catalog before selecting
  task profile or project area. Prefer an explicit accepted mode, select
  automatically only on one unambiguous match, and ask before edits otherwise.
- Load `.ai/assistant/operation-index.json` for an exact operation ID or alias.
  Load the full catalog only for the bare `Alatyr` entry, ambiguity, or
  operation/adapter repair.
- Show `.ai/assistant/templates/pre-change-preview.md` before edits only when
  semantic or protected risk, boundary crossing, external effects, or unclear
  allowed-action scope triggers it.
- Add the `large-or-resumable` task-scale overlay only for multi-workstream,
  cross-boundary, budget-exceeding, or resumable work. Small tasks should not
  create operation packets.
- In an enabled team project, check the compact active-work index before a
  state-changing operation. Expand `team-active` only for explicit team work,
  a selected task/branch match, possible logical overlap, or unresolved index
  evidence. Keep unrelated tasks and team history out of context.
- Before completing material semantic, architectural, or non-obvious repair
  work, apply the lazy durable engineering-evidence gate. Small local work may
  skip with a specific reason; do not load unrelated evidence records.
- For non-trivial work, apply bounded project-knowledge routing after profile
  and area selection, then refine it after concrete facts are known. Read
  canonical owners, deliver only accepted-current constraints, and block on
  contradictions. Promotion requires target review; direct guidance also needs
  registered decision-owner authority, ownership, and exception scope.
- When the optional `debug-mode` module is enabled, activate it only from an
  explicit current-task or current-session request. Checkpoint material events,
  classify architectural impacts and direction replacements structurally, and
  expire activation with the scope. Resolve durable evidence links lazily.

## Quick Operations

Operation: `help`
Use when: the user asks what Alatyr can do or the request is unclear.
Flow: `.ai/assistant/flows/operation-routing.flow.md`
Minimum input: goal or suspected task area.

Operation: `adapter-health`
Use when: the user asks for Alatyr status, doctor, or current adapter health.
Flow: `.ai/assistant/flows/adapter-health.flow.md`
Minimum input: optional health scope. Allowed actions are `read-only`.

Operation: `product-change`
Use when: accepted behavior, architecture, data, runtime, or public contract
may change. Business-rule changes use `.ai/project/business-logic.md` for
Alatyr routing before syncing canonical Doctrine docs, source, and tests.
Flow: `.ai/assistant/flows/blueprint-driven-change.flow.md`
Minimum input: change intent, non-goals, and approval constraints.

Operation: `workspace-mode`
Use when: the user asks to list, suggest, inspect, select, define, accept,
update, disable, deprecate, remove, or review workspace modes.
Flow: `.ai/assistant/flows/workspace-mode.flow.md`
Minimum input: mode action or workspace-role question; mode ID and explicit
user decision for accepted-state changes.

Operation: `create-project-blueprint`
Use when: creating, repairing, or rechecking `.ai/project/blueprint.md` and
equivalent source-of-truth docs from target evidence.
Flow: `.ai/assistant/flows/project-blueprint-creation.flow.md`
Minimum input: blueprint scope and non-goals.

Operation: `documentation-sync`
Use when: syncing public docs, comments, generated reference material, or
adapter explanatory surfaces after a changed fact.
Flow: `.ai/assistant/flows/documentation-sync.flow.md`
Minimum input: changed fact and canonical owner, or bounded source area and
documentation goal.

Operation: `project-knowledge`
Use when: proposing, reviewing, promoting, routing, revalidating, superseding,
or explaining reusable project knowledge.
Flow: `.ai/assistant/flows/project-knowledge.flow.md`
Minimum input: knowledge subject, candidate ID, or selected knowledge ID, plus
the requested lifecycle action.

Detailed blueprint, integrity, update, documentation, and optional-module
vocabulary is in `.ai/assistant/help-reference.md`. The canonical installed
operation set remains `.ai/assistant/operation-catalog.json`.

Use `Alatyr architecture` for project pattern and architecture discussion. Use
`Alatyr diagram` for a capability-checked diagram view, `Alatyr team status`
for the compact team view, and `Alatyr set actor <actor-id-or-name>` to select
local attribution. These route to `architecture-assistance` and
`diagram-discussion`.

When `dependency-knowledge` is enabled, use `Alatyr dependencies` for compact
state, `Alatyr sync dependencies` to compare and update only the reviewed
project projection, `Alatyr explain dependency <package>` for selected current
facts, or `Alatyr dependency impact <package-or-change>` for bounded impact.
These requests never activate nested adapters or update software packages.

Use `Alatyr knowledge` for routed knowledge, `Alatyr remember this` to propose
review, `Alatyr what do we know <subject>` for lookup, or `Alatyr revalidate
knowledge <id>` for freshness. Only accepted canonical-owner updates become
project authority.

When `debug-mode` is enabled, use `Enable Alatyr Debug Mode for this task` to
start explicit task-local observation, `Alatyr debug status` for read-only
state, `Alatyr debug checkpoint` for a material event checkpoint, `Alatyr debug
summary` to finalize or summarize, and `Disable Alatyr Debug Mode` to stop.
Debug records measure execution, Alatyr-system activity, and supervision; they
are not authority and never grant code, commit, publish, or live permission.

## Minimal Request Shape

```text
Use the installed Alatyr adapter in this repository.

Operation type: `<operation-type>`
Goal: `<goal>`
Non-goals: `<non-goals>`
Known context: `<known-context>`
Current user authorization: `<inspect-modify-commit-publish-or-live-external>`
Allowed actions: `<read-only-docs-only-adapter-only-code-and-tests-or-full-with-approval>`
Expected final evidence: `<expected-final-evidence>`
```

## When Unsure

1. Say which parts of the request are ambiguous.
2. Show the two or three closest options.
3. Ask for the smallest missing decision.
4. Avoid repository edits until the operation is selected.
5. Ask before any `modify`, `commit`, `publish`, or `live-external` phase that
   the newest current-scope request did not explicitly authorize.

## Full Capability Set

Full Alatyr is enabled in this branch. Available routed areas include architecture, consistency, diagrams, dependency knowledge, code documentation, project vocabulary, test-first work, workspace modes, AI infrastructure, bridge capability review, extensions, durable approvals, team coordination, change packages, large-task orchestration, subagent delegation, migration diff, effectiveness reports, and scaffolding.
