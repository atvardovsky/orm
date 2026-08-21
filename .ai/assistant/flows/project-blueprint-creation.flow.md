# Project Blueprint Creation Flow

Use this flow when creating, repairing, or rechecking `Doctrine ORM` blueprint
or equivalent source-of-truth docs.

## Target Sources

- Project source of truth: `README.md, docs/en/reference/*.rst, SECURITY.md, CONTRIBUTING.md, composer.json, tests/README.markdown, and CI workflows`
- Blueprint index: `.ai/project/blueprint.md`
- Business logic layer: `.ai/project/business-logic.md`
- Equivalent source-of-truth docs: `README.md and docs/en/reference/*.rst`
- Public docs: `README.md and docs/en/**/*.rst`
- Architecture/design docs: `docs/en/reference/architecture.rst`
- Tests and validation: `CONTRIBUTING.md, tests/README.markdown, phpunit.xml.dist, PHPStan, PHPCS, GitHub Actions`
- Security/live-service policy: `SECURITY.md and docs/en/reference/security.rst; report security vulnerabilities to security@doctrine-project.org, not public GitHub issues`
- Diagram policy: `no target-owned diagram policy found; manual documentation review only`

## Steps

1. Load `AGENTS.md`, `.ai/README.md`, framework docs, target contours,
   `.ai/project/blueprint.md`, `.ai/project/business-logic.md`, and the
   selected target source-of-truth docs.
2. Identify blueprint scope and non-goals.
3. Collect target evidence from docs, code structure, tests, validation, CI,
   diagrams, prompts, skills, gates, and bridge files.
4. Classify facts by owner: framework, project, repository adapter, bridge,
   skill/prompt, or generated artifact.
5. Draft or repair only facts supported by target evidence.
6. Mark missing or contradictory facts explicitly.
7. Apply `.ai/assistant/flows/logical-integrity-review.flow.md`.
8. Update `.ai/project/blueprint.md`, `.ai/project/business-logic.md`,
   equivalent source-of-truth docs, project contour, flow docs, gates, prompts,
   skills, or bridge files only when their owned facts change.
9. Run target validation that exists. Do not invent commands.
10. Report final evidence, unresolved facts, skipped checks, approvals, and
    residual risk.

## Rejection Criteria

Reject or revise blueprint work that:

- invents business rules, architecture, data model, runtime flows, security
  policy, validation commands, or diagram tooling
- copies source-project facts from Alatyr Core or another repository
- treats generated artifacts or bridge files as canonical without checking
  their owning source
- claims blueprint completion while placeholders or missing facts remain
- changes accepted architecture or business behavior without approval
