# Example Adapted Skill

This placeholder shows how an imported assistant skill should look after it is
adapted to `<project-name>`.

Replace every placeholder from target evidence before accepting installation.

## Provenance

- Source: `<imported-skill-source-or-unknown>`
- Imported date: `<date>`
- Original purpose: `<original-skill-purpose>`
- Target purpose: `<target-skill-purpose>`
- Router item ID: `<ai-infrastructure-item-id>`
- Adaptation record: `<target-adaptation-record>`
- Supported assistant surfaces: `<supported-assistant-surfaces>`

## Canonical Target Context

Before using this skill, select its item entry from
`.ai/assistant/ai-infrastructure-router.json`, then read only:

- `AGENTS.md`
- `AI_ASSISTANTS.md`
- `.ai/README.md`
- `<target-skill-required-context>`
- `<target-skill-gate>`
- `<target-project-source-of-truth>`

## Normalized Rules

- Use target source-of-truth files only: `<target-source-of-truth-docs>`.
- Use target validation only: `<target-validation>`.
- Follow target security/live-service policy: `<target-security-policy>`.
- Stay within allowed actions and permissions:
  `<target-skill-allowed-actions-and-permissions>`.
- Do not call live services, run destructive actions, broaden permissions, or
  add dependencies unless the target adapter allows it and approval is present.
- Do not copy source skill commands, paths, fixtures, policies, or project
  facts into `<project-name>`.

## Output Format

```text
Skill: <target-skill-name>
Source/provenance: <imported-skill-source-or-unknown>
Changed facts: <facts changed or none>
Target files reviewed: <canonical target files>
Actions taken: <changes or recommendation>
Validation: <target checks or manual review>
Approvals: <used or not required>
Residual risk: <skipped or unresolved checks>
```
