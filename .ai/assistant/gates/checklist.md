# AI Acceptance Gates

Use this full checklist only for ambiguity, adapter repair, framework update
review, or an explicit acceptance audit. Routine work should select gate
fragments through `.ai/assistant/gates/index.json`.

## Installed Gate Fragments

- `core`: `.ai/assistant/gates/core.md`
- `documentation`: `.ai/assistant/gates/documentation.md`
- `code-and-tests`: `.ai/assistant/gates/code-and-tests.md`
- `semantic-integrity`: `.ai/assistant/gates/semantic-integrity.md`
- `security-approval`: `.ai/assistant/gates/security-approval.md`
- `final-evidence`: `.ai/assistant/gates/final-evidence.md`

Do not reference optional gate files unless a future adapter expansion enables
the module, records it in `.ai/assistant/module-profile.md`, and installs the
gate file.

## Mandatory Checks

- Treat `AGENTS.md` as preloaded and start from
  `.ai/assistant/bootstrap-index.json`.
- If bootstrap is stale or routing is unclear, reload `.ai/alatyr.yaml`,
  `.ai/README.md`, and `.ai/assistant/context-router.json`, then regenerate
  the bootstrap index.
- Select the smallest context profile and affected project-area overlays from
  `.ai/assistant/context-router.json`.
- Use `.ai/project/business-logic.md`, `.ai/project/blueprint.md`, and
  `.ai/project/source-of-truth-registry.md` for business-rule routing and
  changed-fact ownership.
- Check `.ai/assistant/module-profile.md` before relying on optional Alatyr
  capabilities.
- Use `.ai/assistant/operation-catalog.json` and
  `.ai/assistant/operation-index.json` for installed operation routing.
- Show `.ai/assistant/templates/pre-change-preview.md` before semantic,
  protected, cross-boundary, external-effect, or unclear-scope edits.
- Keep deferred optional modules from creating routing obligations, missing-file
  failures, approval bypasses, or hidden validation requirements.
- Check security policy from `SECURITY.md` and
  `docs/en/reference/security.rst` before security-sensitive work.
- Do not invent Doctrine behavior, validation commands, policies, diagrams, or
  lifecycle notes.
- Preserve user work in the Git tree; do not revert unrelated changes.
- Before creating or amending a commit, load `.ai/project/commit-policy.md`,
  verify the staged diff has one logical scope, and write a detailed commit
  message in English.

## Semantic Change Decision Gate

Decide whether behavior, architecture, data, persistence, validation, security,
assistant routing, gate policy, or source-of-truth ownership changed.

If a semantic fact changed:

- name the changed fact and canonical owner;
- re-derive affected invariants and contracts;
- update the owning docs/source/tests/business-logic/adapter surfaces together;
- run relevant target validation or report why it is unavailable;
- require explicit approval before protected changes.

If no semantic fact changed, final evidence must explain why no companion docs,
tests, business-logic layer, blueprint, registry, or gate update was needed.

## Target Validation

Target commands or manual checks recorded for this workspace:

- `/usr/local/bin/composer8 install`
- `/usr/local/bin/php8 vendor/bin/phpunit`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G`
- docs validation after the docs script resolves a PHP 8-compatible composer
  command

Known local blocker: SQLite 3.31.1 lacks SQL `SQRT()`, so the full PHPUnit
suite stops at
`Doctrine\Tests\ORM\Functional\QueryDqlFunctionTest::testFunctionSqrt` in this
environment unless SQLite/runtime/test profile changes.

## Adapter Drift Checks

For adapter health, installation recheck, or framework update work, verify:

- manifest, module profile, operation catalog/index, gate index, router, and
  bootstrap agree;
- all installed operation flows exist;
- JSON/YAML files parse;
- bootstrap index matches canonical source hashes;
- no unresolved all-caps brace-style placeholder markers remain in active files;
- no local temp or absolute checkout paths leak into adapter files;
- optional-module references are clearly deferred or guarded;
- target-local checker status is reported as missing unless one is installed;
- `git diff --check` passes.

## Final Evidence

Every completed task reports selected operation/profile, changed facts/files,
source-of-truth owners, invariant/integrity result, validation run or skipped
with reason, approvals used, context expansion when relevant, commit-policy
check when committing, and residual risk.
