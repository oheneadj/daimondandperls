# Package Data Preservation Plan

This plan addresses the issue where deleting a package causes booking details to become empty or inaccurate.

## Objective
Ensure that every booking permanently preserves the package details (name, description, and price) exactly as they were at the moment of the transaction, regardless of whether the original package is later modified or deleted.

## Proposed Solution

### 1. Database Schema Updates
Add snapshotting columns to the `booking_items` table to store the state of the package at checkout.

- **`package_name`** (string): Captures the name of the package.
- **`package_description`** (text, nullable): Captures the description of the package.
- **`price`** (decimal): (Already exists, but essential for the snapshot).

### 2. Model & Relationship Updates
- **`Package` Model**: Ensure `SoftDeletes` is enabled.
- **`BookingItem` Model**: 
    - Add `package_name` and `package_description` to `$fillable`.
    - Update the `package()` relationship to include `.withTrashed()` so it can still link to deleted packages if needed.

### 3. Logic & Workflow Updates
- **`BookingWizard`**: When creating a booking, copy the `name` and `description` from the `$package` object into the `BookingItem` record alongside the `price`.
- **Admin & Tracking Views**: Update the display logic to prioritize the snapshotled data:
  ```blade
  {{ $item->package_name ?? $item->package?->name ?? 'Deleted Package' }}
  ```

### 4. Backfill (Existing Data)
Create a one-time migration or script to populate the `package_name` for all existing bookings by pulling the names from their current (or trashed) package relations.

---

## Technical Tasks

- [ ] **Step 1: Migration**
  Create `database/migrations/[date]_add_package_details_to_booking_items_table.php`
- [ ] **Step 2: Model Updates**
  Update `App\Models\BookingItem` and `App\Models\Package` (if needed).
- [ ] **Step 3: Logic Update**
  Modify `confirmBooking` in `App\Livewire\Booking\BookingWizard`.
- [ ] **Step 4: View Updates**
  Update `resources/views/livewire/admin/bookings/show.blade.php`.
- [ ] **Step 5: Verification**
  Test by creating a booking, deleting the package, and verifying the booking remains intact.
