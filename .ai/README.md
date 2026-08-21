# AI Area Map

This directory is split by ownership for the Doctrine ORM fork.

For routine routing, treat root `AGENTS.md` as preloaded and read only
`.ai/assistant/bootstrap-index.json`. That file is a generated, hash-bound
projection of this project map, `.ai/alatyr.yaml`, and
`.ai/assistant/context-router.json`; load those canonical sources when the
projection is stale, routing is ambiguous, or adapter repair is required.

## Framework Area

`.ai/framework` contains the installed Alatyr Core standard framework pack.
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

The accepted project blueprint index is `.ai/project/blueprint.md`. The
accepted project commit policy is `.ai/project/commit-policy.md`. No
target-owned architecture catalog, consistency map, vocabulary catalog,
test-first policy, team policy, or diagram policy was accepted during this
installation.

## Repository Adapter Area

`.ai/assistant` contains local assistant operating rules:

- compact generated bootstrap index
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
assistants, source-of-truth files, module state, validation entry points,
known gaps, and local deviations.

## Recovery Note

If adapter state is unclear, load `.ai/assistant/templates/installation-note.md`
and run the read-only `Alatyr status` operation before broad work.
