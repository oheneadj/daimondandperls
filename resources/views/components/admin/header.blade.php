@props([
    'title' => null
])

<header {{ $attributes->merge(['class' => 'h-16 flex-shrink-0 bg-base-200 border-b border-base-content/[0.03] flex items-center justify-between px-4 sm:px-6 lg:px-10']) }}>
    {{-- Left: Mobile Toggle & Page Title --}}
    <div class="flex items-center gap-3">
        {{-- Mobile Menu Toggle --}}
        <button @click="mobileMenuOpen = true" class="p-2 lg:hidden text-[#18542A] hover:bg-[#18542A]/5 rounded-full transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <h1 class="text-[20px] font-bold text-[#18542A] leading-none">
            {{ $title }}
        </h1>
    </div>

    {{-- Right: Tools & Profile --}}
    <div class="flex items-center gap-4 sm:gap-6">
        {{-- Search --}}
        <div class="hidden md:block relative w-[200px]">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                @include('layouts.partials.icons.magnifying-glass', ['class' => 'w-4 h-4 text-[#18542A]/40'])
            </div>
            <input type="text"
                   placeholder="{{ __('Search bookings...') }}"
                   class="text-[13px] bg-base-200 border border-base-content/10 rounded-md pl-9 pr-3 py-1.5 w-full focus:ring-2 focus:ring-dp-rose/10 focus:border-dp-rose outline-none transition-all">
        </div>

        {{-- Notifications --}}
        @auth
            @livewire('admin.notifications.notification-bell')
        @endauth

        {{-- Admin Avatar + Name Block --}}
        @auth
            <div x-data="{ open: false }" class="relative flex items-center gap-3 pl-4 sm:pl-6 border-l border-base-content/10">
                <div class="hidden sm:flex flex-col items-end">
                    <span class="text-[13px] font-bold text-[#18542A] leading-tight">{{ auth()->user()->displayName() }}</span>
                    <span class="text-[11px] text-[#18542A]/60 leading-tight font-medium">{{ ucfirst(auth()->user()->role?->value ?? 'Administrator') }}</span>
                </div>

                <button @click="open = !open" class="relative group">
                    <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-black text-[13px] font-bold shadow-sm transition-all">
                        {{ auth()->user()->initials() }}
                    </div>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open"
                     @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 top-full mt-2 w-48 rounded-lg z-50 overflow-hidden shadow-dp-lg border border-base-content/10 bg-white"
                     style="display: none;">

                    <div class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-base-content/50 border-b border-base-content/10 bg-base-200/30">
                        {{ __('Account') }}
                    </div>

                    <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-2 px-4 py-2.5 text-[13px] font-medium text-base-content hover:bg-base-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('Profile') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <button type="submit" @click.prevent="$root.submit();" class="w-full flex items-center gap-2 px-4 py-2.5 text-[13px] font-medium text-error hover:bg-error/10 transition-colors text-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</header>
