# Doctrine ORM Team Operating Model

Status: enabled
Owner: `@atvardovsky`
Last reviewed: 2026-08-21

This branch uses repository-local Alatyr team coordination. The canonical team
policy is `.ai/project/team-policy.json`; the canonical work registry is
`.ai/assistant/team/work-registry.json`.

## Actors

- `atvardovsky`: repository owner, reviewer, approver, and merge authority.
- `codex`: assistant implementer with no approval authority.

## Coordination

Tasks use the current Git branch unless the user asks for a separate branch or
worktree. Claims and checkpoints are advisory records, not authentication or
merge authority. Do not store raw conversations, secrets, credentials, or
private personal data in team records.

## Review And Decisions

Protected behavior, architecture, security, dependency, live-service,
permission, CI-gate, and weakened-validation changes require explicit owner
approval. Commit work also follows `.ai/project/commit-policy.md`.
