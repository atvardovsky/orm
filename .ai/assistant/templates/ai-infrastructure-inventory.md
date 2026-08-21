# AI Infrastructure Inventory

Use this file in `<project-name>` to record the result of
`ai-infrastructure-inventory` or `alatyr-ai-inventory`.

Replace placeholders with target facts before accepting installation.

Inventory-only work must not import, install, execute, or normalize external
AI infrastructure into canonical target files.

## Inventory Scope

- Operation id: `<operation-id>`
- Inventory date: `<inventory-date>`
- Requested by: `<requester-or-role>`
- Allowed actions: `<read-only-or-adapter-only>`
- Target assistant surfaces: `<target-assistant-surfaces>`
- Source access policy:
  `.ai/assistant/policies/ai-infrastructure-source-access.md`
- Prompt-injection policy: `.ai/assistant/policies/prompt-injection.md`
- Surfaces inspected: `<surfaces-inspected>`
- Surfaces skipped: `<surfaces-skipped-and-reason>`
- Existing inventory source: `<existing-inventory-source-or-none>`
- AI infrastructure router: `.ai/assistant/ai-infrastructure-router.json`
- Router status: `<current-stale-missing-or-conflicting>`

## Item Record

Repeat this block for each skill, prompt, wrapper, bridge file, rule, memory,
MCP/tool config, checker, flow, gate, template, generated assistant artifact,
or other target-defined AI infrastructure item.

- Item id: `<ai-infrastructure-item-id>`
- Router route: `<inventory-use-existing-adapt-import-gate-checker-tool-mcp-or-bridge-wrapper>`
- Router item status: `<active-blocked-deprecated-unresolved-or-missing>`
- Item type:
  `<skill-prompt-wrapper-bridge-rule-memory-mcp-tool-checker-flow-gate-template-generated-artifact-or-other>`
- Path or reference: `<path-or-external-reference>`
- Owner:
  `<framework-project-repository-adapter-bridge-generated-external-unknown>`
- Source/provenance: `<source-or-provenance>`
- Source type:
  `<local-path-git-url-https-url-native-reference-pasted-package-plugin-unknown>`
- Source hash or commit: `<source-hash-commit-version-or-unavailable>`
- License: `<license-unknown-not-applicable>`
- Supported assistants: `<supported-assistants>`
- Declared purpose: `<declared-item-purpose-or-unknown>`
- Project-contour relevance: `<project-area-fact-owner-or-not-established>`
- Observed usage or outcome evidence:
  `<usage-quality-rework-cost-or-validation-evidence-or-unknown>`
- Staleness or maintenance signal: `<stale-unused-duplicated-current-or-unknown>`
- Permission surface: `<files-tools-commands-services-models-or-permissions>`
- Prompt-injection risk: `<prompt-injection-risk-notes>`
- Safety surface:
  `<live-service-destructive-credential-dependency-privacy-or-none>`
- Overlap or conflict: `<overlap-duplicate-policy-or-conflict>`
- Validation or manual review: `<validation-or-manual-review>`
- Approval status: `<approval-status-or-not-required>`
- Required gates and output contract: `<gates-and-output-contract-or-missing>`
- Preliminary disposition: `<keep-review-adapt-replace-remove-skip-or-unresolved>`
- Residual risk: `<residual-risk>`

## Summary

- Items found: `<items-found>`
- Usable without change: `<usable-without-change>`
- Need adaptation: `<need-adaptation>`
- Need approval: `<need-approval>`
- Need removal or replacement: `<need-removal-or-replacement>`
- Need evidence-based recommendation review:
  `<need-recommendation-review>`
- Left unresolved: `<left-unresolved>`
- Validation run: `<target-validation-run-or-manual-review>`
- Approvals needed: `<approvals-needed>`
- Recommended next operation: `<recommended-next-operation>`
- Final evidence: `<final-evidence>`
- Residual risk: `<residual-risk>`
