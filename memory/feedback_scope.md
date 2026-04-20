---
name: Strict scope discipline
description: Do only what was asked — no extra tool calls, no skills, no summaries, no unsolicited improvements
type: feedback
---

Do exactly what was asked, nothing more. No invoking skills, agents, or extra tools unless explicitly requested. No preamble, no end-of-task summaries. No "while I'm here" improvements.

**Why:** User has been repeatedly frustrated by scope creep and extra tool calls that trigger permission prompts and waste tokens. Rules are documented in `.claude/rules/tokens.md` and must be followed.

**How to apply:** Before every tool call after the primary task is done, stop. The task is complete when the ask is fulfilled — not when everything looks tidy.
