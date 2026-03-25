# 📐 rules.md — Development Rules, Conventions & Standards
**Project:** Catering App
**Stack:** Laravel 12 · Livewire 4 · Alpine.js · Tailwind CSS · FlyonUI
**Version:** 1.0 · February 2026

> These rules are non[text](<../app details/database.md>) [text](<../app details/requirements.md>) [text](<../app details/rules.md>) [text](<../app details/todo.md>) [text](<../app details/userstories.md>)-negotiable. Every developer (and every AI assistant) working on this project must follow them. When in doubt, ask before breaking a rule.

---

## 1. Architecture Rules

### 1.1 Layered Architecture — Strict Separation of Concerns

```
Route → Controller → Service → Model → Database
                  ↘ FormRequest (validation)
                  ↘ Resource (API response shaping)
```

| Layer | Responsibility | What it MUST NOT do |
|-------|---------------|---------------------|
| **Controller** | Receive request, call service, return response | Contain business logic |
| **Service** | Orchestrate business logic, call models | Handle HTTP/request objects |
| **Model** | Define relationships, scopes, casts | Contain business logic |
| **FormRequest** | Validation rules only | Touch the database |
| **Livewire Component** | UI state + user interaction | Call Models directly |

**RULE:** Controllers SHALL NOT query the database directly. All DB access goes through a Service or Repository.

**RULE:** Livewire components SHALL NOT instantiate Models directly. They call Service classes.

**RULE:** No business logic in Blade views or Alpine.js components.

---

### 1.2 Service Classes

All business logic lives in `app/Services/`. One service per domain:

```
app/Services/
├── BookingService.php        -- create, confirm, cancel, complete bookings
├── PackageService.php        -- CRUD for packages
├── PaymentService.php        -- initiate, verify, handle webhooks
├── CustomerService.php       -- find or create customers
├── NotificationService.php   -- send SMS and email
├── BookingReferenceService.php -- generate unique references
├── ReportService.php         -- reporting and aggregation queries
└── SettingService.php        -- read/write settings
```

---

### 1.3 Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Booking/          -- customer-facing booking controllers
│   │   └── Admin/            -- admin dashboard controllers
│   ├── Requests/             -- FormRequest validation classes
│   └── Middleware/
├── Livewire/
│   ├── Booking/              -- customer booking wizard components
│   └── Admin/                -- admin dashboard components
├── Models/
├── Services/
├── Enums/                    -- PHP 8.3 Enums for statuses
│   ├── BookingStatus.php
│   ├── PaymentStatus.php
│   └── EventType.php
├── Events/                   -- BookingConfirmed, PaymentReceived, etc.
├── Listeners/
├── Jobs/                     -- queued jobs: SendNotification, etc.
└── Policies/                 -- BookingPolicy, PackagePolicy
```

---

## 2. Database Rules

### 2.1 Migrations
- **RULE:** Every database change MUST use a Laravel migration. No raw SQL edits to production.
- **RULE:** Migrations are forward-only in production. No `down()` rollback on production data.
- **RULE:** Migration file names MUST describe the change: `add_guest_count_to_bookings_table`

### 2.2 Money
- **RULE:** ALL monetary values MUST use `DECIMAL(10, 2)`. Never `FLOAT` or `DOUBLE`.
- **RULE:** Store money in the smallest unit when using gateway APIs (e.g. pesewas for Paystack GHS), convert for display.

### 2.3 Statuses
- **RULE:** Statuses MUST be stored as ENUM in the database AND as PHP Enums in `app/Enums/`.
- **RULE:** NEVER compare status strings as raw strings in code. Use Enum values:
  ```php
  // ✅ CORRECT
  $booking->status === BookingStatus::Confirmed
  // ❌ WRONG
  $booking->status === 'confirmed'
  ```

### 2.4 Soft Deletes
- **RULE:** `bookings` and `packages` MUST use SoftDeletes. Never hard-delete these records.
- **RULE:** When querying for reports, use `withTrashed()` explicitly — don't accidentally exclude soft-deleted records from revenue reports.

### 2.5 Price Snapshots
- **RULE:** When a booking is created, `package_price` and `total_amount` MUST be copied from the package at that moment.
- **RULE:** Reports and invoices MUST use `bookings.total_amount` — never re-derive from `packages.price`.

### 2.6 N+1 Prevention
- **RULE:** All Livewire components and Controllers MUST eager-load relationships:
  ```php
  // ✅ CORRECT
  Booking::with(['customer', 'package', 'payment'])->paginate(20)
  // ❌ WRONG
  Booking::paginate(20) // will N+1 when accessing ->customer
  ```
- Run Laravel Debugbar in development. Zero tolerance for N+1 in production.

---

## 3. Security Rules

### 3.1 Authentication & Authorisation
- **RULE:** ALL admin routes MUST be behind `auth` middleware.
- **RULE:** Role checks MUST use Laravel Gates or Policies — no raw `if ($user->role === 'admin')` in controllers.
- **RULE:** Super admin-only routes MUST use a dedicated `can:super_admin` gate.

### 3.2 Sensitive Data
- **RULE:** Payment gateway secret keys MUST live in `.env` only. Never in the database, never hardcoded.
- **RULE:** Never log customer phone numbers or payment details in application logs.
- **RULE:** `gateway_response` JSON in the database must have sensitive card data stripped before storage (Paystack does this by default — verify for others).

### 3.3 Input & Output
- **RULE:** ALWAYS use `{{ }}` in Blade, never `{!! !!}` unless the content is explicitly sanitised HTML.
- **RULE:** NEVER use raw SQL. Use Eloquent or Query Builder with parameter binding exclusively.
- **RULE:** All file uploads MUST be validated for MIME type and size before storage.

### 3.4 Payment Webhook Security
- **RULE:** EVERY incoming webhook MUST have its signature verified before processing.
  ```php
  // Paystack example
  $hash = hash_hmac('sha512', $payload, config('services.paystack.secret'));
  if ($hash !== $request->header('x-paystack-signature')) {
      abort(401);
  }
  ```
- **RULE:** Webhooks MUST be idempotent — use `gateway_reference` as deduplication key.
- **RULE:** Webhook processing MUST be handled in a queued Job, not synchronously in the controller.

---

## 4. Livewire Rules

- **RULE:** Each step of the booking wizard MUST be a separate Livewire component.
- **RULE:** Public Livewire properties that contain sensitive data (e.g. phone) MUST NOT be directly bound to `wire:model` on hidden fields.
- **RULE:** Use `#[Validate]` attribute-based validation in Livewire 3 — not manual `$this->validate()` blocks.
- **RULE:** Livewire components MUST emit events upward to parent; they MUST NOT reach into sibling components.
- **RULE:** Heavy data fetching in Livewire MUST use `computed properties` with caching — not lifecycle hooks that run on every render.

```php
// ✅ CORRECT — computed property, cached
#[Computed]
public function packages(): Collection
{
    return Package::active()->ordered()->get();
}

// ❌ WRONG — runs on every render
public function render()
{
    return view('livewire.booking.packages', [
        'packages' => Package::all() // called every render
    ]);
}
```

---

## 5. Alpine.js Rules

- **RULE:** Alpine.js is for UI interactions only: toggling menus, modals, show/hide, tab switches.
- **RULE:** Alpine.js MUST NOT make direct API calls or fetch data. Use Livewire for server communication.
- **RULE:** Alpine components MUST be defined inline (`x-data`) or in a registered Alpine store — no global JS variables.

---

## 6. Tailwind CSS / FlyonUI Rules

- **RULE:** Use FlyonUI components first before writing custom CSS. Only write custom CSS if the component doesn't exist.
- **RULE:** Do NOT use arbitrary Tailwind values (e.g. `w-[347px]`) without documenting why.
- **RULE:** Responsive breakpoints: design mobile-first. Start with base (mobile), then `md:`, `lg:`.
- **RULE:** Colours MUST use the design system tokens defined in `tailwind.config.js` — no raw hex values in classes.
- **RULE:** NEVER use `!important` in CSS.

---

## 7. Code Style Rules

### 7.1 PHP
- **RULE:** Follow PSR-12 coding standard.
- **RULE:** Use PHP 8.3+ features: typed properties, readonly classes, match expressions, nullsafe operator.
- **RULE:** All class methods MUST have return type declarations.
- **RULE:** NEVER use `array` as a type hint when a specific typed collection or DTO is possible.
- **RULE:** Use `strict_types=1` on all PHP files.
- **RULE:** Use DRY principles to avoid code duplication.
- **RULE:** Use YAGNI principles to ensure code is maintainable and scalable.

```php
<?php

declare(strict_types=1);

namespace App\Services;
```

### 7.2 Naming Conventions

| Type | Convention | Example |
|------|-----------|---------|
| Models | PascalCase, singular | `Booking`, `Customer` |
| Controllers | PascalCase + Controller | `BookingController` |
| Services | PascalCase + Service | `BookingService` |
| Livewire | PascalCase | `BookingWizard`, `PackageCard` |
| DB tables | snake_case, plural | `bookings`, `payment_logs` |
| DB columns | snake_case | `event_start_time` |
| Routes | kebab-case | `/admin/booking-details` |
| Blade views | kebab-case | `booking-confirmation.blade.php` |
| JS variables | camelCase | `selectedPackage` |
| Enums | PascalCase, members PascalCase | `BookingStatus::InPreparation` |

### 7.3 Comments
- **RULE:** Write comments explaining WHY, not WHAT. The code explains what; comments explain intent.
- **RULE:** Every Service method MUST have a one-line docblock.
- **RULE:** TODO comments MUST include the developer name and date: `// TODO(John, 2026-02-27): refactor after v2`

---

## 8. Git Rules

- **RULE:** Branch naming: `feature/short-description`, `fix/short-description`, `chore/short-description`
- **RULE:** Commit messages follow Conventional Commits: `feat:`, `fix:`, `chore:`, `refactor:`, `test:`
- **RULE:** `.env` is NEVER committed to git. Use `.env.example` with placeholder values.
- **RULE:** No `composer.lock` conflicts — always resolve before merging.
- **RULE:** Feature branches MUST be reviewed before merging to `main`.

```
feat: add payment webhook verification middleware
fix: prevent duplicate booking reference generation
chore: add indexes to bookings event_date column
refactor: extract payment status logic into enum
```

---

## 9. Testing Rules

- **RULE:** Every Service class MUST have unit tests.
- **RULE:** Every critical flow (booking creation, payment webhook, booking confirmation) MUST have a feature test.
- **RULE:** Use factories for test data — never hardcode IDs.
- **RULE:** Tests MUST use a separate `.env.testing` database.
- **RULE:** Payment gateway tests MUST use mock/fake gateway responses — never hit live APIs in tests.

---

## 10. Error Handling Rules

- **RULE:** Never expose raw PHP exceptions or stack traces to end users.
- **RULE:** Use Laravel's exception handler to return friendly error pages.
- **RULE:** Payment failures MUST be caught, logged to `payment_logs`, and shown to the user with a clear retry option.
- **RULE:** All exceptions in queued jobs MUST be caught and logged — silent failures are unacceptable.
- **RULE:** Use Laravel's `report()` helper for non-fatal exceptions that should be logged but not surfaced to the user.

---

*rules.md — Version 1.0 · Catering App*
