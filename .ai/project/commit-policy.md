# Project Commit Policy

This is the target-owned Alatyr commit policy for the atvardovsky Doctrine ORM
fork.

Status: accepted for this Alatyr adapter
Path: `.ai/project/commit-policy.md`
Last reviewed: 2026-08-21
Owner: `@atvardovsky`

## Rule

Every commit must have one logical scope and a detailed commit message written
in English.

## Logical Scope

A commit has one logical scope when all staged changes are needed for the same
intent, bug fix, feature, documentation update, adapter update, or validation
repair.

Before creating a commit:

- inspect the staged diff;
- remove unrelated files or hunks from the commit;
- split independent changes into separate commits;
- keep generated or adapter-derived files with their source change when they
  are required to keep the repository consistent;
- do not include dependency, formatting, documentation, test, or generated
  changes unless they belong to the same logical intent.

## Commit Message

Commit messages must be written in English and explain the change in enough
detail for review without reopening the entire diff.

Each commit message should include:

- a concise subject naming the logical scope and outcome;
- a body explaining why the change is needed;
- a body explaining the main files, layers, or behavior affected;
- validation that was run, skipped, blocked, or not applicable;
- known residual risk or follow-up when relevant.

Do not use vague messages such as "update", "fix", "changes", or "work in
progress" unless the body gives precise scope, reason, and validation evidence.

## Alatyr Adapter Commits

For Alatyr adapter commits, include the affected layers in the body when they
changed, such as manifest, project contour, blueprint, business logic layer,
source-of-truth registry, context router, profiles, operation catalog, flows,
gates, templates, bootstrap index, or root assistant entry points.

Final evidence before committing must state whether the commit policy was
checked and whether the staged diff still has one logical scope.
