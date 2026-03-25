# Diamonds & Pearls Catering — Admin Dashboard
## Master Build Instructions for AI Agent

---

## WHO YOU ARE
You are building the **admin dashboard** for **Diamonds & Pearls Catering Services**, a Ghanaian catering business. The application is already scaffolded. Your job is to build the UI layer only — no new backend logic unless explicitly instructed.



## HOW TO BUILD THIS
The design system is split into 4 phases. You must follow them **in strict order**. Do not start a phase until the previous one is complete and verified.

| Phase | File | Contents | Do This First |
|-------|------|----------|---------------|
| 1 | `01-DESIGN-TOKENS.md` | Colours, typography, spacing, shadows, icons | Register all CSS variables and font imports |
| 2 | `02-COMPONENTS.md` | All reusable UI components | Build each component one at a time |
| 3 | `03-LAYOUTS.md` | Admin screen layouts | Assemble screens using Phase 1 + 2 only |
| 4 | `04-IMPLEMENTATION.md` | File structure, Livewire patterns, rules, build checklist | Read before writing any code |

## CRITICAL RULES — NEVER BREAK THESE
1. **Never hardcode hex colours** in templates. Always use `var(--dp-*)` CSS variables.
2. **Never use a font other than** Cormorant Garamond or DM Sans.
3. **Never place two primary buttons** on the same screen.
4. **Never execute a destructive action** without a confirmation modal.
5. **Never build a new page** for things that can be handled in a modal.
6. **Never use raw Tailwind colour classes** like `text-red-500`. Use design tokens only.
7. **Never show a blank table** — always render the empty state component.
8. **Shadows must be whispers** — maximum 10% opacity. No floating card effect.

## YOUR FIRST TASK
Read `04-IMPLEMENTATION.md` first (Section 4.7 Build Order Checklist), then start `01-DESIGN-TOKENS.md`. Follow the checklist one step at a time. Complete and verify each step before moving to the next.

---
*Design System v1.0 — Diamonds & Pearls Catering Services*
