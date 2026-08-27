# Alatyr Context Profiles

Use this file as the human explanation for the Doctrine ORM context router.
The machine owner is `.ai/assistant/context-router.json`; profile descriptors
under `.ai/assistant/context/profiles/` are the exact load lists.

## Bootstrap

Treat `AGENTS.md` as preloaded. Routine sessions should read only
`.ai/assistant/bootstrap-index.json` first. The generated bootstrap embeds the
required core semantic definitions and is hash-bound to `.ai/alatyr.yaml`,
`.ai/README.md`, `.ai/assistant/context-router.json`, and the installed
semantic-codebook index. If it is stale or routing is ambiguous, reload those
owners and repair the derived projections.

After bootstrap, choose the smallest matching profile and only the affected
project-area overlays. Record context expansion when the task crosses multiple
areas, exceeds the default budget, or needs protected owner evidence.

## Recursive Selection And Semantic Terms

For each selected contour, open its root `context-index.json` and follow only
entries matched by exact task, operation, owner, path, fact, contract,
dependency, risk, or conflict signals. Repeat through child indexes until the
minimum content set is resolved. Selecting a parent never selects all children.
Reject cycles, multiple parents, duplicate content paths, stale digests, stale
word estimates, and traversal beyond the configured maximum depth.

The generated bootstrap is not assistant catalog content because it digests
that catalog. Rebuild project and assistant indexes before bootstrap after
installed target files change.

Use bootstrap semantic definitions by exact ID and version. Resolve any lazy
`semantic_refs` through `.ai/framework/semantics/index.json`, including term
dependencies. A term is lossless shorthand for its complete definition; it
does not own policy or project facts. On missing, stale, ambiguous, superseded,
or conflicting terms, load the named canonical owner and record the fallback.

For non-trivial work, budget expansion, handoff, or resume, materialize the
selected indexes, item IDs/paths/digests/reasons, resolved term definitions,
word totals, and deterministic packet digest with
`.ai/assistant/templates/context-packet.json`.

## Project Knowledge Routing

For every non-trivial selected task, read the compact project-knowledge index
after profile and area selection. Open only shard descriptors matching the
profile plus at least one area, dependency, fact, contract, path, symbol, or
issue signal. Subsystem and architecture-item relationships are also strong
selectors. Profile matching alone never selects an item.

The portable policy owner is `.ai/framework/project-knowledge.md`; load it
only for lifecycle work, ambiguity, conflict, or adapter repair.

Run an initial route before broad orientation and a refined route after source
inspection identifies changed facts, paths, symbols, dependencies, contracts,
or issue lineage. Obey the separate packet limits in the index. Read selected
canonical owners before relying on summaries. Supply only accepted-current
items as candidate constraints; stale items are warnings and contradictions
are blockers. Record selected, used, inapplicable, stale, blocked, and omitted
IDs in the context receipt.

When a material investigation discovers reusable knowledge, the finalization
flow may propose promotion. Human review and canonical-owner update are
required before the item enters routine routing.

## Profile: `docs-local`

Use for local documentation wording, README edits, and non-semantic docs work.
Required descriptor: `.ai/assistant/context/profiles/docs-local.json`.
Operation candidates include `documentation-sync`, `drift-review`,
`logical-integrity-review`, and `support-generation` when enabled. Load
`.ai/framework/support-information.md` only when support diff, generation
ownership, or relationship-candidate semantics are disputed.

Core owners: `README.md`, selected `docs/en/**/*.rst`,
`.ai/assistant/gates/documentation.md`, and
`.ai/assistant/gates/final-evidence.md`.

Validation: docs validation after the docs script resolves a PHP 8-compatible
composer command, or manual documentation review when docs dependencies are
unavailable.

## Profile: `code-local`

Use for bounded implementation or test changes that do not accept a new public
behavior, architecture, or data contract.
Required descriptor: `.ai/assistant/context/profiles/code-local.json`.
Operation candidates include `logical-integrity-review`, `drift-review`,
`product-change`, `test-first-change`, and `support-generation` when enabled.
Load `.ai/framework/support-information.md` only for support-state, bounded
impact, candidate, or generation-contract ambiguity.

Core owners: `composer.json`, selected `src/` and `tests/` files,
`CONTRIBUTING.md`, `.ai/project/blueprint.md`, and
`.ai/project/source-of-truth-registry.md`.

Validation: `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpunit`,
`/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`,
`/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G`,
and `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G`
as applicable.

## Profile: `business-change`

Use for accepted behavior, domain rule, workflow, or public-contract changes.
Required descriptor: `.ai/assistant/context/profiles/business-change.json`.

Core owners: `.ai/project/business-logic.md`,
`.ai/project/blueprint.md`, `README.md`, `docs/en/reference/*.rst`, selected
source/tests, semantic-integrity gate, and final-evidence gate.

Validation: `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpunit` plus relevant docs/manual
review. Approval is required before accepted behavior or public contract
changes.

## Profile: `architecture-change`

Use for module boundary, dependency direction, runtime responsibility, public
API, or cross-component contract changes.
Required descriptor: `.ai/assistant/context/profiles/architecture-change.json`.

Core owners: `.ai/project/blueprint.md`,
`docs/en/reference/architecture.rst`, `composer.json`, selected source/tests,
semantic-integrity gate, and final-evidence gate.

Validation: manual architecture review plus
`/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`
when implementation changes. Approval is required before accepted architecture
changes.

## Profile: `data-change`

Use for mapping, persistence, UnitOfWork, identity, query, migration, or
data-loss-risk work.
Required descriptor: `.ai/assistant/context/profiles/data-change.json`.
Context-discovery guidance is conditional on disputed source selection,
expansion, or missing-context handling.

Core owners: `.ai/project/blueprint.md`,
`docs/en/reference/basic-mapping.rst`,
`docs/en/reference/unitofwork.rst`, selected persistence source/tests,
semantic-integrity gate, code-and-tests gate, and final-evidence gate.

Validation: `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpunit` with relevant database
configuration and manual persistence review. Destructive or data-loss risk
requires explicit approval.

## Profile: `security-sensitive`

Use for secrets, credentials, permissions, network/external services,
destructive actions, production boundaries, security posture, or privacy risk.
Required descriptor: `.ai/assistant/context/profiles/security-sensitive.json`.
The full change-risk owner is conditional on disputed classification or
escalation semantics; the routed security gate carries the routine obligation.

Core owners: `SECURITY.md`, `docs/en/reference/security.rst`,
`.ai/assistant/gates/security-approval.md`, and final-evidence gate.

Validation: policy review plus applicable tests/static analysis. Public
security vulnerability handling must follow Doctrine security reporting
instructions.

## Profile: `framework-upgrade`

Use for installing Alatyr, updating Alatyr Core, adapter rechecks, adapter
maturity review, and framework drift repair.
Required descriptor: `.ai/assistant/context/profiles/framework-upgrade.json`.
Operation candidates include `support-generation` when enabled. Load
`.ai/framework/support-information.md` when migration impact selects support
policy, state, relationship, or generation contracts.

Core owners: `.ai/alatyr.yaml`, `.ai/README.md`,
`.ai/assistant/context-router.json`, `.ai/assistant/module-profile.md`,
`.ai/assistant/templates/installation-note.md`, and generated bootstrap state.

Validation: bootstrap regeneration, JSON/YAML parse, operation flow existence,
target adapter validator, local path/placeholder scans, and `git diff --check`.

## Project Area Overlays

The router has overlays for `src`, `tests`, `docs`, `business-logic`, `ci`,
and `commits`. Use them only when that area is affected. Each overlay adds the
target files needed to close source-of-truth, validation, behavior-rule, CI, or
commit-policy impact for the selected profile.

The `commits` overlay loads `.ai/project/commit-policy.md` before creating,
reviewing, or amending a commit.

## Installed Operations

Installed operations come only from `.ai/assistant/operation-catalog.json` and
`.ai/assistant/operation-index.json`. The current full-profile installed set is:

- `help`
- `adapter-health`
- `create-project-blueprint`
- `architecture-assistance`
- `recheck-after-installation`
- `recheck-after-framework-update`
- `product-change`
- `large-task`
- `team-identity`
- `team-status`
- `team-task`
- `team-conflict-review`
- `team-handoff`
- `team-decision`
- `team-review`
- `team-merge-check`
- `logical-integrity-review`
- `diagram-discussion`
- `ai-infrastructure-inventory`
- `ai-infrastructure-recommendation`
- `skill-adaptation`
- `extension-management`
- `dependency-knowledge`
- `workspace-mode`
- `drift-review`
- `documentation-sync`
- `project-vocabulary`
- `test-first-configuration`
- `test-first-change`
- `adapter-maturity-review`

Do not route to an operation unless its module is enabled in
`.ai/assistant/module-profile.md`, the operation is projected in the compact
index, and the referenced flow exists.

## Deferred Optional Modules

No optional Alatyr modules are deferred in this branch. The manifest uses
`installation.support_profile: full`, `framework.pack: complete`, and records
the full optional capability graph as enabled. Any future module disablement,
blocker, or deferral must be recorded in `.ai/alatyr.yaml`,
`.ai/assistant/module-profile.md`, the operation catalog, and the relevant
target-owned support files.

## Final Evidence

For every routed task, report selected profile and project areas, changed
facts/files, source-of-truth owners, invariant/integrity result, validation run
or skipped with reason, approval scope when used, context expansion, and
residual risk.

## Full Capability Overlays

This branch enables the full Alatyr capability graph. The router includes
project-knowledge delivery plus intent overlays for architecture, diagrams,
code documentation, dependency knowledge, vocabulary, test-first work,
extensions, workspace modes, and AI infrastructure. Task-scale overlays cover
large or resumable work, delegated execution, change packages, and active team
coordination. Area overlays include architecture, dependencies, documentation
knowledge, vocabulary, testing policy, team, workspace modes, and AI
infrastructure in addition to the original Doctrine `src`, `tests`, `docs`,
`business-logic`, `ci`, and `commits` overlays.

Runtime-specific assistant bridge capabilities remain evidence-bound; load `.ai/assistant/assistant-capabilities.json` and `.ai/assistant/bridge-capability-matrix.md` before relying on a non-Codex bridge or delegated execution.

## Profile: `ai-infrastructure`

Use for AI infrastructure inventory, recommendation, skill, prompt, bridge, wrapper, MCP, checker, gate, flow, template, permission, or assistant capability changes. Load `.ai/assistant/context/profiles/ai-infrastructure.json`, `.ai/assistant/ai-infrastructure-router.json`, `.ai/assistant/assistant-capabilities.json`, and selected target item records only when the task needs them.
