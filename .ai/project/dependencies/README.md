# Doctrine ORM Dependency Knowledge

This directory is the enabled Alatyr dependency-knowledge layer for the
atvardovsky Doctrine ORM fork.

Status: enabled
Owner: `@atvardovsky`
Last reviewed: 2026-08-26
Evidence revision: `68a7b23a`

Canonical dependency facts come from `composer.json` and, when present,
`composer.lock`. Dependency knowledge is read-only unless the user explicitly
approves dependency or lockfile changes. Package manager scripts are not run
for discovery; use manifest and lockfile metadata first.

`package_lock_fingerprint` is the SHA-256 digest of the current local
`composer.lock` bytes. The lockfile is ignored by Git in this repository, so
the projection must be refreshed after dependency installation or lock changes.
