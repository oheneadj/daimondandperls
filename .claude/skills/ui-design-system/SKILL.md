---
name: ui-design-system
description: "Enforces the application's established UI design system tokens and component patterns. Activates whenever creating or modifying Blade views, UI components, modals, forms, cards, buttons, inputs, alerts, or any visual elements. Must be consulted before writing any new UI code."
license: MIT
metadata:
  author: dpc
---

# UI Design System

## When to Apply

Activate this skill **any time** you are creating or modifying:

- Blade views (pages, components, partials)
- Forms, inputs, buttons, labels, error messages
- Cards, modals, alerts, badges
- Any visual/layout elements

**Always check these tokens before writing ad-hoc styles.** Do not invent new patterns when an established one exists.

## Component Library

This project has reusable Blade components in `resources/views/components/ui/`. **Always prefer these over hand-rolled HTML:**

| Component | Usage |
|---|---|
| `<x-ui.button>` | All buttons — supports variants: `primary`, `secondary`, `black`, `ghost`, `outline`, `danger`, `success` |
| `<x-ui.input>` | Standard form inputs |
| `<x-ui.select>` | Select dropdowns |
| `<x-ui.textarea>` | Textareas |
| `<x-ui.modal>` | Modals/dialogs |
| `<x-ui.card>` | Content cards |
| `<x-ui.badge>` | Status badges |
| `<x-ui.alert>` | Alert/notification banners |
| `<x-ui.table>` | Data tables |
| `<x-ui.toggle>` | Toggle switches |
| `<x-ui.dropdown>` | Dropdown menus |

### Auth-Specific Components

For auth pages (`resources/views/livewire/auth/`), use:

| Component | Usage |
|---|---|
| `<x-auth.input>` | Auth form inputs (supports icon slot) |
| `<x-auth.button>` | Auth form buttons (variants: `dark`, `primary`, `ghost`) |
| `<x-auth.otp-grid>` | 6-digit OTP input grid |
| `<x-auth.resend-timer>` | Countdown timer for OTP resend |

---

## Design Tokens

### Inputs

```html
<!-- Standard input -->
<input class="w-full px-[14px] py-[10px] text-[15px] bg-base-100 border border-base-content/10 rounded-lg
    transition-all duration-120 outline-none placeholder:text-base-content/40
    focus:border-primary focus:ring-3 focus:ring-primary/20" />

<!-- Input with left icon -->
<div class="relative">
    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-base-content/40 pointer-events-none">
        <!-- icon here -->
    </span>
    <input class="w-full pl-12 pr-[14px] py-[10px] ..." />
</div>
```

**Key rules:**
- Border: `border border-base-content/10`
- Radius: `rounded-lg` (never `rounded-full` or `rounded-xl` for inputs)
- Focus: `focus:border-primary focus:ring-3 focus:ring-primary/20`
- Background: `bg-base-100` (never `bg-[#F4F4F6]` or `shadow-inner`)

### Labels

```html
<!-- Standard form label -->
<label class="text-dp-sm font-medium text-base-content block">Label Text</label>
```

**Never use:** `text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40`

### Buttons (via `<x-ui.button>`)

```html
<x-ui.button variant="primary" size="md">Submit</x-ui.button>
<x-ui.button variant="black" size="lg">Action</x-ui.button>
<x-ui.button variant="ghost" size="sm">Cancel</x-ui.button>
```

**Sizes:**
- `sm`: `px-[12px] py-[6px] text-[11px]`
- `md`: `px-[18px] py-[10px] text-[13px]`
- `lg`: `px-[24px] py-[13px] text-[15px]`

**Key rules:**
- Radius: `rounded-xl`
- Never add `hover:scale-[1.02]` or `hover:-translate-y-1` to buttons
- Never add arrow icons (→) inside buttons unless specifically requested

### Error Messages

```html
<p class="text-xs text-error flex items-center gap-1">
    <span>⚠</span> Error message here
</p>
```

**Never use:** `text-[11px] font-bold text-error uppercase tracking-wider`

### Cards

```html
<div class="bg-base-100 rounded-lg border border-base-content/10 shadow-dp-lg overflow-hidden">
    <div class="p-6">Content</div>
</div>
```

**Key rules:**
- Radius: `rounded-lg` (never `rounded-3xl` or `rounded-[32px]`)
- Shadow: `shadow-dp-lg` or `shadow-sm`
- Border: `border border-base-content/10`

### Modals

```html
<!-- Overlay -->
<div class="fixed inset-0 z-50 ... bg-black/40 backdrop-blur-[2px]">
    <!-- Panel -->
    <div class="bg-base-100 w-full max-w-[400px] rounded-lg shadow-dp-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-base-content/10 flex items-center justify-between">
            <h3 class="text-xl font-semibold text-base-content">Title</h3>
            <button class="p-1 rounded-md hover:bg-base-200 text-base-content/60 hover:text-primary">
                <!-- X icon -->
            </button>
        </div>
        <!-- Body -->
        <div class="p-6">Content</div>
        <!-- Footer (optional) -->
        <div class="px-6 py-4 bg-base-200 border-t border-base-content/10 flex justify-end gap-3">
            <!-- buttons -->
        </div>
    </div>
</div>
```

**Key rules:**
- Overlay: `bg-black/40 backdrop-blur-[2px]` (never `bg-black/60 backdrop-blur-sm`)
- Panel radius: `rounded-lg`
- Always include header bar with title + close button
- Use `<x-ui.modal>` component when possible

### Headings

```html
<!-- Page heading -->
<h1 class="text-xl font-semibold text-base-content">Page Title</h1>
<p class="text-[14px] text-base-content/50 font-medium leading-relaxed">Subtitle text.</p>
```

**Key rules:**
- Page titles: `text-xl font-semibold` (can use up to `text-3xl` for main dashboard pages)
- Section titles: `text-lg font-semibold`
- Subtitles: `text-[14px] text-base-content/50 font-medium`
- Never use decorative pill badges for page headers

### Alerts / Status Banners

```html
<!-- Success -->
<div class="p-4 bg-success/10 border border-success/15 rounded-lg flex items-center gap-3">
    <div class="size-8 bg-success/10 rounded-full flex items-center justify-center shrink-0">
        <!-- check icon -->
    </div>
    <span class="text-[13px] font-medium text-success">Message</span>
</div>

<!-- Error -->
<div class="p-4 bg-error/10 border border-error/15 rounded-lg flex items-center gap-3">
    <!-- same pattern with error colors -->
</div>
```

### Badges

```html
<span class="px-2 py-0.5 rounded-full bg-success/10 text-success text-[9px] font-black uppercase tracking-wider">Active</span>
<span class="px-2 py-0.5 rounded-full bg-warning/10 text-warning text-[9px] font-black uppercase tracking-wider">Pending</span>
<span class="px-2 py-0.5 rounded-full bg-black text-white text-[9px] font-black uppercase tracking-wider">Default</span>
```

### Dividers / Borders

- Section dividers: `border-t border-base-content/5`
- Card borders: `border border-base-content/10`
- Modal header/footer borders: `border-b border-base-content/10`

---

## Anti-Patterns (NEVER DO)

| ❌ Don't | ✅ Do Instead |
|---|---|
| `rounded-full` on inputs | `rounded-lg` |
| `rounded-3xl` on cards | `rounded-lg` |
| `bg-[#F4F4F6]/70 shadow-inner` on inputs | `bg-base-100 border border-base-content/10` |
| `bg-[#121212]` for buttons | `variant="black"` on `<x-ui.button>` |
| `h-15 rounded-[20px]` buttons | Use `<x-ui.button>` or `<x-auth.button>` |
| `text-4xl font-black` page headers | `text-xl font-semibold` |
| `hover:scale-[1.02]` on buttons | Remove — not in design system |
| Decorative pill badges on headers | Remove entirely |
| `bg-black/60 backdrop-blur-sm` on overlays | `bg-black/40 backdrop-blur-[2px]` |
| `font-black uppercase tracking-[0.2em]` on labels | `text-dp-sm font-medium text-base-content` |
| Hand-rolled buttons with arrow icons | `<x-ui.button>` or `<x-auth.button>` |
| `alert alert-success` (DaisyUI class) | Styled `div` with `bg-success/10 border border-success/15 rounded-lg` |

---

## Auth Pages Layout

Auth pages use `<x-layouts::auth>` which renders `layouts/auth/simple.blade.php`:

- Card: `bg-base-100 rounded-lg border border-base-content/10 shadow-dp-lg`
- Logo: `size-16 rounded-xl bg-base-100 shadow-dp-lg`
- Padding: `p-6 sm:p-10`
- Max width: `max-w-[460px]`
- Footer link pattern: `text-[13px] text-base-content/40 font-medium` with `text-primary font-semibold` link
