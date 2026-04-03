# Implementation Plan - Moolre Payment Integration

## Goal Description
Integrate the Moolre API into the booking checkout process to process payments (Mobile Money & Cards) and handle webhook callbacks for automatic status updates.

## Endpoint Documentation Acquired
- **Initiate Payment:** `POST https://api.moolre.com/open/transact/payment`
    - Requires: channel (13=MTN, 6=Telecel, 7=AT), amount, payer (phone), externalref.
- **Payment Status:** `POST https://api.moolre.com/open/transact/status`
    - Used to manually poll if webhook fails.
- **Webhook Check:** Moolre will POST to our server. We verify using the `secret` and check `txstatus == 1`.

## Proposed Changes

### 1. Database Schema & Secrets
#### [MODIFY] `database/migrations/..._create_bookings_table.php` & [app/Models/Booking.php](file:///home/oheneadj/server/dpc/app/Models/Booking.php)
- Add `payment_reference` (string) to track the Moolre transaction ID.
- Add `payment_channel` (string) to track network (MTN, Telecel, AT).
- Add `payer_number` (string) to track the number used.
- Add `payment_details` (JSON) to store the raw webhook metadata from Moolre.

#### [MODIFY] `database/migrations/..._add_payment_methods_to_users_table.php` & [app/Models/User.php](file:///home/oheneadj/server/dpc/app/Models/User.php)
- Add `saved_momo_number` and `saved_momo_network` columns to optionally remember a customer's payment method.

#### [MODIFY] `.env` & [config/services.php](file:///home/oheneadj/server/dpc/config/services.php)
- Add Moolre keys (`API_USER`, `API_KEY`, `PUBKEY`, `VASKEY`, `BASE_URL`).

---

### 2. Payment UI/UX (Checkout & Validations)
#### [MODIFY] [app/Livewire/Booking/BookingWizard.php](file:///home/oheneadj/server/dpc/app/Livewire/Booking/BookingWizard.php)
- **Network Selection & Logos:** Add distinct buttons with official logos (MTN, Telecel, AT) for network selection.
- **Strict Validations:** Implement regex validation on the mobile number based on the selected network (e.g. 024/054 for MTN, 020/050 for Telecel).
- **Saved Methods:** Pre-fill the network and number if the logged-in user has saved them.
- **Persistent "Awaiting Payment" State:** If the page refreshes, [mount()](file:///home/oheneadj/server/dpc/app/Livewire/Booking/EventInquiryWizard.php#46-54) checks if the booking is currently `Pending Payment` with an active `payment_reference`. If so, it instantly resumes the animated loading/polling screen instead of dropping them back to the start.

---

### 3. Hybrid Payment Architecture (Webhooks + UI Polling)
#### [NEW] `app/Http/Controllers/MoolreWebhookController.php`
- The absolute source of truth. Listens for `POST` requests from Moolre.
- Verifies the `secret` token and processes `txstatus` (1=Success, 2=Failed).
- Updates the [Booking](file:///home/oheneadj/server/dpc/app/Models/Booking.php#18-87) database (including `payment_details` JSON).

#### [MODIFY] `routes/api.php`
- Expose the webhook route.

#### [MODIFY] Payment Success / Fallback UI
- **Fast UI Polling**: The "Waiting for Payment" screen uses `wire:poll` to check the local Database.
- **Manual Fallback**: Add a "Verify Payment" button (`MoolrePaymentService::checkStatus()`).
- **Retry Mechanism**: If Moolre explicitly returns a Failed status, drop back to the payment selection to securely retry with a fresh reference.

---

### 4. Admin & Customer Visibility
#### [MODIFY] [app/Livewire/Admin/Bookings/Show.php](file:///home/oheneadj/server/dpc/app/Livewire/Admin/Bookings/Show.php) & Blade
- Add a dedicated "Payment Details" card showing Transaction ID, Network, Timestamp, and raw metadata.
#### [MODIFY] [app/Services/InvoiceService.php](file:///home/oheneadj/server/dpc/app/Services/InvoiceService.php) (and Invoice views)
- Attach the `payment_channel` and `payment_reference` to the generated customer invoice.
#### [MODIFY] Customer Dashboard
- Show payment method and reference in their booking history.

## Verification Plan
1. Add tests in `tests/Feature/MoolrePaymentTest.php` to mock the HTTP facade and verify successful initiation and webhook processing.
2. Manually test booking a Simple Meal up to the payment stage to verify checkout redirect or prompt works.
