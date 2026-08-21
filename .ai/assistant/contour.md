# Repository AI Adapter Contour

This contour describes how assistants operate in Doctrine ORM.

## Owns

- assistant workflows under `.ai/assistant/flows`
- gates under `.ai/assistant/gates`
- context profiles under `.ai/assistant/context-profiles.md`
- module profile under `.ai/assistant/module-profile.md`
- adapter manifest facts under `.ai/alatyr.yaml`
- task-specific maturity under `.ai/assistant/maturity-profile.md`
- target validation commands or manual checks referenced by the adapter
- installed-operation request, adapter-recheck, framework-update review, help, and preview flows
- operation catalog and compact operation index
- documentation-sync rules and final evidence requirements
- required core profile, deferred optional modules, blocked module gaps, framework version, adapter schema version, template version, known gaps, and local deviations

## Does Not Own

- portable Alatyr Core framework rules
- Doctrine ORM product, business, architecture, data, or security facts
- target source code behavior
- target blueprint or equivalent source-of-truth content
- generated visual artifacts unless a future target adapter says they are source

## Relationship To Framework Core

`.ai/framework` defines portable Alatyr Core rules. This adapter applies those
rules to Doctrine ORM using target facts and validation.
