<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100' : 'bg-base-200/100' }} font-sans antialiased text-base-content overflow-x-hidden flex"
    x-data="{ mobileMenuOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false"
        class="fixed inset-0 z-40 bg-base-content/20 backdrop-blur-sm lg:hidden" style="display: none;"></div>

    <x-admin.sidebar 
        ::mobile-menu-open="mobileMenuOpen" 
        x-on:close-mobile-menu="mobileMenuOpen = false"
    />

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">
        <x-admin.header :title="$title ?? null" x-on:open-mobile-menu="mobileMenuOpen = true" />

+        <!-- Main Workspace -->
+        <main class="flex-1 p-6 md:p-10 lg:p-16">
+            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
    @auth
        @livewire('admin.notifications.notification-slideover')
    @endauth
    @stack('scripts')
</body>

</html>