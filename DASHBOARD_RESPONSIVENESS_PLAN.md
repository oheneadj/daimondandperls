# Implementation Plan: Full Dashboard Responsiveness

Objective: Ensure all Admin and Customer dashboard pages are fully responsive, with a premium mobile drawer navigation and optimized grid layouts for graphs and data.

---

## 🏗️ Layout & Navigation (Critical Foundation)

### [Admin Layout](file:///home/oheneadj/server/dpc/resources/views/layouts/admin.blade.php)
- **Drawer System**: Add Alpine.js state `x-data="{ sidebarOpen: false }"`.
- **Sidebar**:
    - Force hide on mobile using `-translate-x-full`.
    - Show as absolute drawer when `sidebarOpen` is true.
    - Solidify desktop visibility at `lg:` breakpoint.
- **Overlay**: Add a semi-transparent backdrop for mobile (`bg-black/50`) to focus on the drawer.

### [Admin Header](file:///home/oheneadj/server/dpc/resources/views/layouts/partials/header.blade.php)
- **Hamburger Button**: Add a `lg:hidden` toggle button on the left to open the sidebar.
- **Responsive Header Metrics**: Hide the secondary profile name text on small screens (`hidden sm:block`) to prevent title crowding.

---

## 📊 Dashboard Page Optimizations

### [Admin Dashboard](file:///home/oheneadj/server/dpc/resources/views/dashboard.blade.php)
- **3:1 Revenue Grid**: Adjust parent container to `grid-cols-1 lg:grid-cols-4`. Change chart span to `lg:col-span-3` (takes full width on mobile).
- **Stat Cards**: Standardize to `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`.
- **Action Grids**: Change 3-card operational intelligence row to stack on mobile.
- **Table Container**: Wrap the `x-data-table` in an `overflow-x-auto` wrapper.

### [Customer Dashboard](file:///home/oheneadj/server/dpc/resources/views/livewire/customer/dashboard.blade.php)
- **Booking Cards**: 
    - Convert `flex-row` to `flex-col` on mobile. 
    - Stack Reference and Status on top of details for better vertical flow.
- **Stat Cards**: Use `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4` for consistent density.

---

## 📐 General UI Modules (Global Fixes)

- **Index Pages (Admin & Customer)**:
    - Ensure filter/search bars stack vertically on mobile.
    - Convert multi-column table rows to card-style layouts or enable horizontal scrolling.
- **Show Pages**:
    - Refine 2:1 "Split Screen" layouts (e.g., Booking Details) to stack vertically.
- **Form Groups**:
    - Ensure all form layouts (e.g., Settings, Profile Edit) use `grid-cols-1` by default, scaling to `md:grid-cols-2` only on wider screens.

---

## ✅ Verification Steps

1. **Mobile Check (375px)**: Open sidebar via hamburger, verify overlay, check stacked cards.
2. **Tablet Check (768px)**: Verify sidebar is hidden/drawer, check 2-column stat grids.
3. **Desktop Check (1024px+)**: Sidebar should be fixed, grids should restore to full expansion.
