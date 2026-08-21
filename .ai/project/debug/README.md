# Alatyr Debug Evidence

This directory stores optional, non-canonical observability evidence for
explicitly selected Alatyr-assisted tasks in Doctrine ORM.

Owner: `@atvardovsky`
Storage mode: `repository-internal`
Visibility: `repository-local`
Retention policy: `retain compact reviewed records while relevant to branch history`
Redaction policy: `exclude raw private chat, chain-of-thought, prompts, secrets, credentials, unrelated personal data, complete diffs, and verbose logs`
External patch policy: `exclude raw private chat secrets credentials and external-only logs`

Debug records answer how Alatyr and human supervision contributed to a task.
They do not own architecture, business rules, code contracts, approvals, or
validation facts. Route accepted findings to their canonical project owner and
link that owner or durable engineering-evidence record from the debug record.

Debug Mode is inactive unless the user explicitly enables it for the current
task or session. Activation expires when that logical scope completes, changes,
is abandoned, or is explicitly disabled.

Use `index.json` for compact cross-task lookup. Load only the selected record
under `records/` when detailed evidence is needed. Never store raw
conversations, chain-of-thought, prompts, credentials, secrets, unrelated
personal data, complete diffs, or verbose logs here.
