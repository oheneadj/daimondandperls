@props([
    'title' => null
])

<header {{ $attributes->merge(['class' => 'h-16 bg-white border-b border-base-content/10 shadow-sm sticky top-0 z-10 flex items-center justify-between px-4 lg:px-10']) }}>
    <!-- Left: Mobile Toggle & Page Title -->
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Toggle -->
        <button @click="mobileMenuOpen = true" class="p-2 lg:hidden text-base-content hover:bg-base-200-mid rounded-md transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <h1 class="text-dp-2xl text-base-content">
            {{ $title }}
        </h1>
    </div>

    <!-- Right: Tools & Profile -->
    <div class="flex items-center gap-6">
        <!-- Search -->
        <div class="hidden md:block relative w-[220px]">
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" placeholder="Search..." class="w-full bg-base-200 border-base-content/10 text-[13px] rounded-md pl-9 pr-3 py-2 focus:ring-2 focus:ring-dp-rose-soft focus:border-dp-rose transition-all outline-none">
        </div>

        <!-- Notifications -->
        @auth
            @livewire('admin.notifications.notification-bell')
        @endauth

        <!-- Admin Profile -->
        @auth
            <div class="flex items-center gap-3 pl-4 border-l border-base-content/10">
                <div class="hidden sm:flex flex-col items-end">
                    <span class=" text-[13px] font-medium text-base-content leading-tight">{{ auth()->user()->name }}</span>
                    <span class=" text-[11px] text-base-content/60 leading-tight uppercase tracking-wider">{{ auth()->user()->role?->value ?? 'Administrator' }}</span>
                </div>
                
                <div x-data="{ userMenuOpen: false }" class="relative">
                    <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="flex items-center">
                        <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white shadow-sm hover:ring-2 hover:ring-dp-rose-soft transition-all">
                            <span class=" text-[13px] font-semibold">{{ auth()->user()->initials() }}</span>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="userMenuOpen" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 top-full mt-2 w-52 bg-base-100 border border-base-content/10 shadow-dp-lg rounded-xl py-1.5 z-50 overflow-hidden"
                        style="display: none;">

                        <div class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-base-content/60 opacity-50">Account</div>

                        <a href="{{ route('profile.edit') }}" wire:navigate
                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-base-200-mid text-[13px] font-medium text-base-content transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('My Profile') }}
                        </a>

                        <div class="h-px border-base-content/10 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-error/10 text-error text-[13px] font-medium transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                {{ __('Log out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</header>
