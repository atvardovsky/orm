# Doctrine ORM Dependency Knowledge

This directory is the enabled Alatyr dependency-knowledge layer for the
atvardovsky Doctrine ORM fork.

Status: enabled
Owner: `@atvardovsky`
Last reviewed: 2026-08-21
Evidence revision: `454db525c`

Canonical dependency facts come from `composer.json` and, when present,
`composer.lock`. Dependency knowledge is read-only unless the user explicitly
approves dependency or lockfile changes. Package manager scripts are not run
for discovery; use manifest and lockfile metadata first.
