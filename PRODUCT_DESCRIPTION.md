# Detailed Product Description
**Diamonds & Pearls Catering — Digital Booking & Payment Platform**

---

**Document Type:** Payment API Integration — Product Description  
**Prepared By:** Diamonds & Pearls Catering  
**Date:** April 14, 2026  
**Version:** 1.0  

---

## 1. Business Overview

**Legal / Trading Name:** Diamonds & Pearls Catering  
**Year Established:** 2018  
**Industry:** Food & Beverage — Professional Event Catering  
**Regulatory Status:** Food and Drugs Authority (FDA) Approved, Ghana  
**Business Address:** P.O. Box 18123, Accra, Ghana  
**Primary Contact Email:** graceayesu@yahoo.com  
**Primary Contact Phone:** +233 244 203 181  
**Support / WhatsApp:** +233 596 070 822  

Diamonds & Pearls Catering is an FDA-certified catering company based in Accra, Ghana. Since 2018, the business has delivered professional catering services for over 500 events including weddings, corporate functions, funerals, naming ceremonies, birthday celebrations, and church events. The company is led by Executive Chef Grace Ayesu and serves clients across Greater Accra, Tema, and surrounding regions.

---

## 2. Platform Overview

The Diamonds & Pearls Catering Platform (internally referred to as **DPC**) is a proprietary web application purpose-built to digitise the end-to-end catering booking experience — from package discovery through to payment confirmation and booking management.

The platform serves two distinct user categories:

- **Customers** — individuals or organisations placing catering orders, either as registered account holders or as guests.
- **Administrators** — internal staff managing bookings, packages, customers, payments, and platform settings.

**Technology Stack**

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 12 (PHP 8.4) |
| Frontend / Reactivity | Livewire 4, Alpine.js |
| Styling | Tailwind CSS 4, FlyonUI 2 |
| Authentication | Laravel Fortify (with 2FA) |
| Database | MySQL |
| Queue / Cache / Session | Database driver |
| Hosting Environment | Linux (Ubuntu) |

---

## 3. Core Product Features

### 3.1 Package Catalogue

Customers browse a curated catalogue of catering packages organised by food category (Rice dishes, Banku & Soup, Grills, and others). Each package displays:

- Package name and description
- Price per head / fixed price
- Category and serving details
- Availability and ordering window status

Packages can be filtered by category in real time. A package detail modal provides full information before a customer commits to adding to cart.

### 3.2 Shopping Cart

A session-based cart allows customers to build an order across multiple packages. Cart state persists across browser sessions. Customers can adjust quantities and review their selection before proceeding to checkout.

### 3.3 Booking Wizard (Checkout)

The checkout is a multi-step wizard with the following stages:

**For Meal Orders (3 active steps):**
1. **Review** — confirm cart contents and quantities
2. **Contact Details** — customer name, phone, email, delivery address, and delivery date
3. **Payment** — Mobile Money payment authorisation
4. **Confirmation** — booking reference and receipt

**For Event Catering (4 active steps):**
1. **Review** — confirm package selection
2. **Contact Details** — customer information
3. **Event Details** — event type, date, venue, expected guest count, and special requirements
4. **Payment** — Mobile Money payment authorisation
5. **Confirmation** — booking reference and receipt

Customers may also submit an **Event Inquiry** via a dedicated wizard for bespoke events not covered by standard packages. These inquiries are reviewed by the admin team before a quote and booking are confirmed.

### 3.4 Booking Tracking

Customers who do not have a registered account — or who need to resume a booking — can track their order at `/booking/track` by entering their **booking reference number** (format: `CAT-YYYY-NNNNN`) and the **phone number** used at checkout. This allows them to check status and return to complete payment if required.

### 3.5 Customer Dashboard

Registered customers have access to a personal dashboard at `/dashboard` providing:

- Full booking history (meal orders and event bookings)
- Individual booking detail views with status timelines
- Payment history and invoice downloads
- Saved Mobile Money payment methods for faster future checkouts
- Profile and notification preference management
- Two-factor authentication settings

### 3.6 Admin Management Portal

The admin portal at `/admin` provides internal staff with:

- Full booking management (status updates, notes, assignment)
- Event inquiry handling
- Package and category management (create, edit, pricing)
- Customer records and search
- Payment oversight and reconciliation
- Analytics and reporting dashboard
- System settings management (business info, API credentials, delivery zones)
- Role-based access control (Super Admin, Admin, Staff)
- Error log viewer

---

## 4. Payment Flow

### 4.1 Supported Payment Methods

All transactions on the platform are processed exclusively in **Ghanaian Cedis (GHS)** via Mobile Money. The following networks are supported:

| Network | Internal Channel Code | Accepted Number Prefixes |
|---|---|---|
| MTN Mobile Money | `13` | 024, 054, 055, 059 |
| Telecel Cash | `6` | 020, 050 |
| AirtelTigo Money | `7` | 026, 056, 027, 057 |

### 4.2 Payment Journey (Customer-Facing)

The following describes the complete payment journey a customer experiences:

**Step 1 — Network Selection**  
The customer is presented with the three supported Mobile Money networks. They select their network provider.

**Step 2 — Number Entry**  
The customer enters their 10-digit Mobile Money number. The platform validates the number prefix against the selected network in real time, preventing mismatched submissions before the request is sent.

**Step 3 — Payment Initiation**  
Upon submission, the platform calls the payment gateway's `/payment` endpoint with the booking reference, amount, network channel code, and payer number. A payment prompt is immediately pushed to the customer's handset.

**Step 4a — Awaiting Authorisation (Standard Flow)**  
The payment page enters a polling state, checking transaction status every 3 seconds. The customer is instructed to approve the prompt on their handset by entering their Mobile Money PIN. A manual "Check Status Now" button is also available.

**Step 4b — OTP Verification (Where Required)**  
If the payment gateway returns a code indicating that an OTP (one-time password) is required (response code `TP14`), the payment page transitions to an OTP entry screen. The customer enters the code sent to their phone. The platform resubmits the payment request with the OTP included.

**Step 5 — Confirmation**  
Upon successful authorisation, the booking status is updated to **Confirmed**, the customer is redirected to the booking confirmation page, and confirmation notifications are dispatched (SMS and/or email, depending on system settings).

### 4.3 Saved Payment Methods

Authenticated (registered) customers may save their Mobile Money details for faster future payments. Saved methods are displayed during checkout, allowing a single-click payment authorisation without re-entering network and number details. Customers may have multiple saved methods and designate one as a default.

### 4.4 Deposit Policy

A deposit is required to confirm all bookings. The deposit percentage is disclosed to the customer during the booking process and is deducted from the final balance due at or before the event date. The remaining balance may be settled separately by arrangement with the business.

### 4.5 Error Handling

The platform distinguishes between two categories of payment errors:

- **Retryable errors** — displayed inline on the payment form, allowing the customer to correct their input and retry immediately (e.g. wrong number, network timeout).
- **Fatal errors** — displayed with a full-screen notice and direct support contact details, used when the gateway is unreachable or the booking is in an unrecoverable state.

All payment attempts — successes, failures, and status checks — are logged server-side for audit and reconciliation purposes.

### 4.6 Invoice Generation

Upon booking confirmation, a downloadable PDF invoice is available to the customer at `/invoice/{reference}/download`. The invoice displays itemised package details, quantities, unit prices, total amount, and payment reference.

---

## 5. Transaction Volume & Value Estimates

| Metric | Estimate |
|---|---|
| Typical single transaction value | GHS 500 — GHS 15,000 |
| Average transaction value | ~GHS 3,500 |
| Expected monthly transaction count | 20 — 80 transactions |
| Expected monthly transaction volume | GHS 10,000 — GHS 280,000 |
| Peak period | November — January (festive & wedding season) |
| Currency | GHS (Ghanaian Cedis) exclusively |

---

## 6. Integration Architecture

### 6.1 API Communication

All payment API calls originate from the platform's server-side application layer. No payment credentials or API keys are ever exposed to the customer's browser.

| Property | Detail |
|---|---|
| Communication direction | Server-to-server (backend) |
| Protocol | HTTPS / REST |
| Request format | JSON |
| Authentication method | Header-based (`X-API-USER`, `X-API-PUBKEY`) |
| External reference format | `CAT-YYYY-NNNNN` (unique per booking) |

### 6.2 Endpoints Used

| Operation | Endpoint | Description |
|---|---|---|
| Initiate payment | `POST /payment` | Send payment prompt to customer's handset |
| Submit OTP | `POST /payment` | Resubmit with OTP code where required |
| Check status | `POST /status` | Query transaction status by external reference |

### 6.3 Status Polling

The platform implements a 3-second client-side polling cycle backed by a server-side status check call. Polling continues until a terminal status (success or failure) is received, or the customer manually cancels. The external reference (`externalref`) is the platform's booking reference number, ensuring unique transaction identification.

---

## 7. Security & Compliance

- All platform traffic is served over HTTPS with TLS encryption.
- API credentials (API User, Public Key, Merchant ID) are stored exclusively as server-side environment variables and are never embedded in client-side code or version control.
- Customer payment numbers are handled transiently during the session and are not stored in plain text in the database beyond what is necessary for saved payment method functionality.
- Saved payment method records store the account number and network identifier only; no PIN or sensitive authentication data is stored.
- The platform implements session-based CSRF protection on all form submissions.
- Admin access is protected by role-based permissions, email verification, and optional two-factor authentication (TOTP).
- All payment events are logged with timestamps, booking references, response codes, and HTTP status codes for full audit traceability.

---

## 8. Notification System

Upon successful payment and booking confirmation, the platform dispatches notifications via:

| Channel | Trigger |
|---|---|
| SMS (GaintSMS — Ghana) | Booking confirmed, payment received |
| Email | Booking confirmed, payment received, invoice attached |

Notification dispatch is configurable per channel from the admin settings panel.

---

## 9. Platform URLs

| Page | URL |
|---|---|
| Homepage / Package Catalogue | `/` |
| All Packages | `/all-packages` |
| Checkout Wizard | `/checkout` |
| Event Inquiry | `/event-booking` |
| Payment Page | `/booking/payment/{booking}` |
| Booking Confirmation | `/booking/confirmation/{booking}` |
| Booking Tracker | `/booking/track` |
| Invoice Download | `/invoice/{reference}/download` |
| Customer Dashboard | `/dashboard` |
| Admin Portal | `/admin/dashboard` |
| Contact Page | `/contact` |

---

## 10. Contact Information for Integration Queries

**Business:** Diamonds & Pearls Catering  
**Email:** graceayesu@yahoo.com  
**Phone:** +233 244 203 181  
**WhatsApp:** +233 596 070 822  
**Address:** P.O. Box 18123, Accra, Ghana  

---

*This document is intended for submission to Moolre Payments as part of the payment gateway API integration onboarding process. All information contained herein is accurate as of the document date above.*
