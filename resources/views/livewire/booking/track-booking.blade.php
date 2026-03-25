<div class="bg-base-200 min-h-screen py-10 lg:py-20 px-4">
    <div class="container mx-auto max-w-xl">
        <div class="bg-base-100 border border-base-content/10 rounded-[32px] overflow-hidden shadow-md">
            {{-- Header --}}
            <div class="p-8 lg:p-12 text-center border-b border-base-content/10 bg-base-200/30">
                <div class="size-16 bg-primary rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm rotate-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h1 class=" text-3xl font-semibold text-base-content mb-2">Track Your Booking</h1>
                <p class="text-base-content/60 text-[14px] font-medium leading-relaxed">
                    Lost your way? Enter your reference and phone number to resume your booking or payment.
                </p>
            </div>

            {{-- Form --}}
            <div class="p-8 lg:p-12 space-y-8">
                @if($message)
                    <div class="bg-dp-danger/5 border border-dp-danger/20 p-5 rounded-2xl flex items-start gap-4 animate-fade-in text-left">
                        <div class="bg-white size-8 rounded-full shadow-sm shrink-0 flex items-center justify-center mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <p class="text-[14px] font-medium text-base-content leading-snug">{{ $message }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="track" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Booking Reference</label>
                        <input type="text" wire:model="reference" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-2xl transition-all text-[15px] font-medium placeholder:text-dp-text-disabled" placeholder="e.g. CAT-2026-00001">
                        @error('reference') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Phone Number</label>
                        <input type="tel" wire:model="phone" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-2xl transition-all text-[15px] font-medium placeholder:text-dp-text-disabled" placeholder="024 XXX XXXX">
                        @error('phone') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4">
                        <x-ui.button type="submit" variant="primary" size="lg" class="w-full shadow-md py-5 rounded-2xl text-lg">
                            {{ __('Resume Booking') }}
                        </x-ui.button>
                    </div>
                </form>

                <div class="pt-8 border-t border-base-content/10">
                    <p class="text-[12px] text-base-content/60 text-center font-medium">
                        Need help? <a href="{{ route('contact') }}" class="text-primary font-bold hover:underline italic">Speak with our concierge</a>
                    </p>
                </div>
            </div>
        </div>

        {{-- Help Card --}}
        <div class="mt-8 bg-primary/5 border border-dp-rose/10 rounded-2xl p-6 flex items-start gap-4">
            <div class="size-8 bg-primary text-white rounded-full flex items-center justify-center shrink-0 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[12px] font-bold text-primary uppercase tracking-widest mb-1">Where is my reference?</p>
                <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">
                    Check your email (including spam) for a "Booking Received" message. Your unique reference is located at the top of the message.
                </p>
            </div>
        </div>
    </div>
</div>
