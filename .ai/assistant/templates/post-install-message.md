# Post-Install Assistant Chat Message

Use this template for the assistant's chat response after Alatyr Core is
installed in `Doctrine ORM`.

Replace placeholders with target facts before sending the message.

```text
Alatyr Core is installed for `Doctrine ORM`.

Entry points:
- `AGENTS.md`
- `AI_ASSISTANTS.md`
- `.ai/alatyr.yaml`
- `.ai/README.md`
- `.ai/assistant/templates/installation-note.md`
- `.ai/assistant/help.md`
- `.ai/assistant/help-reference.md`
- `.ai/assistant/operation-index.json`
- `.ai/assistant/operation-catalog.json`
- `.ai/assistant/context-router.json`
- `.ai/assistant/bootstrap-index.json`
- `.ai/assistant/gates/index.json`
- `.ai/assistant/context-profiles.md`
- `.ai/assistant/module-profile.md`
- `.ai/project/blueprint.md`
- `.ai/project/business-logic.md`
- `.ai/project/source-of-truth-registry.md`
- `.ai/assistant/maturity-profile.md`
- Optional-module files appear only after a future adapter expansion enables
  the module and installs its target-owned files.

Future assistant bootstrap:
- Do not rely on this chat message alone.
- Treat `AGENTS.md` as preloaded; start from `.ai/assistant/bootstrap-index.json`.
- Repair a stale generated index from its named manifest, project-map, and router sources; otherwise load profiles, module state, registries, blueprint, gate fragments, and the installation note only when routing or unclear adapter state requires them.
- Send `Alatyr` for compact actions or `Alatyr status` for a read-only adapter health check.
- If the installation itself is unclear, run `recheck-after-installation` before editing files.

Installed operation help:
- Send `Alatyr` to see adapter state and up to three relevant operations.
- Send `Alatyr status` or `Alatyr doctor` for read-only health evidence.
- Clear development requests route automatically; operation IDs are optional.
- Risky or cross-boundary changes show a pre-change preview before edits.
- Use `.ai/assistant/templates/operation-request.md` for structured requests.
- When team collaboration is enabled, use `Alatyr set actor <actor>`, `Alatyr who am I`, `Alatyr team status`, `Alatyr start`, `Alatyr claim`, `Alatyr conflicts`, `Alatyr checkpoint`, `Alatyr handoff`, `Alatyr decision`, `Alatyr review`, `Alatyr merge check`, or `Alatyr release`. Actor selection is local attribution, not authentication or authority.
- When architecture knowledge is enabled, use `Alatyr architecture` to inventory, explain, discuss, compare, review, or document project architecture and patterns.
- Review installation mode suggestions separately. Installation approval does
  not accept a suggested mode. Use `Alatyr suggest modes`, then `Alatyr accept
  mode <id>` only for a mode the user chooses.

Available next actions:
- `Alatyr`: show adapter state and up to three relevant installed operations.
- `adapter-health`: inspect installed adapter health without edits.
- `create-project-blueprint`: create or repair project source-of-truth docs from target evidence.
- `recheck-after-installation`: verify the installed adapter and report gaps.
- `recheck-after-framework-update`: assess impact from an Alatyr Core update.
- `product-change`: run blueprint-driven change from intent through validation and evidence.
- `logical-integrity-review`: check consistency across code, docs, tests, diagrams, prompts, skills, gates, and bridges.
- `drift-review`: check stale source-of-truth, docs, or adapter facts.
- `documentation-sync`: synchronize documentation and companion explanatory surfaces after a changed fact.
- `adapter-maturity-review`: report readiness for a task scope.
- Optional-module operations are unavailable until the module is enabled,
  cataloged, indexed, and backed by existing flow files.

Validation run:
`adapter structural validation run; Doctrine runtime tests attempted with local PHP 8 and blocked by local SQLite missing SQRT`

Known adapter gaps:
`backup owner missing; no target-local checker; non-blueprint optional modules deferred; default php/composer unsuitable; local SQLite lacks SQRT`

Suggested first request:
Alatyr status
```
