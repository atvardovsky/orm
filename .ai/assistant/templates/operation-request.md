# Installed Alatyr Operation Request

Use this template inside Doctrine ORM when a user wants an explicit Alatyr
operation request. Clear ordinary requests may route automatically without this
template.

## Request

- Operation id or alias: `<installed-operation-or-alias>`
- Requested by: `<requester>`
- Date: `<date>`
- Goal: `<goal>`
- Non-goals: `<non-goals>`
- Known context: `<known-context-or-none>`
- Review comments or defect reports: `<review-items-or-none>`
- Allowed actions: `<read-only-docs-only-adapter-only-code-and-tests-or-full-with-approval>`
- Expected final evidence: `<expected-final-evidence>`
- Pre-change preview: `<shown-skipped-or-pending-with-reason>`
- Approval constraints: `<approval-constraints-or-none>`
- Approved Git diff base when scoped approval applies:
  `<approved-diff-base-or-none>`

## Installed Operation Choices

- `help`: route through `.ai/assistant/flows/operation-routing.flow.md`.
- `adapter-health`: route through `.ai/assistant/flows/adapter-health.flow.md`.
- `create-project-blueprint`: route through
  `.ai/assistant/flows/project-blueprint-creation.flow.md`.
- `recheck-after-installation`: route through
  `.ai/assistant/flows/adapter-recheck.flow.md`.
- `recheck-after-framework-update`: route through
  `.ai/assistant/flows/adapter-recheck.flow.md`.
- `product-change`: route through
  `.ai/assistant/flows/blueprint-driven-change.flow.md`.
- `logical-integrity-review`: route through
  `.ai/assistant/flows/logical-integrity-review.flow.md`.
- `drift-review`: route through
  `.ai/assistant/flows/logical-integrity-review.flow.md`.
- `documentation-sync`: route through
  `.ai/assistant/flows/documentation-sync.flow.md`.
- `adapter-maturity-review`: route through
  `.ai/assistant/flows/adapter-recheck.flow.md`.

Resolve exact IDs and aliases through `.ai/assistant/operation-index.json`.
Use `.ai/assistant/operation-catalog.json` as the canonical operation list.

## Allowed Actions

- `read-only`: inspect files and report; do not edit.
- `docs-only`: update documentation or blueprint-equivalent docs only.
- `adapter-only`: update `.ai/*`, `AGENTS.md`, `AI_ASSISTANTS.md`, or
  `CODEOWNERS`; do not change product code, tests, CI, dependencies, or public
  behavior.
- `code-and-tests`: update source, tests, and required docs sync; do not make
  live, destructive, production dependency, credential, permission, or
  broader external changes.
- `full-with-approval`: perform only the explicitly approved protected scope.

## Required Context

Always start from:

- `AGENTS.md`
- `.ai/assistant/bootstrap-index.json`

Expand only as needed through:

- `.ai/alatyr.yaml`
- `.ai/README.md`
- `.ai/assistant/context-router.json`
- `.ai/assistant/context-profiles.md`
- `.ai/assistant/operation-index.json`
- `.ai/assistant/operation-catalog.json`
- `.ai/assistant/module-profile.md`
- `.ai/project/blueprint.md`
- `.ai/project/business-logic.md`
- `.ai/project/source-of-truth-registry.md`
- selected target docs, source, tests, CI, and validation files

## Constraints

- Use target evidence only.
- Do not invent Doctrine behavior, validation commands, policies, diagrams, or
  lifecycle notes.
- Do not route to optional-module operations unless the module is enabled in
  `.ai/assistant/module-profile.md`, cataloged, indexed, and backed by an
  existing flow file.
- Show `.ai/assistant/templates/pre-change-preview.md` when changed-fact risk,
  protected scope, boundary crossing, external effects, or uncertain allowed
  actions trigger it.
- Use `.ai/project/business-logic.md`, `.ai/project/blueprint.md`, and
  `.ai/project/source-of-truth-registry.md` to choose canonical fact owners and
  business-rule routing when surfaces disagree.
- Re-derive target invariants before implementation. Cluster related review
  items by changed fact and shared contract.
- Require explicit approval before protected changes.
- Run only target-recorded validation or report why it was skipped.
- Report skipped checks and residual risk.
