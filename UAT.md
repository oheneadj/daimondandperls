# USER ACCEPTANCE TEST DOCUMENT

**PROJECT NAME:** Diamond Tech & Merchant Services – Checkout Integration
**SUBMITTED BY:** IT Consortium
**DOCUMENT DATE:** April 2026

---

## SCOPE

| IN SCOPE |
|----------|
| 1. Registration / Signup |
| 2. Login |
| 3. Booking & Payment |

---

## TEST CASES

---

### 1. Registration / Signup

| ID | Test Case | Pass/Fail | Tested By | Date |
|----|-----------|-----------|-----------|------|
| 1.1 | **Valid Registration** | | | |
| | **Test Procedure:** Go to `/register`. Enter a full name, email, valid Ghana phone number (e.g. `0241234567`), and a password. Submit. | | | |
| | **Expected Results:** Account is created. User is redirected to the dashboard. | | | |
| 1.2 | **Missing Required Fields** | | | |
| | **Test Procedure:** Submit the registration form with one or more fields left blank. | | | |
| | **Expected Results:** Validation errors are shown for each missing field. Form does not submit. | | | |
| 1.3 | **Invalid Phone Number** | | | |
| | **Test Procedure:** Enter a phone number that is not a valid Ghana number (e.g. `12345`). Submit. | | | |
| | **Expected Results:** Validation error: phone number format is invalid. | | | |
| 1.4 | **Duplicate Email or Phone** | | | |
| | **Test Procedure:** Register using an email or phone number that already belongs to an existing account. | | | |
| | **Expected Results:** Validation error: "The email has already been taken." or "The phone has already been taken." | | | |
| 1.5 | **Password Mismatch** | | | |
| | **Test Procedure:** Enter a valid password and a different value in the confirm password field. Submit. | | | |
| | **Expected Results:** Validation error: "The password confirmation does not match." | | | |

---

### 2. Login

| ID | Test Case | Pass/Fail | Tested By | Date |
|----|-----------|-----------|-----------|------|
| 2.1 | **Valid Login (Email & Password)** | | | |
| | **Test Procedure:** Go to `/login`. Enter a registered email and correct password. Submit. | | | |
| | **Expected Results:** User is authenticated and redirected to the customer dashboard. | | | |
| 2.2 | **Wrong Password** | | | |
| | **Test Procedure:** Enter a registered email with an incorrect password. Submit. | | | |
| | **Expected Results:** Login fails. Error: "These credentials do not match our records." | | | |
| 2.3 | **OTP Login – Valid** | | | |
| | **Test Procedure:** Go to the OTP login page. Enter a registered phone number. Click "Send OTP". Enter the 6-digit code received via SMS. Submit. | | | |
| | **Expected Results:** User is authenticated and redirected to the customer dashboard. | | | |
| 2.4 | **OTP Login – Wrong Code** | | | |
| | **Test Procedure:** Send an OTP. Enter an incorrect code. Submit. | | | |
| | **Expected Results:** Error shown. User remains on the OTP screen and may resend. | | | |
| 2.5 | **Admin Login** | | | |
| | **Test Procedure:** Log in with an admin account. | | | |
| | **Expected Results:** User is redirected to the `/admin` dashboard, not the customer area. | | | |

---

### 3. Booking & Payment

| ID | Test Case | Pass/Fail | Tested By | Date |
|----|-----------|-----------|-----------|------|
| 3.1 | **Browse & Add to Cart** | | | |
| | **Test Procedure:** Go to the packages page. Click "Book Now" or "Add to Cart" on a package. | | | |
| | **Expected Results:** Item is added to the cart. Cart count updates. | | | |
| 3.2 | **Guest Checkout – Contact Details** | | | |
| | **Test Procedure:** Without logging in, proceed to checkout. Enter a valid name, phone number, and email. Click Next. | | | |
| | **Expected Results:** A one-time OTP is sent to the phone. User is prompted to verify before proceeding. | | | |
| 3.3 | **Authenticated Checkout – Contact Details** | | | |
| | **Test Procedure:** Log in, add a package, proceed to checkout. | | | |
| | **Expected Results:** Name, email, and phone are pre-filled. No OTP step required. User proceeds directly to payment. | | | |
| 3.4 | **MoMo Payment – Successful** | | | |
| | **Test Procedure:** On the payment step, select Mobile Money. Enter a valid MoMo number and network. Click "Pay Now". Approve the prompt on the phone. | | | |
| | **Expected Results:** Booking is confirmed and marked Paid. User is redirected to the confirmation page. Cart is cleared. A confirmation SMS and email are sent. | | | |
| 3.5 | **MoMo Payment – Declined** | | | |
| | **Test Procedure:** Initiate a MoMo payment and reject the prompt on the phone. | | | |
| | **Expected Results:** Payment fails. User is returned to the payment page with an error message. Cart is not cleared. | | | |
| 3.6 | **Card Payment – Successful** | | | |
| | **Test Procedure:** On the payment step, select Card. Click "Pay with Card". Enter valid card details on the payment page and complete the payment. | | | |
| | **Expected Results:** User is redirected back to the confirmation page. Booking is confirmed and marked Paid. Confirmation notification is sent. | | | |
| 3.7 | **Card Payment – Declined / Cancelled** | | | |
| | **Test Procedure:** On the card payment page, use a declined card or click Cancel. | | | |
| | **Expected Results:** User is returned to the payment step with an appropriate error message. Booking remains in pending state. | | | |
| 3.8 | **Booking Confirmation Page** | | | |
| | **Test Procedure:** Complete a successful payment and view the confirmation page. | | | |
| | **Expected Results:** Page shows the order reference, Paid badge, items, total amount, and delivery details. A "Download Invoice" button is visible. A confirmation SMS and email have been received. | | | |
| 3.9 | **Download Invoice** | | | |
| | **Test Procedure:** On the confirmation page, click "Download Invoice". | | | |
| | **Expected Results:** A PDF invoice opens or downloads with the correct booking details. | | | |

---

## UAT Sign Off

**For Diamond Tech & Merchant Services**

Signature: ___________________________
Name:
Title:
Date:
Time:

&nbsp;

**For IT Consortium**
IT Consortium, No.3 Gem Street, Adjiringanor, Accra. P.O.BOX CT8010, Cantonments, Accra-Ghana

Signature: ___________________________
Name:
Title:
Date:
Time:

&nbsp;

**Witnessed By (Merchant Side)**

Signature: ___________________________
Name:
Title:
Date:
Time:

&nbsp;

**Witnessed By (IT Consortium)**

Signature: ___________________________
Name:
Title:
Date:
Time:
