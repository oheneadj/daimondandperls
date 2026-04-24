<div class="bg-base-200 min-h-screen py-6 lg:py-20 px-3 sm:px-4">
    <div class="container mx-auto max-w-6xl">
        <!-- Progress Bar -->
        @php
            $isEvent = $booking->booking_type === \App\Enums\BookingType::Event;
            $steps = $isEvent ? ['Review', 'Contact', 'Event', 'Payment', 'Done'] : ['Review', 'Contact', 'Payment', 'Done'];
            $currentStep = $isEvent ? 4 : 3;
            $totalSteps = count($steps);
            $progressWidth = round(($currentStep - 1) / ($totalSteps - 1) * 100);
        @endphp
        <div class="mb-12 lg:mb-16 max-w-4xl mx-auto">
            <div class="flex items-center justify-between relative max-w-3xl mx-auto">
                {{-- Line connector --}}
                <div class="absolute top-5 left-0 w-full h-0.5 border-base-content/10 -z-10"></div>
                <div class="absolute top-5 left-0 h-0.5 bg-primary -z-10 transition-all duration-700"
                    style="width: {{ $progressWidth }}%"></div>

                @foreach($steps as $index => $label)
                    @php $stepNum = $index + 1; @endphp
                    <div class="flex flex-col items-center gap-3">
                        <div @class([
                            'size-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-500',
                            'bg-primary text-white shadow-xl scale-110 ring-4 ring-primary/20' => $currentStep === $stepNum,
                            'bg-primary text-white' => $currentStep > $stepNum,
                            'bg-base-100 text-dp-text-disabled border-2 border-base-content/10' => $currentStep < $stepNum,
                        ])>
                            @if($currentStep > $stepNum)
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            @else
                                {{ $stepNum }}
                            @endif
                        </div>
                        <span @class([
                            'text-[10px] uppercase tracking-[0.15em] font-bold hidden sm:block',
                            'text-primary' => $currentStep === $stepNum,
                            'text-base-content/60' => $currentStep !== $stepNum,
                        ])>{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 order-2 lg:order-1"
                x-data="{ ready: false }"
                x-init="$nextTick(() => { ready = true })"
            >
                {{-- Skeleton --}}
                <div x-show="!ready"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="bg-base-100 border border-base-content/10 rounded-lg overflow-hidden shadow-dp-lg p-5 sm:p-8 lg:p-10 space-y-6">
                    <div class="space-y-2 mb-8">
                        <div class="h-5 w-40 bg-base-200 rounded-lg animate-pulse"></div>
                        <div class="h-3.5 w-64 bg-base-200 rounded animate-pulse"></div>
                    </div>
                    @foreach([1, 2, 3] as $_)
                        <div class="flex items-center gap-4 py-4 border-b border-base-content/5">
                            <div class="size-8 rounded-lg bg-base-200 animate-pulse shrink-0"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-3.5 w-36 bg-base-200 rounded animate-pulse"></div>
                                <div class="h-3 w-24 bg-base-200 rounded animate-pulse"></div>
                            </div>
                            <div class="size-5 rounded-full border-2 border-base-200 animate-pulse shrink-0"></div>
                        </div>
                    @endforeach
                    <div class="pt-4">
                        <div class="h-12 w-full bg-base-200 rounded-lg animate-pulse"></div>
                    </div>
                </div>

                {{-- Real content --}}
                <div x-show="ready"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    style="display:none"
                    class="bg-base-100 border border-base-content/10 rounded-lg overflow-hidden shadow-dp-lg">
                    <div class="p-5 sm:p-8 lg:p-10">
                        <div class="mb-8">
                            <h1 class="text-xl font-semibold text-base-content mb-2">Secure Payment</h1>
                            <p class="text-[14px] text-base-content/50 font-medium">Complete your payment for booking
                                <span class="text-primary font-semibold">{{ $booking->reference }}</span>
                            </p>
                        </div>

                        {{-- Fatal error --}}
                        @if ($fatalError)
                            <div class="mb-8 animate-fade-in">
                                <div class="p-5 bg-error/10 border border-error/15 rounded-lg flex items-start gap-4">
                                    <div class="size-9 bg-error/15 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[14px] font-semibold text-error mb-1">Payment Unavailable</p>
                                        <p class="text-[13px] text-error/80">{{ $fatalError }}</p>
                                        <div class="mt-3">
                                            <a href="tel:+233596070822"
                                                class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-error hover:text-error/80 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                Call / WhatsApp: +233 59 607 0822
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Retryable error --}}
                        @if ($errorMessage)
                            <div class="mb-6 animate-fade-in">
                                <div class="p-4 bg-error/10 border border-error/15 rounded-lg flex items-start gap-3">
                                    <div class="size-8 bg-error/10 rounded-full flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <p class="text-[13px] font-medium text-error flex-1">{{ $errorMessage }}</p>
                                </div>
                            </div>
                        @endif

                        @if (!$fatalError)
                        <div class="space-y-8">
                            <div class="animate-fade-in space-y-6">

                                {{-- Awaiting step --}}
                                @if ($paymentStep === 'awaiting')
                                    <div wire:poll.3s="checkPaymentStatus"
                                        class="bg-base-200/50 border border-base-content/10 rounded-lg p-8 text-center relative overflow-hidden transition-all duration-500">
                                        <div class="absolute inset-0 bg-primary/5 animate-pulse"></div>
                                        <div class="relative z-10">
                                            <div class="flex justify-center mb-5">
                                                <span class="loading loading-ring loading-lg text-primary scale-150"></span>
                                            </div>
                                            <h3 class="text-lg font-semibold text-base-content mb-2">Confirming Your Payment</h3>
                                            <p class="text-[14px] text-base-content/50 font-medium max-w-sm mx-auto mb-6 leading-relaxed">
                                                If you've completed payment on the checkout page, please wait a moment while we confirm your transaction.
                                            </p>
                                            <div class="flex items-center justify-center gap-3">
                                                <x-app.button wire:click="checkPaymentStatus" variant="outline" size="sm">
                                                    Check Status Now
                                                </x-app.button>
                                                <x-app.button wire:click="cancelPayment" variant="ghost" size="sm"
                                                    class="text-error hover:bg-error/10">
                                                    Cancel & Try Again
                                                </x-app.button>
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    {{-- Form step --}}
                                    @php
                                        $networks = [
                                            ['id' => '13', 'name' => 'MTN Mobile Money', 'logo' => 'logos/mtn-momo.png'],
                                            ['id' => '6', 'name' => 'Telecel Cash', 'logo' => 'logos/Telecel-Cash.jpg'],
                                            ['id' => '7', 'name' => 'AirtelTigo Money', 'logo' => 'logos/airteltigo-money.png'],
                                        ];
                                        $networkLogos = collect($networks)->keyBy('id');
                                    @endphp

                                    @if ($savedMethods->isNotEmpty())
                                        {{-- Saved methods list (logged-in users with existing methods) --}}
                                        <div>
                                            <label class="text-dp-sm font-medium text-base-content block mb-3">Your Payment Methods</label>
                                            <div class="divide-y divide-base-content/5">
                                                @foreach($savedMethods as $method)
                                                    @php $logo = $networkLogos->get($method->provider); @endphp
                                                    <button type="button" wire:click="selectPaymentMethod({{ $method->id }})"
                                                        class="w-full flex items-center gap-4 px-2 py-4 text-left transition-colors hover:bg-base-200/50 group">
                                                        @if($logo)
                                                            <img src="{{ asset($logo['logo']) }}"
                                                                class="size-8 object-contain rounded-md shrink-0"
                                                                alt="{{ $logo['name'] }}">
                                                        @else
                                                            <div class="size-8 bg-base-200 rounded-md flex items-center justify-center shrink-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                </svg>
                                                            </div>
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
                                                            'border-primary' => $paymentChoice === 'saved' && $selectedMethodId === $method->id,
                                                            'border-base-content/20 group-hover:border-base-content/40' => !($paymentChoice === 'saved' && $selectedMethodId === $method->id),
                                                        ])>
                                                            @if($paymentChoice === 'saved' && $selectedMethodId === $method->id)
                                                                <div class="size-2.5 rounded-full bg-primary"></div>
                                                            @endif
                                                        </div>
                                                    </button>
                                                @endforeach

                                                {{-- Add a new MoMo number --}}
                                                <button type="button" wire:click="$set('paymentChoice', 'new_momo')"
                                                    class="w-full flex items-center gap-4 px-2 py-4 text-left transition-colors hover:bg-base-200/50 group">
                                                    <div class="size-8 bg-base-200 rounded-md flex items-center justify-center shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-[15px] font-medium text-base-content flex-1">Add a new MoMo number</span>
                                                    <div @class([
                                                        'size-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all',
                                                        'border-primary' => $paymentChoice === 'new_momo',
                                                        'border-base-content/20 group-hover:border-base-content/40' => $paymentChoice !== 'new_momo',
                                                    ])>
                                                        @if($paymentChoice === 'new_momo')
                                                            <div class="size-2.5 rounded-full bg-primary"></div>
                                                        @endif
                                                    </div>
                                                </button>

                                                {{-- Pay by card --}}
                                                <button type="button" wire:click="$set('paymentChoice', 'card')"
                                                    class="w-full flex items-center gap-4 px-2 py-4 text-left transition-colors hover:bg-base-200/50 group">
                                                    <div class="size-8 bg-base-200 rounded-md flex items-center justify-center shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-[15px] font-medium text-base-content flex-1">Pay by card</span>
                                                    <div @class([
                                                        'size-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all',
                                                        'border-primary' => $paymentChoice === 'card',
                                                        'border-base-content/20 group-hover:border-base-content/40' => $paymentChoice !== 'card',
                                                    ])>
                                                        @if($paymentChoice === 'card')
                                                            <div class="size-2.5 rounded-full bg-primary"></div>
                                                        @endif
                                                    </div>
                                                </button>
                                            </div>
                                        </div>

                                    @else
                                        {{-- Guest or user with no saved methods: tabs --}}
                                        <div>
                                            <label class="text-dp-sm font-medium text-base-content block mb-3">Payment Method</label>
                                            <div class="flex gap-2 mb-6">
                                                <button type="button" wire:click="$set('paymentChoice', 'new_momo')"
                                                    @class([
                                                        'flex-1 py-2.5 px-4 rounded-lg text-[14px] font-semibold transition-all border',
                                                        'bg-primary text-white border-primary' => $paymentChoice === 'new_momo' || $paymentChoice === '',
                                                        'bg-base-100 text-base-content/60 border-base-content/10 hover:border-base-content/20' => $paymentChoice === 'card',
                                                    ])>
                                                    Mobile Money
                                                </button>
                                                <button type="button" wire:click="$set('paymentChoice', 'card')"
                                                    @class([
                                                        'flex-1 py-2.5 px-4 rounded-lg text-[14px] font-semibold transition-all border',
                                                        'bg-primary text-white border-primary' => $paymentChoice === 'card',
                                                        'bg-base-100 text-base-content/60 border-base-content/10 hover:border-base-content/20' => $paymentChoice !== 'card',
                                                    ])>
                                                    Card
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- New MoMo number form --}}
                                    @if ($paymentChoice === 'new_momo' || ($paymentChoice === '' && $savedMethods->isEmpty()))
                                        <div class="space-y-6">
                                            <!-- Network Selection -->
                                            <div>
                                                <label class="text-dp-sm font-medium text-base-content block mb-3">Select Network</label>
                                                <div class="divide-y divide-base-content/5">
                                                    @foreach($networks as $network)
                                                        <button type="button" wire:click="$set('momoNetwork', '{{ $network['id'] }}')"
                                                            class="w-full flex items-center gap-4 px-2 py-4 text-left transition-colors hover:bg-base-200/50 group">
                                                            <img src="{{ asset($network['logo']) }}"
                                                                class="size-8 object-contain rounded-md shrink-0"
                                                                alt="{{ $network['name'] }}">
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
                                                @error('momoNetwork') <p class="text-xs text-error flex items-center gap-1 mt-2"><span>⚠</span> {{ $message }}</p> @enderror
                                            </div>

                                            <!-- Phone Number -->
                                            <x-app.input name="momoNumber" type="tel" label="Mobile Money Number"
                                                wire:model.live="momoNumber" inputmode="numeric" pattern="[0-9]*" maxlength="10"
                                                :placeholder="match ($momoNetwork) { '13' => '024 / 054 / 055 / 059', '6' => '020 / 050', '7' => '026 / 056 / 027 / 057', default => 'Select a network first'}"
                                                :disabled="empty($momoNetwork)" :hint="($momoNetwork && strlen($momoNumber) > 0 && strlen($momoNumber) < 10) ? match ($momoNetwork) { '13' => 'Accepted prefixes: 024, 054, 055, 059', '6' => 'Accepted prefixes: 020, 050', '7' => 'Accepted prefixes: 026, 056, 027, 057', default => null} : null">
                                                <x-slot:icon>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                </x-slot:icon>
                                            </x-app.input>

                                            @if($momoNumber && strlen($momoNumber) === 10 && !$this->isMomoFormValid)
                                                <p class="text-xs text-error flex items-center gap-1 -mt-4"><span>⚠</span> This number doesn't match the selected network</p>
                                            @endif

                                            @auth
                                                <label class="flex items-center gap-3 cursor-pointer">
                                                    <input type="checkbox" wire:model="saveNewMethod" class="checkbox checkbox-primary checkbox-sm">
                                                    <span class="text-[13px] text-base-content/70">Save this number to my account</span>
                                                </label>
                                            @endauth
                                        </div>
                                    @endif

                                    @if ($paymentChoice === 'card')
                                        <div class="p-4 bg-base-200/60 rounded-lg text-[13px] text-base-content/60 leading-relaxed">
                                            You'll be redirected to a secure payment page to complete your card payment. Visa and Mastercard are accepted.
                                        </div>
                                    @endif

                                    {{-- Pay button --}}
                                    <div class="pt-4 border-t border-base-content/5">
                                        <x-app.button type="button" variant="primary" size="lg" class="w-full"
                                            wireClick="initiateCheckout" wireTarget="initiateCheckout"
                                            loadingText="Redirecting to secure checkout...">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Pay GH₵ {{ number_format($booking->total_amount, 2) }} — Proceed to Checkout
                                        </x-app.button>
                                    </div>

                                @endif {{-- end form step --}}
                            </div>
                        </div>
                        @endif {{-- !$fatalError --}}
                    </div>
                </div>
            </div> {{-- end x-show="ready" wrapper --}}

            <!-- Sidebar Summary -->
            <div class="lg:col-span-1 space-y-6 order-1 lg:order-2">
                <div class="bg-base-100 border border-base-content/10 rounded-lg p-6 lg:p-8 shadow-dp-lg">
                    <h4 class="text-lg font-semibold text-base-content mb-6 pb-4 border-b border-base-content/10">Order Summary</h4>

                    <div class="space-y-4 mb-6">
                        @foreach($booking->items as $item)
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex-1">
                                    <div class="text-[14px] font-semibold text-base-content line-clamp-1">
                                        {{ $item->package->name }}
                                    </div>
                                    @if($booking->booking_type !== \App\Enums\BookingType::Event)
                                        <div class="text-[12px] text-base-content/50 font-medium">GH₵ {{ number_format($item->price, 0) }} × {{ $item->quantity }}</div>
                                    @endif
                                </div>
                                @if($booking->booking_type !== \App\Enums\BookingType::Event)
                                    <div class="text-[14px] font-semibold text-base-content whitespace-nowrap">
                                        GH₵ {{ number_format($item->price * $item->quantity, 0) }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 pt-6 border-t border-dashed border-base-content/10">
                        @if($booking->booking_type !== \App\Enums\BookingType::Event)
                            <div class="flex justify-between items-center text-[14px] text-base-content/50 font-medium">
                                <span>Subtotal</span>
                                <span>GH₵ {{ number_format((float) $booking->total_amount, 0) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center pt-3">
                            <span class="text-[15px] font-semibold text-base-content">Total Due</span>
                            <span class="text-xl font-bold text-primary">GH₵ {{ number_format((float) $booking->total_amount, 0) }}</span>
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-base-200 border border-base-content/10 rounded-lg flex items-center gap-3">
                        <div class="size-8 bg-base-100 rounded-full flex items-center justify-center shadow-sm shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A3.333 3.333 0 0010 15V5.748a3.333 3.333 0 005.338 2.668 3.333 3.333 0 011.045 4.542 3.333 3.333 0 01-4.765 2.027z" />
                            </svg>
                        </div>
                        <p class="text-xs text-base-content/50 font-medium leading-relaxed">
                            Transaction protected by industry-standard encryption
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
