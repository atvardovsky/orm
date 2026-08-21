# Project Contour

This contour describes Doctrine ORM project facts.

## Owns

- product purpose: object-relational mapper for PHP 8.1+ using Doctrine DBAL
- blueprint index and source routing in `.ai/project/blueprint.md`
- business-logic layer and behavior-rule routing in `.ai/project/business-logic.md`
- commit policy in `.ai/project/commit-policy.md`
- architecture facts from `docs/en/reference/architecture.rst`
- mapping, entity, UnitOfWork, query, persistence, and cache behavior from `docs/en/reference/*.rst` and `src/`
- contribution and test expectations from `CONTRIBUTING.md`, `tests/README.markdown`, and CI workflows
- security reporting and SQL-injection assumptions from `SECURITY.md` and `docs/en/reference/security.rst`
- validation command evidence from Composer metadata, PHPUnit/PHPStan/PHPCS configs, and `.github/workflows/`

## Does Not Own

- portable Alatyr Core framework rules
- assistant workflow mechanics
- assistant bridge-file mechanics
- local validation command policy outside project facts

## Source Of Truth

Target source-of-truth files inspected during installation:

- `README.md`
- `CONTRIBUTING.md`
- `SECURITY.md`
- `composer.json`
- `docs/en/reference/architecture.rst`
- `docs/en/reference/security.rst`
- `docs/en/reference/basic-mapping.rst`
- `docs/en/reference/unitofwork.rst`
- `tests/README.markdown`
- `.github/workflows/continuous-integration.yml`
- `.github/workflows/static-analysis.yml`
- `.github/workflows/coding-standards.yml`
- `.ai/project/blueprint.md`
- `.ai/project/business-logic.md`
- `.ai/project/commit-policy.md`
- `.ai/project/source-of-truth-registry.md`

The accepted Alatyr project blueprint index is `.ai/project/blueprint.md`.
The accepted Alatyr business-logic layer is `.ai/project/business-logic.md`.
The accepted Alatyr project commit policy is `.ai/project/commit-policy.md`.
Other optional Alatyr project catalogs remain deferred.

## AI Infrastructure Evidence Boundary

Project-contour sources may justify why an assistant capability is needed and
which project outcome it must improve. The assistant contour owns how a flow,
gate, checker, prompt, bridge, or wrapper is routed, validated, and maintained.

Raw conversations, secrets, credentials, and personal data must not be stored
in project evidence.
