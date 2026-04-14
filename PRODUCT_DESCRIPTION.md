# Official Product Description Document

## 1. Overview
This document explains the **Digital Booking & Payment Platform** custom-built for **Diamonds and Pearls Catering Services**. The platform allows the business to manage catering orders, receive mobile payments, and track deliveries online.

**Built By:** Diamond Tech & General Merchant  
**Built For:** Diamonds and Pearls Catering Services

---

## 2. Developer Details
*Note: Please update the [bracketed] text with your official company information before sharing.*

- **Company Name:** Diamond Tech & General Merchant
- **Business Registration Number:** [Enter Registration Number]
- **Official Address:** [Enter Corporate Address]
- **Contact Person:** [Enter Contact Name / Title]
- **Email Address:** [Enter Official Email Address]
- **Phone Number:** [Enter Official Phone Number]
- **Website:** [Enter Website URL]

---

## 3. What the System Does

The platform is a web application that replaces manual paper forms and cash transactions with a fully digital process. 

The system serves two main user groups:
1. **Customers:** People who visit the website to browse the menu, add food to their cart, enter delivery details, and pay using Mobile Money.
2. **Staff (Diamonds and Pearls Team):** The business owners and kitchen staff who log into an admin panel to manage orders, update the menu, and track payments.

---

## 4. Key Features

### 4.1 For Customers
- **Online Menu:** Customers can view photos, descriptions, and prices of the food packages available.
- **Shopping Cart:** Customers can add items, change quantities, and see their total price before checking out.
- **Checkout Process:** A step-by-step form to collect the customer's name, delivery address, and contact details.
- **Mobile Money Payments:** Customers can pay instantly using their MTN, Telecel, or AirtelTigo accounts. Payment requests are sent directly to their phones.
- **Order Tracking:** Customers get a unique reference number to check if their food is being prepared, ready, or out for delivery.

### 4.2 For Staff and Management (Admin Dashboard)
- **Business Dashboard:** A summary showing daily orders, monthly earnings, and overall business performance.
- **Order Management:** Staff can update an order's status (e.g., from "Pending" to "In Preparation" to "Completed").
- **Catalogue Control (CRUD):** Staff have full Create, Read, Update, and Delete capabilities to add new food items, change prices, or hide out-of-stock packages.
- **Payment Records:** A clear ledger of all successful and failed payments to help balance accounts.

---

## 5. Technical Architecture

The platform was built using modern, industry-standard technologies to ensure it is fast, secure, and reliable.

- **Backend Framework:** Laravel 12 (PHP 8.4)
- **Frontend Interactivity:** Livewire 4 & Alpine.js
- **User Interface Framework:** Tailwind CSS 4
- **Database:** Relational MySQL database for secure data storage.
- **Authentication:** Laravel Fortify integration for secure logins and session management.

---

## 6. Security and Compliance

Keeping data safe is a top priority. The system includes the following security measures:
- **No Storage of Sensitive Payment Data:** The system processes Mobile Money payments securely but never stores secret payment PINs or sensitive credentials in the database.
- **Role-Based Access Control (RBAC):** Staff accounts are restricted by their specific roles, ensuring they can only see what they are authorized to see.
- **CSRF & XSS Protection:** Built-in safeguards protect the website from cross-site scripting and fraudulent form submissions.
- **Rate-Limiting:** The checkout and login pages are rate-limited to block spam, automated bots, and Denial of Service (DoS) attacks.

---

## 7. How an Order Works (Step-by-Step)

1. **Ordering:** A customer selects their food package and clicks checkout.
2. **Details:** The customer enters their delivery location and date.
3. **Payment Initiation:** The website connects to the Mobile Money API. The customer receives a secure payment prompt on their phone.
4. **Confirmation:** Once the customer enters their PIN on their phone, the payment provider confirms the success via API. The website alerts the kitchen staff.
5. **Fulfillment:** The staff prepares the food, updates the website to "Completed," and delivers the order.

---
*Document Version: 1.3*  
*Document Type: Official Product Documentation*
