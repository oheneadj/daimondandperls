<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? __('Welcome') }} - {{ config('app.name', 'Catering App') }}</title>

    @include('partials.head')
</head>

<body class="bg-base-200 text-dp-text-body min-h-screen  antialiased overflow-x-hidden flex flex-col selection:bg-primary-soft selection:text-primary">
    @php
        $whatsappNumber = \App\Models\Setting::where('key', 'business_whatsapp')->value('value') ?: '233244203181';
    @endphp
    <!-- Navbar -->
    <header class="bg-base-100/80 backdrop-blur-md sticky top-0 z-50 border-b border-base-content/10" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-4 lg:px-8 flex justify-between items-center h-[68px]">
            <!-- Brand -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="size-9 bg-primary rounded-full flex items-center justify-center text-white text-sm transition-transform group-hover:scale-110">
                    <span class=" font-medium">D&P</span>
                </div>
                <div class="flex flex-col leading-none">
                    <span class=" text-xl font-semibold text-base-content">Diamonds & Pearls</span>
                    <span class="text-[10px] text-base-content/60 uppercase tracking-[0.08em] font-bold">Catering Services</span>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-6 h-full">
                <a href="{{ route('home') }}" class="relative h-full flex items-center transition-colors {{ request()->routeIs('home') ? 'text-primary' : 'text-base-content/70 hover:text-primary' }}">
                    Home
                    @if(request()->routeIs('home')) <span class="absolute bottom-0 left-0 w-full h-[3px] bg-primary rounded-t-full"></span> @endif
                </a>
                <a href="{{ route('packages.browse') }}" class="relative h-full flex items-center transition-colors {{ request()->routeIs('packages.browse') ? 'text-primary' : 'text-base-content/70 hover:text-primary' }}">
                    Our Menu
                    @if(request()->routeIs('packages.browse')) <span class="absolute bottom-0 left-0 w-full h-[3px] bg-primary rounded-t-full"></span> @endif
                </a>
                <a href="{{ route('booking.track') }}" class="relative h-full flex items-center transition-colors {{ request()->routeIs('booking.track') ? 'text-primary' : 'text-base-content/70 hover:text-primary' }}">
                    Track Order
                    @if(request()->routeIs('booking.track')) <span class="absolute bottom-0 left-0 w-full h-[3px] bg-primary rounded-t-full"></span> @endif
                </a>
                <a href="{{ route('about') }}" class="relative h-full flex items-center transition-colors {{ request()->routeIs('about') ? 'text-primary' : 'text-base-content/70 hover:text-primary' }}">
                    About Us
                    @if(request()->routeIs('about')) <span class="absolute bottom-0 left-0 w-full h-[3px] bg-primary rounded-t-full"></span> @endif
                </a>
                <a href="{{ route('contact') }}" class="relative h-full flex items-center transition-colors {{ request()->routeIs('contact') ? 'text-primary' : 'text-base-content/70 hover:text-primary' }}">
                    Contact
                    @if(request()->routeIs('contact')) <span class="absolute bottom-0 left-0 w-full h-[3px] bg-primary rounded-t-full"></span> @endif
                </a>
               
            </nav>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">

                 <a href="{{ route('event-booking') }}" class="h-full hidden sm:flex items-center text-[13px] font-bold text-white bg-green-500 px-4 py-2 rounded-full border border-green-500/20 hover:bg-green-600 transition-all">
                    Plan an Event
                </a>
                @auth
                    <div class="h-6 w-px border-base-content/10 mx-1 hidden sm:block"></div>
                    
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 px-2 py-1.5 text-base-content/80 hover:text-primary hover:bg-primary/5 rounded-full transition-all border border-transparent hover:border-primary/10">
                            <div class="size-7 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-[12px]">
                                {{ method_exists(Auth::user(), 'initials') ? Auth::user()->initials() : substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="text-[13px] font-bold hidden sm:block">{{ method_exists(Auth::user(), 'displayName') ? explode(' ', Auth::user()->displayName())[0] : explode(' ', Auth::user()->name)[0] }}</span>
                            <svg class="size-3.5 text-base-content/40 transition-transform duration-200" :class="{'rotate-180': open}" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-transition.opacity.duration.200ms
                             class="absolute right-0 mt-3 w-56 bg-base-100 rounded-2xl shadow-xl shadow-base-content/5 border border-base-content/10 py-2 z-50 flex flex-col"
                             style="display: none;">
                            
                            <div class="px-4 py-3 border-b border-base-content/5 mb-1 bg-base-200/30 rounded-t-2xl -mt-2">
                                <p class="text-[14px] font-bold text-base-content truncate">{{ method_exists(Auth::user(), 'displayName') ? Auth::user()->displayName() : Auth::user()->name }}</p>
                                <p class="text-[11px] font-medium text-base-content/50 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            @if(Auth::user()->role?->value === 'admin' || Auth::user()->role?->value === 'super_admin')
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-[13px] font-semibold text-base-content/70 hover:text-primary hover:bg-primary/5 transition-colors mx-1 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    Admin Dashboard
                                </a>
                            @else
                                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-2 text-[13px] font-semibold text-base-content/70 hover:text-primary hover:bg-primary/5 transition-colors mx-1 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    My Bookings
                                </a>
                            @endif

                            <div class="h-px bg-base-content/5 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}" class="block px-1">
                                @csrf
                                <button type="submit" class="flex items-center w-full gap-3 px-3 py-2 text-[13px] font-semibold text-error/80 hover:text-error hover:bg-error/10 transition-colors rounded-xl text-left">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Log out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 text-[13px] font-bold text-white bg-primary hover:bg-primary/90 transition-all px-4 py-2 rounded-full shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log in
                    </a>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenuOpen = true" class="lg:hidden p-2 text-base-content hover:bg-base-200-mid rounded-xl transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            </div>
        </div>

        <!-- Mobile Menu Slide-over -->
        <template x-teleport="body">
            <div class="fixed inset-0 z-[100] lg:hidden"
                 x-show="mobileMenuOpen"
                 x-transition.opacity.duration.300ms
                 style="display: none;"
                 @keydown.escape.window="mobileMenuOpen = false">

                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>

                <!-- Slide-over panel -->
                <div class="absolute inset-y-0 right-0 w-[85vw] max-w-[340px] bg-base-100 shadow-2xl flex flex-col transition-transform duration-300 transform"
                     :class="mobileMenuOpen ? 'translate-x-0' : 'translate-x-full'"
                     @click.stop>

                    {{-- Red hero header band --}}
                    <div class="relative bg-primary overflow-hidden px-6 pt-6 pb-8">
                        <div class="absolute top-0 right-0 size-40 bg-white/8 rounded-full blur-2xl -translate-y-1/2 translate-x-1/4"></div>
                        <div class="absolute bottom-0 left-0 size-28 bg-black/15 rounded-full blur-xl translate-y-1/2 -translate-x-1/4"></div>

                        <div class="relative z-10 flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="size-9 bg-white/20 rounded-full flex items-center justify-center text-white text-[11px] font-bold">D&P</div>
                                <div>
                                    <div class="text-[14px] font-bold text-white leading-tight">Diamonds & Pearls</div>
                                    <div class="text-[10px] text-white/60 font-medium uppercase tracking-widest">Catering Services</div>
                                </div>
                            </div>
                            <button @click="mobileMenuOpen = false" class="size-8 flex items-center justify-center bg-white/15 text-white hover:bg-white/25 rounded-lg transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Quick action in header --}}
                        <a href="{{ route('event-booking') }}" class="relative z-10 flex items-center gap-2.5 bg-white/15 hover:bg-white/25 text-white text-[13px] font-bold px-4 py-3 rounded-xl transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                            Plan an Event
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <!-- Nav links -->
                    <div class="flex-1 overflow-y-auto px-4 py-4">
                        @php
                            $mobileNavLinks = [
                                ['route' => 'home',           'label' => 'Home',        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                                ['route' => 'packages.browse','label' => 'Our Menu',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'],
                                ['route' => 'booking.track', 'label' => 'Track Order',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>'],
                                ['route' => 'about',         'label' => 'About Us',     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                                ['route' => 'contact',       'label' => 'Contact',      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                            ];
                        @endphp

                        <nav class="space-y-1">
                            @foreach($mobileNavLinks as $link)
                                @php $active = request()->routeIs($link['route']); @endphp
                                <a href="{{ route($link['route']) }}"
                                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-[15px] font-semibold transition-all {{ $active ? 'bg-primary/8 text-primary' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                                    <span class="size-8 rounded-lg flex items-center justify-center shrink-0 {{ $active ? 'bg-primary text-white' : 'bg-base-200 text-base-content/50' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $link['icon'] !!}</svg>
                                    </span>
                                    {{ $link['label'] }}
                                    @if($active)
                                        <span class="ml-auto size-1.5 rounded-full bg-primary"></span>
                                    @endif
                                </a>
                            @endforeach
                        </nav>

                        {{-- Divider --}}
                        <div class="my-4 border-t border-base-content/8"></div>

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank"
                           class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-[15px] font-semibold text-base-content/70 hover:bg-base-200 hover:text-base-content transition-all">
                            <span class="size-8 rounded-lg bg-[#25D366]/15 text-[#25D366] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/></svg>
                            </span>
                            WhatsApp Us
                        </a>
                    </div>

                    <!-- Footer CTA -->
                    <div class="px-4 pb-6 pt-2 border-t border-base-content/8 space-y-2.5">
                        @auth
                            <a href="{{ route('admin.dashboard') }}"
                               class="flex items-center justify-center gap-2 w-full bg-base-content text-base-100 font-bold py-3.5 rounded-xl text-[14px] hover:bg-base-content/90 transition-all">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('packages.browse') }}"
                               class="flex items-center justify-center gap-2 w-full bg-primary text-white font-bold py-3.5 rounded-xl text-[14px] shadow-sm hover:bg-primary/90 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                Browse & Book
                            </a>
                            <a href="{{ route('login') }}"
                               class="flex items-center justify-center w-full bg-base-200 text-base-content font-bold py-3.5 rounded-xl hover:bg-base-300 transition-all text-[14px]">
                                Log in to your account
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </template>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    @php
        $isBookingPage = request()->routeIs('checkout', 'event-booking', 'booking.payment', 'booking.confirmation', 'booking.select-type');
    @endphp

    @if($isBookingPage)
        {{-- Minimal footer for booking/checkout pages — business details only --}}
        <footer class="relative bg-neutral text-white overflow-hidden">
            <div class="h-1 w-full bg-gradient-to-r from-primary via-accent to-success"></div>
            <div class="container mx-auto px-4 lg:px-8 py-10">
                <div class="flex flex-col items-center text-center space-y-5">
                    {{-- Brand --}}
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                        <div class="size-9 bg-primary rounded-full flex items-center justify-center text-white text-xs font-bold transition-transform group-hover:scale-110 shadow-lg shadow-primary/30">
                            D&P
                        </div>
                        <div class="flex flex-col leading-none text-left">
                            <span class="text-lg font-semibold text-white">Diamonds & Pearls</span>
                            <span class="text-[10px] text-white/40 uppercase tracking-[0.1em] font-bold">Catering Services</span>
                        </div>
                    </a>

                    {{-- Business contact details only --}}
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6 text-[12px] text-white/50 font-medium">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white/25 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            P.O. Box 18123, Accra
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white/25 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            +233 244 203 181
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white/25 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:graceayesu@yahoo.com" class="hover:text-white transition-colors">graceayesu@yahoo.com</a>
                        </div>
                    </div>

                    {{-- Copyright --}}
                    <p class="text-[11px] text-white/25 font-medium pt-2">© {{ date('Y') }} Diamonds & Pearls Catering Services. All rights reserved.</p>
                </div>
            </div>
        </footer>
    @else
        {{-- Full footer for all other pages --}}
        <footer class="relative bg-neutral text-white overflow-hidden">
            {{-- Gradient accent strip --}}
            <div class="h-1 w-full bg-gradient-to-r from-primary via-accent to-success"></div>

            <div class="container mx-auto px-4 lg:px-8 pt-16 pb-12">
                {{-- Main Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">

                    {{-- Brand Column --}}
                    <div class="sm:col-span-2 lg:col-span-1 space-y-6 text-center sm:text-left">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                            <div class="size-10 bg-primary rounded-full flex items-center justify-center text-white text-xs font-bold transition-transform group-hover:scale-110 shadow-lg shadow-primary/30">
                                D&P
                            </div>
                            <div class="flex flex-col leading-none">
                                <span class="text-lg font-semibold text-white">Diamonds & Pearls</span>
                                <span class="text-[10px] text-white/40 uppercase tracking-[0.1em] font-bold">Catering Services</span>
                            </div>
                        </a>
                        <p class="text-[13px] text-white/80 leading-relaxed max-w-xs mx-auto sm:mx-0">
                            Authentic Ghanaian cuisine delivered with care and professionalism. Serving Accra and surrounding areas since 2018.
                        </p>
                        {{-- Contact details --}}
                        <div class="flex flex-col gap-3 text-[12px] text-white/45 font-medium">
                            <div class="flex items-center justify-center sm:justify-start gap-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white/25 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                P.O. Box 18123, Accra
                            </div>
                            <div class="flex items-center justify-center sm:justify-start gap-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white/25 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                +233 244 203 181
                            </div>
                            <div class="flex items-center justify-center sm:justify-start gap-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white/25 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a href="mailto:graceayesu@yahoo.com" class="hover:text-white transition-colors">graceayesu@yahoo.com</a>
                            </div>
                        </div>
                    </div>

                    {{-- Packages Column --}}
                    <div class="space-y-5 text-center sm:text-left">
                        <h6 class="text-[11px] font-bold text-white/30 uppercase tracking-[0.15em]">Our Packages</h6>
                        <ul class="space-y-3">
                            @php
                                $footerPackages = \App\Models\Package::where('is_active', true)->ordered()->limit(4)->get();
                            @endphp
                            @foreach($footerPackages as $pkg)
                                <li>
                                    <a href="{{ route('packages.browse', ['categoryId' => $pkg->category_id]) }}" class="text-[13px] text-white/50 hover:text-white hover:translate-x-1 inline-flex items-center gap-1.5 transition-all duration-200">
                                        <span class="size-1 rounded-full bg-primary/60 shrink-0"></span>
                                        {{ $pkg->name }}
                                    </a>
                                </li>
                            @endforeach
                            <li class="pt-1">
                                <a href="{{ route('packages.browse') }}" class="text-[13px] text-primary font-semibold hover:text-primary/80 inline-flex items-center gap-1 transition-colors">
                                    View All Selection
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Quick Links Column --}}
                    <div class="space-y-5 text-center sm:text-left">
                        <h6 class="text-[11px] font-bold text-white/30 uppercase tracking-[0.15em]">Quick Links</h6>
                        <ul class="space-y-3">
                            <li><a href="{{ route('packages.browse') }}" class="text-[13px] text-white/50 hover:text-white transition-colors">Our Menu</a></li>
                            <li><a href="{{ route('booking.track') }}" class="text-[13px] text-white/50 hover:text-white transition-colors">Track Order</a></li>
                            <li><a href="{{ route('about') }}" class="text-[13px] text-white/50 hover:text-white transition-colors">About Us</a></li>
                            <li><a href="{{ route('contact') }}" class="text-[13px] text-white/50 hover:text-white transition-colors">Contact</a></li>
                            <li><a href="{{ route('privacy') }}" class="text-[13px] text-white/50 hover:text-white transition-colors">Privacy Policy</a></li>
                            <li><a href="{{ route('terms') }}" class="text-[13px] text-white/50 hover:text-white transition-colors">Terms of Service</a></li>
                        </ul>
                    </div>

                    {{-- Connect Column --}}
                    <div class="space-y-5 text-center sm:text-left">
                        <h6 class="text-[11px] font-bold text-white/30 uppercase tracking-[0.15em]">Get In Touch</h6>
                        <p class="text-[13px] text-white/45 leading-relaxed">
                            Ready to plan your next event? Reach out to us on WhatsApp for a quick response.
                        </p>
                        <x-ui.whatsapp-button label="Chat on WhatsApp" class="shadow-lg shadow-[#25D366]/20 hover:shadow-[#25D366]/30 hover:-translate-y-0.5" />

                        {{-- Social links --}}
                        <div class="flex items-center justify-center sm:justify-start gap-3 pt-2">
                            <a href="https://www.instagram.com" target="_blank" class="size-9 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-white/40 hover:text-white transition-all" title="Instagram">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            <a href="https://www.facebook.com" target="_blank" class="size-9 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-white/40 hover:text-white transition-all" title="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="https://www.tiktok.com" target="_blank" class="size-9 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-white/40 hover:text-white transition-all" title="TikTok">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Footer Bottom --}}
                <div class="mt-14 pt-8 border-t border-white/[0.06]">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-[12px] text-white/30">
                        <p>© {{ date('Y') }} Diamonds & Pearls Catering Services. All rights reserved.</p>
                        <div class="flex items-center gap-1.5">
                            <span class="size-1.5 rounded-full bg-success animate-pulse"></span>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-white/25">Premium Ghanaian Catering</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Background decorative elements --}}
            <div class="absolute top-0 right-0 w-96 h-96 bg-primary/[0.03] rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/[0.02] rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>
        </footer>
    @endif

    <!-- Floating WhatsApp Widget -->
    <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank"
       class="fixed bottom-6 left-6 z-[100] group flex items-center justify-center size-[60px] bg-[#25D366] text-white rounded-full shadow-2xl shadow-[#25D366]/40 hover:scale-110 hover:shadow-[#25D366]/50 transition-all duration-300 animate-in slide-in-from-bottom-10 fade-in zoom-in"
       aria-label="Chat on WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-8 group-hover:rotate-12 transition-transform duration-300" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/>
        </svg>
        <span class="absolute -top-1 -right-1 flex h-3 w-3">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#25D366] opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3 w-3 bg-[#25D366] border-2 border-white"></span>
        </span>
    </a>

    <livewire:booking.cart-sidebar />
</body>

</html>
