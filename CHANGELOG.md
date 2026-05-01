# Changelog

All notable changes to DPC (Diamonds & Pearls Catering) are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

---

## [Unreleased] — 2026-05-01

### Fixed

#### Password Reset Email — Switched Default Mailer to Brevo API
Password reset emails were failing with 535 SMTP auth errors because `MAIL_MAILER` was still set to `smtp`. Changed to `brevo` so all system mail (including Fortify's built-in password reset and OTP flows) routes through the Brevo API transport.

**Affected files:**
- `.env` — changed `MAIL_MAILER=smtp` to `MAIL_MAILER=brevo`

**Why:** Fortify's password reset sends mail directly through the default mailer without going through `MailChannels::primary()`. Setting the default to `brevo` covers all mail paths without needing to override each Fortify notification.

---

### Changed

#### Offline Waiting Page — Live Polling with Redirect on Confirmation
Converted the offline waiting page from a static Blade view to a Livewire component. It now polls every 5 seconds and automatically redirects the customer to the booking confirmation page the moment an admin confirms payment — no manual refresh needed.

**Affected files:**
- `app/Livewire/Booking/OfflineWaiting.php` — new Livewire component; polls `checkConfirmation()` which refreshes the booking and redirects when `payment_status === Paid`
- `resources/views/livewire/booking/offline-waiting.blade.php` — new view with `wire:poll.5s`; animated ping rings, bouncing dots, and pulsing status badge while waiting
- `routes/web.php` — route now maps to `OfflineWaiting::class` instead of a static view closure
- `resources/views/booking/offline-waiting.blade.php` — deleted (replaced by Livewire view)

**Why:** Customers had no way to know when their payment was confirmed without refreshing. The polling closes that gap — the page transitions automatically when the admin clicks Verify.

---

### Added

#### Offline / Manual Payment Mode Toggle
Admins can now switch between Online (gateway) and Offline (manual MoMo transfer) payment modes from the admin settings. When offline is active, the checkout shows the business MoMo number and amount instead of launching the payment gateway. After clicking "I've Made the Payment", the customer lands on a waiting page explaining that an admin will confirm. Admin confirms via the existing "Verify Payment" button on the booking — only then does the booking confirmation SMS/email fire.

**Affected files:**
- `database/migrations/2026_05_01_085513_seed_payment_mode_settings.php` — seeds `payment_mode = 'online'`, `business_momo_network`, and `business_momo_number` settings
- `app/Livewire/Admin/Settings/AdminSettings.php` — added `$payment_mode`, `$business_momo_network`, `$business_momo_number` properties, `savePaymentMode()` and `saveBusinessMomoDetails()` methods
- `resources/views/livewire/admin/settings/admin-settings.blade.php` — added Payment Mode card (Online/Offline toggle) and Business MoMo Details card (shown only in offline mode) in the Payments tab
- `app/Livewire/Booking/CheckoutPayment.php` — `initiateCheckout()` branches to `paymentStep = 'offline'` when mode is offline; added `confirmOfflinePayment()` which redirects to the waiting page
- `resources/views/livewire/booking/checkout-payment.blade.php` — added offline instructions block showing business MoMo number, network, and amount
- `routes/web.php` — added `GET /booking/offline-waiting/{booking}` route (`bookings.offline-waiting`)
- `resources/views/booking/offline-waiting.blade.php` — new static waiting page: shows booking reference, 3-step next-steps list, and contact phone

**Why:** Provides a fallback when the gateway is unavailable and gives customers who prefer direct transfers a clear path to complete their booking. Admin retains full control — no booking is confirmed until manually verified.

---



### Added

#### Branded Email Templates — DPC Design System
All transactional emails now render with DPC branding: red gradient accent bar, company logo, business name, contact details in footer, and red CTA buttons.

**Affected files:**
- `resources/views/vendor/mail/html/layout.blade.php` — full custom layout with logo, brand name, accent bar, and footer with address/phone/email from settings
- `resources/views/vendor/mail/html/button.blade.php` — red (`#D52518`) rounded CTA button
- `resources/views/vendor/mail/html/themes/default.css` — complete DPC brand CSS: colors, typography, card styling, responsive breakpoints

**Why:** Default Laravel mail template had no branding. Every email now looks like it came from DPC.

#### Brevo API Transport — Replaced Failing SMTP
Switched from Brevo SMTP (persistently returning 535 auth errors) to the Brevo API transport via `symfony/brevo-mailer` + `symfony/http-client`.

**Affected files:**
- `composer.json` — added `symfony/brevo-mailer ^7.4` and `symfony/http-client ^7.4`
- `config/mail.php` — `brevo` mailer now uses `transport: brevo` (API) with `BREVO_API_KEY`
- `app/Providers/AppServiceProvider.php` — registers `BrevoApiTransport` via `Mail::extend('brevo', ...)`

**Why:** Brevo SMTP credentials repeatedly failed with 535 auth errors. The API key (already in use for the SDK) works reliably.

---

## [Unreleased] — 2026-04-30

### Added

#### Email Logging — Track All Sent Emails in System Logs
Every outbound email is now recorded in the `email_logs` table and shown on the System Logs page under a new "Email Logs" tab.

**Affected files:**
- `database/migrations/2026_04_30_194416_create_email_logs_table.php` — new table: `to`, `subject`, `mailer`, `message_id`, `status`, `error_message`
- `app/Models/EmailLog.php` — new model
- `app/Listeners/LogSentEmail.php` — new listener; fires on `MessageSent` event, writes a row to `email_logs`
- `app/Providers/AppServiceProvider.php` — registers the `MessageSent → LogSentEmail` listener via `Event::listen()`
- `app/Livewire/Admin/ErrorLogs/ErrorLogIndex.php` — added `emailLogs` to render, `email` tab query, `email_total` and `email_failed` to stats
- `resources/views/livewire/admin/error-logs/error-log-index.blade.php` — added Email Logs stat card, "Email Logs" tab, and full email logs table with status filter

**Why:** Transactional emails (booking confirmations, OTPs, invitations) were invisible — no way to tell if Brevo delivered them without checking the Brevo dashboard. The log provides an instant in-app view of every email sent and any failures.

---

#### Payment Methods Form — Real-Time Network Mismatch Validation
The phone number field now shows an inline error when the user enters a 10-digit number that doesn't match the selected network's valid prefixes.

**Affected files:**
- `resources/views/livewire/customer/payment-methods.blade.php` — added inline `@if` block below the account number input using `isMomoFormValid` computed property; shows network name and expected prefix hint in error style

**Why:** The save button was already disabled for invalid numbers, but users had no feedback explaining why. The mismatch message makes the problem immediately clear without waiting for form submission.

---

### Added

#### Multi-Provider Email — Brevo First
All transactional emails now route through a configurable provider (Brevo by default), mirroring the existing SMS provider abstraction. Adding a future provider requires only a new mailer entry in `config/mail.php` and a new option in admin settings — no notification code changes.

**Affected files:**
- `config/mail.php` — added `brevo` named mailer (SMTP relay via `BREVO_SMTP_*` env vars)
- `.env.example` / `.env` — added `BREVO_SMTP_HOST`, `BREVO_SMTP_PORT`, `BREVO_SMTP_ENCRYPTION`, `BREVO_SMTP_USERNAME`, `BREVO_SMTP_PASSWORD`
- `app/Notifications/Channels/MailChannels.php` — new class; `primary()` reads `email_primary_provider` from settings, falls back to `'brevo'`
- All 7 notification `toMail()` methods — added `->mailer(MailChannels::primary())`: `AdminInvitationNotification`, `BookingConfirmedNotification`, `BookingCompletedNotification`, `BookingReceivedNotification`, `ContactMessageReceivedNotification`, `QuoteUpdatedNotification`, `CustomerBookingReceivedNotification`
- `database/migrations/2026_04_30_145558_seed_email_provider_setting.php` — seeds `email_primary_provider = 'brevo'` into settings table
- `app/Livewire/Admin/Settings/AdminSettings.php` — added `$email_primary_provider` property, loads from settings in `mount()`, added `saveEmailProvider()` method
- `resources/views/livewire/admin/settings/admin-settings.blade.php` — added Email Provider card (same style as SMS provider selector) on the notifications settings tab

**Why:** Email was previously tied to a single global `MAIL_MAILER` env value with no way to switch providers from the admin UI or add fallback providers without code changes.

---

### Added

#### Image Optimisation — Background WebP Conversion on Upload
Uploaded images (package photos and business logo) are now converted to WebP at 75% quality and capped at 1920 px on the longest side via a queued background job. The original file is deleted after conversion. This keeps the admin save response instant while delivering smaller, web-optimised images to customers.

**Affected files:**
- `composer.json` — added `intervention/image ^4.0`
- `app/Jobs/OptimiseImage.php` — new job: reads the uploaded file, converts to WebP 75, scales down to 1920 px max, saves in place, deletes original, updates the model column in the DB
- `app/Livewire/Packages/PackageForm.php` — captures the saved model after create/update, dispatches `OptimiseImage` when a new image is uploaded
- `app/Livewire/Admin/Settings/AdminSettings.php` — dispatches `OptimiseImage` for the business logo after the setting row is saved

**Why:** Raw PNG/JPEG uploads were served directly to customers with no size reduction, adding unnecessary page weight to the public package listing and cart.

---

### Changed

#### Package Form — Removed `min_guests` / `serving_size`, Renamed Price Label
Stripped the Minimum Guests and Serving Size fields from the package create/edit form since they are not used anywhere in the booking flow. Renamed the section heading from "Price & Guest Requirements" to "Pricing" and the field label from "Price per Head" to "Price".

**Affected files:**
- `app/Livewire/Packages/PackageForm.php` — removed `$min_guests` and `$serving_size` properties, removed their population in `mount()`, removed their validation rules, removed them from the `$data` array passed to `Package::create/update`
- `resources/views/livewire/packages/package-form.blade.php` — removed Minimum Guests and Serving Size input blocks, renamed section heading to "Pricing", renamed field label to "Price", collapsed the 3-column grid to 2-column

**Why:** The fields were never wired into pricing calculations or order logic. Keeping unused inputs in admin forms creates confusion about what data actually matters.

---

### Added

#### Phone Verification After Registration
Users are now required to verify their phone number after registering. The verification is optional (skip link provided) but unverified users see persistent nudges on the dashboard and profile page.

**Affected files:**
- `database/migrations/2026_04_30_000122_add_phone_verified_at_to_users_table.php` — new migration adding nullable `phone_verified_at` timestamp to `users`
- `app/Models/User.php` — added `phone_verified_at` to `$fillable`, added `datetime` cast, added `hasVerifiedPhone()` helper
- `app/Livewire/Auth/PhoneVerification.php` — new Livewire MFC component; auto-sends OTP on mount, handles verify and resend
- `resources/views/livewire/auth/phone-verification.blade.php` — view for the phone verification page with OTP grid, resend timer, and "I'll do this later" skip link
- `app/Notifications/PhoneOtpNotification.php` — new notification class for post-registration phone verification OTPs; mirrors OtpNotification
- `app/Http/Responses/RegisterResponse.php` — custom Fortify register response; redirects unverified users to `/verify-phone` instead of dashboard
- `app/Providers/FortifyServiceProvider.php` — binds the custom RegisterResponse
- `routes/web.php` — added `GET /verify-phone` route mapped to PhoneVerification component
- `app/Traits/HandlesBookingCheckout.php` — sets `phone_verified_at` when guest OTP is verified at checkout

**Why:** Phone numbers are used for payment and SMS notifications. Unverified numbers waste SMS credits and cause delivery failures.

---

#### Ghana Phone Format Hints on Auth Pages
Added visible format hint below phone inputs on registration and OTP login pages.

**Affected files:**
- `resources/views/livewire/auth/register.blade.php` — added hint text: "Ghana numbers only · starts with 0 · 10 digits"
- `resources/views/livewire/auth/otp-login.blade.php` — added same hint; added `inputmode="numeric"`, `pattern`, `maxlength="10"` attributes

**Why:** Users were entering invalid formats (spaces, +233 prefix, wrong length) causing OTP send failures.

---

#### Payment Account Verification — Dedicated `startVerification()` Method
Added a separate method for initiating verification from the payment methods list, distinct from resending.

**Affected files:**
- `app/Livewire/Customer/PaymentMethods.php` — added `startVerification(int $id)` method; uses primary SMS provider; no rate limit (first-time action)

**Why:** The "Verify" button was calling `resendOtp()` which is rate-limited to 1/minute. If the user had just saved the method, the rate limiter silently blocked the button making it appear broken.

---

### Fixed

#### OTP SMS — Payment Account OTP Sent to Wrong Number
OTPs for payment method verification were being sent to the customer's profile phone number instead of the MoMo account number being verified.

**Affected files:**
- `app/Livewire/Customer/PaymentMethods.php` — replaced `$customer->notify(...)` calls with new private `sendPaymentOtp(string $accountNumber, ...)` that uses `Notification::route()` to target the account number directly

**Why:** The purpose of payment account OTP is to prove ownership of that specific MoMo number, not the customer's registered phone.

---

#### OTP Login — Resend Always Used Primary Provider
`resendOtp()` called `sendOtp()` without the `isResend` flag, so resends always went via the primary SMS provider instead of switching to the secondary.

**Affected files:**
- `app/Livewire/Auth/OtpLogin.php` — added `bool $isResend = false` parameter to `sendOtp()`; updated `resendOtp()` to pass `isResend: true`; replaced `rand()` with `random_int()` for secure OTP generation

**Why:** The secondary provider exists specifically as a fallback for resends when the primary may have failed to deliver.

---

#### User Model — Missing `otp_expires_at` Cast
`otp_expires_at` was in `$fillable` but not in `casts()`, meaning it was treated as a raw string instead of a Carbon datetime.

**Affected files:**
- `app/Models/User.php` — added `'otp_expires_at' => 'datetime'` to `casts()`

**Why:** Consistency and correctness; ensures datetime comparisons behave as expected across timezones.

---

#### Checkout — Cancel & Try Again Reloads Saved Payment Methods
When a logged-in user cancelled from the "Confirming Your Payment" screen, the form showed a blank MoMo entry form instead of their saved payment methods.

**Affected files:**
- `app/Livewire/Booking/CheckoutPayment.php` — extracted saved methods loading into private `loadSavedMethods()`; called it from both `mount()` (when not in awaiting state) and `cancelPayment()` (to reload fresh from DB on cancel)

**Why:** On the return trip from Transflow, `mount()` exits early when it detects `payment_awaiting` session flag, so `savedMethods` was never populated. `cancelPayment()` then had an empty collection and defaulted to the blank form.

---

#### Payment Methods — Verify Button Did Nothing
Clicking "Verify" on an unverified payment method opened no modal and sent no OTP.

**Affected files:**
- `resources/views/livewire/customer/payment-methods.blade.php` — changed `wire:click="resendOtp()"` to `wire:click="startVerification()"` on the Verify button
- `app/Livewire/Customer/PaymentMethods.php` — added `startVerification()` (see Added section above)

**Why:** `resendOtp()` is rate-limited (1/min) and if triggered right after save, the limiter blocked it silently.

---

### Added

#### `PaymentConfirmationService` — Centralised Payment Success Handler
Extracted the repeated "mark booking paid + create Payment record + notify customer + save MoMo number" logic into a single reusable service. Previously duplicated across `TransflowReturnController` and `TransflowWebhookController`.

**Affected files:**
- `app/Services/Payment/PaymentConfirmationService.php` — new service with one public method `confirmFromVerify(Booking, VerifyResult)`; includes `$alreadyPaid` guard to prevent duplicate confirmation notifications

**Why:** DRY — the same four-step success flow existed in two controllers and was about to be needed in a third place (`checkPaymentStatus()`). Centralising it also ensures the duplicate-notification guard is applied consistently everywhere.

---

#### `checkPaymentStatus()` — Transflow Direct Verify Fallback
The poll and "Check Status Now" button now call Transflow's `/check-transaction-status` endpoint directly when the local booking is still Pending. Previously they only read the local database.

**Affected files:**
- `app/Livewire/Booking/CheckoutPayment.php` — `checkPaymentStatus()` now accepts `TransflowGateway` and `PaymentConfirmationService` via method injection; calls `gateway->verify()` as a fallback when DB status is still Pending; extracted failure handling into private `handleFailedStatus()` which also calls `loadSavedMethods()` so saved methods are restored on failure

**Why:** If the Transflow webhook is delayed or never arrives, the customer was stuck on the awaiting screen indefinitely even though Transflow had already confirmed their payment. The direct verify call closes this gap.

---

### Changed

#### `TransflowReturnController` — Uses `PaymentConfirmationService`
Replaced the inline success-handling block (DB updates + Payment record + notification + MoMo save) with a call to `PaymentConfirmationService::confirmFromVerify()`. Also added a `CartService::clear()` call on the early-exit path when the webhook already marked it paid.

**Affected files:**
- `app/Http/Controllers/Booking/TransflowReturnController.php` — removed `InvoiceService`, `PaymentMethodService`, `BookingStatus`, `Payment`, `BookingConfirmedNotification` imports; injected `PaymentConfirmationService` instead; simplified `handleSuccessReturn()`

**Why:** DRY — same logic now lives in one place (`PaymentConfirmationService`) rather than being duplicated across controllers.

---

#### Checkout Awaiting Screen — "Check Status Now" Loading State
The button now shows a spinner and "Checking..." text while the Transflow verify call is in flight, and disables both buttons to prevent double-taps.

**Affected files:**
- `resources/views/livewire/booking/checkout-payment.blade.php` — added `wire:loading` / `wire:loading.remove` states to the Check Status Now button; added `wire:loading.attr="disabled"` to both buttons scoped to their respective `wire:target`

**Why:** The verify call hits an external API and can take a second or two. Without feedback, users click the button multiple times or assume it's broken.

---

### Tests Updated

- `tests/Feature/Auth/RegistrationTest.php` — updated assertion from `dashboard.index` redirect to `verification.phone` redirect to match new post-registration flow
- `tests/Feature/Customer/PaymentMethodsTest.php` — updated two OTP assertions from `Notification::assertSentTo($customer, ...)` to `Notification::assertSentOnDemand(...)` to match new on-demand routing to account number
