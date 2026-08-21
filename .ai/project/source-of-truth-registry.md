# Source Of Truth Registry

Use this registry in Doctrine ORM to decide which file owns each fact type.
When an owner is missing, report the gap instead of inventing a project fact.

### Fact Type: `product behavior`

Fact type: `product behavior`
Canonical owner: `.ai/project/business-logic.md` and `.ai/project/blueprint.md` for source routing, `README.md` for high-level purpose, and `docs/en/reference/*.rst` for documented behavior
Consistency level: manual review required for semantic changes
Project area: `docs`, `src`
Consistency map node: missing, consistency-map module deferred
Relationship coverage: manual impact closure through selected docs, source, and tests
Invariant and dependency constraints: ORM behavior must stay consistent with public docs and PHPUnit coverage
Derived surfaces:

- `.ai/project/business-logic.md`
- `src/`
- `tests/`
- `docs/`

Sync direction: accepted docs and code changes must be reconciled both ways before final evidence
Validation or manual review: `/usr/local/bin/php8 vendor/bin/phpunit` and relevant docs/manual review
Conflict resolver: repository owner or upstream Doctrine maintainer review when contributing back
Approval trigger: accepted public behavior change or weakened validation
Final evidence: changed facts, docs/source/test sync, validation, residual risk

### Fact Type: `business rule`

Fact type: `business rule`
Canonical owner: `.ai/project/business-logic.md` for business-rule routing plus `.ai/project/blueprint.md`, public docs under `docs/en/reference/*.rst`, relevant source, and tests
Consistency level: manual review required
Project area: `docs`, `src`, `tests`
Consistency map node: missing, consistency-map module deferred
Relationship coverage: manual impact closure
Invariant and dependency constraints: documented ORM rules must match implementation and tests
Derived surfaces:

- `.ai/project/business-logic.md`
- `src/`
- `tests/Tests/ORM/`
- `docs/en/reference/`

Sync direction: implementation, tests, and docs must be reconciled before final evidence
Validation or manual review: `/usr/local/bin/php8 vendor/bin/phpunit`; docs review for public behavior
Conflict resolver: repository owner or upstream Doctrine maintainer review when contributing back
Approval trigger: accepted behavior or public contract change
Final evidence: changed rule, owner evidence, tests/docs sync, residual risk

### Fact Type: `business logic layer`

Fact type: `business logic layer`
Canonical owner: `.ai/project/business-logic.md`
Consistency level: adapter-owned current state with target evidence review
Project area: `business-logic`, `docs`, `src`, `tests`
Consistency map node: missing, consistency-map module deferred
Relationship coverage: manual impact closure through business-rule families, canonical Doctrine docs, source, tests, and validation
Invariant and dependency constraints: business-logic routing must describe accepted Doctrine ORM behavior contracts without creating new product behavior or overriding canonical docs/source/tests
Derived surfaces:

- `.ai/project/blueprint.md`
- `.ai/project/source-of-truth-registry.md`
- `.ai/assistant/context/profiles/business-change.json`
- `.ai/assistant/context-profiles.md`
- `.ai/assistant/maturity-profile.md`
- `.ai/assistant/flows/blueprint-driven-change.flow.md`
- `AGENTS.md`

Sync direction: business-rule routing changes must be reflected in blueprint, source registry, business-change context, maturity evidence, and affected flows
Validation or manual review: adapter validator plus manual owner-evidence review; run relevant PHPUnit/PHPStan/PHPCS checks when implementation changes
Conflict resolver: canonical Doctrine docs/source/tests win for product behavior; `.ai/project/business-logic.md` wins for Alatyr business-rule routing
Approval trigger: accepted behavior, public contract, security posture, persistence rule, query behavior, or validation-routing change
Final evidence: changed business rule family, canonical owners inspected, synchronized surfaces, validation, residual risk

### Fact Type: `architecture decision`

Fact type: `architecture decision`
Canonical owner: `.ai/project/blueprint.md` for source routing and `docs/en/reference/architecture.rst` for accepted architecture facts
Consistency level: manual review required
Project area: `architecture`
Consistency map node: missing, consistency-map module deferred
Relationship coverage: manual impact closure
Invariant and dependency constraints: ORM package boundaries and DBAL/Persistence/Collections relationships must remain explicit
Derived surfaces:

- `composer.json`
- `src/`
- `.github/workflows/`

Sync direction: architecture docs to implementation and validation plan
Validation or manual review: `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G` plus architecture doc review
Conflict resolver: repository owner or upstream Doctrine maintainer review when contributing back
Approval trigger: architecture boundary, dependency direction, or public API change
Final evidence: affected areas, invariant review, validation, residual risk

### Fact Type: `data model`

Fact type: `data model`
Canonical owner: `.ai/project/blueprint.md` for source routing plus mapping and persistence docs under `docs/en/reference/` and relevant mapping source in `src/Mapping/`
Consistency level: manual review required
Project area: `mapping`, `persistence`
Consistency map node: missing, consistency-map module deferred
Relationship coverage: manual impact closure
Invariant and dependency constraints: mapping metadata, UnitOfWork, identity, and persistence behavior must stay consistent
Derived surfaces:

- `src/Mapping/`
- `src/UnitOfWork.php`
- `tests/Tests/ORM/`

Sync direction: docs, source, and tests must be reconciled before final evidence
Validation or manual review: `/usr/local/bin/php8 vendor/bin/phpunit` with relevant database configuration when needed
Conflict resolver: repository owner or upstream Doctrine maintainer review when contributing back
Approval trigger: persistence behavior, schema, identity, query, or data-loss risk change
Final evidence: affected data facts, validation, rollback or residual risk

### Fact Type: `validation command`

Fact type: `validation command`
Canonical owner: `.ai/project/blueprint.md` for local command routing plus `composer.json`, `phpunit.xml.dist`, `phpstan*.neon`, `phpcs.xml.dist`, and `.github/workflows/`
Consistency level: exact command evidence from target files
Project area: `validation`
Consistency map node: missing, consistency-map module deferred
Relationship coverage: command-to-task mapping maintained in `.ai/alatyr.yaml` and context profiles
Invariant and dependency constraints: do not invent validation commands; report missing dependencies or extensions
Derived surfaces:

- `.ai/alatyr.yaml`
- `.ai/assistant/context/profiles/*.json`
- `.ai/assistant/maturity-profile.md`

Sync direction: target validation files to adapter validation references
Validation or manual review: parse JSON/YAML and run available target checks with `/usr/local/bin/php8` and `/usr/local/bin/composer8`
Conflict resolver: target validation files win over adapter summaries
Approval trigger: weakened tests, CI, gates, or validation requirements
Final evidence: command run or skipped with reason

### Fact Type: `commit policy`

Fact type: `commit policy`
Canonical owner: `.ai/project/commit-policy.md`
Consistency level: project-owned accepted rule
Project area: `commits`
Consistency map node: missing, consistency-map module deferred
Relationship coverage: manual review of staged diff and commit message
Invariant and dependency constraints: each commit must have one logical scope and a detailed commit message written in English
Derived surfaces:

- `AGENTS.md`
- `.ai/README.md`
- `.ai/project/blueprint.md`
- `.ai/assistant/context-router.json`
- `.ai/assistant/context-profiles.md`
- `.ai/assistant/gates/checklist.md`
- `.ai/assistant/gates/final-evidence.md`

Sync direction: commit policy changes must be reflected in bootstrap, routing,
gate, and final-evidence surfaces before committing
Validation or manual review: inspect staged diff with Git and review the final
commit message text
Conflict resolver: `.ai/project/commit-policy.md`
Approval trigger: weakening logical-scope or English-message requirements
Final evidence: staged scope review, commit message review, validation, and
residual risk

### Fact Type: `security policy`

Fact type: `security policy`
Canonical owner: `SECURITY.md` and `docs/en/reference/security.rst`
Consistency level: exact policy reference plus manual review
Project area: `security`
Consistency map node: missing, consistency-map module deferred
Relationship coverage: manual impact closure
Invariant and dependency constraints: security vulnerabilities are reported privately; do not post security bugs on public GitHub issues
Derived surfaces:

- `.ai/assistant/gates/security-approval.md`
- `AGENTS.md`

Sync direction: security policy to assistant gates and final evidence
Validation or manual review: security-sensitive work requires explicit approval and policy review
Conflict resolver: `SECURITY.md` and Doctrine security policy links
Approval trigger: security, credential, permission, privacy, dependency trust, live-service, or destructive changes
Final evidence: security owner evidence, approvals, validation, residual risk

### Fact Type: `assistant operation`

Fact type: `assistant operation`
Canonical owner: `.ai/assistant/operation-catalog.json`
Consistency level: adapter-owned current state
Project area: `assistant-adapter`
Consistency map node: missing, consistency-map module deferred
Relationship coverage: operation index and catalog must stay synchronized
Invariant and dependency constraints: operation routing does not grant approval or broaden allowed actions
Derived surfaces:

- `.ai/assistant/operation-index.json`
- `.ai/assistant/help.md`
- `.ai/assistant/flows/operation-routing.flow.md`

Sync direction: catalog to compact index and help text
Validation or manual review: Alatyr source validator from the installation source checkout or manual adapter review
Conflict resolver: `.ai/assistant/operation-catalog.json`
Approval trigger: weakened gates, approval rules, or imported AI infrastructure
Final evidence: selected operation, allowed actions, validation, residual risk

### Fact Type: `project blueprint index`

Fact type: `project blueprint index`
Canonical owner: `.ai/project/blueprint.md`
Consistency level: adapter-owned current state with target evidence review
Project area: `project`, `assistant-adapter`
Consistency map node: missing, consistency-map module deferred
Relationship coverage: manual impact closure through registry, docs, source, tests, and validation files
Invariant and dependency constraints: the blueprint index routes facts to canonical Doctrine owners and must not replace or contradict those owners
Derived surfaces:

- `.ai/alatyr.yaml`
- `.ai/assistant/module-profile.md`
- `.ai/assistant/flows/project-blueprint-creation.flow.md`
- `.ai/assistant/flows/blueprint-driven-change.flow.md`
- `.ai/assistant/context/profiles/*.json`

Sync direction: blueprint routing changes must be reflected in manifest, module profile, source registry, context profiles, and affected flows
Validation or manual review: adapter validator plus manual owner-evidence review
Conflict resolver: canonical Doctrine docs/source/tests win for product facts; adapter owners win for assistant routing
Approval trigger: changing accepted source-of-truth routing, public behavior ownership, protected validation, or approval gates
Final evidence: sources inspected, changed routing facts, validation, residual risk

### Fact Type: `development process pattern`

Fact type: `development process pattern`
Canonical owner: missing; development-evidence module deferred
Consistency level: not accepted for this installation
Project area: `process`
Consistency map node: missing
Relationship coverage: not enabled
Invariant and dependency constraints: do not store raw conversations, secrets, credentials, or personal data
Derived surfaces:

- no accepted derived surfaces

Sync direction: separate user decision required before enabling development evidence capture
Validation or manual review: manual review only
Conflict resolver: repository owner
Approval trigger: enabling persistent development-evidence capture or assistant recommendation records
Final evidence: module remains deferred unless separately accepted
