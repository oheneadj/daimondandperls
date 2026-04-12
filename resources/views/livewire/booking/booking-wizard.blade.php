<div class="bg-base-200 min-h-screen py-10 lg:py-20" x-data>
    <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Order summary: above form on mobile, right sidebar on desktop --}}
            <div class="order-first lg:order-none lg:col-span-5">
                <x-booking.order-summary :cartItems="$cartItems" :cartTotal="$cartTotal" :isEvent="false" :hideOnMobile="false" />
            </div>

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

                {{-- 3. Payment Method --}}
                <div class="bg-base-100 border border-base-content/10 rounded-lg p-5 sm:p-8 shadow-dp-lg">
                    <div class="mb-5">
                        <h2 class="text-[17px] font-semibold text-base-content mb-0.5">Payment Method</h2>
                        <p class="text-[13px] text-base-content/50">Choose how you'd like to pay.</p>
                    </div>

                    @php
                        $networks = [
                            ['id' => '13', 'name' => 'MTN Mobile Money', 'logo' => 'logos/mtn-momo.png'],
                            ['id' => '6',  'name' => 'Telecel Cash',      'logo' => 'logos/Telecel-Cash.jpg'],
                            ['id' => '7',  'name' => 'AirtelTigo Money',  'logo' => 'logos/airteltigo-money.png'],
                        ];
                        $networkLogos = collect($networks)->keyBy('id');
                    @endphp

                    {{-- Saved methods (logged-in users only) --}}
                    @auth
                        @if($savedMethods->isNotEmpty())
                            <div class="mb-4">
                                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-3">Your Payment Methods</label>
                                <div class="divide-y divide-base-content/5">
                                    @foreach($savedMethods as $method)
                                        @php $logo = $networkLogos->get($method->provider); @endphp
                                        <button type="button" wire:click="selectPaymentMethod({{ $method->id }})"
                                            class="w-full flex items-center gap-4 px-2 py-3.5 text-left transition-colors hover:bg-base-200/50 group">
                                            @if($logo)
                                                <img src="{{ asset($logo['logo']) }}" class="size-8 object-contain rounded-md shrink-0" alt="{{ $logo['name'] }}">
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <span class="text-[15px] font-medium text-base-content block truncate">{{ $method->label }}</span>
                                                <span class="text-[12px] text-base-content/50">{{ $method->account_number }}</span>
                                            </div>
                                            @if($method->is_default)
                                                <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[9px] font-black uppercase tracking-wider shrink-0">Default</span>
                                            @endif
                                            <div @class([
                                                'size-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all',
                                                'border-primary' => $selectedMethodId === $method->id && !$useNewNumber,
                                                'border-base-content/20 group-hover:border-base-content/40' => $selectedMethodId !== $method->id || $useNewNumber,
                                            ])>
                                                @if($selectedMethodId === $method->id && !$useNewNumber)
                                                    <div class="size-2.5 rounded-full bg-primary"></div>
                                                @endif
                                            </div>
                                        </button>
                                    @endforeach

                                    <button type="button" wire:click="useNewPaymentMethod"
                                        class="w-full flex items-center gap-4 px-2 py-3.5 text-left transition-colors hover:bg-base-200/50 group">
                                        <div class="size-8 bg-base-200 rounded-md flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </div>
                                        <span class="text-[15px] font-medium text-base-content flex-1">Use a different number</span>
                                        <div @class([
                                            'size-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all',
                                            'border-primary' => $useNewNumber,
                                            'border-base-content/20 group-hover:border-base-content/40' => !$useNewNumber,
                                        ])>
                                            @if($useNewNumber)
                                                <div class="size-2.5 rounded-full bg-primary"></div>
                                            @endif
                                        </div>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endauth

                    {{-- Network + number entry --}}
                    @if($useNewNumber || !Auth::check())
                        <div class="space-y-5">
                            <div>
                                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-3">Select Network</label>
                                <div class="divide-y divide-base-content/5">
                                    @foreach($networks as $network)
                                        <button type="button" wire:click="$set('momoNetwork', '{{ $network['id'] }}')"
                                            class="w-full flex items-center gap-4 px-2 py-3.5 text-left transition-colors hover:bg-base-200/50 group">
                                            <img src="{{ asset($network['logo']) }}" class="size-8 object-contain rounded-md shrink-0" alt="{{ $network['name'] }}">
                                            <span class="text-[15px] font-medium text-base-content flex-1">{{ $network['name'] }}</span>
                                            <div @class([
                                                'size-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all',
                                                'border-primary' => $momoNetwork == $network['id'],
                                                'border-base-content/20 group-hover:border-base-content/40' => $momoNetwork != $network['id'],
                                            ])>
                                                @if($momoNetwork == $network['id'])
                                                    <div class="size-2.5 rounded-full bg-primary"></div>
                                                @endif
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                                @error('momoNetwork')
                                    <p class="text-xs text-error flex items-center gap-1 mt-2"><span>⚠</span> {{ $message }}</p>
                                @enderror
                            </div>

                            <x-app.input
                                name="momoNumber"
                                type="tel"
                                label="Mobile Money Number"
                                wire:model.live="momoNumber"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="10"
                                :placeholder="match ($momoNetwork) { '13' => '024 / 054 / 055 / 059', '6' => '020 / 050', '7' => '026 / 056 / 027 / 057', default => 'Select a network first'}"
                                :disabled="empty($momoNetwork)">
                                <x-slot:icon>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </x-slot:icon>
                            </x-app.input>

                            @if($momoNumber && strlen($momoNumber) === 10 && !$this->getIsMomoFormValidProperty())
                                <p class="text-xs text-error flex items-center gap-1 -mt-2"><span>⚠</span> This number doesn't match the selected network</p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- 4. Confirm & Pay --}}
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
                        {{ __('Confirm & Pay') }}
                    </x-ui.button>
                    <p class="text-center text-[11px] text-base-content/40 font-medium">
                        {{ __('Your payment will be processed securely on the next step') }}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
