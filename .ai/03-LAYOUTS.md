# Phase 3 of 4 — Dashboard Layouts
## Diamonds & Pearls Catering Admin Dashboard

> **AI Agent Instructions:** Phases 1 and 2 must be complete before starting this phase.
> Build one screen at a time in the exact order listed below.
> Use ONLY Phase 1 tokens and Phase 2 components — never introduce new styles here.
> Follow the layout composition for each screen exactly.
> Do not deviate from the structure defined here.

---

## 3.0 — Master Layout Shell
**File:** `resources/views/layouts/admin.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin') — Diamonds & Pearls</title>
  {{-- Google Fonts (Phase 1) --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body class="bg-[#FAF8F5]" style="font-family: var(--font-body);">

  <div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR: fixed 256px wide, full height, dark background --}}
    <aside class="w-64 flex-shrink-0 overflow-y-auto" style="background-color: var(--dp-ink);">
      @include('layouts.partials.sidebar')
    </aside>

    {{-- MAIN AREA: fluid, contains header + scrollable content --}}
    <div class="flex-1 flex flex-col overflow-hidden">

      {{-- HEADER: 64px tall, white, bottom border --}}
      <header class="h-16 flex-shrink-0 flex items-center px-8"
              style="background: var(--dp-white); border-bottom: 1px solid var(--dp-border);">
        @include('layouts.partials.header')
      </header>

      {{-- PAGE CONTENT: scrollable, 32px padding all sides --}}
      <main class="flex-1 overflow-y-auto p-8">
        @if(session('success'))
          <x-alert type="success" :message="session('success')" class="mb-6" />
        @endif
        @if(session('error'))
          <x-alert type="danger" :message="session('error')" class="mb-6" />
        @endif
        @yield('content')
      </main>

    </div>
  </div>

  @livewireScripts
</body>
</html>
```

---

## 3.0a — Sidebar Partial
**File:** `resources/views/layouts/partials/sidebar.blade.php`

### Structure
```
[Logo area: p-5, border-bottom 1% white]
  "Diamonds & Pearls" — Cormorant Garamond 18px weight 600, white
  "Catering Services" — DM Sans 11px uppercase tracking-wide, 40% white

[Nav: p-4]
  [Section label: "MAIN" — 10px uppercase tracking-wide, 30% white]
  [Nav items...]
  [Section label: "REPORTS"]
  [Nav items...]
  [Section label: "SYSTEM"]
  [Nav items...]

[Admin user: mt-auto, p-4, border-top 8% white]
  [Avatar: 32px rose circle, initials]
  [Name + Logout link]
```

### Nav item states
- **Default:** DM Sans 13px, 60% white, `py-2.5 px-3 rounded`
- **Hover:** 7% white overlay background, 90% white text
- **Active:** `--dp-rose` background, white text — determined by current route name

### Nav items with badges
- Bookings badge: live count of unreviewed bookings
- Payments badge: live count of pending verifications
- Badge style: `--dp-rose-soft` background, `--dp-rose` text, `rounded-full`, 10px

### Nav item icon size: `w-5 h-5` Heroicons outline

```
Dashboard     → squares-2x2
Bookings      → clipboard-document-list  [badge: pending count]
Packages      → cake
Payments      → credit-card              [badge: pending verifications]
Reports       → chart-bar-square
Settings      → cog-6-tooth
```

---

## 3.0b — Header Partial
**File:** `resources/views/layouts/partials/header.blade.php`

### Left side
- Dynamic page title: Cormorant Garamond 20px weight 600 `--dp-ink`
- Set per-screen using `@section('page-title', 'Bookings Management')`

### Right side (flex row, gap-3)
1. Search input: `input-sm` width 200px, placeholder "Search bookings…", Heroicon `magnifying-glass` left
2. Notification bell: 36px ghost icon button, Heroicon `bell`, rose dot indicator when unread
3. Admin avatar + name block:
   - Avatar: 36px circle, `--dp-rose` background, initials in white
   - Name: DM Sans 13px weight 500 `--dp-ink`
   - Role: DM Sans 12px `--dp-muted` — "Administrator"
   - Clicking avatar opens Alpine dropdown with "Profile" + "Logout"

---

## SCREEN 1 — Dashboard Home
**Route:** `/admin` | **Livewire:** `App\Livewire\Admin\Dashboard`
**File:** `resources/views/livewire/admin/dashboard.blade.php`

### Layout composition (top to bottom)
```
[Page header row]
  Left: "Good morning, [name]" (Cormorant 24px) + date (DM Sans 13px muted)
  Right: "New Booking" primary button → opens new booking modal

[Conditional alert — if pending manual payments exist]
  Info alert: "X payments require manual verification" with link to /admin/payments

[4 stat cards — 4-column grid, gap-4]
  1. Rose: "Bookings Today" — live count from today
  2. Green: "Upcoming Events" — events in next 7 days
  3. Warning: "Pending Payments" — unverified/unpaid count
  4. Info: "Completed" — completed bookings this month

[2-column row — gap-6]
  Left (col-span-3 of 5): "Recent Bookings" card
    Card header: "Recent Bookings" title + "View All" outline button → /admin/bookings
    Mini table: 3 columns only (Ref # | Customer | Status)
    Show last 5 bookings. No pagination here.

  Right (col-span-2 of 5): "Upcoming Events" card
    Card header: "Upcoming Events" title
    List: next 5 upcoming events
    Each item: customer name + event type badge + package name + date
    No table — use a simple list with dividers between items
```

### No charts on this screen.
Dashboard home is for operational awareness only. Charts live in Reports.

---

## SCREEN 2 — Bookings Management
**Route:** `/admin/bookings` | **Livewire:** `App\Livewire\Admin\Bookings\BookingsList`
**File:** `resources/views/livewire/admin/bookings/list.blade.php`

### Layout composition
```
[Page heading row]
  Left: "Bookings Management" (set via page-title section)
  Right: "New Booking" primary button → opens new booking form modal

[Filter bar — white card, p-4, rounded-lg, mb-6]
  Row: Search input (wire:model.live.debounce.300ms)
       + Status dropdown filter (wire:model.live)
       + Event Type dropdown filter (wire:model.live)
       + Date filter (wire:model.live)

[Bookings data table]
  Columns (in this exact order):
  Ref # | Customer | Package | Event Date | Event Type | Payment | Status | Actions

  Row actions (action column):
  - View (eye icon) → wire:click="openDetail($booking->id)"
  - More (ellipsis) → Alpine dropdown with: Confirm / Contact / Cancel Booking

  Pending payment rows: --dp-warning-soft background tint

[Pagination — below table]
  Left: "Showing X of Y bookings"
  Right: page controls

[Booking Detail Modal — overlay]
  Triggered by openDetail() Livewire method
  Uses Phase 2 Modal component
  Content: 2-column grid
    Col 1: Customer info (name, phone) + Event info (type, date, time)
    Col 2: Package info (name, amount, payment method/status) + Admin actions
  Admin actions in modal:
    - "Mark as Completed" (secondary/green button)
    - "Contact Customer" (outline button, tel: link)
    - "Cancel Booking" (danger button → opens confirmation modal)
  Status flow component below the header in modal
```

---

## SCREEN 3 — Packages Management
**Route:** `/admin/packages` | **Livewire:** `App\Livewire\Admin\Packages\PackageManager`
**File:** `resources/views/livewire/admin/packages/index.blade.php`

### Layout composition
```
[Page heading row]
  Left: "Packages & Menu"
  Right: "Add Package" primary button → opens add modal

[Package card grid — 3 columns, gap-6]
  Each package card (white, rounded-lg, border, shadow-xs):
    [Image area — 80px tall, --dp-pearl-mid background, rounded-t-lg]
      Show package icon (Heroicon cake) if no image uploaded
    [Card body — p-5]
      Package name: Cormorant 20px weight 600 --dp-ink
      Description: DM Sans 13px --dp-muted, 2 lines max, ellipsis overflow
      Price: DM Sans 18px weight 600 --dp-ink (GH₵ X,XXX)
    [Card footer — border-top, p-4, flex justify-between]
      Toggle switch (active/inactive) on left
      "Edit" ghost button on right

  Inactive packages: opacity-60 on entire card

  [Last card — "Add New Package"]
    Dashed border --dp-border, rounded-lg
    Centered: Heroicon plus (48px, --dp-border colour) + "Add Package" label
    Clicking opens add modal

[Add/Edit Package Modal]
  Fields: Package Name | Description (textarea) | Price (GH₵) | Serving size note (optional)
  Standard modal structure from Phase 2
  Edit modal: same form, pre-filled with existing data
```

---

## SCREEN 4 — Payments Overview
**Route:** `/admin/payments` | **Livewire:** `App\Livewire\Admin\Payments\PaymentsOverview`
**File:** `resources/views/livewire/admin/payments/overview.blade.php`

### Layout composition
```
[3 summary stat cards — 3-column grid]
  1. Rose: "Total Revenue (This Month)" — GH₵ amount + month-over-month % change
  2. Warning: "Pending Verification" — count with "Requires manual review"
  3. Danger: "Failed Transactions" — count with "Contact customer required"

[Payments data table]
  Columns: Ref # | Customer | Package | Amount | Payment Method | Status | Action

  Amount cell: Cormorant Garamond 16px weight 600
  Pending rows: --dp-warning-soft background tint
  Row actions:
    - Pending rows: "Verify" green secondary button → opens verification confirmation modal
    - Failed rows: "Contact" outline button → tel: link to customer phone
    - Paid rows: "View" ghost icon button

[Verification Confirmation Modal]
  Small modal (max-w-sm)
  Body: "Confirm you have received and verified payment for BK-XXXX from [Customer Name]"
  Footer: Ghost "Cancel" + Green "Confirm Payment Received"
```

---

## SCREEN 5 — Reports
**Route:** `/admin/reports` | **Livewire:** `App\Livewire\Admin\Reports\ReportsView`
**File:** `resources/views/livewire/admin/reports/index.blade.php`

### Layout composition
```
[Date range filter bar — white card, p-4]
  Preset buttons (ghost button group): Today | This Week | This Month | Custom Range
  Custom range: shows two date inputs (From / To) when selected
  All Livewire reactive — updates all data below on change

[Summary stat cards — 3 columns]
  1. Total Bookings (for period)
  2. Total Revenue (for period) — GH₵
  3. Average Order Value (for period) — GH₵

[2-column row]
  Left: "Bookings by Status" card
    Simple stacked progress bars, one per status
    Each bar: status badge + count + bar (FlyonUI progress, semantic colours)
    Sorted by count descending

  Right: "Revenue by Event Type" card
    Simple list (NOT a chart)
    Each row: event type badge + booking count + revenue amount
    Sorted by revenue descending

[Upcoming Events table — full width]
  Title: "Upcoming Events"
  Right: "Export CSV" outline button
  Table: next 10 upcoming events
  Columns: Date | Customer | Package | Event Type | Status

[Export]
  Exports the current filtered view as CSV
  No PDF export in initial release
  No complex charts (line charts, pie charts) in initial release
```

---

## SCREEN 6 — Settings
**Route:** `/admin/settings` | **Livewire:** `App\Livewire\Admin\Settings\AdminSettings`
**File:** `resources/views/livewire/admin/settings/index.blade.php`

### Layout: single column, max-w-2xl, sections separated by dividers

```
[Section 1: Business Information]
  Fields: Business Name | Phone | Email | Address (textarea)
  [Save Business Info] secondary button — bottom right of section

[Divider]

[Section 2: Payment Configuration]
  Fields: Mobile Money Number | Bank Name | Account Number | Manual Payment Note (textarea)
  [Save Payment Config] secondary button

[Divider]

[Section 3: Notification Preferences]
  Toggle: "Send SMS on new booking"
  Toggle: "Send email on new booking"
  Textarea: Confirmation message template
  [Save Notifications] secondary button

[Divider]

[Section 4: Admin Account]
  Fields: Full Name | Email
  [Change Password] ghost button → reveals password fields via Alpine x-show
  Password fields (hidden by default): Current Password | New Password | Confirm Password
  [Save Account] secondary button
```

### Rules
- Each section has its own Save button — saving one section does not affect others
- Password change fields hidden by default, revealed with Alpine `x-show` + `x-transition`
- Livewire handles per-section form submission independently
- No sidebar tabs — single scrollable page for initial release

---

## ✅ Phase 3 Complete When:
- [ ] Master layout shell renders with sidebar + header + scrollable main content
- [ ] Sidebar shows active nav item based on current route
- [ ] Sidebar nav badges show live counts
- [ ] Screen 1 (Dashboard Home) — 4 stat cards + recent bookings + upcoming events
- [ ] Screen 2 (Bookings) — filter bar + table + pagination + detail modal working
- [ ] Screen 2b (Booking Detail Modal) — all fields + status flow + admin actions
- [ ] Screen 3 (Packages) — card grid + toggles + add/edit modals working
- [ ] Screen 4 (Payments) — summary cards + table + verify modal working
- [ ] Screen 5 (Reports) — date filters reactive + all data sections render
- [ ] Screen 6 (Settings) — 4 sections with independent save buttons + password reveal

**→ Proceed to Phase 4 for polish, validation, and final checks.**
