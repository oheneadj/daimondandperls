# Phase 2 of 4 — Component Library
## Diamonds & Pearls Catering Admin Dashboard

> **AI Agent Instructions:** Phase 1 must be complete before starting this phase.
> Build each component listed below **one at a time, in order**.
> Each component uses Phase 1 tokens only — never hardcode colours.
> Do NOT assemble page layouts yet. That is Phase 3.
> Test each component renders correctly before building the next.

---

## 2.1 — Button Component
**File:** `resources/views/components/button.blade.php`

### Variants
| Variant | Background | Text | Border | Use For |
|---------|------------|------|--------|---------|
| Primary | `--dp-rose` | white | `--dp-rose` | Main CTA — **one per screen max** |
| Secondary | `--dp-green` | white | `--dp-green` | Confirm Booking, Mark Complete |
| Outline | transparent | `--dp-rose` | `--dp-rose-border` | Secondary actions, Export, Filter |
| Ghost | transparent | `--dp-body` | `--dp-border` | Table row actions, Close |
| Danger | `--dp-danger` | white | `--dp-danger` | Cancel, Delete — **always requires confirmation modal** |

### Sizes
| Size | Padding | Font Size |
|------|---------|-----------|
| sm | `py-1.5 px-3` | 11px |
| md | `py-2.5 px-4.5` | 13px |
| lg | `py-3 px-6` | 15px |
| icon | `p-2` square | — |

### States
- **Hover:** Slightly lighter/darker shade of variant colour. Use `--dp-rose-light` for primary hover.
- **Disabled:** `opacity-50 cursor-not-allowed pointer-events-none` — never remove from DOM.
- **Loading:** Replace label with inline spinner (same dimensions, no layout shift).

### Rules
- All buttons: `rounded` (8px), DM Sans, font-weight 500
- Icon-only buttons: always add a `title` attribute for accessibility
- Icons inside buttons: Heroicons at `w-4 h-4`, positioned left of label

---

## 2.2 — Badge Component
**File:** `resources/views/components/badge.blade.php`

### Always: uppercase · 11px · weight 700 · pill shape (`rounded-full`) · padding `py-0.5 px-2.5`

### Booking Status Badges (include dot indicator)
| Status | Background | Text Colour | Dot Colour |
|--------|------------|-------------|------------|
| New Booking | `--dp-info-soft` | `--dp-info` | `--dp-info` |
| Confirmed | `--dp-success-soft` | `--dp-success` | `--dp-success` |
| In Preparation | `--dp-warning-soft` | `--dp-warning` | `--dp-warning` |
| Completed | `--dp-green-soft` | `--dp-green` | `--dp-green` |
| Cancelled | `--dp-danger-soft` | `--dp-danger` | `--dp-danger` |

### Payment Status Badges (no dot)
| Status | Background | Text Colour |
|--------|------------|-------------|
| Paid | `--dp-success-soft` | `--dp-success` |
| Pending | `--dp-warning-soft` | `--dp-warning` |
| Failed | `--dp-danger-soft` | `--dp-danger` |
| Manual Verification | `--dp-rose-soft` | `--dp-rose` |

### Event Type Badges (no dot)
| Type | Background | Text Colour |
|------|------------|-------------|
| Wedding | `--dp-rose-soft` | `--dp-rose` |
| Birthday | `--dp-success-soft` | `--dp-success` |
| Corporate | `--dp-info-soft` | `--dp-info` |
| Funeral | `--dp-warning-soft` | `--dp-warning` |
| Party | `--dp-green-soft` | `--dp-green` |
| Other | `--dp-pearl-mid` | `--dp-muted` |

### Rules
- Never create badge colours outside this defined set
- Badge text: maximum 3 words
- Dot size: 6px circle, same colour as badge text

---

## 2.3 — Form Input Component
**File:** `resources/views/components/form-input.blade.php`

### Base input styles
```
background: --dp-white
border: 1px solid --dp-border
border-radius: 8px (rounded)
padding: py-2.5 px-3.5
font: DM Sans 15px weight 400 --dp-body
```

### States
| State | Border | Shadow/Ring |
|-------|--------|-------------|
| Default | `--dp-border` | none |
| Focus | `--dp-rose` | `0 0 0 3px --dp-rose-soft` (3px rose ring) |
| Error | `--dp-danger` | `0 0 0 3px --dp-danger-soft` |
| Disabled | `--dp-border` | none — `background: --dp-pearl`, `cursor: not-allowed`, `opacity-60` |

### Input types to build
1. Text input
2. Select / Dropdown
3. Textarea (min-height 96px, resize vertical)
4. Date picker (native `<input type="date">` styled to match)
5. Search input (sm size, with magnifying-glass icon left)

### Labels and hints
- Label: DM Sans 13px weight 500 `--dp-ink` — always above the input
- Required indicator: red asterisk `*` in `--dp-danger` next to label
- Hint text: DM Sans 12px `--dp-muted` — below input
- Error message: DM Sans 12px `--dp-danger` — below input, prefixed with warning symbol
- Livewire `@error` directive should populate the error message slot

### Rules
- All input types share the same base class — only padding and font-size change for `input-sm`
- Border radius stays 8px regardless of size variant
- Focus ring is universal — applies to ALL interactive form elements in this app
- Group related fields: `gap-4` (16px) between fields, `gap-8` or divider between sections

---

## 2.4 — Stat Card Component
**File:** `resources/views/components/stat-card.blade.php`

### Structure
```
[3px top border accent]
[Card: white background, rounded-lg, shadow-xs, p-5 p-6]
  [Label: DM Sans 11px uppercase weight 700 --dp-muted]
  [Value: Cormorant Garamond 36px weight 600 --dp-ink]
  [Change: DM Sans 11px weight 500 — green if positive, red if negative]
  [Icon: 40×40px soft-tinted square, rounded, top-right corner]
```

### Four variants
| Variant | Top Border | Icon Background | Icon Colour |
|---------|------------|-----------------|-------------|
| Rose (Bookings Today) | `--dp-rose` | `--dp-rose-soft` | `--dp-rose` |
| Green (Upcoming Events) | `--dp-green` | `--dp-green-soft` | `--dp-green` |
| Warning (Pending Payments) | `--dp-warning` | `--dp-warning-soft` | `--dp-warning` |
| Info (Completed) | `--dp-info` | `--dp-info-soft` | `--dp-info` |

### Rules
- Stat value: Cormorant Garamond 36px weight 600 — this is intentional for elegance
- Change indicator: use Heroicon `arrow-up` (success green) or `arrow-down` (danger red)
- Always 4 cards in a 4-column grid on dashboard home — never stack vertically on desktop
- The 3px top border is the only place border-as-accent is used in this system

---

## 2.5 — Data Table Component
**File:** `resources/views/components/data-table.blade.php`

### Table wrapper
```
border: 1px solid --dp-border
border-radius: 12px (rounded-lg)
overflow: hidden
background: --dp-white
```

### Table header row
```
background: --dp-pearl-mid
border-bottom: 1px solid --dp-border
```

### Column header cells
```
DM Sans · 11px · weight 700 · uppercase · letter-spacing 0.08em · --dp-muted
padding: py-3 px-5
```

### Table row cells
```
DM Sans · 13px · weight 400 · --dp-body
padding: py-4 px-5
border-bottom: 1px solid --dp-border (last row: no border)
```

### Row states
- Default: white background
- Hover: `--dp-pearl` background
- Warning tint (pending payment): `--dp-warning-soft` background

### Special cell types
- **Reference number:** monospace font, `--dp-pearl-mid` background chip, `rounded-sm`, format as `BK-XXXX`
- **Customer cell:** Name on top (13px weight 500 `--dp-ink`) + phone below (11px `--dp-muted`)
- **Amount cell:** Cormorant Garamond 16px weight 600 `--dp-ink`
- **Action cell:** icon-only ghost buttons, max 2–3 icons, use `ellipsis-horizontal` for overflow

### Pagination (below table, outside wrapper)
- Left side: "Showing X of Y results" in 13px `--dp-muted`
- Right side: prev/next buttons + page numbers using ghost button style
- Active page: rose background, white text

### Rules
- Table always has a wrapping container with `overflow-hidden` to clip rounded corners
- Never show a table with headers and no rows — show the Empty State component instead

---

## 2.6 — Alert Component
**File:** `resources/views/components/alert.blade.php`

### Structure
```
[3px left border accent]
[Alert body: soft background, rounded-md, p-4]
  [Icon: Heroicon solid 20px, flex-shrink-0]
  [Content: Title (13px bold) + Body (13px regular)]
  [Dismiss button: optional, top-right]
```

### Four variants
| Variant | Left Border | Background | Text/Icon |
|---------|-------------|------------|-----------|
| Success | `--dp-success` | `--dp-success-soft` | `--dp-success` |
| Warning | `--dp-warning` | `--dp-warning-soft` | `--dp-warning` |
| Danger | `--dp-danger` | `--dp-danger-soft` | `--dp-danger` |
| Info | `--dp-info` | `--dp-info-soft` | `--dp-info` |

### Rules
- Auto-dismiss after 5 seconds for success and info alerts (Livewire or Alpine timer)
- Danger and warning alerts require explicit user dismissal — do NOT auto-dismiss
- Page-level alerts: position at top of `<main>` content area, below page heading, above all other content
- Use Livewire `session()->flash()` to trigger alerts after actions
- The 3px left border accent mirrors the stat card pattern — consistent visual language

---

## 2.7 — Modal Component
**File:** `resources/views/components/modal.blade.php`

### Structure
```
[Backdrop: fixed inset-0, bg-black/40, z-50]
  [Modal panel: white, rounded-xl, shadow-modal, max-w-2xl, relative]
    [Header: white bg, border-bottom --dp-border, p-5 px-6]
      [Title: Cormorant Garamond 20px weight 600 --dp-ink]
      [Subtitle: DM Sans 12px --dp-muted]
      [Close button: ghost icon button, top-right]
    [Body: white bg, p-6]
      [Content: 1 or 2 column grid depending on content]
    [Footer: --dp-pearl bg, border-top --dp-border, p-4 px-6]
      [Right-aligned: Ghost "Close" button + Primary action button]
```

### Alpine.js modal toggle pattern
```html
<!-- Parent component controls visibility via Livewire property -->
<div x-show="$wire.showModal"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 z-50 flex items-center justify-center">
  <div class="absolute inset-0 bg-black/40" @click="$wire.showModal = false"></div>
  <div class="relative w-full max-w-2xl mx-4 bg-white rounded-xl ...">
    <!-- modal content -->
  </div>
</div>
```

### Confirmation modal variant (destructive actions)
- Max width: `max-w-sm` (400px)
- Single column, centered
- Warning/danger icon at top
- Clear consequence statement in body
- Footer: Ghost "Cancel" + Danger "Confirm Cancel" buttons

### Rules
- Backdrop click closes the modal (`@click="$wire.showModal = false"`)
- Escape key should also close the modal
- Modal state lives in the Livewire component as a boolean property — never in Alpine data
- Transition duration: 200ms — professional tool, not consumer app
- Detail view modals: `max-w-2xl` (640px). Confirmation modals: `max-w-sm` (400px)

---

## 2.8 — Booking Status Flow Component
**File:** `resources/views/components/status-flow.blade.php`

### Structure
```
[Step dot — completed: --dp-success fill + checkmark]
[Connector line — completed: --dp-success]
[Step dot — active: --dp-rose fill + step number]
[Connector line — future: --dp-border]
[Step dot — future: white fill, --dp-border border, --dp-disabled text]
```

### Four steps
1. New Booking
2. Confirmed
3. In Preparation
4. Completed

### Step dot size: 32×32px circle, border 2px, step number in 11px DM Sans bold

### Rules
- Component appears on Booking Detail modal only
- Cancelled bookings: do NOT show this component — show a single danger badge with reason note instead
- Current step: `--dp-rose` background, white step number
- Completed steps: `--dp-success` background, Heroicon `check` (white, 14px)
- Future steps: white background, `--dp-border` border, `--dp-disabled` text

---

## 2.9 — Toggle Switch Component
**File:** `resources/views/components/toggle.blade.php`

### Alpine.js pattern
```html
<div x-data="{ enabled: @entangle('packageActive') }" class="flex items-center gap-3">
  <button @click="enabled = !enabled; $wire.toggleActive()"
          :class="enabled ? 'bg-[#3D6B47]' : 'bg-[#E8E3DC]'"
          class="relative w-10 h-5 rounded-full transition-colors duration-200">
    <span :class="enabled ? 'translate-x-5' : 'translate-x-1'"
          class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform duration-200">
    </span>
  </button>
  <span :class="enabled ? 'text-[#3D3B38]' : 'text-[#7A746C]'"
        class="text-sm font-medium transition-colors duration-200">
    <span x-text="enabled ? 'Active' : 'Inactive'"></span>
  </span>
</div>
```

### Rules
- On state: `--dp-green` background
- Off state: `--dp-border` background
- Label colour changes: `--dp-body` when on, `--dp-muted` when off
- Used ONLY on Packages screen (active/inactive) and Settings (notification preferences)
- Alpine handles visual toggle. Livewire handles the database update.

---

## 2.10 — Empty State Component
**File:** `resources/views/components/empty-state.blade.php`

### Structure
```
[Centered container, py-16]
  [Heroicon: 48px outline, --dp-border colour]
  [Title: Cormorant Garamond 20px weight 600 --dp-ink, mt-4]
  [Description: DM Sans 13px --dp-muted, mt-2, max-w-xs centered]
  [Action button: optional, Primary or Outline variant, mt-6]
```

### Rules
- Every list view and table MUST have an empty state — never show a blank page or empty table
- Icon colour is intentionally `--dp-border` (light) — draw attention to the action, not the empty state
- Customise title and description text per screen:
  - Bookings: "No bookings yet" / "No bookings match your filters"
  - Packages: "No packages yet" / "Add your first package"
  - Payments: "No payment records" 
  - Reports: "No data for selected period"

---

## ✅ Phase 2 Complete When:
- [ ] Button (5 variants, 3 sizes, disabled + loading states) renders correctly
- [ ] Badge (all booking, payment, event type variants) renders correctly
- [ ] Form Input (all types and states including error + Livewire `@error`) renders correctly
- [ ] Stat Card (all 4 colour variants) renders correctly
- [ ] Data Table (header, rows, hover state, ref chip, customer cell) renders correctly
- [ ] Alert (all 4 variants, auto-dismiss working on success/info) renders correctly
- [ ] Modal (detail modal + confirmation modal) opens/closes correctly via Livewire property
- [ ] Booking Status Flow (all step states) renders correctly
- [ ] Toggle Switch (on/off states, Alpine + Livewire wired) renders correctly
- [ ] Empty State renders correctly with customisable title/description/action

**→ Proceed to Phase 3 only after all components above are verified.**
