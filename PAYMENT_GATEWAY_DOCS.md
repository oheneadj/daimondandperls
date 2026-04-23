# Payment Gateway System

## Overview

The payment system is designed to support multiple payment gateways without the checkout UI
knowing which one is active. You switch gateways from the admin settings panel at runtime —
no code changes required.

Currently supported: **Moolre** (Ghanaian MoMo aggregator) · **Transflow** (hosted checkout — MoMo + Card)

---

## Architecture

```
config/payments.php
  └── credentials per gateway (read from .env)
  └── default gateway key

PaymentGatewayContract (interface)
  └── initiate(Booking, context[]) → InitiateResult
  └── verify(reference) → VerifyResult

PaymentManager (extends Laravel Manager)
  └── reads active gateway from settings table at runtime
  └── falls back to config('payments.default')
  └── createMoolreDriver() → MoolreGateway
  └── createTransflowDriver() → TransflowGateway

PaymentServiceProvider
  └── binds PaymentGatewayContract → active driver
  └── singleton PaymentManager

MoolreGateway (implements PaymentGatewayContract)
  └── initiate() → prompt or OTP challenge
  └── submitOtp() → Moolre-specific step
  └── verify() → confirm a transaction

TransflowGateway (implements PaymentGatewayContract)
  └── initiate() → returns redirect URL to Transflow's hosted checkout
  └── verify() → confirm a transaction via /check-transaction-status

InitiateResult / VerifyResult
  └── typed value objects — no raw array juggling in the UI

MoolreWebhookController
  └── receives POST from Moolre after each transaction
  └── looks up booking by booking.reference = externalref
  └── marks booking paid/failed

TransflowWebhookController
  └── receives POST from Transflow after each transaction
  └── looks up booking by booking.payment_reference = refNo
  └── marks booking paid/failed, creates Payment record, sends notification

TransflowReturnController
  └── handles the customer's browser redirect back from Transflow
  └── resolves the race condition between webhook and browser redirect
```

---

## Payment Flow (Moolre MoMo)

```
1. Customer fills in MoMo network + number → CheckoutPayment::processMobileMoney()
2. app(PaymentGatewayContract::class)->initiate($booking, ['channel' => ..., 'payer' => ...])
3a. InitiateResult::promptSent  → show "awaiting" screen, poll checkPaymentStatus()
3b. InitiateResult::otpRequired → show OTP input, call submitOtp()
      └── MoolreGateway::submitOtp() verifies OTP then re-initiates → back to 3a
4. Customer approves on phone
5. Moolre POSTs to /webhooks/moolre
6. MoolreWebhookController marks booking Paid, creates Payment record, sends notification
```

---

## Payment Flow (Transflow — MoMo + Card)

```
1. Customer clicks "Proceed to Checkout" → CheckoutPayment::initiateCheckout()
2. TransflowGateway::initiate($booking) → POST /request-payments
3. Transflow returns transactionReference + checkoutUrl (hosted page)
4. We save payment_reference = transactionReference, payment_status = Pending
5. Customer's browser redirects to Transflow's hosted checkout page
6. Customer picks MoMo or card and completes payment

Two things happen in parallel after payment:

  A. SERVER-TO-SERVER (webhook):
     Transflow POSTs to /webhooks/transflow
     → TransflowWebhookController looks up booking WHERE payment_reference = refNo
     → responseCode '01' (string) = success, anything else = failure
     → Marks booking Paid, creates Payment record, sends notification
     → Returns 200 (always — non-2xx causes Transflow to retry)

  B. CUSTOMER BROWSER (return URL):
     Transflow redirects customer to /booking/payment/return/{ref}?status=success
     → TransflowReturnController calls gateway->verify($booking->payment_reference)
     → If already Paid (webhook beat redirect): redirect to booking.confirmation
     → If verify confirms paid: mark Paid (idempotent), redirect to confirmation
     → If verify not yet confirmed: redirect to payment page with payment_awaiting=true
        → Livewire polls checkPaymentStatus() every few seconds until webhook fires

  On failure:
     Transflow redirects to /booking/payment/return/{ref}?status=failure
     → TransflowReturnController marks booking Failed (idempotent)
     → Redirect to booking.payment with error flash "Payment was declined"
```

### Why the race condition matters

Transflow's server-to-server webhook and the customer's browser redirect can arrive in either
order. If the customer's browser lands on `booking.confirmation` before the webhook has marked
the booking Paid, the page shows stale data. `TransflowReturnController` is the buffer:
it calls `verify()` directly to check the current state, and only shows confirmation once
payment is confirmed. Both paths are idempotent — safe if either fires first.

---

## Environment Variables

### Moolre

```env
PAYMENT_GATEWAY=moolre

MOOLRE_BASE_URL=https://api.moolre.com/open/transact
MOOLRE_API_USER=your-api-user
MOOLRE_API_PUBKEY=your-public-key
MOOLRE_MERCHANT_ID=your-merchant-id
MOOLRE_WEBHOOK_SECRET=your-webhook-secret
```

### Transflow

```env
PAYMENT_GATEWAY=transflow

# UAT base URL is used automatically in non-production environments.
# Live base URL is used automatically in production (APP_ENV=production).
# Override either with TRANSFLOW_BASE_URL if you need to force a specific endpoint.
# TRANSFLOW_BASE_URL=https://apisuat.itcsrvc.com/checkout  ← UAT (default for local/staging)
# TRANSFLOW_BASE_URL=https://apis.itcsrvc.com/checkout     ← Live

TRANSFLOW_API_KEY=your-api-key
TRANSFLOW_ID=your-transflow-id
TRANSFLOW_MERCHANT_PRODUCT_ID=your-merchant-product-id
```

Never put credentials in the database. They live in `.env` only.

---

## UAT vs Live Switching (Transflow)

The base URL is auto-selected from `APP_ENV`:

| Environment          | Base URL used                             |
|----------------------|-------------------------------------------|
| `local` / `staging`  | `https://apisuat.itcsrvc.com/checkout`    |
| `production`         | `https://apis.itcsrvc.com/checkout`       |
| `TRANSFLOW_BASE_URL` set | Override — uses whatever you set      |

UAT test credentials: any MoMo number / any network. Test card: PAN `5123450000000008`, CVV `100`, expiry `01/39`.

---

## Switching the Active Gateway

**At runtime (admin panel):**
Update `Setting::where('key', 'active_payment_gateway')` to the gateway key (e.g. `transflow`).

**Via config default:**
Set `PAYMENT_GATEWAY=transflow` in `.env`.

The checkout UI (`CheckoutPayment`) detects the active gateway at render time via
`$this->isRedirectGateway` — redirect gateways show a "Proceed to Checkout" button,
direct gateways show the MoMo network + number form.

---

## Transflow Callback Payload Structure

Transflow sends a flat JSON payload (not nested under `data`) to the webhook:

```json
{
  "refNo": "uuid-transaction-reference",
  "responseCode": "01",
  "responseMessage": "Transaction processed successfully",
  "amount": "250.00",
  "msisdn": "233241234567",
  "network": "MTN"
}
```

- `refNo` maps to `booking.payment_reference`
- `responseCode === '01'` (string) = confirmed paid
- `network === 'CARD'` = card payment; otherwise mobile money
- The full payload is stored in `booking.payment_details` for auditing (Transflow recommends this)

---

## InitiateResult Types

| type           | meaning                                    | UI action              |
|----------------|--------------------------------------------|------------------------|
| `prompt_sent`  | MoMo push sent to customer's phone         | Show awaiting screen   |
| `otp_required` | Customer must enter OTP first              | Show OTP input         |
| `redirect`     | Send customer to `$result->redirectUrl`    | JS redirect            |
| `error`        | Something failed — show `$result->message` | Show error message     |

---

## Adding a New Gateway

1. Add credentials to `config/payments.php`:
   ```php
   'paystack' => [
       'label' => 'Paystack',
       'secret_key' => env('PAYSTACK_SECRET_KEY'),
       'webhook_secret' => env('PAYSTACK_WEBHOOK_SECRET'),
   ],
   ```

2. Create the driver class:
   ```php
   // app/Services/Payment/PaystackGateway.php
   class PaystackGateway implements PaymentGatewayContract
   {
       public function initiate(Booking $booking, array $context = []): InitiateResult
       {
           // Call Paystack API, return InitiateResult::redirect($ref, $url)
       }

       public function verify(string $reference): VerifyResult { ... }
   }
   ```

3. Register the driver in `PaymentManager`:
   ```php
   protected function createPaystackDriver(): PaymentGatewayContract
   {
       return new PaystackGateway;
   }
   ```

4. If redirect-based: add `'paystack'` to the `in_array` check in `CheckoutPayment::getIsRedirectGatewayProperty()`

5. Add enum case to `App\Enums\PaymentGateway`

6. Create `app/Http/Controllers/Webhooks/PaystackWebhookController.php`

7. Add the route in `routes/web.php` and CSRF exclusion in `bootstrap/app.php`

8. Write tests in `tests/Feature/Payment/`

9. Set `active_payment_gateway = paystack` in the settings table
