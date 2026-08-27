# AI Assistant Entry Point

This repository uses Alatyr Core. All assistants should treat `AGENTS.md` as
the canonical instruction file.

Before making changes:

1. Ensure `AGENTS.md` is loaded once; if it was not preloaded by the host,
   read it now. Then read `.ai/assistant/bootstrap-index.json`.
2. If the bootstrap index is stale, repair it from `.ai/alatyr.yaml`,
   `.ai/README.md`, `.ai/assistant/context-router.json`, and the installed
   semantic-codebook index after repairing recursive context indexes.
3. Load the bootstrap semantic preload once; resolve lazy IDs through the
   semantic index, falling back to canonical owner prose on missing, stale, or
   conflicting terms.
4. Select the smallest task profile and follow only matching
   `context-index.json` entries; never load a directory solely because its
   parent index was selected.
5. For code or support changes, start from changed paths, the support-state
   difference, and the consistency reverse index when enabled. Load selected
   graph shards only; hashes and relationship candidates are not authority.
6. For an exact operation ID or alias, read `.ai/assistant/operation-index.json`;
   for bare `Alatyr`, ambiguity, or repair, read
   `.ai/assistant/operation-catalog.json`, `.ai/assistant/help.md`, and
   `.ai/assistant/flows/operation-routing.flow.md`.
7. After installation/update or unclear state, read
   `.ai/assistant/templates/installation-note.md` and the post-install/update
   message templates.

Assistant-specific bridge files must stay short and point back to canonical
target files.
