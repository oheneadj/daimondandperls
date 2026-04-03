# Moolre Payment System Integration - Technical Walkthrough

This document serves as a comprehensive technical guide and summary of the Moolre Payment API integration within the Laravel application. It outlines the architectural decisions, database changes, user experience refinements, and testing strategies implemented.

## 1. Architectural Overview
The payment architecture transitioned from simulated dummy operations to a **hybrid-async** payment flow utilizing the Moolre REST API. To guarantee security and reliable transaction status tracking, we implemented a server-side robust Webhook receiver along with client-side Livewire UI polling.

### Key Components:
- **Service Layer (`MoolrePaymentService`)**: Handles HTTP interactions with Moolre, specifically `initiatePayment()` and `checkStatus()`. It centralizes the integration headers and keys.
- **Webhook Controller (`MoolreWebhookController`)**: An asynchronously called endpoint used by Moolre to securely broadcast payment successes or failures. Uses the hidden `WEBHOOK_SECRET` variable to verify incoming payload authenticity.

## 2. Database Modifications
Additional columns were migrated to persistently track the exact details and lifecycle of the API payments.

**`bookings` Table:**
- `payment_reference`: Moolre's unique reference token for the transaction.
- `payment_channel`: Tracks the exact network (e.g., `13` for MTN MoMo).
- `payer_number`: Captures the mobile number that actually completed the payment.
- `payment_details`: A robust JSON column designed primarily to store any raw Moolre webhook payloads or gateway responses for future auditing.

**`users` Table:**
- `saved_momo_number` & `saved_momo_network`: Used to save the user's preferred Mobile Money information for faster future checkouts.

## 3. Frontend & UX Improvements (`CheckoutPayment` Component)
The Livewire Checkout flow was substantially overhauled to prioritize responsiveness and provide active visual feedback during asynchronous API wait times.

- **Responsive Network Selection**: Clear, branded buttons exist for MTN MoMo, Telecel Cash, and AT Money utilizing the official network logos. 
- **Persisted "Awaiting Payment" State**: Upon initiating payment, the system avoids synchronously redirecting. Instead, it flips into an `isAwaitingPayment` state, generating a sleek, `wire:poll.3s` interface that politely asks the customer to check their handset for the prompt.
- **Retry Mechanism**: The UI robustly manages exceptions or timeout errors from Moolre, offering a clear "Cancel & Try Again" option without breaking the customer's cart state.
- **Validation**: Strict RegEx validations are applied directly in Livewire (`/^0\d{9}$/`) to enforce valid Ghanaian phone number structures before reaching the Moolre API.

## 4. Administrative & Customer Visibility
All administrative dashboards, customer displays, and PDF generation scripts have been tightly integrated directly with the new schema columns.

- **Admin Booking Show UI (`Show.blade.php`)**: Added a dynamic "Payment Details" card rendering the exact Moolre reference ID, the specific Mobile Network provider name, and the Payer Number.
- **Customer Booking UI**: Augmented the standard details layout to display the transaction reference and the parsed Mobile Network so users have transparent confidence in their ledger.
- **PDF Invoices (`invoice.blade.php`)**: Dynamically populated the generated backend DOMPDF outputs to place the Moolre Metadata beneath the primary total grid block.

## 5. Configuration & Environment
The Moolre endpoint URIs and secret values are dynamically managed by the framework's `.env` configuration mapping to `config/services.php`.

The final required configurations are:
```env
MOOLRE_API_USER=...
MOOLRE_API_PUBKEY=...
MOOLRE_MERCHANT_ID=...
MOOLRE_WEBHOOK_SECRET=...
MOOLRE_BASE_URL=https://api.moolre.com/open/transact
```

## 6. Testing & Verifications
- **Mocking Abstraction**: Rewrote tests inside `CheckoutPaymentTest` to successfully fake the `MoolrePaymentService`.
- Tests assure correct transition between `Unpaid` to `Pending` to `Paid` while protecting against unintended validation failures. 
- The newly introduced `PaymentGateway::Moolre` Enumeration was properly typed across all modules reducing static analyzer warning footprints.

## Conclusion
The Moolre functionality provides the platform with secure, dynamic, interactive, and fully-recorded API-driven processing flows. Operations perfectly synchronize between Moolre's webhook broadcasts and Laravel's persistent layer making manual reconciliation a relic of the past.
