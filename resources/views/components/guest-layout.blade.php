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
                <a href="{{ route('event-booking') }}" class="h-full flex items-center text-[13px] font-bold text-primary bg-primary/5 px-4 rounded-full border border-primary/20 hover:bg-primary/10 transition-all">
                    Plan an Event
                </a>
            </nav>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">
                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="hidden rounded-full sm:inline-flex bg-[#25D366] text-white text-[13px] font-bold px-4 py-2 hover:bg-[#20bd5a] transition-all shadow-sm items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/>
                    </svg>
                    Chat
                </a>

                @auth
                    <div class="h-6 w-px border-base-content/10 mx-1 hidden sm:block"></div>
                    
                    @if(Auth::user()->role?->value === 'admin' || Auth::user()->role?->value === 'super_admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-base-content/60 hover:text-primary hover:bg-primary-soft rounded-lg transition-all group" title="Admin Dashboard">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <span class="text-[13px] font-semibold hidden md:inline">Admin</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2 px-3 py-2 text-base-content/60 hover:text-primary hover:bg-primary-soft rounded-lg transition-all group" title="My Bookings">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-[13px] font-semibold hidden md:inline">Dashboard</span>
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-base-content/60 hover:text-error transition-colors" title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
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
            <div class="absolute inset-0 bg-neutral/80 backdrop-blur-sm" 
                 @click="mobileMenuOpen = false"></div>

            <!-- Slide-over panel -->
            <div class="absolute inset-y-0 right-0 w-full max-w-[300px] sm:max-w-sm bg-base-100 shadow-2xl flex flex-col border-l border-base-content/10 transition-transform duration-300 transform"
                 :class="mobileMenuOpen ? 'translate-x-0' : 'translate-x-full'"
                 @click.stop>
                 
                {{-- Decorative background blur --}}
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

                <!-- Header -->
                <div class="flex items-center justify-between p-6 relative z-10 border-b border-base-content/10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white text-[11px] font-bold shadow-md">D&P</div>
                        <span class="text-xl font-bold text-base-content tracking-tight">Menu</span>
                    </div>
                    <button @click="mobileMenuOpen = false" class="p-2.5 bg-base-200 text-base-content/60 hover:text-base-content hover:bg-base-300 rounded-full transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-y-auto px-6 py-8 relative z-10 flex flex-col gap-8">
                    <nav class="space-y-2">
                        @php
                            $mobileNavLinks = [
                                ['route' => 'home', 'label' => 'Home'],
                                ['route' => 'packages.browse', 'label' => 'Our Menu'],
                                ['route' => 'booking.track', 'label' => 'Track Order'],
                                ['route' => 'about', 'label' => 'About Us'],
                                ['route' => 'contact', 'label' => 'Contact'],
                            ];
                            $eventBookingUrl = route('event-booking');
                        @endphp
                        
                        @foreach($mobileNavLinks as $link)
                            <a href="{{ route($link['route']) }}" class="group flex items-center justify-between p-4 rounded-2xl text-[17px] font-bold {{ request()->routeIs($link['route']) ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }} transition-all">
                                {{ $link['label'] }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all {{ request()->routeIs($link['route']) ? 'opacity-100 translate-x-0 text-white' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </nav>

                    <div class="space-y-4">
                        <div class="h-px w-full bg-base-content/10 mt-2 mb-4"></div>
                        <a href="{{ $eventBookingUrl }}" class="flex justify-center items-center gap-2 bg-primary/5 text-primary font-bold py-4 rounded-2xl text-[15px] border border-primary/20 hover:bg-primary/10 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            Plan an Event
                        </a>
                        <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="flex justify-center items-center gap-2 bg-[#25D366] text-white font-bold py-4 rounded-2xl shadow-md text-[15px] hover:bg-[#20bd5a] hover:-translate-y-0.5 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/>
                            </svg>
                            WhatsApp Us
                        </a>

                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="flex justify-center bg-base-content text-base-100 font-bold py-4 rounded-2xl uppercase tracking-wider text-[13px] hover:bg-base-content/90 transition-all">
                                Go to Dashboard
                            </a>
                        @else
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('packages.browse') }}" class="flex justify-center items-center bg-primary text-white font-bold py-4 rounded-2xl text-[14px] shadow-sm hover:bg-primary-hover hover:-translate-y-0.5 transition-all">
                                    Book Now
                                </a>
                                <a href="{{ route('login') }}" class="flex justify-center items-center bg-base-200 border border-base-content/10 text-base-content font-bold py-4 rounded-2xl hover:bg-base-300 transition-all text-[14px]">
                                    Log in
                                </a>
                            </div>
                        @endauth
                    </div>
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
                                <a href="{{ route('packages.show', $pkg) }}" class="text-[13px] text-white/50 hover:text-white hover:translate-x-1 inline-flex items-center gap-1.5 transition-all duration-200">
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
                    <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="inline-flex items-center gap-2.5 bg-[#25D366] text-white text-[13px] font-bold px-5 py-3 rounded-xl hover:bg-[#20bd5a] transition-all shadow-lg shadow-[#25D366]/20 hover:shadow-[#25D366]/30 hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/>
                        </svg>
                        Chat on WhatsApp
                    </a>

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

    <livewire:booking.cart-sidebar />
</body>

</html>
