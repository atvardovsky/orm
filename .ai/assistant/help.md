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
- Use `.ai/assistant/context-router.json` to choose task context before
  expanding the reading set, and use `.ai/assistant/context-profiles.md` when
  human rationale or conflict resolution is needed.
- Use `.ai/assistant/module-profile.md` to avoid routing to blocked or
  disabled optional modules.
- Load `.ai/assistant/operation-index.json` for an exact operation ID or alias.
  Load the full catalog only for the bare `Alatyr` entry, ambiguity, or
  operation/adapter repair.
- Show `.ai/assistant/templates/pre-change-preview.md` before edits only when
  semantic or protected risk, boundary crossing, external effects, or unclear
  allowed-action scope triggers it.
- Do not route to optional-module operations unless they are enabled in
  `.ai/assistant/module-profile.md` and present in
  `.ai/assistant/operation-catalog.json`.

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

Detailed blueprint, integrity, update, documentation, and optional-module
vocabulary is in `.ai/assistant/help-reference.md`. The canonical installed
operation set remains `.ai/assistant/operation-catalog.json`.

## Minimal Request Shape

```text
Use the installed Alatyr adapter in this repository.

Operation type: `<operation-type>`
Goal: `<goal>`
Non-goals: `<non-goals>`
Known context: `<known-context>`
Allowed actions: `<read-only-docs-only-adapter-only-code-and-tests-or-full-with-approval>`
Expected final evidence: `<expected-final-evidence>`
```

## When Unsure

1. Say which parts of the request are ambiguous.
2. Show the two or three closest options.
3. Ask for the smallest missing decision.
4. Avoid repository edits until the operation is selected.

## Full Capability Set

Full Alatyr is enabled in this branch. Available routed areas include architecture, consistency, diagrams, dependency knowledge, code documentation, project vocabulary, test-first work, workspace modes, AI infrastructure, bridge capability review, extensions, durable approvals, team coordination, change packages, large-task orchestration, subagent delegation, migration diff, effectiveness reports, and scaffolding.
