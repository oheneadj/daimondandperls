# Customer Dashboard Implementation Plan

This document outlines the architecture and UI components for the new Customer Dashboard. The focus is to provide a seamless "Client Portal" experience using the application's established 'Bright & Bold' design language.

## Architecture Decisions Needed

**1. Payment Options Implementation**: You requested to "add payment options". We need to decide whether to securely save actual payment details via a card/MoMo gateway (like Paystack or Stripe tokens), or simply allow the customer to set a "Preferred Payment Method" (e.g., MTN MoMo, Telecel Cash, Visa) on their profile for faster auto-filling during checkout.

**2. Navigation Layout**: The Customer Dashboard will require its own layout. The proposal is to use a left-sidebar navigation for customers on desktop (collapsing to a hamburger menu on mobile) similar to the administrative panel, but styled specifically for clients.

---

## Proposed Changes

### 1. Routes & Architecture

Create a dedicated route group for customers protected by authentication and role middleware.

#### `routes/web.php` additions
- `Route::prefix('portal')->middleware(['auth', 'role:customer'])`
  - `/` -> `Customer\Dashboard` (Overview)
  - `/bookings` -> `Customer\Bookings\Index` & `/bookings/{id}` -> `Customer\Bookings\Show`
  - `/payments` -> `Customer\Payments\Index`
  - `/profile` -> `Customer\Profile\Settings`

### 2. Layouts

#### `resources/views/layouts/customer.blade.php`
- A specific layout extending the 'Bright & Bold' aesthetics.
- Includes a client-focused sidebar: Dashboard, My Bookings, Payment History, Profile Settings.
- Clean white cards (`bg-base-100`), vibrant primary accents, and `rounded-[24px]` containers.

---

### 3. Livewire Components

#### `app/Livewire/Customer/Dashboard.php`
- **Purpose**: At-a-glance view.
- **Features**: 
  - "Active Bookings" quick-card.
  - "Recent Payments" mini-table.
  - "Profile Status" prompt (e.g., "Add a payment method for faster checkout").

#### `app/Livewire/Customer/Bookings/Index.php` & `Show.php`
- **Purpose**: Track and manage personal events.
- **Features**:
  - List view of all past and upcoming bookings with status badges (Pending, Confirmed, Completed).
  - Detailed view showing the specific packages ordered, total cost, event date, and a specific "Make Payment" button if the booking has a balance.

#### `app/Livewire/Customer/Payments/Index.php`
- **Purpose**: Payment history and receipts.
- **Features**:
  - List of all transactions (successful, failed, pending).
  - Downloadable invoice/receipt links.

#### `app/Livewire/Customer/Profile/Settings.php`
- **Purpose**: Manage personal details and payment options.
- **Features**:
  - **Contact Details**: Update Full Name, Email, and Phone Number.
  - **Security**: Update Password (if they didn't use OTP exclusively) or manage OTP preferences.
  - **Payment Options**: A dedicated tab to manage saved Payment Methods (e.g., adding a default Mobile Money number or saving a Card, depending on your gateway).

---

### 4. Database Modifications

#### `database/migrations/xxxx_xx_xx_create_payment_methods_table.php` (If storing actual methods)
- We will need a table to securely store customer payment preferences or gateway identifiers.
- Columns: `user_id`, `provider` (e.g., 'paystack'), `type` (e.g., 'momo', 'card'), `last_four`, `is_default`.

---

## Task Checklist

### Phase 1: Planning & Architecture
- [x] Draft Customer Dashboard implementation plan
- [ ] Finalize Payment Options approach
- [ ] Finalize UI Layout approach

### Phase 2: Foundation & Routing
- [ ] Set up `portal` route group in `routes/web.php`
- [ ] Create `layouts/customer.blade.php` with sidebar navigation
- [ ] Update standard login redirect to send Customers to `/portal`

### Phase 3: Core Components
- [ ] Implement `Customer\Dashboard` (Overview page)
- [ ] Implement `Customer\Bookings\Index` and `Show` (Customer perspective)
- [ ] Implement `Customer\Payments\Index` (History)
- [ ] Implement `Customer\Profile\Settings` (Contact & Details updates)

### Phase 4: Payment Options
- [ ] Create database migration for `payment_methods` or customer preferences
- [ ] Build "Add Payment Option" UI in Profile Settings
- [ ] Integrate saved payment option into the Booking Wizard checkout

### Phase 5: Polish & Verify
- [ ] Apply 'Bright & Bold' styling consistently across all new views
- [ ] Write integration tests for route security and scoping
- [ ] Perform manual end-to-end verification of the portal
