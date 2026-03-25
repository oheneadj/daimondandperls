

### Key Livewire rules
- Every page component specifies layout via `->layout('layouts.admin')` in render()
- Search inputs: `wire:model.live.debounce.300ms="search"` (300ms debounce to reduce requests)
- Other filters: `wire:model.live="statusFilter"` (instant)
- Modal state: boolean property on parent Livewire component — never in Alpine data
- Flash messages: `session()->flash('success', '...')` — displayed by layout shell automatically
- Pagination: use `WithPagination` trait, call `$this->resetPage()` when filters change

---

## 4.4 — Alpine.js Usage Rules

### What Alpine handles (UI state only)
- Open/closed state of dropdowns
- Show/hide of panels or sections (e.g. password fields in Settings)
- CSS class toggling for visual states
- Transition effects

### What Livewire handles (data state)
- Loading records from the database
- Submitting forms
- Toggling database values (package active/inactive)
- Modal visibility (boolean property)
- All business logic

**Never use Alpine to make API calls or handle business logic. If it touches data, it belongs in Livewire.**

### Dropdown pattern (table row actions)
```html
<div x-data="{ open: false }" class="relative">
  <button @click="open = !open"
          class="...ghost icon button...">
    {{-- Heroicon: ellipsis-horizontal --}}
  </button>
  <div x-show="open"
       @click.outside="open = false"
       x-transition:enter="transition ease-out duration-100"
       x-transition:enter-start="opacity-0 scale-95"
       x-transition:enter-end="opacity-100 scale-100"
       x-transition:leave="transition ease-in duration-75"
       x-transition:leave-start="opacity-100 scale-100"
       x-transition:leave-end="opacity-0 scale-95"
       class="absolute right-0 mt-1 w-44 rounded-lg z-10"
       style="background: var(--dp-white); border: 1px solid var(--dp-border); box-shadow: 0 2px 6px rgba(28,26,24,0.08);">
    <button wire:click="openDetail({{ $booking->id }})"
            class="w-full text-left px-4 py-2.5 text-sm hover:bg-[#FAF8F5] first:rounded-t-lg">
      View Details
    </button>
    <button wire:click="confirmBooking({{ $booking->id }})"
            class="w-full text-left px-4 py-2.5 text-sm hover:bg-[#FAF8F5]">
      Confirm Booking
    </button>
    <button @click="open = false"
            wire:click="openCancelModal({{ $booking->id }})"
            class="w-full text-left px-4 py-2.5 text-sm last:rounded-b-lg"
            style="color: var(--dp-danger);">
      Cancel Booking
    </button>
  </div>
</div>
```

### Modal pattern
```html
<div x-show="$wire.showDetailModal"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center"
     style="display: none;">
  {{-- Backdrop --}}
  <div class="absolute inset-0 bg-black/40"
       @click="$wire.showDetailModal = false"></div>
  {{-- Modal panel --}}
  <div class="relative w-full max-w-2xl mx-4 rounded-xl overflow-hidden"
       style="background: var(--dp-white); box-shadow: 0 4px 16px rgba(28,26,24,0.10);">
    {{-- Content from Phase 2.7 modal structure --}}
  </div>
</div>
```

### Transition duration rule
All Alpine transitions: **100–200ms maximum**. This is a professional admin tool, not a consumer app.

---

## 4.5 — FlyonUI Integration Rules

- FlyonUI provides structural component patterns (modal, dropdown, tooltip, badge base classes, progress bars)
- Always override FlyonUI's default colours with the `--dp-*` token system
- When FlyonUI default colours conflict with design tokens, override with Tailwind arbitrary values: `bg-[#A31C4E]` or `var(--dp-rose)`
- Acceptable FlyonUI base classes to extend: `btn`, `badge`, `input`, `progress`
- Do NOT use FlyonUI's built-in dark mode — no dark mode in initial release
- FlyonUI tooltips: use for all icon-only buttons. Dark ink background, white text, 12px DM Sans
- FlyonUI dropdown structure: use for admin user menu and table row action menus. Apply `--dp-white` background, `--dp-border`, `rounded-lg`
- FlyonUI progress bars: use for Reports screen bookings-by-status section, overriding colours with semantic tokens

---

## 4.6 — Non-Negotiable Design Rules

These rules cannot be overridden under any circumstance. If another requirement conflicts with these rules, these take priority. Flag the conflict.

1. **No hardcoded hex values** in Blade templates. Always use `var(--dp-*)` or Tailwind arbitrary class with token comment.
2. **No fonts other than** Cormorant Garamond and DM Sans.
3. **One primary button per screen** — maximum. Never two rose primary buttons in the same view.
4. **No destructive action without a confirmation modal.** Cancel booking, delete package — always confirm first.
5. **No new pages for modal actions.** Booking detail, package edit, payment verify — always in a modal.
6. **No raw Tailwind colour utilities** — `text-red-500`, `bg-green-400` are forbidden. Use `var(--dp-danger)` or `text-[#B91C1C]`.
7. **No empty tables** — always render the empty state component when there is no data.
8. **Shadows max 10% opacity.** `rgba(28,26,24,0.10)` is the ceiling. Nothing darker.
9. **No raw IDs or timestamps** shown to admin users. Booking refs: `BK-XXXX`. Dates: human-readable.
10. **Sidebar always visible on desktop.** No collapsible sidebar in initial release.

---

## 4.7 — Build Order Checklist

**Follow this in exact order. Complete and verify each step before moving to the next.**

### Foundation — Phase 1
- [ ] Add Google Fonts import to admin layout `<head>`
- [ ] Add all CSS custom properties to `resources/css/app.css`
- [ ] Verify `--dp-pearl` is applied as page background
- [ ] Verify `--dp-rose` shows correct deep rose crimson (not bright pink)

### Layout Shell — Phase 3 Section 3.0
- [ ] Create `resources/views/layouts/admin.blade.php` — sidebar + header + main content
- [ ] Create `sidebar.blade.php` partial — logo area + all 6 navigation items
- [ ] Create `header.blade.php` partial — page title slot + search + bell + avatar
- [ ] Verify sidebar is exactly 256px wide with ink background
- [ ] Verify header is exactly 64px tall with white background + bottom border
- [ ] Verify main content area scrolls independently while sidebar and header stay fixed
- [ ] Apply active state to correct nav item based on `Route::currentRouteName()`

### Reusable Components — Phase 2 (one at a time)
- [ ] **Button** — build all 5 variants, 3 sizes, icon variant, disabled state, loading state
- [ ] **Badge** — build all booking status, payment status, and event type variants
- [ ] **Form Input** — text, select, textarea, date, search; all 4 states (default, focus, error, disabled)
- [ ] **Stat Card** — all 4 colour variants, icon slot, change indicator
- [ ] **Data Table** — wrapper, header row, data rows, hover state, ref chip, customer cell
- [ ] **Alert** — all 4 variants, auto-dismiss for success/info, persistent for warning/danger
- [ ] **Modal** — full detail modal + smaller confirmation modal, backdrop close, ESC close
- [ ] **Booking Status Flow** — 4 steps, completed/active/future states, cancelled variant
- [ ] **Toggle Switch** — on/off states, Alpine + Livewire wired, label colour change
- [ ] **Empty State** — icon + title + description + optional action button
- [ ] **Pagination** — showing count + page controls, active page in rose



### Polish & Validation
- [ ] All Livewire form validations display error messages using the error input state
- [ ] Flash messages (success/error) appear as alert components after actions
- [ ] All confirmation modals appear before destructive actions execute
- [ ] Empty states display on all list/table views when no data exists
- [ ] Sidebar nav badges show live counts for bookings and payments
- [ ] All icon-only buttons have `title` attributes for accessibility
- [ ] Layout renders correctly at 1280px, 1440px, and 1920px viewport widths
- [ ] No raw hex colours in any Blade template (audit with grep)
- [ ] No Tailwind colour utilities like `text-red-*` or `bg-green-*` (audit with grep)

---

## 4.8 — Quick Reference: Colour × Context

| Context | CSS Variable | Hex |
|---------|-------------|-----|
| Primary button, active nav item, focus ring | `--dp-rose` | `#A31C4E` |
| Confirm/complete/positive actions | `--dp-green` | `#3D6B47` |
| Destructive action buttons | `--dp-danger` | `#B91C1C` |
| Page background | `--dp-pearl` | `#FAF8F5` |
| Card / surface background | `--dp-white` | `#FFFFFF` |
| Table header row | `--dp-pearl-mid` | `#F2EEE9` |
| Sidebar background | `--dp-ink` | `#1C1A18` |
| All heading text | `--dp-ink` | `#1C1A18` |
| All body text | `--dp-body` | `#3D3B38` |
| Labels, hints, captions | `--dp-muted` | `#7A746C` |
| New Booking badge | `--dp-info` / `--dp-info-soft` | `#1D4ED8` / `#EFF6FF` |
| Confirmed badge | `--dp-success` / `--dp-success-soft` | `#2E7D52` / `#E8F5EE` |
| In Preparation badge | `--dp-warning` / `--dp-warning-soft` | `#B45309` / `#FEF3E2` |
| Completed badge | `--dp-green` / `--dp-green-soft` | `#3D6B47` / `#EBF4EE` |
| Cancelled badge | `--dp-danger` / `--dp-danger-soft` | `#B91C1C` / `#FEF2F2` |

---

*Design System Complete. Phase 1 → Phase 2 → Phase 3 → Phase 4.*
*Diamonds & Pearls Catering Services · Laravel 12 · Livewire · Alpine.js · FlyonUI · Tailwind CSS*
