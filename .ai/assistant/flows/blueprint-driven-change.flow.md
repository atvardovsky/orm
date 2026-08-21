# Blueprint-Driven Change Flow

Use this flow when a requested change may affect `Doctrine ORM` accepted
behavior, source-of-truth docs, implementation, tests, diagrams, or assistant
governance.

## Target Sources

- Project source of truth: `README.md, docs/en/reference/*.rst, SECURITY.md, CONTRIBUTING.md, composer.json, tests/README.markdown, and CI workflows`
- Blueprint index: `.ai/project/blueprint.md`
- Business logic layer: `.ai/project/business-logic.md`
- Equivalent source-of-truth docs: `README.md and docs/en/reference/*.rst`
- Project flow docs: `docs/en/reference/*.rst and selected source/test files`
- Test strategy and validation: `CONTRIBUTING.md, tests/README.markdown, phpunit.xml.dist, PHPStan, PHPCS, GitHub Actions`
- Diagram policy: `no target-owned diagram policy found; manual documentation review only`
- Security/live-service policy: `SECURITY.md and docs/en/reference/security.rst; report security vulnerabilities to security@doctrine-project.org, not public GitHub issues`

## Steps

1. State change intent and non-goals.
2. Use `.ai/assistant/context-router.json` to load the smallest matching
   profile and project-area overlays plus the target source-of-truth docs.
3. Apply `.ai/assistant/flows/logical-integrity-review.flow.md`.
4. List changed fact IDs and canonical owners, re-derive testable invariants,
   and cluster related review items by shared fact or contract. The
   consistency-map module is not installed, so use manual impact closure unless
   a future adapter expansion enables a project map.
5. Update target blueprint, business-logic layer, or equivalent
   source-of-truth docs when accepted facts change.
6. Update project flow, use-case, data, runtime, architecture, or public docs
   when those facts change.
7. Prepare an implementation plan that names affected boundaries, tests,
   approvals, machine-readable scope records when used, and validation.
8. Evaluate whether tests should be added or updated from existing Doctrine
   test and validation evidence. The optional test-first module is deferred, so
   do not require test-first artifacts unless a future target policy enables it.
9. Change remaining code, tests, diagrams, prompts, skills, bridge files,
   gates, or checker rules as required by the accepted fact change.
10. Run target validation that exists. Do not invent commands.
11. When approval was used, compare the complete Git change set with the
    explicitly selected machine-readable approval scope and fail on uncovered
    or excluded paths.
12. Perform a final consistency check across changed surfaces and related
    review-item clusters.
13. Report final evidence, skipped checks, approvals, and residual risk.

## Approval Gate

Require explicit programmer approval before:

- architecture changes
- accepted business behavior changes
- weakened tests, gates, documentation-sync rules, or approval requirements
- new production dependencies, services, permissions, or credentials
- live, destructive, spend-affecting, data-loss, security, or privacy changes
- overwriting existing AI instructions
- integrating third-party assistant infrastructure into canonical target files

## Final Evidence

Report:

- changed facts
- re-derived invariants and reconciled review-item clusters
- relationship impact closure, missing links, and areas reached
- business-logic layer sync result
- source-of-truth or blueprint updates
- implementation, test, diagram, prompt, skill, gate, bridge, or checker updates
- validation run or unresolved
- test update result, accepted exception, or reason no test change was indicated
- approvals used
- changed-path approval scope enforcement result
- skipped checks and residual risk
