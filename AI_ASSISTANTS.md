# AI Assistant Entry Point

This repository uses Alatyr Core. All assistants should treat `AGENTS.md` as
the canonical instruction file.

Before making changes:

1. Ensure `AGENTS.md` is loaded once; if it was not preloaded by the host,
   read it now. Then read `.ai/assistant/bootstrap-index.json`.
2. If the bootstrap index is stale, repair it from `.ai/alatyr.yaml`,
   `.ai/README.md`, and `.ai/assistant/context-router.json`.
3. Select the smallest task profile and project-area overlays, then read only
   their required framework, project, assistant, flow, gate, policy, and
   validation files.
4. For an exact operation ID or alias, read `.ai/assistant/operation-index.json`;
   for bare `Alatyr`, ambiguity, or repair, read
   `.ai/assistant/operation-catalog.json`, `.ai/assistant/help.md`, and
   `.ai/assistant/flows/operation-routing.flow.md`.
5. After installation/update or unclear state, read
   `.ai/assistant/templates/installation-note.md` and the post-install/update
   message templates.

Assistant-specific bridge files must stay short and point back to canonical
target files.
