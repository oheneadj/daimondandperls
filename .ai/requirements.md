# 📋 requirements.md — Catering App Requirements Specification
**Version:** 1.0 · February 2026
**Stack:** PHP · Laravel 11 · Livewire 3 · Alpine.js · Tailwind CSS · FlyonUI · MySQL 8

---

## 1. Project Summary

An online catering booking platform that allows customers to browse packages, book events, and pay — all within a target of under 2 minutes. Staff manage bookings, payments, and packages through a secure admin dashboard.

---

## 2. Functional Requirements

### 2.1 Customer Booking Flow

#### FR-01 — Package Browsing
- The system SHALL display all active catering packages to visitors without requiring login
- Each package SHALL display: name, description, price, serving size, and image
- Packages SHALL be sorted by `sort_order` defined by admin
- Deactivated or soft-deleted packages SHALL NOT appear to customers

#### FR-02 — Package Selection
- A customer SHALL be able to select exactly one package per booking
- Selecting a package SHALL initiate the booking wizard
- The selected package price SHALL be snapshotted at time of booking creation

#### FR-03 — Contact Information (Required)
- The system SHALL collect full name and phone number before proceeding
- Phone number SHALL be validated for format (Ghana: +233XXXXXXXXX or 0XXXXXXXXX)
- Both fields SHALL be mandatory — booking SHALL NOT proceed without them
- Email address SHOULD be collected but is optional

#### FR-04 — Event Details (Optional)
- Event details SHALL be optional — customers may skip this step
- If event date is entered, it SHALL NOT allow past dates
- If start time is entered, end time SHALL be required and must be later than start time
- Event type options SHALL be: Wedding, Birthday, Corporate Event, Funeral, Party, Other
- If "Other" is selected, a text input SHALL appear for custom event type description
- Fields SHALL only be validated if the customer interacts with them (conditional validation)

#### FR-05 — Payment Processing
- The system SHALL display a clear order summary before payment (package name, price, total)
- Supported payment methods: Mobile Money, Card, Bank Transfer (manual)
- The system SHALL redirect to the payment gateway and await callback/webhook
- On successful payment: booking SHALL be confirmed and reference generated
- On failed payment: the customer SHALL be shown an error and offered a retry
- Payment SHALL NOT be retried more than 3 times without admin review

#### FR-06 — Booking Confirmation
- On successful payment the system SHALL generate a unique booking reference (e.g. `CAT-2026-00042`)
- A confirmation screen SHALL display: reference, package, event details, total paid
- The system SHOULD send an SMS confirmation to the customer's phone number
- The system SHOULD send an email confirmation if email was provided
- The booking record SHALL be saved immediately upon payment confirmation

---

### 2.2 Admin Dashboard

#### FR-07 — Authentication
- Admin login SHALL require email and password
- Passwords SHALL be hashed using bcrypt (Laravel default)
- Failed login attempts SHALL be rate-limited (max 5 attempts per minute)
- Session SHALL timeout after 120 minutes of inactivity
- "Remember Me" SHALL be supported

#### FR-08 — Dashboard Home
- The home screen SHALL show at a glance:
  - Total bookings today
  - Upcoming confirmed events (next 7 days)
  - Pending payments count
  - Completed bookings count (current month)
- Data SHALL refresh without full page reload (Livewire polling or manual refresh)

#### FR-09 — Bookings Management
- Admin SHALL be able to view a paginated, searchable, filterable list of all bookings
- Filterable by: status, payment status, event type, date range
- Searchable by: reference, customer name, phone
- Admin SHALL be able to view full booking details
- Admin SHALL be able to perform status transitions:
  - Pending → Confirmed
  - Confirmed → In Preparation
  - In Preparation → Completed
  - Any status → Cancelled (with reason required)
- Admin SHALL be able to add internal notes to any booking
- Admin SHALL be able to contact the customer (click-to-call or open SMS)

#### FR-10 — Package Management
- Admin SHALL be able to create new packages with: name, description, price, serving size, image, active status
- Admin SHALL be able to edit all package fields
- Admin SHALL be able to activate or deactivate packages (immediate effect on customer view)
- Admin SHALL be able to soft-delete packages
- Admin SHALL be able to reorder packages via sort order field
- Packages with existing bookings SHALL NOT be hard-deleted

#### FR-11 — Payments Management
- Admin SHALL see a list of all payments with status, method, gateway, and amount
- Admin SHALL be able to filter by: status, method, gateway, date range
- Admin SHALL be able to manually verify a bank transfer or cash payment
- Manual payment verification SHALL require admin confirmation and log the verifying user
- Admin SHALL be able to view the raw gateway response for any payment

#### FR-12 — Reports
- Admin SHALL be able to view a daily booking summary (count and revenue by day)
- Admin SHALL be able to view total revenue for a selected date range
- Admin SHALL be able to view upcoming events list
- Reports SHALL be exportable to CSV
- All revenue figures SHALL use snapshotted `total_amount` from bookings

#### FR-13 — Settings
- Super admin SHALL be able to update: business name, phone, email
- Super admin SHALL be able to configure payment gateway credentials
- Super admin SHALL be able to toggle SMS and email notifications on/off
- Super admin SHALL be able to update the booking reference prefix
- Settings changes SHALL take effect immediately (no deploy required)

#### FR-14 — User Management (Super Admin Only)
- Super admin SHALL be able to create new admin/staff accounts
- Super admin SHALL be able to activate or deactivate accounts
- Super admin SHALL be able to assign roles: `super_admin`, `admin`, `staff`
- Super admin SHALL NOT be able to delete their own account

---

## 3. Non-Functional Requirements

### 3.1 Performance
- NFR-01: Package listing page SHALL load within 2 seconds on a standard mobile connection
- NFR-02: Booking wizard steps SHALL transition without full page reload (Livewire)
- NFR-03: Admin dashboard SHALL load within 3 seconds on all pages
- NFR-04: Database queries SHALL use eager loading — no N+1 query problems allowed
- NFR-05: Images SHALL be stored in compressed format; maximum upload size 2MB per image

### 3.2 Security
- NFR-06: All routes SHALL use HTTPS in production
- NFR-07: All forms SHALL include CSRF protection (Laravel default)
- NFR-08: Admin routes SHALL be protected by authentication middleware
- NFR-09: Role-based access SHALL be enforced on all admin actions via Gates/Policies
- NFR-10: Payment gateway secret keys SHALL be stored in `.env`, never in the database in plaintext
- NFR-11: Customer phone numbers SHALL be treated as PII — not exposed in URLs or logs
- NFR-12: SQL injection SHALL be prevented via Eloquent parameterised queries exclusively
- NFR-13: XSS SHALL be prevented via Blade's auto-escaping (`{{ }}` not `{!! !!}`)
- NFR-14: Payment webhooks SHALL validate gateway signature before processing

### 3.3 Reliability
- NFR-15: Payment webhook processing SHALL be idempotent — duplicate webhooks SHALL NOT create duplicate records
- NFR-16: The system SHALL use database transactions when creating bookings and payments together
- NFR-17: Failed jobs (notifications, webhook processing) SHALL be retried up to 3 times via Laravel Queue
- NFR-18: The system SHALL gracefully handle payment gateway timeouts without leaving orphaned bookings

### 3.4 Usability
- NFR-19: The booking flow SHALL be completable on mobile in under 2 minutes
- NFR-20: All form validation errors SHALL be displayed inline, not on a separate page
- NFR-21: The system SHALL be fully responsive — mobile-first design
- NFR-22: Confirmation screens SHALL be printable / shareable

### 3.5 Maintainability
- NFR-23: All environment-specific config SHALL live in `.env` — no hardcoded values
- NFR-24: Business logic SHALL live in Service classes, not Controllers or Models
- NFR-25: All database changes SHALL use Laravel migrations — no raw DB edits
- NFR-26: Code SHALL follow PSR-12 coding standards

---

## 4. Technical Requirements

### 4.1 Stack
| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.2+ / Laravel 11 |
| Frontend Reactivity | Livewire 3 |
| JS Interactivity | Alpine.js |
| CSS Framework | Tailwind CSS + FlyonUI |
| Database | MySQL 8.0+ |
| Queue | Laravel Queue (database driver → Redis in production) |
| File Storage | Laravel Storage (local → S3 in production) |
| Notifications | Laravel Notifications (SMS via Africa's Talking / Twilio, Email via Mailgun) |

### 4.2 Payment Gateway
| Gateway | Use Case |
|---------|---------|
| Paystack | Card + Mobile Money (primary recommendation) |
| Flutterwave | Alternative — similar feature set |
| Hubtel | Ghana-specific Mobile Money alternative |
| Manual | Cash / Bank Transfer — admin-verified |

> **Recommendation:** Start with **Paystack** — best developer experience, reliable Ghana MoMo support.

### 4.3 Environment Configuration (`.env` keys required)
```
APP_NAME, APP_URL, APP_ENV, APP_KEY
DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
QUEUE_CONNECTION
MAIL_MAILER, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM_ADDRESS
PAYSTACK_PUBLIC_KEY, PAYSTACK_SECRET_KEY, PAYSTACK_PAYMENT_URL
SMS_PROVIDER, SMS_API_KEY, SMS_SENDER_ID
FILESYSTEM_DISK
```

---

## 5. Validation Rules Reference

| Field | Rule |
|-------|------|
| Customer name | required, string, max:100 |
| Phone | required, regex Ghana format, max:20 |
| Email | nullable, email, max:150 |
| Event date | nullable, date, after_or_equal:today |
| Event start time | nullable, date_format:H:i |
| Event end time | nullable, date_format:H:i, after:event_start_time |
| Event type | nullable, in:wedding,birthday,corporate,funeral,party,other |
| Event type other | required_if:event_type,other, max:100 |
| Package ID | required, exists:packages,id, active package |
| Payment method | required, in:mobile_money,card,bank_transfer,cash |

---

## 6. Out of Scope (v1.0)

The following SHALL NOT be built in the initial release:

- Advanced analytics or business intelligence dashboards
- Inventory management or ingredient tracking
- Staff scheduling or shift management
- Multi-branch / multi-location support
- Loyalty or rewards programs
- Customer-facing account login or profile pages
- Discount codes or promotional pricing
- Third-party calendar integrations (Google Calendar, etc.)

---

*requirements.md — Version 1.0 · Catering App*
