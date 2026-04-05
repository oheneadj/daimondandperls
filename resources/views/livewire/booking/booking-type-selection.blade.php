<div class="bg-base-200 min-h-screen py-10 lg:py-24 px-4 overflow-hidden relative">
    {{-- Decorative background --}}
    <div class="absolute top-0 right-0 size-[500px] bg-primary/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/3 -z-10"></div>
    <div class="absolute bottom-0 left-0 size-[400px] bg-accent/5 blur-[120px] rounded-full translate-y-1/2 -translate-x-1/3 -z-10"></div>

    <div class="container mx-auto max-w-4xl">
        {{-- Header --}}
        <div class="text-center mb-14 animate-fade-in">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                {{ $cartCount }} {{ str('item')->plural($cartCount) }} selected
            </div>
            <h1 class="text-3xl lg:text-5xl font-semibold text-base-content tracking-tight mb-4">
                {{ __('Choose the option that best fits your needs.') }}
            </h1>
           
        </div>

        {{-- Selection Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">

            {{-- Simple Meal Card --}}
            <button
                wire:click="selectMeal"
                class="group relative flex flex-col h-full bg-base-100 border-2 border-base-content/10 hover:border-primary rounded-[28px] p-8 lg:p-10 text-left transition-all duration-300 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-primary/20"
            >
                <div class="absolute top-6 right-6 size-10 rounded-full bg-primary/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>

                <div class="size-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-6 group-hover:bg-primary/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>

                <h3 class="text-2xl font-bold text-base-content mb-3 group-hover:text-primary transition-colors">
                    {{ __('Simple Meal') }}
                </h3>
                <p class="text-[14px] text-base-content/50 font-medium leading-relaxed mb-8">
                    {{ __('Order your selected food packages for delivery or pickup. Pay online and get it sorted quickly.') }}
                </p>

                <div class="mt-auto pt-4 w-full">
                    <span class="inline-flex items-center justify-center w-full py-4 px-6 rounded-xl bg-primary text-white font-black uppercase tracking-widest text-[13px] transition-all duration-300 shadow-lg shadow-primary/20 group-hover:bg-primary/90 group-hover:shadow-primary/40 group-hover:-translate-y-1">
                        {{ __('Checkout Now') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 ml-2 transition-transform group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </span>
                </div>
            </button>

            {{-- Event Booking Card --}}
            <button
                wire:click="selectEvent"
                class="group relative flex flex-col h-full bg-base-100 border-2 border-base-content/10 hover:border-success rounded-[28px] p-8 lg:p-10 text-left transition-all duration-300 hover:shadow-xl hover:shadow-success/5 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-success/20"
            >
                <div class="absolute top-6 right-6 size-10 rounded-full bg-success/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>

                <div class="size-16 rounded-2xl bg-success/10 flex items-center justify-center mb-6 group-hover:bg-success/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>

                <h3 class="text-2xl font-bold text-base-content mb-3 group-hover:text-success transition-colors">
                    {{ __('Event Catering') }}
                </h3>
                <p class="text-[14px] text-base-content/50 font-medium leading-relaxed mb-8">
                    {{ __('Planning a wedding, birthday, or corporate event? Tell us about it and we\'ll prepare a tailored quote for you.') }}
                </p>

                <div class="mt-auto pt-4 w-full">
                    <span class="inline-flex items-center justify-center w-full py-4 px-6 rounded-xl bg-[#18542A] text-white font-black uppercase tracking-widest text-[13px] transition-all duration-300 shadow-lg shadow-[#18542A]/20 group-hover:bg-[#18542A]/90 group-hover:shadow-[#18542A]/40 group-hover:-translate-y-1">
                        {{ __('Request Quote') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 ml-2 transition-transform group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </span>
                </div>
            </button>
        </div>

        {{-- Selected items preview --}}
        <div class="mt-12 max-w-3xl mx-auto">
            <div class="bg-base-100 border border-base-content/10 rounded-2xl p-5">
                <div class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest mb-3">{{ __('Your selected packages') }}</div>
                <div class="flex flex-wrap gap-2">
                    @foreach($cartItems as $item)
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-base-200 border border-base-content/10 text-[12px] font-bold text-base-content">
                            {{ $item['package']->name }}
                            <span class="text-base-content/40">×{{ $item['quantity'] }}</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Back link --}}
        <div class="text-center mt-10">
            <a href="{{ route('packages.browse') }}" class="inline-flex items-center gap-2 text-[12px] font-bold text-base-content/40 hover:text-primary transition-colors uppercase tracking-widest">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                {{ __('Back to menu') }}
            </a>
        </div>
    </div>
</div>
