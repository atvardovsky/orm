# AI Area Map

This directory is split by ownership for the Doctrine ORM fork.

For routine routing, treat root `AGENTS.md` as preloaded and read only
`.ai/assistant/bootstrap-index.json`. That file is a generated, hash-bound
projection of this project map, `.ai/alatyr.yaml`, and
`.ai/assistant/context-router.json`, plus the installed semantic-codebook index;
load those canonical sources when the projection is stale, routing is
ambiguous, or adapter repair is required.

The bootstrap embeds the small core semantic codebook once. After routing,
start from the selected contour's `context-index.json` and follow only matching
child indexes or content entries. The indexes are derived navigation and cost
metadata, not project authority. Domain codebook shards are lazy; every compact
term resolves to versioned prose and its canonical owner before use.

## Installation State

Read `.ai/alatyr.yaml` before describing this adapter as installed or ready.
`scaffolded` means only placeholder structure exists. `staged` means adaptation
or update work remains unresolved. `accepted` requires strict validation for
the current branch and revision with no active placeholders or blocking gaps.
`degraded` means a previously accepted contract is no longer satisfied or
cannot currently be proven. Only `accepted` may qualify for `ready` health.
The ordered evidence for that claim lives at
`.ai/assistant/installation-state.json`; its final state must match the
manifest. The record does not grant authorization or approval.

## Framework Area

`.ai/framework` contains the installed Alatyr Core complete framework pack.
Framework files must not contain Doctrine ORM project facts, target commands,
security policy, lifecycle facts, or target-specific assistant infrastructure.

## Project Area

`.ai/project` contains target facts for Doctrine ORM:

- blueprint index and source routing in `.ai/project/blueprint.md`
- business-logic layer and behavior-rule routing in `.ai/project/business-logic.md`
- commit policy in `.ai/project/commit-policy.md`
- product purpose from `README.md`
- architecture and terminology from `docs/en/reference/architecture.rst`
- mapping, persistence, data, and UnitOfWork behavior from `docs/en/reference/*.rst` and `src/`
- security reporting and SQL-injection guidance from `SECURITY.md` and `docs/en/reference/security.rst`
- contribution and test expectations from `CONTRIBUTING.md`, `tests/README.markdown`, and CI workflows
- source-of-truth registry entries in `.ai/project/source-of-truth-registry.md`
- required support collection/classification policy and canonical state for
  locating changed support surfaces without loading them all
- optional dependency-ordered generation registry for target-selected derived
  support artifacts
- durable engineering evidence in `.ai/project/engineering-evidence/index.json`
- project-guidance intake, routing policy, coverage state, and empty active
  index in `.ai/project/knowledge/`
- optional non-canonical Debug Mode evidence that separates executor,
  Alatyr-system, automation, and human contributions in `.ai/project/debug/`

The accepted project blueprint index is `.ai/project/blueprint.md`. The
accepted project commit policy is `.ai/project/commit-policy.md`. The full
optional capability graph is accepted on this branch through
`.ai/assistant/module-profile.md`; target facts remain owned by their
registered canonical sources.

## Repository Adapter Area

`.ai/assistant` contains local assistant operating rules:

- compact generated bootstrap index
- recursive, digest-bound context indexes that expose only selected branches
- a versioned semantic codebook for lossless compact rule references
- deterministic context-packet evidence for non-trivial or expanded routes
- context profiles and machine router
- module and maturity profiles
- flows, gates, help, operation catalog, and output templates
- target validation command references and manual-review expectations
- final evidence requirements

Target commands and manual checks belong here or in linked target docs. They
are not framework core.

## Adapter Manifest

`.ai/alatyr.yaml` records framework version, adapter schema version, template
version, selected support profile, installed framework pack, supported
assistants, explicit installation state, source-of-truth files, module state,
validation entry points, known gaps, and local deviations.

## Recovery Note

If adapter state is unclear, load `.ai/assistant/templates/installation-note.md`
and run the read-only `Alatyr status` operation before broad work.

## Full Alatyr Capability Set

This branch uses the complete Alatyr framework pack and enables the full optional capability graph recorded in `.ai/assistant/module-profile.md`: consistency map, architecture knowledge, code documentation, project vocabulary, test-first development, dependency knowledge, workspace modes, diagrams, AI infrastructure, multi-assistant bridges, installed operations, large-task orchestration, subagent delegation, change packages, team collaboration, durable approvals, migration diff, effectiveness metrics, extensions, and scaffolding.

Capability use remains evidence-bound: runtime bridge, delegation, extension, network, permission, dependency, and protected project changes require the relevant owner files, gates, validation, and approval before action.
