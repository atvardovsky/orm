# Alatyr Help Reference

Use this file in Doctrine ORM for the full installed Alatyr operation
reference. The compact default help is `.ai/assistant/help.md`. Canonical
operation metadata lives in `.ai/assistant/operation-catalog.json`; exact alias
routing lives in `.ai/assistant/operation-index.json`. This file explains those
operations without becoming a competing registry.

Alatyr is an assistant request layer, not a shell command. Local validation in
this workspace uses `/usr/local/bin/php8` and `/usr/local/bin/composer8`.

## Installed Operations

### `help`

Aliases: `Alatyr`, `Alatyr help`.

Use when the user asks what Alatyr can do or the request is unclear. This is a
read-only route through `.ai/assistant/flows/operation-routing.flow.md`.

### `adapter-health`

Aliases: `Alatyr status`, `Alatyr doctor`.

Use for read-only adapter health checks. The flow is
`.ai/assistant/flows/adapter-health.flow.md`.

### `create-project-blueprint`

Aliases: `create blueprint`, `repair blueprint`, `recheck blueprint`.

Use when creating, repairing, or rechecking `.ai/project/blueprint.md` and
equivalent Doctrine source-of-truth docs from repository evidence. The flow is
`.ai/assistant/flows/project-blueprint-creation.flow.md`.

Allowed actions: `read-only`, `docs-only`, or `full-with-approval`.

### `recheck-after-installation`

Aliases: `check Alatyr after installation`, `recheck installation`.

Use after initial installation to verify the adapter, report drift, or repair
adapter-only support files. The flow is
`.ai/assistant/flows/adapter-recheck.flow.md`.

### `recheck-after-framework-update`

Aliases: `update Alatyr`, `recheck Alatyr update`.

Use when comparing the installed adapter against a newer Alatyr Core baseline.
The flow is `.ai/assistant/flows/adapter-recheck.flow.md`.

### `product-change`

Aliases: `change business rule`, `product change`.

Use when accepted behavior, architecture, data, runtime, or public-contract
facts may change. The flow is
`.ai/assistant/flows/blueprint-driven-change.flow.md`.

Allowed actions: `read-only`, `docs-only`, `code-and-tests`, or
`full-with-approval`. Protected changes require explicit approval before edits.

### `logical-integrity-review`

Aliases: `check integrity`, `logical integrity review`, `Alatyr impact`,
`Alatyr support diff`.

Use for changed facts, invariants, owner conflicts, or companion surface
review. The support diff and consistency reverse index select only matching
graph shards before semantic/invariant review. The flow is
`.ai/assistant/flows/logical-integrity-review.flow.md`.

### `support-generation`

Aliases: `Alatyr generate support`, `Alatyr check generated support`.

Use when target-owned derived support is stale and the optional module is
enabled. Planning and checking are read-only. Apply requires current `modify`
authorization, an exact current plan digest, unchanged repository state,
staged deterministic output, and protected approval when triggered. The flow
is `.ai/assistant/flows/support-generation.flow.md`.

### `drift-review`

Aliases: `review drift`, `find stale Alatyr facts`.

Use to find stale source-of-truth, documentation, or adapter claims without
assuming repair scope. The flow is
`.ai/assistant/flows/logical-integrity-review.flow.md`.

### `documentation-sync`

Aliases: `sync documentation`, `sync diagrams`, `document code`,
`propose comment style`, `generate code docs`, `review code documentation`.

Use for synchronized docs, comments, generated reference material, or companion
assistant surfaces after a changed fact. The flow is
`.ai/assistant/flows/documentation-sync.flow.md`. The enabled
code-documentation profiles live under `.ai/project/documentation/` and remain
bounded by their canonical source owners.

### `project-knowledge`

Aliases: `Alatyr knowledge`, `Alatyr remember this`, `Alatyr record guidance`,
`Alatyr what do we know`, `Alatyr revalidate knowledge`.

Use when proposing, directly recording authorized decision-owner guidance,
reviewing, promoting, excepting, routing, revalidating, superseding, or
explaining reusable project guidance. The flow is
`.ai/assistant/flows/project-knowledge.flow.md`.

Default allowed actions are `read-only` for lookup and proposals.
Canonical-owner, promotion-record, or route changes require the matching
current-scope modify authorization and normal approval policy.

### `adapter-maturity-review`

Aliases: `check Alatyr maturity`, `review adapter readiness`.

Use for task-specific adapter readiness and blocker review. The flow is
`.ai/assistant/flows/adapter-recheck.flow.md`.

### `architecture-assistance`

Aliases: `Alatyr architecture`, `Alatyr architecture inventory`,
`explain architecture`, `discuss architecture pattern`,
`compare architecture options`, `review architecture`,
`document architecture`.

Use to inventory, explain, discuss, compare, review, or document Doctrine ORM
architecture from current target evidence. The flow is
`.ai/assistant/flows/architecture-assistance.flow.md`.

Allowed actions: `read-only`, `docs-only`, or `full-with-approval`.

### `large-task`

Aliases: `plan large task`, `continue large task`, `resume Alatyr task`.

Use to coordinate bounded workstreams, checkpoints, and convergence for large
or resumable work. The flow is
`.ai/assistant/flows/large-task-orchestration.flow.md`.

Allowed actions: `read-only`, `docs-only`, `adapter-only`, `code-and-tests`,
or `full-with-approval`.

### `team-status`

Alias: `Alatyr team status`.

Use to report current team work, stale claims, conflicts, handoffs, and review
state without editing. The flow is
`.ai/assistant/flows/team-task-coordination.flow.md`.

Allowed actions: `read-only`.

### `team-identity`

Aliases: `Alatyr set actor`, `Alatyr who am I`, `Alatyr clear actor`.

Use to inspect, select, or clear the current local actor. Local actor selection
does not grant authentication or authority. The flow is
`.ai/assistant/flows/team-identity.flow.md`.

Allowed actions: `read-only` or `adapter-only`.

### `team-task`

Aliases: `Alatyr start`, `Alatyr claim`, `Alatyr checkpoint`,
`Alatyr release`.

Use to start, claim, checkpoint, or release a team task without broadening its
project-change scope. The flow is
`.ai/assistant/flows/team-task-coordination.flow.md`.

Allowed actions: `read-only` or `adapter-only`.

### `team-conflict-review`

Alias: `Alatyr conflicts`.

Use to detect active-task overlap from facts, owners, contracts, dependencies,
and secondary file evidence. The flow is
`.ai/assistant/flows/team-task-coordination.flow.md`.

Allowed actions: `read-only`.

### `team-handoff`

Alias: `Alatyr handoff`.

Use to checkpoint and hand a task to another actor or role with bounded resume
context. The flow is `.ai/assistant/flows/team-handoff.flow.md`.

Allowed actions: `read-only` or `adapter-only`.

### `team-decision`

Aliases: `Alatyr decision`, `Alatyr discuss`.

Use to structure a priority, business, architecture, data, security, or
adapter decision and route accepted facts to their canonical owners. The flow
is `.ai/assistant/flows/team-decision.flow.md`.

Allowed actions: `read-only`, `docs-only`, or `full-with-approval`.

### `team-review`

Alias: `Alatyr review`.

Use to review a task against current scope, concurrent work, required
reviewers, validation, and logical integrity. The flow is
`.ai/assistant/flows/team-review.flow.md`.

Allowed actions: `read-only`.

### `team-merge-check`

Alias: `Alatyr merge check`.

Use to classify revision-bound merge readiness without performing a merge.
The flow is `.ai/assistant/flows/team-review.flow.md`.

Allowed actions: `read-only`.

### `engineering-evidence`

Aliases: `Alatyr evidence`, `Alatyr capture evidence`,
`Alatyr explain decision`.

Use to capture or inspect compact project-owned invariant, root-cause,
solution, regression, validation, and repository-binding evidence. The flow is
`.ai/assistant/flows/engineering-evidence-capture.flow.md`.

Allowed actions: `read-only`, `docs-only`, `adapter-only`, `code-and-tests`,
or `full-with-approval`.

### `debug-mode`

Aliases: `Enable Alatyr Debug Mode`, `Alatyr debug`, `Alatyr debug status`,
`Alatyr debug checkpoint`, `Alatyr debug summary`,
`Disable Alatyr Debug Mode`, `Alatyr compare debug`.

Use to explicitly enable, inspect, checkpoint, finalize, disable, or compare
non-canonical task observability evidence. Enablement requires an explicit
current-task or current-session request. The flow is
`.ai/assistant/flows/debug-mode.flow.md`.

Allowed actions: `read-only` or `adapter-only`. Activation never grants code,
commit, publish, live-external, protected-change, or tool permission.

### `diagram-discussion`

Aliases: `Alatyr diagram`, `show as a diagram`, `visualize architecture`.

Use to present, compare, or revise a diagram with a bounded portable ASCII
view and an optional capability-checked rich presentation. The flow is
`.ai/assistant/flows/diagram-discussion.flow.md`.

Allowed actions: `read-only` or `docs-only`.

### `ai-infrastructure-inventory`

Alias: `alatyr-ai-inventory`.

Use to report existing target-owned AI infrastructure and unresolved inventory
gaps. The flow is
`.ai/assistant/flows/ai-infrastructure-inventory.flow.md`.

Allowed actions: `read-only`.

### `ai-infrastructure-recommendation`

Aliases: `alatyr-suggest-ai`, `alatyr-improve-ai`,
`Alatyr suggest extensions`.

Use to recommend evidence-based additions or improvements without changing AI
infrastructure. The flow is
`.ai/assistant/flows/ai-infrastructure-recommendation.flow.md`.

Allowed actions: `read-only`.

### `skill-adaptation`

Aliases: `alatyr-adaptation`, `alatyr-add-ai`.

Use to review or adapt skills, prompts, gates, tools, bridges, wrappers, and
related sources. The flow is `.ai/assistant/flows/skill-adaptation.flow.md`.

Allowed actions: `read-only`, `adapter-only`, or `full-with-approval`.

### `extension-management`

Aliases: `Alatyr extensions`, `Alatyr inspect extension`,
`Alatyr add extension`, `Alatyr update extension`,
`Alatyr disable extension`, `Alatyr remove extension`,
`Alatyr review extension`.

Use to list, inspect, plan, install, update, disable, remove, or review
declarative extension packages through target-owned locks and bindings. The
flow is `.ai/assistant/flows/extension-lifecycle.flow.md`.

Allowed actions: `read-only`, `adapter-only`, or `full-with-approval`.

### `dependency-knowledge`

Aliases: `Alatyr dependencies`, `Alatyr dependency status`,
`Alatyr sync dependencies`, `Alatyr inspect dependency`,
`Alatyr explain dependency`, `Alatyr dependency impact`.

Use to discover, inspect, plan, synchronize, explain, or assess passive
package knowledge without activating nested adapters or changing dependencies.
The flow is `.ai/assistant/flows/dependency-knowledge-sync.flow.md`.

Allowed actions: `read-only`, `adapter-only`, or `full-with-approval`.

### `workspace-mode`

Aliases: `Alatyr modes`, `Alatyr mode status`, `Alatyr suggest modes`,
`Alatyr mode`, `Alatyr define mode`, `Alatyr accept mode`.

Use to list, suggest, inspect, select, define, accept, update, disable,
deprecate, remove, or review user-owned workspace modes. The flow is
`.ai/assistant/flows/workspace-mode.flow.md`.

Allowed actions: `read-only`, `adapter-only`, or `full-with-approval`.

### `project-vocabulary`

Aliases: `Alatyr glossary`, `Alatyr define term`, `propose glossary entry`,
`check terminology`, `review project vocabulary`.

Use to explain, propose, review, or synchronize scoped project terms, aliases,
acronyms, and terminology links. The flow is
`.ai/assistant/flows/project-vocabulary.flow.md`.

Allowed actions: `read-only`, `docs-only`, or `full-with-approval`.

### `test-first-configuration`

Aliases: `Alatyr enable test-first`, `Alatyr configure TDD`,
`Alatyr review test-first`, `Alatyr disable test-first`.

Use to assess, enable, revise, disable, or review target-adapted test-first
development without silently changing project tooling or gates. The flow is
`.ai/assistant/flows/test-first-configuration.flow.md`.

Allowed actions: `read-only`, `adapter-only`, or `full-with-approval`.

### `test-first-change`

Aliases: `Alatyr test first`, `Alatyr TDD`,
`fix regression test first`, `characterize before refactor`,
`define contract first`.

Use to apply the enabled target policy through recommendation, RED, GREEN,
refactor, broader validation, and logical-integrity evidence. The flow is
`.ai/assistant/flows/test-first-change.flow.md`.

Allowed actions: `read-only`, `code-and-tests`, or `full-with-approval`.

## Routing Rules

- Start from `AGENTS.md` and `.ai/assistant/bootstrap-index.json`.
- Use `.ai/assistant/context-router.json` to choose exactly one smallest
  matching profile, then add project-area overlays only when affected.
- Use `.ai/assistant/module-profile.md` before relying on optional modules.
- Use `.ai/project/business-logic.md`, `.ai/project/blueprint.md`, and
  `.ai/project/source-of-truth-registry.md` for business-rule routing, fact
  ownership, and blueprint routing.
- Use `.ai/assistant/gates/index.json` to select installed gate fragments.
- For non-trivial work, use `.ai/project/knowledge/index.json` and
  `.ai/assistant/context/project-knowledge-routing.json` after profile/area
  selection and again after concrete facts are known. Treat coverage as
  `mapped`, `known-gap`, or `unknown`; item counts do not prove completeness.
- Show `.ai/assistant/templates/pre-change-preview.md` before semantic,
  protected, cross-boundary, external-effect, or unclear-scope edits.

## Validation

Target validation commands recorded for this workspace:

- `/usr/local/bin/composer8 install`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpunit`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G`
- docs validation after the docs script resolves a PHP 8-compatible composer
  command

Known local test constraint: SQLite 3.31.1 here does not provide SQL `SQRT()`,
so the full PHPUnit suite reports SQLite `SQRT()` errors in `Doctrine\Tests\ORM\Functional\QueryDqlFunctionTest::testFunctionSqrt` and `Doctrine\Tests\ORM\Functional\Ticket\GH7941Test::typesShouldBeConvertedForDQLFunctions` unless
the SQLite runtime or test profile changes.

## Enabled Modules

This branch enables the complete Alatyr support profile recorded in
`.ai/assistant/module-profile.md` and `.ai/alatyr.yaml`. Optional-module
operations may be routed when the module is enabled, the operation appears in
`.ai/assistant/operation-index.json` and `.ai/assistant/operation-catalog.json`,
and the referenced flow exists.

Runtime assistant capabilities remain evidence-bound and must be checked from
`.ai/assistant/assistant-capabilities.json` before use.

## Final Evidence

Every completed Alatyr-routed task should report the selected operation and
profile, changed facts/files, source-of-truth owners, integrity result,
validation run or skipped with reason, approvals used, and residual risk.
Adapter readiness additionally requires the manifest and
`.ai/assistant/installation-state.json` to report `accepted` after strict
validation for the current branch and revision.

## Full Capability Set

This branch uses `.ai/assistant/module-profile.md` as the accepted complete capability graph. Use the operation catalog and router for exact flows, and use target-owned owner files before claiming or changing module facts. Runtime assistant capabilities remain evidence-bound and must be checked from `.ai/assistant/assistant-capabilities.json`.
