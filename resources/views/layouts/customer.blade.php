<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-base-200 antialiased text-base-content overflow-x-hidden flex"
    x-data="{ mobileMenuOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" style="display: none;"></div>

    <x-customer.sidebar />

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen lg:ml-64">
        <x-customer.header :title="$title ?? null" />

        {{-- Payment method nudge: shown until the customer adds at least one --}}
        @auth
            @php $hasPaymentMethod = Auth::user()->customer?->paymentMethods()->exists(); @endphp
            @if(!$hasPaymentMethod)
                <div class="bg-[#FFC926]/15 border-b border-[#FFC926]/30 px-6 md:px-10 lg:px-16 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-[#FFC926]/30 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#9A7A00]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <p class="text-[13px] font-semibold text-[#7A6000]">
                            Add a payment method to speed up your checkout experience.
                        </p>
                    </div>
                    <a href="{{ route('dashboard.payment-methods') }}" wire:navigate
                        class="shrink-0 inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg bg-[#FFC926] text-[#5A4500] text-[12px] font-bold hover:bg-[#e6b520] transition-colors whitespace-nowrap">
                        Add Payment Method
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @endif
        @endauth

        <!-- Page Content -->
        <main class="flex-1 p-6 md:p-10 lg:p-16">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('scripts')

    <div
        x-data="{
            toasts: [],
            addToast(detail) {
                const type = detail.type || detail.style || 'info';
                const message = detail.message || (typeof detail === 'string' ? detail : '');
                if (!message) return;
                const id = Date.now();
                this.toasts.push({ id, type, message });
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 7000);
            }
        }"
        x-init="
            @if(session('success')) addToast({ type: 'success', message: '{{ session('success') }}' }); @endif
            @if(session('error')) addToast({ type: 'error', message: '{{ session('error') }}' }); @endif
            @if(session('status')) addToast({ type: 'success', message: '{{ session('status') }}' }); @endif
        "
        x-on:toast.window="addToast($event.detail)"
        x-on:banner.window="addToast($event.detail)"
        class="fixed top-20 right-6 z-[100] flex flex-col gap-3 min-w-[320px] max-w-md pointer-events-none"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div
                class="pointer-events-auto relative group alert shadow-lg border-none flex gap-3 animate-in slide-in-from-right duration-300"
                :class="{
                    'bg-[#9ABC05] text-white': toast.type === 'success',
                    'bg-[#F96015] text-white': toast.type === 'warning',
                    'bg-[#D52518] text-white': toast.type === 'error' || toast.type === 'danger',
                    'bg-[#FFC926] text-black': toast.type === 'info'
                }"
                x-show="true"
                x-transition:leave="transition ease-in duration-300 transform"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="translate-x-full opacity-0"
            >
                <div class="flex-shrink-0">
                    <template x-if="toast.type === 'success'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </template>
                    <template x-if="toast.type === 'info' || !toast.type">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </template>
                </div>

                <div class="flex-1 text-[13px] font-bold" x-text="toast.message"></div>

                <button
                    @click="toasts = toasts.filter(t => t.id !== toast.id)"
                    class="p-1 hover:bg-black/10 rounded-md transition-colors opacity-0 group-hover:opacity-60 hover:opacity-100"
                    :class="toast.type === 'warning' ? 'text-black' : 'text-white'"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </template>
    </div>
</body>

</html>
