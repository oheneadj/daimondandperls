# 🗄️ database.md — Catering App Database Reference
**Stack:** Laravel 11 · MySQL 8 · Eloquent ORM
**Version:** 1.0 · February 2026

---

## Entity Relationship Overview

```
users (admins)
    └── manages ──────────────────► bookings
                                        ├── belongs to ──► customers
                                        ├── belongs to ──► packages
                                        └── has one ─────► payments
                                                               └── has many ──► payment_logs

bookings ──► notifications
settings (standalone key-value config)
activity_logs (standalone audit trail)
```

---

## Tables

---

### 1. `users`
Admin and staff accounts only. Customers are NOT stored here.

```sql
CREATE TABLE users (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)    NOT NULL,
    email           VARCHAR(150)    NOT NULL UNIQUE,
    password        VARCHAR(255)    NOT NULL,
    role            ENUM('super_admin', 'admin', 'staff') NOT NULL DEFAULT 'staff',
    is_active       BOOLEAN         NOT NULL DEFAULT TRUE,
    last_login_at   TIMESTAMP       NULL,
    remember_token  VARCHAR(100)    NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

| Column | Type | Notes |
|--------|------|-------|
| `role` | ENUM | `super_admin` > `admin` > `staff` — controls permission gates |
| `is_active` | BOOLEAN | Disable without deleting the account |
| `last_login_at` | TIMESTAMP | Auditing and session management |

---

### 2. `customers`
Contact info collected at booking time. Kept separate to support repeat customer profiles later.

```sql
CREATE TABLE customers (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)    NOT NULL,
    phone           VARCHAR(20)     NOT NULL,
    email           VARCHAR(150)    NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_phone (phone),
    INDEX idx_email (email)
);
```

| Column | Type | Notes |
|--------|------|-------|
| `phone` | VARCHAR(20) | Required. Indexed for fast dedup lookup |
| `email` | VARCHAR(150) | Optional. Used for email notifications if provided |

> **Future Extension:** Add `loyalty_points INT DEFAULT 0`, `total_bookings INT DEFAULT 0`, `last_booked_at TIMESTAMP`

---

### 3. `packages`
Catering offerings managed by admins and shown to customers.

```sql
CREATE TABLE packages (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(150)    NOT NULL,
    slug            VARCHAR(150)    NOT NULL UNIQUE,
    description     TEXT            NULL,
    price           DECIMAL(10, 2)  NOT NULL,
    serving_size    VARCHAR(100)    NULL,
    image_path      VARCHAR(255)    NULL,
    is_active       BOOLEAN         NOT NULL DEFAULT TRUE,
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP       NULL,

    INDEX idx_active (is_active),
    INDEX idx_sort   (sort_order)
);
```

| Column | Type | Notes |
|--------|------|-------|
| `slug` | VARCHAR | Clean URL e.g. `/packages/wedding-deluxe` |
| `price` | DECIMAL(10,2) | Never FLOAT for money |
| `serving_size` | VARCHAR | Range allowed: "Serves 50–80 guests" |
| `is_active` | BOOLEAN | Hide from public without deleting |
| `deleted_at` | TIMESTAMP | SoftDeletes — admin can restore |
| `sort_order` | SMALLINT | Admin-controlled display ordering |

---

### 4. `bookings`
Core table. Central record linking customer, package, event, and payment.

```sql
CREATE TABLE bookings (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference           VARCHAR(20)     NOT NULL UNIQUE,
    customer_id         BIGINT UNSIGNED NOT NULL,
    package_id          BIGINT UNSIGNED NOT NULL,

    -- Event details (all optional)
    event_date          DATE            NULL,
    event_start_time    TIME            NULL,
    event_end_time      TIME            NULL,
    event_type          ENUM('wedding','birthday','corporate','funeral','party','other') NULL,
    event_type_other    VARCHAR(100)    NULL,

    -- Financials (price snapshot at booking time)
    package_price       DECIMAL(10, 2)  NOT NULL,
    total_amount        DECIMAL(10, 2)  NOT NULL,

    -- Booking status
    status              ENUM('pending','confirmed','in_preparation','completed','cancelled')
                        NOT NULL DEFAULT 'pending',

    -- Payment status
    payment_status      ENUM('unpaid','pending','paid','failed','refunded')
                        NOT NULL DEFAULT 'unpaid',

    -- Admin fields
    admin_notes         TEXT            NULL,
    cancelled_reason    VARCHAR(255)    NULL,
    confirmed_by        BIGINT UNSIGNED NULL,
    confirmed_at        TIMESTAMP       NULL,
    completed_at        TIMESTAMP       NULL,
    cancelled_at        TIMESTAMP       NULL,

    created_at          TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at          TIMESTAMP       NULL,

    FOREIGN KEY (customer_id)  REFERENCES customers(id) ON DELETE RESTRICT,
    FOREIGN KEY (package_id)   REFERENCES packages(id)  ON DELETE RESTRICT,
    FOREIGN KEY (confirmed_by) REFERENCES users(id)     ON DELETE SET NULL,

    INDEX idx_reference  (reference),
    INDEX idx_status     (status),
    INDEX idx_payment    (payment_status),
    INDEX idx_event_date (event_date),
    INDEX idx_created    (created_at)
);
```

**Reference Format:** `CAT-2026-00001` — generated via `BookingReferenceService`

**Status Workflow:**
```
pending → confirmed → in_preparation → completed
                   ↘ cancelled (any stage)
```

**Payment Status Workflow:**
```
unpaid → pending → paid
               ↘ failed → (retry resets to pending)
paid → refunded
```

> ⚠️ `package_price` is a snapshot — do NOT recalculate from `packages.price` at report time

---

### 5. `payments`
One payment record per booking. Replaced (not updated) on retry.

```sql
CREATE TABLE payments (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id          BIGINT UNSIGNED NOT NULL UNIQUE,
    gateway             ENUM('paystack','flutterwave','hubtel','manual') NOT NULL,
    method              ENUM('mobile_money','card','bank_transfer','cash') NOT NULL,
    gateway_reference   VARCHAR(100)    NULL UNIQUE,
    gateway_response    JSON            NULL,
    amount              DECIMAL(10, 2)  NOT NULL,
    currency            CHAR(3)         NOT NULL DEFAULT 'GHS',
    status              ENUM('initiated','pending','successful','failed','refunded')
                        NOT NULL DEFAULT 'initiated',
    paid_at             TIMESTAMP       NULL,
    verified_by         BIGINT UNSIGNED NULL,
    verified_at         TIMESTAMP       NULL,
    created_at          TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (booking_id)  REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id)    ON DELETE SET NULL,

    INDEX idx_gateway_ref (gateway_reference),
    INDEX idx_status      (status),
    INDEX idx_paid_at     (paid_at)
);
```

| Column | Type | Notes |
|--------|------|-------|
| `gateway_response` | JSON | Raw webhook/callback payload — never discard |
| `currency` | CHAR(3) | ISO 4217: `GHS` default |
| `verified_by` | FK | Admin who approved a manual payment |

---

### 6. `payment_logs`
Immutable log of every gateway event, webhook, and retry. Never update rows.

```sql
CREATE TABLE payment_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payment_id      BIGINT UNSIGNED NOT NULL,
    event           VARCHAR(100)    NOT NULL,
    status          VARCHAR(50)     NULL,
    gateway_ref     VARCHAR(100)    NULL,
    payload         JSON            NULL,
    ip_address      VARCHAR(45)     NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE CASCADE,

    INDEX idx_payment_id (payment_id),
    INDEX idx_event      (event)
);
```

> No `updated_at` — these rows are never modified. Append only.

---

### 7. `notifications`
Log of every SMS and email sent. Used for resend capability and delivery tracking.

```sql
CREATE TABLE notifications (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id      BIGINT UNSIGNED NOT NULL,
    channel         ENUM('sms','email')                      NOT NULL,
    recipient       VARCHAR(150)    NOT NULL,
    template        VARCHAR(100)    NOT NULL,
    status          ENUM('pending','sent','failed')          NOT NULL DEFAULT 'pending',
    provider_ref    VARCHAR(100)    NULL,
    sent_at         TIMESTAMP       NULL,
    error_message   TEXT            NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,

    INDEX idx_booking (booking_id),
    INDEX idx_status  (status)
);
```

**Templates:** `booking_confirmed`, `booking_cancelled`, `payment_received`, `payment_failed`

---

### 8. `settings`
Key-value store for all runtime configuration. Admin-editable from dashboard.

```sql
CREATE TABLE settings (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key         VARCHAR(100)    NOT NULL UNIQUE,
    value       TEXT            NULL,
    type        ENUM('string','integer','boolean','json') NOT NULL DEFAULT 'string',
    label       VARCHAR(150)    NULL,
    group       VARCHAR(50)     NULL,
    updated_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_group (group)
);
```

**Seed Data:**

| Key | Default | Group |
|-----|---------|-------|
| `business_name` | `Delicious Catering Co.` | business |
| `business_phone` | `+233200000000` | business |
| `business_email` | `info@catering.com` | business |
| `payment_gateway` | `paystack` | payment |
| `paystack_public_key` | *(empty)* | payment |
| `paystack_secret_key` | *(empty)* | payment |
| `sms_enabled` | `false` | notifications |
| `email_enabled` | `true` | notifications |
| `booking_ref_prefix` | `CAT` | business |

---

### 9. `activity_logs`
Full admin audit trail. Every create, update, confirm, cancel action is recorded.

```sql
CREATE TABLE activity_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED NULL,
    action          VARCHAR(100)    NOT NULL,
    subject_type    VARCHAR(100)    NULL,
    subject_id      BIGINT UNSIGNED NULL,
    old_values      JSON            NULL,
    new_values      JSON            NULL,
    ip_address      VARCHAR(45)     NULL,
    user_agent      TEXT            NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,

    INDEX idx_user    (user_id),
    INDEX idx_action  (action),
    INDEX idx_subject (subject_type, subject_id),
    INDEX idx_created (created_at)
);
```

**Action Naming Convention:** `model.action` — e.g. `booking.confirmed`, `package.updated`, `payment.verified`

---

## Migration Order

```
1.  create_users_table
2.  create_customers_table
3.  create_packages_table
4.  create_bookings_table
5.  create_payments_table
6.  create_payment_logs_table
7.  create_notifications_table
8.  create_settings_table
9.  create_activity_logs_table
```

---

## Eloquent Model Relationships

```php
// User
User::hasMany(Booking::class, 'confirmed_by')
User::hasMany(ActivityLog::class)

// Customer
Customer::hasMany(Booking::class)

// Package
Package::hasMany(Booking::class)

// Booking
Booking::belongsTo(Customer::class)
Booking::belongsTo(Package::class)
Booking::belongsTo(User::class, 'confirmed_by')
Booking::hasOne(Payment::class)
Booking::hasMany(Notification::class)

// Payment
Payment::belongsTo(Booking::class)
Payment::hasMany(PaymentLog::class)
Payment::belongsTo(User::class, 'verified_by')
```

---

## Booking Reference Generator

```php
// app/Services/BookingReferenceService.php
public function generate(): string
{
    $prefix = setting('booking_ref_prefix', 'CAT');
    $year   = now()->year;
    $next   = Booking::whereYear('created_at', $year)->count() + 1;
    return sprintf('%s-%d-%05d', $prefix, $year, $next);
}
// Output: CAT-2026-00001
```

---

## Future Schema Extensions

```sql
-- Guest tracking (Section 9 of scope)
ALTER TABLE bookings ADD COLUMN guest_count SMALLINT NULL AFTER event_type_other;

-- Venue/location capture
ALTER TABLE bookings ADD COLUMN venue_address TEXT NULL;

-- Dietary requirements
CREATE TABLE booking_dietary_notes (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id BIGINT UNSIGNED NOT NULL,
    note       TEXT NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Loyalty points
ALTER TABLE customers ADD COLUMN loyalty_points INT NOT NULL DEFAULT 0;
ALTER TABLE customers ADD COLUMN total_bookings INT NOT NULL DEFAULT 0;
ALTER TABLE customers ADD COLUMN last_booked_at TIMESTAMP NULL;
```

---

*database.md — Version 1.0 · Catering App*
