<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? __('Welcome') }} - {{ config('app.name', 'Catering App') }}</title>

    @include('partials.head')
</head>

<body class="bg-base-200 text-dp-text-body min-h-screen  antialiased overflow-x-hidden flex flex-col selection:bg-primary-soft selection:text-primary">
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
            <nav class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-[14px] font-medium transition-colors {{ request()->routeIs('home') ? 'text-primary' : 'text-base-content/60 hover:text-base-content' }}">Home</a>
                <a href="{{ route('packages.browse') }}" class="text-[14px] font-medium transition-colors {{ request()->routeIs('packages.browse') ? 'text-primary' : 'text-base-content/60 hover:text-base-content' }}">Book Selection</a>
                <a href="{{ route('booking.track') }}" class="text-[14px] font-medium transition-colors {{ request()->routeIs('booking.track') ? 'text-primary' : 'text-base-content/60 hover:text-base-content' }}">Track My Booking</a>
                <a href="{{ route('about') }}" class="text-[14px] font-medium transition-colors {{ request()->routeIs('about') ? 'text-primary' : 'text-base-content/60 hover:text-base-content' }}">About Our Story</a>
                <a href="{{ route('contact') }}" class="text-[14px] font-medium transition-colors {{ request()->routeIs('contact') ? 'text-primary' : 'text-base-content/60 hover:text-base-content' }}">Contact Concierge</a>
                <a href="{{ route('privacy') }}" class="text-[14px] font-medium transition-colors {{ request()->routeIs('privacy') ? 'text-primary' : 'text-base-content/60 hover:text-base-content' }}">Privacy</a>
                <a href="{{ route('terms') }}" class="text-[14px] font-medium transition-colors {{ request()->routeIs('terms') ? 'text-primary' : 'text-base-content/60 hover:text-base-content' }}">Terms</a>
            </nav>

                <a href="{{ route('packages.browse') }}" class="hidden lg:inline-flex bg-base-100 border border-primary text-primary text-[13px] font-semibold px-5 py-2.5 rounded-lg hover:bg-base-200 transition-all shadow-sm">
                    View Packages
                </a>
                <a href="https://wa.me/233244203181" target="_blank" class="hidden sm:inline-flex bg-[#25D366] text-white text-[13px] font-semibold px-5 py-2.5 rounded-lg hover:bg-[#20bd5a] transition-all shadow-sm items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/>
                    </svg>
                    Chat
                </a>

                @auth
                    <div class="h-6 w-px border-base-content/10 mx-2 hidden sm:block"></div>
                    
                    @if(Auth::user()->role?->value === 'admin' || Auth::user()->role?->value === 'super_admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-base-content/60 hover:text-primary hover:bg-primary-soft rounded-lg transition-all group" title="Admin Dashboard">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <span class="text-[13px] font-semibold hidden md:inline">Admin</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-base-content/60 hover:text-primary hover:bg-primary-soft rounded-lg transition-all group" title="My Bookings">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-[13px] font-semibold hidden md:inline">My Dashboard</span>
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
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 text-[14px] font-bold text-base-content hover:text-primary transition-all px-4 py-2 border border-base-content/10 rounded-xl bg-base-200 hover:bg-base-100 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log in
                    </a>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenuOpen = true" class="lg:hidden p-2 text-base-content hover:bg-base-200-mid rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Slide-over -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed inset-0 z-50 lg:hidden" 
             style="display: none;">
            
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>

            <div class="fixed inset-y-0 right-0 w-full max-w-xs bg-base-100 shadow-dp-lg flex flex-col p-6">
                <div class="flex items-center justify-between mb-8 pb-6 border-b border-base-content/10">
                    <div class="flex items-center gap-3">
                        <div class="size-8 bg-primary rounded-full flex items-center justify-center text-white text-[10px]">D&P</div>
                        <span class=" text-lg font-semibold text-base-content">Diamonds & Pearls</span>
                    </div>
                    <button @click="mobileMenuOpen = false" class="p-2 text-base-content/60 hover:text-base-content">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 space-y-4">
                    <a href="{{ route('home') }}" class="block p-3 rounded-lg text-lg  font-medium {{ request()->routeIs('home') ? 'text-primary bg-primary-soft' : 'text-base-content hover:bg-primary-soft hover:text-primary' }} transition-all">Home</a>
                    <a href="{{ route('packages.browse') }}" class="block p-3 rounded-lg text-lg  font-medium {{ request()->routeIs('packages.browse') ? 'text-primary bg-primary-soft' : 'text-base-content hover:bg-primary-soft hover:text-primary' }} transition-all">Book Selection</a>
                    <a href="{{ route('booking.track') }}" class="block p-3 rounded-lg text-lg  font-medium {{ request()->routeIs('booking.track') ? 'text-primary bg-primary-soft' : 'text-base-content hover:bg-primary-soft hover:text-primary' }} transition-all">Track My Booking</a>
                    <a href="{{ route('about') }}" class="block p-3 rounded-lg text-lg  font-medium {{ request()->routeIs('about') ? 'text-primary bg-primary-soft' : 'text-base-content hover:bg-primary-soft hover:text-primary' }} transition-all">About Our Story</a>
                    <a href="{{ route('contact') }}" class="block p-3 rounded-lg text-lg  font-medium {{ request()->routeIs('contact') ? 'text-primary bg-primary-soft' : 'text-base-content hover:bg-primary-soft hover:text-primary' }} transition-all">Contact Concierge</a>
                    <a href="{{ route('privacy') }}" class="block p-3 rounded-lg text-lg  font-medium {{ request()->routeIs('privacy') ? 'text-primary bg-primary-soft' : 'text-base-content hover:bg-primary-soft hover:text-primary' }} transition-all">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="block p-3 rounded-lg text-lg  font-medium {{ request()->routeIs('terms') ? 'text-primary bg-primary-soft' : 'text-base-content hover:bg-primary-soft hover:text-primary' }} transition-all">Terms of Service</a>
                    
                    <div class="pt-6 mt-6 border-t border-base-content/10">
                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="flex justify-center bg-primary text-white font-bold py-4 rounded-xl shadow-md uppercase tracking-widest text-[11px]">
                                Go to Dashboard
                            </a>
                        @else
                            <div class="space-y-3">
                                <a href="{{ route('packages.browse') }}" class="flex justify-center bg-primary text-white font-bold py-4 rounded-xl shadow-md uppercase tracking-widest text-[11px]">
                                    Book Now
                                </a>
                                <a href="{{ route('login') }}" class="flex justify-center bg-base-100 text-base-content border border-base-content/10 font-bold py-4 rounded-xl hover:bg-base-200 transition-all">
                                    Log in
                                </a>
                            </div>
                        @endauth
                    </div>
                </nav>

                <div class="mt-auto pt-6 border-t border-base-content/10 flex items-center gap-3">
                    <div class="size-2 rounded-full bg-primary animate-pulse"></div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-base-content/60">Premium Catering Hub</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-dp-text-primary py-12 lg:py-16">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
                <!-- Brand Col -->
                <div class="space-y-6">
                    <div class=" text-2xl font-semibold text-white"> Diamonds & Pearls Catering </div>
                    <p class="text-[13px] text-white/45 leading-relaxed max-w-xs mx-auto md:mx-0">
                        Authentic Ghanaian cuisine delivered with care and professionalism. Serving Accra and surrounding areas since 2018.
                    </p>
                    <div class="pt-4 flex flex-col gap-2 text-[12px] text-white/40 font-medium">
                        <div class="flex items-center justify-center md:justify-start gap-2">
                            <span class="text-white/20 font-bold uppercase tracking-widest text-[10px]">Office:</span> P.O. Box 18123, Accra
                        </div>
                        <div class="flex items-center justify-center md:justify-start gap-2">
                            <span class="text-white/20 font-bold uppercase tracking-widest text-[10px]">Call:</span> 244 203 181
                        </div>
                    </div>
                </div>

                <!-- Packages Col -->
                <div class="space-y-6">
                    <h6 class="text-[11px] font-bold text-white/30 uppercase tracking-[0.12em]">Packages</h6>
                    <ul class="space-y-4">
                        @php
                            $footerPackages = \App\Models\Package::where('is_active', true)->ordered()->limit(3)->get();
                        @endphp
                        @foreach($footerPackages as $pkg)
                            <li><a href="{{ route('packages.show', $pkg) }}" class="text-[13px] text-white/55 hover:text-white transition-colors">{{ $pkg->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('packages.browse') }}" class="text-[13px] text-white/55 hover:text-white transition-colors font-semibold italic">View All Selection</a></li>
                    </ul>
                </div>

                <!-- Quick Links Col -->
                <div class="space-y-6">
                    <h6 class="text-[11px] font-bold text-white/30 uppercase tracking-[0.12em]">Quick Links</h6>
                    <ul class="space-y-4">
                        <li><a href="{{ route('packages.browse') }}" class="text-[13px] text-white/55 hover:text-white transition-colors">Book Selection</a></li>
                        <li><a href="{{ route('about') }}" class="text-[13px] text-white/55 hover:text-white transition-colors">About Our Story</a></li>
                        <li><a href="{{ route('contact') }}" class="text-[13px] text-white/55 hover:text-white transition-colors">Contact Concierge</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-[13px] text-white/55 hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}" class="text-[13px] text-white/55 hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="mt-16 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-[12px] text-white/30">
                <p>© {{ date('Y') }} Diamonds & Pearls Catering Services. All rights reserved.</p>
                <a href="mailto:graceayesu@yahoo.com" class="hover:text-white transition-colors">graceayesu@yahoo.com</a>
            </div>
        </div>
    </footer>

    <livewire:booking.cart-sidebar />
</body>

</html>
