<div class="bg-base-200 min-h-screen py-10 lg:py-20" x-data x-ref="wizardTop">
    <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
        <x-booking.progress-bar :steps="['Review', 'Contact', 'Summary']" :currentStep="$currentStep" />

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <!-- Main Form Column -->
            <div class="lg:col-span-7">
                <div class="bg-base-100 border border-base-content/10 rounded-[24px] p-5 sm:p-8 lg:p-12 shadow-sm">

                    {{-- ══ STEP 1: Review Cart ══ --}}
                    @if($currentStep === 1)
                        <div wire:key="step-review" class="animate-fade-in space-y-8">
                            <div>
                                <h2 class="text-3xl font-semibold text-base-content mb-2 text-center sm:text-left">Review Selection</h2>
                                <p class="text-base-content/60 text-[14px] font-medium text-center sm:text-left">Review your chosen packages and quantities before proceeding.</p>
                            </div>

                            <div class="space-y-6">
                                @foreach($cartItems as $item)
                                    <div wire:key="cart-review-{{ $item['package']->id }}" class="flex items-center gap-6 p-4 bg-base-200 rounded-2xl border border-base-content/10 group transition-all hover:bg-white hover:shadow-sm">
                                        <div class="flex-1">
                                            <h4 class="text-lg lg:text-xl font-semibold text-base-content mb-1">{{ $item['package']->name }}</h4>
                                            <div class="flex items-center gap-4">
                                                <div class="text-[14px] font-bold text-primary">GH₵ {{ number_format($item['package']->price, 0) }}</div>
                                                <div class="size-1 border-base-content/10 rounded-full"></div>
                                                <div class="text-[13px] text-base-content/60 font-medium">Qty: {{ $item['quantity'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-end pt-8 border-t border-base-content/10">
                                <x-ui.button type="button" wire:click="nextStep" x-on:click="$nextTick(() => $refs.wizardTop.scrollIntoView({ behavior: 'smooth' }))" variant="primary" size="lg" class="w-full sm:w-auto shadow-md">
                                    {{ __('Next: Contact Details') }} &rarr;
                                </x-ui.button>
                            </div>
                        </div>

                    {{-- ══ STEP 2: Contact Details ══ --}}
                    @elseif($currentStep === 2)
                        <div wire:key="step-contact" class="animate-fade-in space-y-8">
                            <div>
                                <h2 class="text-3xl font-semibold text-base-content mb-2">Contact Details</h2>
                                <p class="text-base-content/60 text-[14px] font-medium">Enter your information so we can coordinate your catering experience.</p>
                            </div>

                            <x-booking.contact-form :verifyPhone="$verifyPhone" :otpStep="$otpStep" :otpError="$otpError" :phone="$phone" />

                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-base-content/10">
                                <x-ui.button variant="ghost" wire:click="previousStep" x-on:click="$nextTick(() => $refs.wizardTop.scrollIntoView({ behavior: 'smooth' }))" class="w-full sm:w-auto font-bold">&larr; {{ __('Back') }}</x-ui.button>
                                <x-ui.button variant="primary" size="lg" wire:click="nextStep" x-on:click="$nextTick(() => $refs.wizardTop.scrollIntoView({ behavior: 'smooth' }))" class="w-full sm:w-auto shadow-md">
                                    {{ __('Next: Review Summary') }} &rarr;
                                </x-ui.button>
                            </div>
                        </div>

                    {{-- ══ STEP 3: Summary ══ --}}
                    @elseif($currentStep === 3)
                        <div wire:key="step-summary" class="animate-fade-in space-y-8">
                            <div>
                                <h2 class="text-3xl font-semibold text-base-content mb-2">Final Summary</h2>
                                <p class="text-base-content/60 text-[14px] font-medium">Verify all details before proceeding to secure payment.</p>
                            </div>

                            <div class="bg-base-200 rounded-2xl p-6 lg:p-8 space-y-6 border border-base-content/10">
                                <div>
                                    <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-3">Client Information</div>
                                    <div class="text-lg font-semibold text-base-content">{{ $name }}</div>
                                    <div class="text-[13px] text-base-content/60 font-medium">{{ $phone }}</div>
                                    @if($email) <div class="text-[13px] text-base-content/60 font-medium">{{ $email }}</div> @endif
                                </div>
                            </div>

                            <div class="bg-success/5 border border-success rounded-2xl p-5 flex items-start gap-4">
                                <div class="size-10 bg-success text-white rounded-full flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-[13px] font-bold text-success uppercase tracking-wide">Ready for confirmation</div>
                                    <p class="text-[12px] text-success/80 font-medium leading-relaxed">By clicking finalize, you agree to our catering terms. A booking reference will be generated for your payment.</p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-base-content/10">
                                <x-ui.button variant="ghost" wire:click="previousStep" x-on:click="$nextTick(() => $refs.wizardTop.scrollIntoView({ behavior: 'smooth' }))" class="w-full sm:w-auto font-bold">&larr; {{ __('Back') }}</x-ui.button>
                                <x-ui.button variant="primary" size="lg" wire:click="confirmBooking" wire:loading.attr="disabled" :loading="$loading === 'confirmBooking'" class="w-full sm:w-auto shadow-xl text-lg">
                                    {{ __('Finalise & Pay') }}
                                </x-ui.button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <x-booking.order-summary :cartItems="$cartItems" :cartTotal="$cartTotal" :isEvent="false" />
        </div>
    </div>
</div>
