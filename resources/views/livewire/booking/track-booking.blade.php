<div class="bg-base-200 min-h-screen">

    {{-- Hero header --}}
    <div class="bg-primary relative overflow-hidden py-12 lg:py-16">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]" aria-hidden="true"></div>
        <div class="absolute top-0 right-0 size-[400px] bg-white/6 blur-[100px] rounded-full -translate-y-1/2 translate-x-1/3" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 size-[300px] bg-black/15 blur-[80px] rounded-full translate-y-1/2 -translate-x-1/4" aria-hidden="true"></div>
        <div class="absolute top-1/2 right-1/4 size-[200px] bg-white/4 blur-[60px] rounded-full -translate-y-1/2" aria-hidden="true"></div>
        {{-- Floating icons --}}
        <div class="absolute top-4 left-8 text-white/8 hidden lg:block -rotate-6" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.9"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
        </div>
        <div class="absolute bottom-4 right-8 text-white/6 hidden lg:block rotate-12" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        </div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 bg-white/15 text-white text-[11px] font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                Booking Tracker
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-white tracking-tight leading-tight mb-3">
                Track your booking
            </h1>
            <p class="text-[15px] text-white/60 font-medium max-w-md mx-auto">
                Enter your booking reference and phone number to check your order status.
            </p>
        </div>
    </div>

    {{-- Main content --}}
    <div class="container mx-auto max-w-lg px-4 py-10 lg:py-16">

        {{-- Form card --}}
        <div class="bg-base-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8 space-y-6">
                @if($message)
                    <div class="bg-error/5 border border-error/20 p-4 rounded-xl flex items-start gap-3">
                        <div class="size-8 bg-error/10 rounded-lg shrink-0 flex items-center justify-center mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <p class="text-[13px] font-medium text-base-content leading-snug">{{ $message }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="track" class="space-y-5">
                    <x-app.input
                        name="reference"
                        type="text"
                        label="Booking Reference"
                        wire:model="reference"
                        placeholder="e.g. CAT-2026-00001"
                    />

                    <x-app.input
                        name="phone"
                        type="tel"
                        label="Phone Number"
                        wire:model="phone"
                        placeholder="024 XXX XXXX"
                    />

                    <div class="pt-2">
                        <x-ui.button type="submit" variant="primary" size="lg" class="w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Track my Booking
                        </x-ui.button>
                    </div>
                </form>
            </div>

            {{-- Help strip inside card --}}
            <div class="bg-base-200/50 border-t border-base-content/8 px-6 sm:px-8 py-4 flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/40 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-[12px] text-base-content/50 font-medium leading-relaxed">
                    Your reference number (e.g. <span class="font-bold text-base-content/70">CAT-2026-00001</span>) was sent to you by SMS and email after booking.
                </p>
            </div>
        </div>

        {{-- Bottom links --}}
        <div class="mt-6 flex items-center justify-center gap-6 text-[12px] font-medium text-base-content/40">
            <a href="{{ route('packages.browse') }}" class="hover:text-primary transition-colors">Browse packages</a>
            <span>·</span>
            <a href="{{ route('contact') }}" class="hover:text-primary transition-colors">Contact support</a>
            <span>·</span>
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Go home</a>
        </div>
    </div>
</div>
