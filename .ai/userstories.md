# 👤 userstories.md — User Stories & Acceptance Criteria
**Project:** Catering App
**Version:** 1.0 · February 2026

---

## Actors

| Actor | Description |
|-------|------------|
| **Customer** | Any visitor browsing and booking catering packages |
| **Staff** | Admin user with limited access — view and manage bookings |
| **Admin** | Full access to bookings, packages, payments, and reports |
| **Super Admin** | Full access including settings, user management, and configuration |

---

## Epic 1 — Package Discovery

---

### US-001 — Browse Catering Packages
**As a** customer,
**I want to** see all available catering packages on the homepage,
**So that** I can understand what's on offer and choose the right option for my event.

**Acceptance Criteria:**
- [ ] All active packages are visible without requiring login or account creation
- [ ] Each package shows: name, description, price (in GHS), and serving size
- [ ] If a package has an image, it is displayed
- [ ] Packages are displayed in admin-defined sort order
- [ ] Deactivated packages do not appear
- [ ] The page is fully usable on mobile (320px+ width)
- [ ] Page loads within 2 seconds

---

### US-002 — View Package Details
**As a** customer,
**I want to** read the full details of a package before selecting it,
**So that** I can make an informed decision without committing to a booking.

**Acceptance Criteria:**
- [ ] Clicking a package reveals full description and details
- [ ] The price is clearly displayed
- [ ] A "Book This Package" call-to-action is visible
- [ ] Customer can return to the package list without losing their place

---

## Epic 2 — Customer Booking Flow

---

### US-003 — Select a Package and Start Booking
**As a** customer,
**I want to** select a package and begin the booking process,
**So that** I can reserve the catering service I want.

**Acceptance Criteria:**
- [ ] Clicking "Book" on a package takes the customer into the booking wizard
- [ ] The selected package name and price are shown throughout the wizard
- [ ] Customer can only have one package selected at a time
- [ ] The price is captured/snapshotted at the point of selection

---

### US-004 — Enter My Contact Information
**As a** customer,
**I want to** enter my name and phone number,
**So that** the caterer can contact me about my booking.

**Acceptance Criteria:**
- [ ] The form collects: Full Name (required), Phone Number (required), Email (optional)
- [ ] Full Name: required, max 100 characters
- [ ] Phone: required, validated as a valid Ghana phone number format
- [ ] Email: optional, validated as a proper email if provided
- [ ] Clear inline error messages appear if required fields are missing or invalid
- [ ] Customer cannot proceed to the next step until required fields are valid
- [ ] No page reload occurs — validation is inline via Livewire

---

### US-005 — Provide Event Details (Optional)
**As a** customer,
**I want to** optionally enter details about my event,
**So that** the caterer knows when and what type of event to prepare for.

**Acceptance Criteria:**
- [ ] All event fields are clearly marked as optional
- [ ] Customer can skip this step entirely
- [ ] Event date picker does not allow selecting dates in the past
- [ ] If start time is entered, end time becomes required
- [ ] End time must be later than start time — shown as inline error if not
- [ ] Event type dropdown shows: Wedding, Birthday, Corporate Event, Funeral, Party, Other
- [ ] Selecting "Other" reveals a text input for custom description (required if Other selected)
- [ ] If no event details are entered, the booking proceeds without them

---

### US-006 — Review My Order Before Paying
**As a** customer,
**I want to** see a summary of my booking before I pay,
**So that** I can confirm all details are correct.

**Acceptance Criteria:**
- [ ] Order summary shows: Package name, price, event date/time (if entered), event type, total amount
- [ ] Customer can go back and edit contact or event details from the summary screen
- [ ] Total amount is displayed clearly in GHS
- [ ] A payment method selector is shown (Mobile Money / Card / Bank Transfer)

---

### US-007 — Pay for My Booking
**As a** customer,
**I want to** pay for my booking using Mobile Money or card,
**So that** my reservation is confirmed immediately.

**Acceptance Criteria:**
- [ ] Payment is processed through the configured gateway (Paystack recommended)
- [ ] Customer is redirected to the gateway or shown an inline payment prompt
- [ ] On successful payment, customer is taken to a confirmation screen
- [ ] On failed payment, customer is shown a clear error message with the option to retry
- [ ] Payment can be retried without restarting the whole booking
- [ ] A loading indicator is shown while the payment is being processed
- [ ] Customer is not charged twice (idempotency enforced)

---

### US-008 — Receive Booking Confirmation
**As a** customer,
**I want to** receive a confirmation of my booking after payment,
**So that** I have proof of my reservation.

**Acceptance Criteria:**
- [ ] A unique booking reference number is generated (e.g. `CAT-2026-00042`)
- [ ] Confirmation screen shows: reference, package, event details, amount paid, date/time
- [ ] An SMS is sent to the customer's phone number (if SMS is enabled in settings)
- [ ] An email is sent to the customer's email (if provided and email notifications enabled)
- [ ] The confirmation screen is printable
- [ ] Customer can screenshot or share the reference

---

## Epic 3 — Admin Dashboard

---

### US-009 — Log In to the Admin Dashboard
**As an** admin,
**I want to** securely log in to the dashboard,
**So that** I can manage bookings and the catering operation.

**Acceptance Criteria:**
- [ ] Login requires email and password
- [ ] Wrong credentials show a generic error (not which field is wrong)
- [ ] After 5 failed attempts, login is rate-limited for 1 minute
- [ ] Session expires after 120 minutes of inactivity
- [ ] "Remember me" keeps the session for 30 days
- [ ] Logged-in admin is redirected to the dashboard home

---

### US-010 — See Operational Summary on Dashboard Home
**As an** admin,
**I want to** see today's key numbers at a glance when I log in,
**So that** I can quickly understand the state of operations.

**Acceptance Criteria:**
- [ ] Dashboard shows: bookings today, upcoming events (next 7 days), pending payments, completed this month
- [ ] Each metric is a clickable link to the relevant filtered view
- [ ] Data is current — no stale caching beyond 60 seconds
- [ ] Dashboard loads within 3 seconds

---

### US-011 — View and Search All Bookings
**As an** admin,
**I want to** see a list of all bookings and search/filter them,
**So that** I can quickly find any booking I need.

**Acceptance Criteria:**
- [ ] Bookings are listed with: reference, customer name, package, event date, status, payment status
- [ ] Table is paginated (20 per page default)
- [ ] Admin can search by: reference, customer name, phone number
- [ ] Admin can filter by: booking status, payment status, event type, date range
- [ ] Search and filter work together
- [ ] Results update without full page reload (Livewire)
- [ ] Table is sortable by: date created, event date

---

### US-012 — View Full Booking Details
**As an** admin,
**I want to** open a booking and see all its details,
**So that** I can understand the full context before taking action.

**Acceptance Criteria:**
- [ ] Detail page shows: customer info, package selected, event details, payment info, booking timeline, admin notes
- [ ] Timeline shows history: created, confirmed, prepared, completed/cancelled with timestamps
- [ ] Payment section shows: method, gateway, amount, gateway reference, verification info
- [ ] Admin notes are editable inline

---

### US-013 — Confirm a New Booking
**As an** admin,
**I want to** confirm a booking after verifying it,
**So that** the customer knows their booking is accepted.

**Acceptance Criteria:**
- [ ] Admin can click "Confirm" on any booking in `pending` status
- [ ] Confirmation records the admin's name and timestamp
- [ ] Booking status changes to `confirmed`
- [ ] Customer receives an SMS/email confirmation notification (if enabled)
- [ ] Action is logged in `activity_logs`

---

### US-014 — Update Booking Progress
**As an** admin,
**I want to** update the booking status as I prepare and deliver the service,
**So that** the system reflects real-world progress.

**Acceptance Criteria:**
- [ ] Admin can move booking from `confirmed` → `in_preparation`
- [ ] Admin can move booking from `in_preparation` → `completed`
- [ ] Each transition records the admin and timestamp
- [ ] Each status change is logged in `activity_logs`
- [ ] Backwards transitions (e.g. completed → pending) are NOT allowed

---

### US-015 — Cancel a Booking
**As an** admin,
**I want to** cancel a booking when needed,
**So that** both the customer and system are updated.

**Acceptance Criteria:**
- [ ] Admin can cancel a booking from any status except `completed`
- [ ] Cancellation requires a reason to be entered (mandatory)
- [ ] Customer receives a cancellation notification via SMS/email (if enabled)
- [ ] Booking status changes to `cancelled`
- [ ] `cancelled_at` timestamp and `cancelled_reason` are stored
- [ ] Action is logged in `activity_logs`

---

### US-016 — Manage Catering Packages
**As an** admin,
**I want to** create, edit, and manage packages,
**So that** customers always see up-to-date offerings.

**Acceptance Criteria:**
- [ ] Admin can create a new package with: name, description, price, serving size, image, active status
- [ ] Admin can edit any existing package field
- [ ] Admin can activate or deactivate a package — change takes immediate effect on customer view
- [ ] Admin can reorder packages via sort order
- [ ] Admin can soft-delete a package
- [ ] A package with existing bookings cannot be hard-deleted (system prevents this)
- [ ] Image upload accepts JPG/PNG, max 2MB, and stores it securely

---

### US-017 — Verify a Manual Payment
**As an** admin,
**I want to** manually verify a bank transfer or cash payment,
**So that** the booking is confirmed when the gateway wasn't used.

**Acceptance Criteria:**
- [ ] Admin can see all bookings with `payment_status = pending` and `method = bank_transfer` or `cash`
- [ ] Admin can click "Verify Payment" on any such booking
- [ ] Verification prompts a confirmation modal
- [ ] On confirm: `payment_status` → `paid`, `verified_by` = current admin, `verified_at` = now
- [ ] Booking status automatically moves to `confirmed` after payment verification
- [ ] Action is logged in `activity_logs`

---

### US-018 — View Revenue Reports
**As an** admin,
**I want to** see daily booking counts and revenue,
**So that** I can understand how the business is performing.

**Acceptance Criteria:**
- [ ] Admin can view daily summary: date, bookings count, total revenue
- [ ] Admin can filter by custom date range
- [ ] Revenue uses `bookings.total_amount` (snapshotted values)
- [ ] Upcoming events for the next 30 days are listed
- [ ] Admin can export the report to CSV
- [ ] Revenue figures are in GHS

---

### US-019 — Configure System Settings
**As a** super admin,
**I want to** update business info, payment gateway, and notification settings,
**So that** the system reflects the current business configuration.

**Acceptance Criteria:**
- [ ] Super admin can update: business name, phone, email
- [ ] Super admin can set the active payment gateway and enter API keys
- [ ] API keys are masked after saving (show only last 4 characters)
- [ ] Super admin can toggle SMS and email notifications on/off
- [ ] Super admin can change the booking reference prefix
- [ ] Changes take immediate effect — no redeploy required
- [ ] Only super_admin role can access settings — admin and staff are blocked

---

### US-020 — Manage Admin Accounts
**As a** super admin,
**I want to** create and manage other admin accounts,
**So that** I can control who has access to the system.

**Acceptance Criteria:**
- [ ] Super admin can create new accounts with: name, email, password, role
- [ ] Role options: super_admin, admin, staff
- [ ] Super admin can activate or deactivate any account (except their own)
- [ ] Super admin cannot delete their own account
- [ ] Deactivated accounts cannot log in
- [ ] All account changes are logged in `activity_logs`

---

## Epic 4 — Notifications

---

### US-021 — Receive SMS Booking Confirmation
**As a** customer,
**I want to** receive an SMS with my booking reference after paying,
**So that** I have my confirmation accessible on my phone.

**Acceptance Criteria:**
- [ ] SMS is sent to the phone number provided during booking
- [ ] SMS content includes: business name, booking reference, package name, event date (if provided)
- [ ] SMS is sent within 2 minutes of payment confirmation
- [ ] If SMS fails, it is retried up to 3 times via queue
- [ ] SMS send attempt (success or failure) is logged in `notifications` table

---

### US-022 — Receive Email Booking Confirmation
**As a** customer who provided an email,
**I want to** receive an email confirmation with my booking details,
**So that** I have a record I can easily refer back to.

**Acceptance Criteria:**
- [ ] Email is only sent if customer provided an email address
- [ ] Email includes: booking reference, package details, event details, total paid, business contact info
- [ ] Email is sent within 2 minutes of payment confirmation
- [ ] If email fails, it is retried up to 3 times via queue
- [ ] Email send attempt is logged in `notifications` table

---

## Story Point Reference

| Story | Complexity | Est. Points |
|-------|-----------|-------------|
| US-001 Package Browsing | Low | 2 |
| US-002 Package Details | Low | 1 |
| US-003 Start Booking | Low | 2 |
| US-004 Contact Info | Medium | 3 |
| US-005 Event Details | Medium | 3 |
| US-006 Order Review | Medium | 3 |
| US-007 Payment | High | 8 |
| US-008 Confirmation | Medium | 5 |
| US-009 Admin Login | Low | 2 |
| US-010 Dashboard Home | Medium | 3 |
| US-011 Booking List | Medium | 5 |
| US-012 Booking Detail | Medium | 3 |
| US-013 Confirm Booking | Low | 2 |
| US-014 Update Status | Low | 2 |
| US-015 Cancel Booking | Medium | 3 |
| US-016 Package CRUD | Medium | 5 |
| US-017 Verify Payment | Medium | 3 |
| US-018 Reports | Medium | 5 |
| US-019 Settings | Medium | 5 |
| US-020 User Management | Medium | 5 |
| US-021 SMS Notification | Medium | 3 |
| US-022 Email Notification | Medium | 3 |
| **TOTAL** | | **~76 pts** |

---

*userstories.md — Version 1.0 · Catering App*
