# Booking Wizard Inline Auth Implementation Plan

This document details the implementation of an inline OTP (One-Time Password) login and registration flow within the guest checkout process.

## Objective
Allow guest users to easily create an account or log in during Step 2 (Contact Details) of the booking checkout using just their phone number and a 6-digit OTP. This ensures they can track their booking later without interrupting the checkout flow.

## 1. UI/UX Flow (Step 2 of `BookingWizard`)

When an unauthenticated user reaches Step 2 (Contact Details):
1. **The Hook**: Display an inviting banner or card above the contact form:
    * *"Want to track your booking later? Verify your phone number to instantly create an account or sign in."*
2. **The Interaction**:
    * Instead of a redirect, interacting with this section opens/expands the redesigned `⚡otp-login` (Phone + OTP) component right there in the wizard.
3. **The UX**:
    * The user enters their phone number -> receives SMS.
    * The user enters the 6-digit OTP into a modern 6-box grid.
4. **The Resolution**:
    * Upon successful verification, the backend automatically logs them in (or creates their `Customer` account and logs them in).
    * The `BookingWizard` component immediately updates:
        * The "Hook" banner disappears.
        * The contact details form (Name, Phone, Email) auto-fills with their known data.
        * The user seamlessly continues to Step 3.

## 2. Component Modifications

### A. `Livewire/Booking/BookingWizard.php`
- Remove the old password-based `createAccount` checkbox logic from Step 2.
- Add an event listener to listen for a successful login event from the OTP component.
    ```php
    #[On('logged-in')]
    public function handleLogin() {
        // Refresh auth state and strictly pre-fill contact fields from Auth::user()
    }
    ```
- Update `confirmBooking()` to no longer handle manual user creation via password. It should simply check `Auth::user()` and attach the booking to them if present.

### B. `resources/views/livewire/booking/booking-wizard.blade.php`
- **In Step 2**:
    ```blade
    @guest
        <!-- Inline OTP Component Container -->
        <div class="mb-6 p-6 border rounded-2xl bg-base-200">
            <livewire:auth.otp-login context="checkout" />
        </div>
    @endguest
    
    <!-- Standard Contact Form (Auto-filled if @auth) -->
    ```

### C. `Livewire/Auth/OtpLogin.php` & View
- **Responsiveness**: Ensure the `OtpLogin` component styling adapts nicely when embedded inside the Booking Wizard card versus when standalone on the Login page.
- **Event Dispatching**: Upon successful `Auth::login()`, the component should dispatch a `logged-in` or similar Livewire event so the parent `BookingWizard` knows to refresh its state.
- **Security**: Ensure the auto-registration triggered by OTP *only* ever creates a `UserRole::Customer` account.

## 3. Implementation Steps

- [ ] **Step 1**: Update `OtpLogin.php` to handle a `context` prop (optional) and dispatch a Livewire event upon successful login.
- [ ] **Step 2**: Remove the old password-based account creation fields from `BookingWizard.php` and its Blade view.
- [ ] **Step 3**: Inject `<livewire:auth.otp-login />` into Step 2 of the booking wizard.
- [ ] **Step 4**: Add the `#[On('...')]` listener to `BookingWizard.php` to trigger a re-render and auto-fill the contact dataset when the OTP is successful.
- [ ] **Step 5**: Style the embedded OTP component to match the surrounding "Bright & Bold" checkout UI.
