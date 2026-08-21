# Rule Ownership

This file is generated from `framework/rule-registry.json`. Per-rule canonical
owners define rule semantics. Category routing owners group related rules for
maintainers and tools but do not become additional semantic owners.
Derived documents should reference the owner or rule ID and avoid copying
the complete policy language.

## Ownership Rules

- Change the canonical owner document before changing a rule summary.
- Keep installer, template, bridge, and help wording as short references.
- Keep owner front matter aligned with registered IDs and dependencies.
- Record material contract changes in the changelog and migration evidence.

## Category Routing Owners

Category: `CONTEXT`
Routing owner: `.ai/framework/context-profiles.md`
Rule IDs: `ALATYR-CONTEXT-001`
Derived surfaces: README source context, installation source context, target
context profiles, target context router, task-scale overlays, operation packet
context receipts, consistency relationship routing, session bootstrap
instructions, AI infrastructure capability and recommendation routing.

Category: `SOURCE`
Routing owner: `.ai/framework/source-of-truth-registry.md`
Rule IDs: `ALATYR-SOURCE-001`
Derived surfaces: project adapter contract, logical integrity, blueprint
change, target source-of-truth registry template, invariant constraints,
consistency map.

Category: `RISK`
Routing owner: `.ai/framework/change-risk-model.md`
Rule IDs: `ALATYR-RISK-001`
Derived surfaces: installer approval planning, target gates, operation request
templates, external failure observability, final evidence.

Category: `APPROVAL`
Routing owner: `.ai/framework/approval-records.md`
Rule IDs: `ALATYR-APPROVAL-001`
Derived surfaces: installation approval gate, installed-operation allowed
actions, human and machine-readable approval templates, strict target diff
scope validation, security-sensitive profiles.

Category: `SAFETY`
Routing owner: `.ai/framework/security-safety-guidance.md`
Rule IDs: `ALATYR-SAFETY-001`, `ALATYR-SAFETY-002`
Derived surfaces: prompt-injection guidance, skill adaptation, source-access
policy, adaptation records, security-sensitive context profile.

Category: `INTEGRITY`
Routing owner: `.ai/framework/logical-integrity.md`
Rule IDs: `ALATYR-INTEGRITY-001`
Derived surfaces: target gates, documentation sync, adapter recheck,
relationship or manual invariant impact closure, review-item reconciliation,
workstream convergence, final evidence.

Category: `CHANGE`
Routing owner: `.ai/framework/blueprint-driven-change.md`
Rule IDs: `ALATYR-CHANGE-001`
Derived surfaces: product-change operation, blueprint-driven target flow,
large-task orchestration, documentation and diagram sync.

Category: `ADAPTER`
Routing owner: `.ai/framework/project-adapter-contract.md`
Rule IDs: `ALATYR-ADAPTER-001`
Derived surfaces: installation plan, readiness checklist, manifest template,
adapter recheck flow, target development-pattern evidence, framework pack and
projected inventory, AI infrastructure router, AI infrastructure recommendation
contract, AI infrastructure item contracts.

Category: `MODULE`
Routing owner: `.ai/framework/module-profile.md`
Rule IDs: `ALATYR-MODULE-001`
Derived surfaces: target module profile, manifest modules, framework pack
catalog, scaffold profile-to-pack mapping, operation help routing, maturity
review.

Category: `OPERATION`
Routing owner: `.ai/framework/operation-help.md`
Rule IDs: `ALATYR-OPERATION-001`
Derived surfaces: target operation catalog, checked compact operation index,
automatic routing flow, compact help, adapter health flow, pre-change preview,
manifest operation paths, assistant bridges.

Category: `BRIDGE`
Routing owner: `.ai/framework/bridge-capability-matrix.md`
Rule IDs: `ALATYR-BRIDGE-001`
Derived surfaces: assistant bridge templates, bridge renderer, bridge
capability target template, cross-assistant AI item routing.

Category: `LIFECYCLE`
Routing owner: `.ai/framework/lifecycle.md`
Rule IDs: `ALATYR-LIFECYCLE-001`
Derived surfaces: version files, installed framework pack, migration notes,
framework update recheck, changelog.

Category: `EVIDENCE`
Routing owner: `.ai/framework/guarantees.md`
Rule IDs: `ALATYR-EVIDENCE-001`
Derived surfaces: final evidence, process commitments, conformance reports,
effectiveness reports, operation packets.

## Rule Canonical Owners

Rule: `ALATYR-CONTEXT-001`
Canonical owner: `.ai/framework/context-profiles.md`

Rule: `ALATYR-SOURCE-001`
Canonical owner: `.ai/framework/source-of-truth-registry.md`

Rule: `ALATYR-RISK-001`
Canonical owner: `.ai/framework/change-risk-model.md`

Rule: `ALATYR-APPROVAL-001`
Canonical owner: `.ai/framework/approval-records.md`

Rule: `ALATYR-SAFETY-001`
Canonical owner: `.ai/framework/security-safety-guidance.md`

Rule: `ALATYR-SAFETY-002`
Canonical owner: `.ai/framework/prompt-injection.md`

Rule: `ALATYR-INTEGRITY-001`
Canonical owner: `.ai/framework/logical-integrity.md`

Rule: `ALATYR-CHANGE-001`
Canonical owner: `.ai/framework/blueprint-driven-change.md`

Rule: `ALATYR-ADAPTER-001`
Canonical owner: `.ai/framework/project-adapter-contract.md`

Rule: `ALATYR-MODULE-001`
Canonical owner: `.ai/framework/module-profile.md`

Rule: `ALATYR-OPERATION-001`
Canonical owner: `.ai/framework/operation-help.md`

Rule: `ALATYR-BRIDGE-001`
Canonical owner: `.ai/framework/bridge-capability-matrix.md`

Rule: `ALATYR-LIFECYCLE-001`
Canonical owner: `.ai/framework/lifecycle.md`

Rule: `ALATYR-EVIDENCE-001`
Canonical owner: `.ai/framework/guarantees.md`

## Change Protocol

1. Update the owning framework document and its `alatyr_doc` metadata.
2. Update `framework/rule-registry.json`.
3. Regenerate this file and `framework/rule-registry.md`.
4. Update affected installer, target, checker, and conformance surfaces.
5. Keep assistant bridges as pointers.
6. Record behavioral changes in `CHANGELOG.md` and migration evidence.
