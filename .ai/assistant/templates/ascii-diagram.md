# ASCII Diagram Presentation

Use this target template for the mandatory portable view of a discussion
diagram. Replace placeholders from target evidence before returning a result.

## Layout

- Diagram kind: `<architecture-flow-sequence-hierarchy-state-graph-or-chart>`
- Reading direction: `<left-to-right-or-top-to-bottom>`
- Preferred width: `88`
- Hard maximum width: `100`
- Character set: `printable 7-bit ASCII plus line feeds`
- Connector meanings: `<connector-legend-or-single-obvious-connector>`

```text
<ascii-diagram>
```

## Readability Check

- Pure ASCII, no tabs or ANSI codes: `<pass-or-revise>`
- Longest line at most 100 columns: `<pass-or-revise>`
- Direction and connector labels are unambiguous: `<pass-or-revise>`
- No crossing connectors: `<pass-or-split-into-focused-views>`
- Values, units, and scale are explicit for charts: `<pass-not-applicable-or-revise>`
- Stable target names are preserved: `<pass-or-revise>`

## Evidence

- Fact owners: `<fact-ids-and-canonical-owners-or-missing>`
- Assumptions: `<assumptions-or-none>`
- Unresolved facts: `<unresolved-facts-or-none>`
- Omitted detail: `<omitted-detail-or-none>`
- Validation: `<target-review-or-not-run>`

This ASCII view is presentation evidence. It is not accepted project source of
truth unless the target source-of-truth registry explicitly assigns ownership.
