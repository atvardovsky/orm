# Operation Routing Flow

Use this flow in `Doctrine ORM` for the single `Alatyr` conversational entry,
automatic operation selection, help, status, or genuine routing ambiguity.

These names are assistant request shortcuts, not shell commands.

## Target Sources

- Context router: `.ai/assistant/context-router.json`
- Compact operation index: `.ai/assistant/operation-index.json`
- Operation catalog: `.ai/assistant/operation-catalog.json`
- Compact help: `.ai/assistant/help.md`
- Full help reference: `.ai/assistant/help-reference.md`
- Module profile: `.ai/assistant/module-profile.md`
- Team-collaboration sources: deferred until the module is enabled and target
  owners accept the operating model and work registry paths
- Pre-change preview: `.ai/assistant/templates/pre-change-preview.md`
- Installed operations guidance: `.ai/framework/installed-operations.md`
- Operation routing guidance: `.ai/framework/operation-help.md`
- Project blueprint index: `.ai/project/blueprint.md`
- Business logic layer: `.ai/project/business-logic.md`
- Commit policy: `.ai/project/commit-policy.md`
- Project source of truth: `README.md, docs/en/reference/*.rst, SECURITY.md, CONTRIBUTING.md, composer.json, tests/README.markdown, and CI workflows`
- Target validation: `/usr/local/bin/composer8 install`; `/usr/local/bin/php8 vendor/bin/phpunit`; `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`; `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G`; `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G`; docs validation only after the docs script resolves a PHP 8-compatible composer command
- Approval constraints: `explicit approval required for protected behavior, architecture, security, dependency, live, destructive, or weakened-gate changes`

## Entry Behavior

For `Alatyr` without a task:

1. Load bootstrap context only: treat `AGENTS.md` as preloaded and read
   `.ai/alatyr.yaml`,
   `.ai/README.md`, `.ai/assistant/context-router.json`, the operation catalog,
   and module profile. The bare entry needs the catalog; routine exact aliases
   do not.
2. Report health as unchecked unless fresh health evidence identifies its
   observation time or repository revision.
3. Show no more than three operations that are available under the current
   module profile and relevant to current evidence.
4. Do not edit files or require a formal request template.

For `Alatyr status` or `Alatyr doctor`, route directly to `adapter-health` with
`read-only` allowed actions and continue with
`.ai/assistant/flows/adapter-health.flow.md`.

## Automatic Routing

1. Restate the request in concrete language and record supplied allowed
   actions. When absent, infer only the minimum actions needed for an
   unambiguous routine request; ask before broadening the surface.
2. Apply an explicit operation ID or exact alias through the compact operation
   index first. Otherwise use bounded router candidates; load catalog
   `use_when` fields only when ambiguity remains.
3. Check the indexed `required_module` against manifest module state. Load the
   full module profile only when state is unknown, conflicting, or under
   repair. Route an unavailable operation to compact help and name the gap.
4. Use profile `operation_candidates` in the compact context router to select
   the smallest likely operation without loading the full catalog for every
   routine task.
5. Select the smallest matching context profile from the router, then select
   only affected project-area overlays, including `business-logic` when
   accepted ORM behavior-rule routing is in scope. Do not load all
   `.ai/framework` or `.ai/project` files; load only required context and
   record budget exceptions.
6. Classify contour, changed facts, risk, source-of-truth owners, and approval
   triggers. For business-rule changes, load
   `.ai/project/business-logic.md`. Operation selection does not grant
   approval.
7. When exactly one operation fits and its allowed-action scope is sufficient,
   state the operation and reason briefly, then continue without asking the
   user to confirm routing.
8. When two or more operations remain plausible, load compact help or the full
   help reference, present only the closest two or three choices, and ask the
   smallest missing question. Do not edit while ambiguity remains material.
9. Route optional-module requests to help unless the module is enabled,
   cataloged, indexed, and backed by an existing flow file.

## Pre-Change Decision

Before the selected flow edits files, show the pre-change preview when:

- an accepted semantic, business, architecture, data, security, or public
  contract fact may change;
- a protected category or approval gate applies;
- scope crosses contours, project areas, or workstreams;
- destructive, live external, permission, credential, or spend effects are
  possible; or
- expected surfaces or allowed actions remain uncertain.

The preview is not approval. Refresh it when risk or scope changes. For
read-only work and clear local changes with no semantic or protected effect,
record that preview was skipped and why.

## Specialized Aliases

Installed aliases are the aliases in `.ai/assistant/operation-index.json`.
This repository currently installs help, adapter health, blueprint creation,
installation/framework recheck, product change, logical-integrity review,
drift review, documentation sync, and adapter maturity review. Optional
upstream aliases must route to help unless the module is enabled, the operation
is added to `.ai/assistant/operation-catalog.json`, and the referenced flow
exists.

## Final Evidence

Report:

- requested action
- matched operation or unresolved candidates
- routing mode: explicit, automatic, or ambiguity resolution
- selected context profile and overlays
- matching flow and required module state
- reason for selection
- allowed actions and approval needs
- pre-change preview shown, refreshed, or skipped with reason
- team overlay, task/actor IDs, and registry evidence revision when applicable
- diagram presentation mode, source status, and fallback when applicable
- test-first recommendation result, policy state, trigger, mode, likely level,
  cost, and selected configuration or execution route when applicable
- Delegation preference, activation decision, packets, role/model or
  unverified status, capability freshness, validation, fallback, and primary
  convergence when applicable
- extension lifecycle mode, selected ID/source, source-access state, immutable
  revision/digest, compatibility, permissions, ownership, and next safe action
  when applicable
- missing input, if any
- next safe action

## Rejection Criteria

Reject or revise routing that:

- invents a portable `alatyr` executable command
- requires an operation ID for a clear routine request
- loads the full operation catalog or help reference for every task
- loads the full bridge matrix or module profile for a clear indexed route
- routes through a disabled, deferred, not-applicable, or blocked module
- starts edits while material routing or allowed-action ambiguity remains
- treats the pre-change preview as approval
- claims adapter health without fresh evidence
- claims target validation exists without target evidence
- claims a diagram was rendered without current surface capability evidence
