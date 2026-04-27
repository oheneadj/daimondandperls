<div class="bg-base-200 min-h-screen py-10 lg:py-20" x-data>
    <div class="container mx-auto px-4 lg:px-8 max-w-6xl space-y-6">

        {{-- Checkout progress: Details → Payment → Done (step 1 of 3) --}}
        @include('livewire.booking._checkout-progress', ['currentStep' => 1])

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

            {{-- Main column --}}
            <div class="order-last lg:order-first lg:col-span-7 space-y-5">

                {{-- 1. Delivery Location (only if admin has configured locations) --}}
                @if(!empty($deliveryLocations))
                    <div class="bg-base-100 border border-base-content/10 rounded-lg p-5 sm:p-8 shadow-dp-lg">
                        <div class="mb-5">
                            <h2 class="text-[17px] font-semibold text-base-content mb-0.5">Delivery Location</h2>
                            <p class="text-[13px] text-base-content/50">Where should we deliver your order?</p>
                        </div>
                        <div class="divide-y divide-base-content/5">
                            @foreach($deliveryLocations as $location)
                                <button type="button" wire:click="$set('deliveryLocation', '{{ $location }}')"
                                    class="w-full flex items-center gap-4 px-2 py-3.5 text-left transition-colors hover:bg-base-200/50 group">
                                    <div class="size-8 bg-base-200 rounded-lg flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-[15px] font-medium text-base-content flex-1">{{ $location }}</span>
                                    <div @class([
                                        'size-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all',
                                        'border-primary' => $deliveryLocation === $location,
                                        'border-base-content/20 group-hover:border-base-content/40' => $deliveryLocation !== $location,
                                    ])>
                                        @if($deliveryLocation === $location)
                                            <div class="size-2.5 rounded-full bg-primary"></div>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>
                        @error('deliveryLocation')
                            <p class="text-xs text-error flex items-center gap-1 mt-3"><span>⚠</span> {{ $message }}</p>
                        @enderror
                    </div>
                @endif

                {{-- 2. Contact Details --}}
                <div class="bg-base-100 border border-base-content/10 rounded-lg shadow-dp-lg overflow-hidden">
                    @auth
                        {{-- Auth: summary card with edit toggle --}}
                        <div x-data="{ editing: false }">
                            {{-- Summary view --}}
                            <div x-show="!editing" class="p-5 sm:p-8">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="size-11 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                            <span class="text-[17px] font-bold text-primary">{{ strtoupper(substr($name ?? Auth::user()->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-semibold text-base-content leading-tight">{{ $name ?? Auth::user()->name }}</p>
                                            <p class="text-[13px] text-base-content/50 mt-0.5">{{ $phone ?? Auth::user()->phone }}</p>
                                            @if($email ?? Auth::user()->email)
                                                <p class="text-[12px] text-base-content/40 mt-0.5">{{ $email ?? Auth::user()->email }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="button" x-on:click="editing = true"
                                        class="shrink-0 text-[12px] font-semibold text-primary hover:text-primary/80 transition-colors flex items-center gap-1.5 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
                                </div>
                            </div>

                            {{-- Edit form --}}
                            <div x-show="!editing" x-cloak class="hidden"></div>
                            <div x-show="editing" x-cloak class="p-5 sm:p-8">
                                <div class="flex items-center justify-between mb-5">
                                    <div>
                                        <h2 class="text-[17px] font-semibold text-base-content mb-0.5">Edit Your Details</h2>
                                        <p class="text-[13px] text-base-content/50">Changes will update your account profile.</p>
                                    </div>
                                    <button type="button" x-on:click="editing = false"
                                        class="text-[12px] font-semibold text-base-content/40 hover:text-base-content transition-colors">
                                        Cancel
                                    </button>
                                </div>
                                <x-booking.contact-form :verifyPhone="$verifyPhone" :otpStep="$otpStep" :otpError="$otpError" :phone="$phone" />
                                <div class="mt-5 pt-5 border-t border-base-content/5">
                                    <button type="button" x-on:click="editing = false"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-[13px] font-semibold rounded-lg hover:bg-primary/90 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Save & Continue
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Guest: form with sign-in prompt --}}
                        <div class="p-5 sm:p-8">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div>
                                    <h2 class="text-[17px] font-semibold text-base-content mb-0.5">Your Details</h2>
                                    <p class="text-[13px] text-base-content/50">We'll use this to confirm your order.</p>
                                </div>
                                <a href="{{ route('login') }}" class="shrink-0 text-[12px] font-semibold text-primary hover:text-primary/80 transition-colors mt-1 whitespace-nowrap">
                                    Sign in →
                                </a>
                            </div>
                            <x-booking.contact-form :verifyPhone="$verifyPhone" :otpStep="$otpStep" :otpError="$otpError" :phone="$phone" />
                        </div>
                    @endauth
                </div>

                {{-- 3. Confirm & Pay --}}
                <div class="space-y-3">
                    <x-ui.button
                        wire:click="confirmBooking"
                        wire:loading.attr="disabled"
                        :loading="$loading === 'confirmBooking'"
                        :disabled="!$this->isReadyToConfirm"
                        variant="primary"
                        size="lg"
                        class="w-full"
                    >
                        {{ __('Continue to Payment') }}
                    </x-ui.button>
                    <p class="text-center text-[11px] text-base-content/40 font-medium">
                        {{ __('You\'ll choose your payment method on the next step') }}
                    </p>
                </div>

            </div>

            {{-- Order summary: right sidebar on desktop, above form on mobile --}}
            <div class="order-first lg:order-none lg:col-span-5">
                <x-booking.order-summary :cartItems="$cartItems" :cartTotal="$cartTotal" :isEvent="false" :hideOnMobile="false" />
            </div>

        </div>
    </div>
</div>
