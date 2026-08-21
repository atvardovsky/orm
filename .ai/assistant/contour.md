# Repository AI Adapter Contour

This contour describes how assistants operate in Doctrine ORM.

## Owns

- assistant workflows under `.ai/assistant/flows`
- gates under `.ai/assistant/gates`
- policies under `.ai/assistant/policies`
- current-scope action authorization under
  `.ai/assistant/policies/action-authorization.json`; it separates inspect,
  modify, commit, publish, and live-external phases for the newest user request
- context profiles under `.ai/assistant/context-profiles.md`
- module profile under `.ai/assistant/module-profile.md`
- adapter manifest facts under `.ai/alatyr.yaml`
- task-specific maturity under `.ai/assistant/maturity-profile.md`
- target validation commands or manual checks referenced by the adapter
- installed-operation request, adapter-recheck, framework-update review, help, and preview flows
- operation catalog and compact operation index
- operation routing with current-scope action authorization
- documentation-sync rules and final evidence requirements
- durable engineering-evidence routing and capture mechanics under
  `.ai/project/engineering-evidence`
- optional Debug Mode routing and non-canonical records under
  `.ai/project/debug`
- required core profile, enabled optional modules, blocked module gaps, framework version, adapter schema version, template version, known gaps, and local deviations

## Does Not Own

- portable Alatyr Core framework rules
- Doctrine ORM product, business, architecture, data, or security facts
- target source code behavior
- target blueprint or equivalent source-of-truth content
- generated visual artifacts unless a future target adapter says they are source

## Relationship To Framework Core

`.ai/framework` defines portable Alatyr Core rules. This adapter applies those
rules to Doctrine ORM using target facts and validation.
