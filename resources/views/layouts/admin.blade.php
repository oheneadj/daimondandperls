<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="bg-base-200 antialiased text-base-content overflow-hidden">
    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR: fixed 256px wide, full height, dark background (#121212) --}}
        <aside class="w-64 flex-shrink-0 overflow-y-auto bg-neutral border-r border-white/[0.03]">
            @include('layouts.partials.sidebar')
        </aside>

        {{-- MAIN AREA: fluid, contains header + scrollable content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- TOP BAR: Light (Cream) background (#f3e8cc) --}}
            <header class="h-16 flex-shrink-0 bg-base-200 border-b border-base-content/[0.03] flex items-center px-6 md:px-10">
                @include('layouts.partials.header')
            </header>

            {{-- PAGE CONTENT: scrollable, spacious padding --}}
            <main class="flex-1 overflow-y-auto p-6 md:p-10 lg:p-16">
                <div class="max-w-7xl mx-auto">
                @if(session()->has('impersonator_id'))
                    <div class="mb-8 bg-neutral text-neutral-content px-6 py-4 rounded-2xl flex items-center justify-between shadow-md animate-in slide-in-from-top duration-500">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center animate-pulse">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary-content" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[14px] font-bold tracking-tight">Impersonating Customer: <span class="text-primary-content/80">{{ auth()->user()->name }}</span></p>
                                <p class="text-[11px] opacity-70 font-medium uppercase tracking-widest">You are currently viewing the ecosystem as this customer.</p>
                            </div>
                        </div>

                        <a href="{{ route('admin.impersonate.stop') }}" class="btn btn-primary btn-sm">
                            Leaving Impersonation
                        </a>
                    </div>
                @endif
                
                {{ $slot }}
                </div>
            </main>

        </div>
    </div>

    @stack('scripts')

    {{-- Global Toast Container --}}
    <div 
        x-data="{ 
            toasts: [], 
            addToast(detail) { 
                const type = detail.type || 'info';
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
        @toast.window="addToast($event.detail)"
        class="fixed top-20 right-6 z-[100] flex flex-col gap-3 min-w-[320px] max-w-md pointer-events-none"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div 
                class="pointer-events-auto relative group"
                x-show="true"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="-translate-y-4 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transition ease-in duration-300 transform"
                x-transition:leave-start="translate-y-0 opacity-100"
                x-transition:leave-end="-translate-y-4 opacity-0"
            >
                <x-ui.toast 
                    x-bind:type="toast.type" 
                    x-bind:message="toast.message" 
                />
                
                {{-- Manual dismiss button --}}
                <button 
                    @click="toasts = toasts.filter(t => t.id !== toast.id)" 
                    class="absolute top-4 right-4 p-1 hover:bg-black/10 rounded-md transition-colors opacity-0 group-hover:opacity-60 hover:opacity-100"
                    :class="toast.type === 'warning' ? 'text-black' : 'text-white'"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </template>
    </div>
</body>

</html>
