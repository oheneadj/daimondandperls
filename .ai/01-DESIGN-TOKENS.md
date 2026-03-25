# Master Design Specification (Definitive)

This document formalizes the "Appetizing UI" strategy for Diamonds & Pearls, harmonizing modern professionalism with culinary warmth.

## 🎨 Global Identity

### Primary Palette (Food-Inspired)
- **Sunshine** (`#FFC926`): **Primary Brand / Main CTA / Revenue focus**. High energy, triggers appetite.
- **Crisp Carrot** (`#F96015`): **Secondary Action / Active states / Ongoing processes**. "In motion".
- **Kiwi** (`#9ABC05`): **Success / Completed metrics / Fresh indicators**. Positive and reassuring.
- **Tomato Burst** (`#D52518`): **Urgent Alerts / Cancelled states / Errors**. High visibility.

### Structural Palette
- **Modern Black** (`#121212`): **Sidebar background**. Deep, high-trust, professional anchor.
- **Base Gray** (`#F9FAFA`): **Universal app background and Top Bar**. Clean, high-contrast neutral.
- **Forest Green** (`#18542A`): **Authoritative header text and navigation titles**. Signals command and control.
- **White** (`#FFFFFF`): **Elevated card surfaces**. High contrast against Base Gray for a clean "App" feel.

## 🧱 Component Standards

### 1. Modern Sidebar
- **Active State**: **3px Sunshine left-border** + `bg-primary/10` tint + **Sunshine** text/icons.
- **Hover State**: `#1A1A1A` background + **Crisp Carrot** text/icons.
- **Typography**: **Outfit** Medium for items, Bold for grouped headers.

### 2. Mixed Stat Card System (Soft Tints)
- **Primary KPIs (Revenue, Bookings)**: Soft-tinted backgrounds (e.g., `bg-primary/5`) for instant focus and appetite.
- **Secondary KPIs**: Clean white with soft borders (`border-base-content/5`) to maintain balance.
- **Universal Rule**: All card titles are **Forest Green** bold uppercase for authority.

### 3. Data Visualization
- **Lead Metric**: **Crisp Carrot** line for energy and motion.
- **Secondary/Projected**: **Kiwi** dashed line for growth and freshness.
- **Typography**: **Outfit** specifically for all axis and tooltip labels to ensure modern legibility.

## 🏁 Design Hierarchy
1. **Structure** → Black Sidebar (#121212)
2. **Base** → Cream Background (#F3E8CC)
3. **Focus** → Sunshine Highlights (#FFC926)
4. **Action** → Crisp Carrot Accents (#F96015)
5. **Status** → Kiwi / Tomato Burst Functional coloring
