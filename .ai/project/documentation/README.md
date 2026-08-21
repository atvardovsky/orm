# Doctrine ORM Code Documentation Knowledge

Status: enabled
Owner: `@atvardovsky`
Last reviewed: 2026-08-21
Evidence revision: `454db525c`

This layer records how Alatyr handles code documentation and comment work in
this Doctrine ORM fork. Public behavior belongs in `docs/en/reference/*.rst`.
Code comments should clarify invariants, edge cases, and non-obvious ORM
contracts without duplicating identifiers, types, or canonical documentation.
Code-authoring rules for architecture, PHP format, comment usage, tests, and
validation live in `.ai/project/code-authoring.md`.

## Areas

- Runtime PHP source: `src/**/*.php`
- PHPUnit tests and fixtures: `tests/**/*.php`
- Public reference documentation: `docs/en/**/*.rst`
- Adapter documentation: `.ai/**/*.md`, `.ai/**/*.json`

## Profile States

Accepted profiles may guide documentation synchronization. Proposed profiles require review. Deprecated, contradicted, or unknown profiles are not automatic generation authority.

## Source-Of-Truth Boundary

Documentation profiles describe where comments and generated references may appear. They do not replace Doctrine ORM source, tests, public RST documentation, or the source-of-truth registry as fact owners.

## Documentation Areas

Runtime comments, test comments, and public RST documentation are separate areas. Each area must use the matching profile in `profiles.json` and report validation evidence.
