# Premium Auth Experience Redesign Plan

This plan outlines the visual and functional overhaul of the authentication system to align with a premium, 'Bright & Bold' design language.

## Design Objectives
- **Breathable Layout**: Eliminate the cramped feeling of the current narrow auth cards.
- **Modern Interactions**: Use `rounded-full` elements for a softer, more premium feel.
- **Enhanced Readability**: Ensure icons and inputs have proper spacing and visibility.
- **User-Centric OTP**: Replace the single text field with a modern, 6-digit grid input.
- **Role Awareness**: Clearly distinguish between Customer registration and Admin invitations.

## Proposed Changes

### 1. Global Auth Layout (`simple.blade.php`)
- **Container**: Increase `max-w-[440px]` to `max-w-[550px]`.
- **Canvas**: Maintain the decorative blurred background elements but adjust padding for the wider card.

### 2. Login & Registration Pages
- **Buttons**: All primary "Authenticate" and "Sign Up" buttons will transition to `rounded-full`.
- **Inputs**: 
    - Increase `pl-14` to `pl-16` for better icon breathing room.
    - Use `bg-base-content/5` (subtle gray) for input backgrounds with `ring-4` focus effects.
- **Copy**: 
    - Register page will be rebranded as "Join the DPC Experience".
    - Login page will be updated with more inviting, premium typography.

### 3. Modern OTP Component (`⚡otp-login.blade.php`)
- **Input Grid**: Implement 6 individual input boxes that auto-focus on the next digit.
- **Style**: Individual boxes will have a `rounded-2xl` or `rounded-full` shape with high-contrast borders on focus.
- **Security**: Update the auto-registration logic to strictly assign the `Customer` role and link to existing customer records via phone number.

### 4. Booking Wizard Integration (Inline Auth)
- **Objective**: Allow guest users to seamlessly create an account or log in via OTP *during* the booking process to easily track their order later.
- **Implementation**: 
    - Inject the redesigned `⚡otp-login` component into Step 2 (Contact Details) of the `booking-wizard.blade.php`.
    - If a user is not logged in, present a clean "Verify with Phone to Track Your Booking" option.
    - Upon successful OTP verification, automatically log them in, pre-fill their contact details, and seamlessly resume their checkout flow without losing cart data.

### 5. Role-Specific Flow
- **Registration**: Only available for Customers.
- **Admins/Staff**: The registration link will be removed from the public view or clearly labeled as "Customer only". Staff must be invited via the Admin Dashboard.

## Technical Tasks

- [ ] **Step 1: Layout Update**
  Modify `resources/views/layouts/auth/simple.blade.php`.
- [ ] **Step 2: Login/Register Refresh**
  Update `resources/views/livewire/auth/login.blade.php` and `register.blade.php`.
- [ ] **Step 3: OTP Overhaul**
  Redesign the UI and logic in `resources/views/components/auth/⚡otp-login.blade.php`.
- [ ] **Step 4: Verification**
  Cross-browser testing for the new wider layout and OTP auto-focus logic.
