# Auth Redesign Plan Update Diff

```diff
@@ -29,10 +29,18 @@
 - **Style**: Individual boxes will have a `rounded-2xl` or `rounded-full` shape with high-contrast borders on focus.
 - **Security**: Update the auto-registration logic to strictly assign the `Customer` role and link to existing customer records via phone number.
 
+### 4. Booking Wizard Integration (Inline Auth)
+- **Objective**: Allow guest users to seamlessly create an account or log in via OTP *during* the booking process to easily track their order later.
+- **Implementation**: 
+    - Inject the redesigned `⚡otp-login` component into Step 2 (Contact Details) of the `booking-wizard.blade.php`.
+    - If a user is not logged in, present a clean "Verify with Phone to Track Your Booking" option.
+    - Upon successful OTP verification, automatically log them in, pre-fill their contact details, and seamlessly resume their checkout flow without losing cart data.
+
-### 4. Role-Specific Flow
+### 5. Role-Specific Flow
 - **Registration**: Only available for Customers.
 - **Admins/Staff**: The registration link will be removed from the public view or clearly labeled as "Customer only". Staff must be invited via the Admin Dashboard.
```
