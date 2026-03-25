# ✅ todo.md — Development Task Breakdown
**Project:** Catering App
**Stack:** Laravel 11 · Livewire 3 · Alpine.js · Tailwind CSS · FlyonUI
**Version:** 1.0 · February 2026

**Legend:**
- `[ ]` Not started
- `[~]` In progress
- `[x]` Done
- 🔴 Blocker — nothing downstream can start without this
- 🟡 High priority
- 🟢 Normal priority

---

## Phase 0 — Project Setup 🔴

### Environment

- [ ] Configure `.env` and `.env.example`
- [ ] Set up MySQL database and connection
- [ ] Install and configure Livewire 3
- [ ] Install and configure Tailwind CSS
- [ ] Install and configure FlyonUI
- [ ] Set up Alpine.js
- [ ] Install Laravel Debugbar (dev only)
- [ ] Configure queue driver (database for dev, Redis for production)
- [ ] Configure file storage (local dev, S3 config ready for production)
- [ ] Set up mail driver (log for dev, Mailgun config for production)
- [ ] Configure timezone to Africa/Accra in `config/app.php`
- [ ] Set up GitHub repository and `.gitignore`
- [ ] Create `develop` and `main` branches

### Code Standards Setup
- [ ] Install Laravel Pint (code formatter)
- [ ] Configure Pint with PSR-12 rules
- [ ] Add `declare(strict_types=1)` to project stubs
- [ ] Set up IDE helper: `php artisan ide-helper:generate`

---

## Phase 1 — Database & Models 🔴

### Migrations (in order)
- [ ] `create_users_table` (extend Laravel default: add `role`, `is_active`, `last_login_at`)
- [ ] `create_customers_table`
- [ ] `create_packages_table`
- [ ] `create_bookings_table`
- [ ] `create_payments_table`
- [ ] `create_payment_logs_table`
- [ ] `create_notifications_table`
- [ ] `create_settings_table`
- [ ] `create_activity_logs_table`
- [ ] Run all migrations and verify: `php artisan migrate`

### Enums
- [ ] `app/Enums/BookingStatus.php` (Pending, Confirmed, InPreparation, Completed, Cancelled)
- [ ] `app/Enums/PaymentStatus.php` (Unpaid, Pending, Paid, Failed, Refunded)
- [ ] `app/Enums/EventType.php` (Wedding, Birthday, Corporate, Funeral, Party, Other)
- [ ] `app/Enums/UserRole.php` (SuperAdmin, Admin, Staff)
- [ ] `app/Enums/PaymentGateway.php` (Paystack, Flutterwave, Hubtel, Manual)
- [ ] `app/Enums/PaymentMethod.php` (MobileMoney, Card, BankTransfer, Cash)

### Models
- [ ] `User` model — add role enum cast, is_active, scopes
- [ ] `Customer` model — relationships, phone validation helper
- [ ] `Package` model — SoftDeletes, slug generation, active scope, ordered scope
- [ ] `Booking` model — SoftDeletes, relationships (customer, package, payment, user), status casts
- [ ] `Payment` model — relationships (booking, logs, verifiedBy)
- [ ] `PaymentLog` model — readonly (no update), relationship to payment
- [ ] `Notification` model — relationship to booking
- [ ] `Setting` model — key-value helpers, type casting
- [ ] `ActivityLog` model — relationship to user

### Factories & Seeders
- [ ] `UserFactory` — generate admin/staff users
- [ ] `CustomerFactory`
- [ ] `PackageFactory` — generate 5 sample packages
- [ ] `BookingFactory` — generate bookings in various statuses
- [ ] `PaymentFactory`
- [ ] `DatabaseSeeder` — orchestrate all seeders
- [ ] `SettingsSeeder` — populate all default settings
- [ ] Run seeders: `php artisan db:seed`

---

## Phase 2 — Core Services 🔴

- [ ] `BookingReferenceService` — generate `CAT-YYYY-NNNNN`
- [ ] `CustomerService` — `findOrCreateByPhone()` method
- [ ] `PackageService` — CRUD, activate/deactivate, reorder
- [ ] `BookingService` — create, confirm, updateStatus, cancel, complete
- [ ] `PaymentService` — initiate, handleWebhook, verifyManual, logEvent
- [ ] `NotificationService` — sendSms, sendEmail, logAttempt
- [ ] `SettingService` — get, set, getGroup, global `setting()` helper function
- [ ] `ReportService` — dailySummary, revenueSummary, upcomingEvents
- [ ] `ActivityLogService` — log action with old/new values

---

## Phase 3 — Authentication

- [ ] Configure login with email + password
- [ ] Set session timeout to 120 minutes
- [ ] Add rate limiting: 5 attempts per minute (already in Laravel 11 default)
- [ ] Add `last_login_at` update on successful login
- [ ] Create auth middleware for admin routes
- [ ] Create `super_admin` Gate
- [ ] Create `admin_or_above` Gate
- [ ] Create Policies: `BookingPolicy`, `PackagePolicy`, `PaymentPolicy`, `UserPolicy`
- [ ] Test: unauthenticated users cannot access `/admin/*`
- [ ] Test: staff cannot access settings or user management

---

## Phase 4 — Customer Booking Frontend

### Package Listing Page
- [ ] Create `BookingController` for customer-facing routes
- [ ] Define route: `GET /` → Package listing
- [ ] Create Livewire component: `Booking/PackageListing`
- [ ] Design package card component (Tailwind + FlyonUI)
- [ ] Show: name, description, price, serving size, image
- [ ] Apply active filter and sort order
- [ ] Mobile-responsive grid layout
- [ ] "Book Now" button per package

### Booking Wizard (Livewire Multi-Step)
- [ ] Create parent Livewire component: `Booking/BookingWizard`
- [ ] Step indicator component (Step 1 of 4, progress bar)

**Step 1 — Contact Info**
- [ ] Create Livewire component: `Booking/Steps/ContactInfo`
- [ ] Fields: name, phone, email (optional)
- [ ] Phone validation regex for Ghana format
- [ ] Inline error messages via `#[Validate]`
- [ ] "Next" button disabled until valid

**Step 2 — Event Details**
- [ ] Create Livewire component: `Booking/Steps/EventDetails`
- [ ] Fields: event_date, start_time, end_time, event_type, event_type_other
- [ ] All fields optional
- [ ] Date picker — disable past dates (Alpine.js + Flatpickr)
- [ ] Conditional validation: end_time required if start_time entered
- [ ] Conditional input: "Other" text field appears when event_type = other
- [ ] "Skip" and "Next" buttons

**Step 3 — Order Summary**
- [ ] Create Livewire component: `Booking/Steps/OrderSummary`
- [ ] Display: package name, price, event details, total in GHS
- [ ] Payment method selector (Mobile Money / Card / Bank Transfer)
- [ ] "Edit" links back to previous steps
- [ ] "Proceed to Payment" button

**Step 4 — Payment**
- [ ] Integrate Paystack JS (or Flutterwave)
- [ ] Pass amount, email/phone, booking reference to gateway
- [ ] Handle success callback → redirect to confirmation
- [ ] Handle failure callback → show error + retry button
- [ ] Loading spinner during processing

### Booking Confirmation Page
- [ ] Route: `GET /booking/confirmation/{reference}`
- [ ] Display: reference, package, event details, amount, timestamp
- [ ] Print stylesheet
- [ ] Trigger notification dispatch (queued job)

---

## Phase 5 — Admin Dashboard

### Layout & Navigation
- [ ] Create admin layout: `resources/views/layouts/admin.blade.php`
- [ ] Sidebar navigation with FlyonUI
- [ ] Navigation items: Dashboard, Bookings, Packages, Payments, Reports, Settings
- [ ] Role-based nav (staff don't see Settings)
- [ ] User info and logout in header
- [ ] Mobile-responsive sidebar (collapsible)

### Dashboard Home
- [ ] Create Livewire component: `Admin/DashboardHome`
- [ ] Stat cards: bookings today, upcoming events, pending payments, completed this month
- [ ] Each card links to relevant filtered view
- [ ] Data refreshes every 60 seconds (Livewire polling)

### Bookings Management
- [ ] Create Livewire component: `Admin/BookingList`
- [ ] Table: reference, customer name, package, event date, status, payment status, created
- [ ] Search input (reference, customer name, phone)
- [ ] Filter dropdowns: status, payment status, event type
- [ ] Date range filter
- [ ] Pagination (20 per page)
- [ ] Table sortable by date created and event date
- [ ] Row click → booking detail

- [ ] Create Livewire component: `Admin/BookingDetail`
- [ ] Customer info section
- [ ] Package + event details section
- [ ] Booking timeline (status history)
- [ ] Payment info section with raw gateway response toggle
- [ ] Admin notes (inline edit)
- [ ] Action buttons: Confirm, Mark In Preparation, Mark Completed, Cancel
- [ ] Cancel modal with reason input (required)
- [ ] Contact customer: click-to-call link for phone

### Package Management
- [ ] Create Livewire component: `Admin/PackageList`
- [ ] Table with all packages (including inactive)
- [ ] Toggle active/inactive inline
- [ ] Sort order input (inline edit)
- [ ] Edit and delete actions

- [ ] Create Livewire component: `Admin/PackageForm` (create/edit)
- [ ] Fields: name, slug (auto-generated), description, price, serving_size, image upload, is_active, sort_order
- [ ] Image preview before save
- [ ] Slug auto-generated from name (editable)
- [ ] Prevent delete if package has bookings

### Payments Management
- [ ] Create Livewire component: `Admin/PaymentList`
- [ ] Table: booking reference, customer, amount, method, gateway, status, date
- [ ] Filter by: status, method, date range
- [ ] View raw gateway response modal
- [ ] "Verify Manual Payment" button for bank_transfer / cash pending

- [ ] Create Livewire component: `Admin/ManualPaymentVerification`
- [ ] Confirmation modal: show booking details before verify
- [ ] On confirm: update payment + booking status + log action

### Reports
- [ ] Create Livewire component: `Admin/Reports`
- [ ] Date range selector
- [ ] Daily summary table: date, bookings, revenue
- [ ] Total revenue for selected range
- [ ] Upcoming events list (next 30 days)
- [ ] Export to CSV button

### Settings
- [ ] Create Livewire component: `Admin/Settings/BusinessSettings`
- [ ] Create Livewire component: `Admin/Settings/PaymentSettings`
- [ ] Create Livewire component: `Admin/Settings/NotificationSettings`
- [ ] API keys masked after save (show last 4 chars only)
- [ ] Test connection button for payment gateway
- [ ] Super admin only — middleware guard

### User Management
- [ ] Create Livewire component: `Admin/UserList`
- [ ] Create Livewire component: `Admin/UserForm` (create/edit)
- [ ] Activate/deactivate toggle
- [ ] Role assignment (super_admin only)
- [ ] Cannot deactivate own account

---

## Phase 6 — Notifications

- [ ] Configure SMS provider (GaintSMS recommended for Ghana)
- [ ] Configure email driver (Mailgun or SMTP) (Use Mailpit for local testing)
- [ ] Create notification templates:
  - [ ] `BookingConfirmedSms`
  - [ ] `BookingCancelledSms`
  - [ ] `BookingConfirmedEmail` (Blade template)
  - [ ] `BookingCancelledEmail` (Blade template)
  - [ ] `PaymentReceivedEmail`
- [ ] Create queued job: `SendBookingNotification`
- [ ] Implement retry logic (3 attempts) via job `$tries = 3`
- [ ] Log every send attempt to `notifications` table
- [ ] Test SMS delivery on staging with real Ghana number
- [ ] Test email delivery on staging

---

## Phase 7 — Payment Gateway Integration

- [ ] Register Paystack account and get API keys
- [ ] Install Paystack PHP SDK or write HTTP client wrapper
- [ ] Create `PaystackGateway` class implementing `PaymentGatewayInterface`
- [ ] Implement: `initiatePayment()`, `verifyPayment()`, `handleWebhook()`
- [ ] Register webhook URL in Paystack dashboard
- [ ] Create webhook route (exclude from CSRF): `POST /webhook/paystack`
- [ ] Create `WebhookController::handlePaystack()`
- [ ] Verify webhook signature on every incoming request
- [ ] Process payment status updates idempotently
- [ ] Dispatch queued job for webhook processing
- [ ] Write feature test: simulate successful webhook
- [ ] Write feature test: simulate failed webhook
- [ ] Write feature test: duplicate webhook (idempotency check)

---

## Phase 8 — Testing

- [ ] Unit test: `BookingReferenceService` — unique reference generation
- [ ] Unit test: `CustomerService::findOrCreateByPhone`
- [ ] Unit test: `BookingService::create` — validation, snapshot, status
- [ ] Unit test: `PaymentService::handleWebhook` — success, failure, duplicate
- [ ] Unit test: `SettingService` — get/set/type casting
- [ ] Feature test: Customer booking flow end-to-end
- [ ] Feature test: Admin login and session expiry
- [ ] Feature test: Booking status workflow transitions
- [ ] Feature test: Manual payment verification
- [ ] Feature test: Package activation/deactivation visible on frontend
- [ ] Feature test: CSV export from reports
- [ ] Browser test (Laravel Dusk): full booking flow on mobile viewport
- [ ] Performance: run Debugbar on all admin pages — zero N+1 queries

---

## Phase 9 — Pre-Launch

- [ ] Set up production server (recommended: Laravel Forge or Hetzner VPS)
- [ ] Configure HTTPS / SSL certificate
- [ ] Set production `.env` values (all secrets, APP_ENV=production, APP_DEBUG=false)
- [ ] Configure Redis for queue and cache
- [ ] Set up queue worker as systemd service
- [ ] Configure scheduled tasks: `php artisan schedule:run` via cron
- [ ] Set up S3 bucket for file storage
- [ ] Switch SMS and email to live credentials
- [ ] Switch payment gateway to live keys
- [ ] Final smoke test: full booking flow on production with real payment (small amount)
- [ ] Set up error monitoring (Sentry or Laravel Telescope)
- [ ] Set up uptime monitoring (UptimeRobot)
- [ ] Document admin handover notes

---

## Backlog (Post-Launch)

- [ ] Guest count field on bookings
- [ ] Venue/address capture
- [ ] Dietary requirements
- [ ] Repeat customer profile pages
- [ ] Loyalty points system
- [ ] Multi-admin role permissions (granular)
- [ ] Advanced analytics dashboard
- [ ] Google Calendar integration for upcoming events
- [ ] Multi-branch support

---

*todo.md — Version 1.0 · Catering App*
