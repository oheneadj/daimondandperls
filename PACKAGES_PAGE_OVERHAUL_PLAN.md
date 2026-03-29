# Packages Browsing Page Audit & Overhaul Plan

This document outlines the UI/UX audit and technical improvement plan for the [Packages Browse](file:///home/oheneadj/server/dpc/resources/views/livewire/packages/packages-browse.blade.php) experience.

## UI/UX Audit Results

### 🔬 Observations
1.  **Static Visuals**: The `package-card` uses hardcoded background colors based on category slugs. This is brittle and doesn't scale well as new categories are added.
2.  **Metadata Gaps**: Essential information like "Minimum Guests" is currently hardcoded in the modal (`50`) rather than being source-of-truth from the database.
3.  **Pricing Clarity**: While GH₵ prices are shown, it's not always clear if these are "starting at" or "per head" without reading the description.
4.  **Sorting & Filtering**: There is no way to sort packages (e.g., by Price: Low to High) which is a standard expectation for "shopping" experiences.
5.  **Selection Summary**: The floating booking bar is great, but it could be more informative by showing an estimated total price for the selected items.

---

## 🏗️ Proposed Improvements

### 1. Database-Driven Visuals
- **Category Metadata**: Add `color_class` and `icon` fields to the `Category` model.
- **Dynamic Cards**: Update the `package-card` to use these model attributes instead of hardcoded `match` statements.

### 2. Enhanced Package Models
- **New Fields**: Add `min_guests` and `per_head_pricing` (boolean) to the `Package` model.
- **Rich Display**: Show these on the card using consistent iconography (e.g., a "Users" icon for guest count).

### 3. Navigation & Discovery
- **"Sort By" Dropdown**: Add a dropdown in the filter strip for "Price: Low to High", "Price: High to Low", and "Most Popular".
- **Visual Categories**: Replace text-only buttons with small icon + text pills for better visual scanning.

### 4. Selection Feedback (Booking Bar)
- **Estimated Total**: Calculate and display a "Starting Total" in the booking bar based on the sum of package prices * minimum guests.
- **Mini-Cart**: Allow clicking the booking bar to see a mini-summary of selected packages without leaving the page.

---

## 🛠️ Implementation Phases

### Phase 1: Database & Model Prep
- Create a migration to add new fields to `packages` and `categories`.
- Update factories and seeders to ensure data is populated.

### Phase 2: Refactoring Components
- Update `x-package-card` to be fully dynamic based on the new model fields.
- Refine the `x-package-details-modal` to pull its guest count from the DB.

### Phase 3: Livewire Logic
- Update `PackagesBrowse.php` to handle sorting logic.
- Implement the "Starting Total" calculation in the component.

### Phase 4: UI/UX Polish
- Refine the filter strip for tighter mobile responsiveness.
- Add subtle entrance animations for cards when filtering.

---

## 🧪 Verification Plan

1.  **Data Integrity**: Ensure new packages created via Admin show correctly with their icons/colors.
2.  **Selection Flow**: Verify that adding/removing packages updates the booking bar total correctly.
3.  **Responsive Testing**: Check that the sticky filter and booking bar don't block content on small mobile screens.
