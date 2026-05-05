<div class="bg-base-200 min-h-screen py-6 lg:py-20 px-3 sm:px-4">
    <div class="container mx-auto max-w-6xl">
        {{-- Checkout progress: Details → Payment → Done (step 2 of 3) --}}
        @include('livewire.booking._checkout-progress', ['currentStep' => 2])

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 order-2 lg:order-1" x-data="{ ready: false }"
                x-init="$nextTick(() => { ready = true })">
                {{-- Skeleton --}}
                <div x-show="!ready" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
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
                <div x-show="ready" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0" style="display:none"
                    class="bg-base-100 border border-base-content/10 rounded-lg overflow-hidden shadow-dp-lg">
                    <div class="p-5 sm:p-8 lg:p-10">
                        <div class="mb-8">

                        </div>

                        {{-- Fatal error --}}
                        @if ($fatalError)
                            <div class="mb-8 animate-fade-in">
                                <div class="p-5 bg-error/10 border border-error/15 rounded-lg flex items-start gap-4">
                                    <div
                                        class="size-9 bg-error/15 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-error" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[14px] font-semibold text-error mb-1">Payment Unavailable</p>
                                        <p class="text-[13px] text-error/80">{{ $fatalError }}</p>
                                        @php
                                            $supportPhone = dpc_setting('business_phone', '+233244203181');
                                        @endphp
                                        <div class="mt-3">
                                            <a href="tel:{{ $supportPhone }}"
                                                class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-error hover:text-error/80 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                Call / WhatsApp: {{ $supportPhone }}
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
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-error" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <p class="text-[13px] font-medium text-error flex-1">{{ $errorMessage }}</p>
                                </div>
                            </div>
                        @endif

                        @if (!$fatalError)
                            <div class="space-y-8">
                                <div class="animate-fade-in space-y-6">

                                    {{-- Offline payment instructions step --}}
                                    @if ($paymentStep === 'offline')
                                        <div class="animate-fade-in space-y-5">

                                            {{-- Manual transfer card (active) --}}
                                            <div class="bg-primary/5 border border-primary/20 rounded-2xl p-5">
                                                <div class="flex items-center gap-3 mb-4">
                                                    <div
                                                        class="w-9 h-9 rounded-xl bg-primary/15 flex items-center justify-center shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-primary"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center gap-2">
                                                            <h3 class="text-sm font-bold text-base-content">Direct MoMo Transfer
                                                            </h3>
                                                            <span
                                                                class="inline-flex items-center gap-1 text-[10px] font-bold text-primary uppercase tracking-wide bg-primary/10 px-2 py-0.5 rounded-full">
                                                                <span
                                                                    class="w-1.5 h-1.5 rounded-full bg-primary inline-block"></span>
                                                                Available Now
                                                            </span>
                                                        </div>
                                                        <p class="text-xs text-base-content/50 mt-0.5">Send directly to our MoMo
                                                            number — no app needed</p>
                                                    </div>
                                                </div>

                                                <div
                                                    class="bg-white rounded-xl border border-base-content/10 divide-y divide-base-content/5 mb-4">
                                                    <div class="flex items-center justify-between px-4 py-3">
                                                        <span class="text-xs text-base-content/50 font-medium">Account
                                                            Name</span>
                                                        <span
                                                            class="text-sm font-bold text-base-content">{{ dpc_setting('business_momo_name') ?: '—' }}</span>
                                                    </div>
                                                    <div class="flex items-center justify-between px-4 py-3">
                                                        <span class="text-xs text-base-content/50 font-medium">Network</span>
                                                        <span
                                                            class="text-sm font-bold text-base-content">{{ dpc_setting('business_momo_network') ?: '—' }}</span>
                                                    </div>
                                                    <div class="flex items-center justify-between px-4 py-3">
                                                        <span class="text-xs text-base-content/50 font-medium">MoMo
                                                            Number</span>
                                                        <span
                                                            class="text-sm font-bold text-base-content tracking-widest">{{ dpc_setting('business_momo_number') ?: '—' }}</span>
                                                    </div>
                                                    <div class="flex items-center justify-between px-4 py-3">
                                                        <span class="text-xs text-base-content/50 font-medium">Amount to
                                                            Send</span>
                                                        <span class="text-sm font-bold text-primary">GH₵
                                                            {{ number_format($booking->total_amount, 2) }}</span>
                                                    </div>
                                                </div>

                                                <p class="text-xs text-base-content/60 leading-relaxed">
                                                    Send exactly <strong>GH₵
                                                        {{ number_format($booking->total_amount, 2) }}</strong> to the number
                                                    above, then tap the button below. Our team will verify your payment and
                                                    confirm your booking shortly.
                                                </p>
                                            </div>

                                            <x-app.button wire:click="confirmOfflinePayment" wire:loading.attr="disabled"
                                                variant="primary" size="lg" class="w-full">
                                                <span wire:loading.remove wire:target="confirmOfflinePayment">I've Made the
                                                    Payment</span>
                                                <span wire:loading wire:target="confirmOfflinePayment"
                                                    class="flex items-center gap-2">
                                                    <span class="loading loading-spinner loading-xs"></span>
                                                    Processing...
                                                </span>
                                            </x-app.button>

                                            {{-- Divider --}}
                                            <div class="flex items-center gap-3">
                                                <div class="flex-1 h-px bg-base-content/10"></div>
                                                <span
                                                    class="text-[11px] font-semibold text-base-content/30 uppercase tracking-widest">Other
                                                    payment options</span>
                                                <div class="flex-1 h-px bg-base-content/10"></div>
                                            </div>

                                            {{-- Online unavailability notice --}}
                                            <div
                                                class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3.5">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-[12px] text-amber-700 leading-relaxed">
                                                    <span class="font-semibold">Online payments are temporarily
                                                        unavailable.</span>
                                                    We're putting the finishing touches on our secure checkout — it'll be ready
                                                    very soon.
                                                    We'll notify you as soon as it's live. In the meantime, the direct transfer
                                                    option above works just as well!
                                                </p>
                                            </div>

                                            {{-- Disabled MoMo option --}}
                                            <div
                                                class="relative rounded-xl border-2 border-base-content/8 bg-base-100/50 p-5 opacity-50 select-none pointer-events-none">
                                                <div
                                                    class="absolute top-3 right-3 inline-flex items-center gap-1 text-[10px] font-bold text-base-content/40 uppercase tracking-wide bg-base-200 px-2 py-0.5 rounded-full">
                                                    Coming soon
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-9 h-9 rounded-lg bg-base-200 flex items-center justify-center shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-4.5 h-4.5 text-base-content/30" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-base-content/40">Mobile Money (Online)
                                                        </p>
                                                        <p class="text-xs text-base-content/30">Instant MoMo prompt to your
                                                            phone</p>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Disabled Card option --}}
                                            <div
                                                class="relative rounded-xl border-2 border-base-content/8 bg-base-100/50 p-5 opacity-50 select-none pointer-events-none">
                                                <div
                                                    class="absolute top-3 right-3 inline-flex items-center gap-1 text-[10px] font-bold text-base-content/40 uppercase tracking-wide bg-base-200 px-2 py-0.5 rounded-full">
                                                    Coming soon
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-9 h-9 rounded-lg bg-base-200 flex items-center justify-center shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-4.5 h-4.5 text-base-content/30" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-base-content/40">Card Payment</p>
                                                        <p class="text-xs text-base-content/30">Visa & Mastercard accepted</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        {{-- Awaiting step --}}
                                    @elseif ($paymentStep === 'awaiting')
                                        <div wire:poll.3s="pollPaymentStatus"
                                            class="bg-base-200/50 border border-base-content/10 rounded-lg p-8 text-center relative overflow-hidden transition-all duration-500">
                                            <div class="absolute inset-0 bg-primary/5 animate-pulse"></div>
                                            <div class="relative z-10">
                                                <div class="flex justify-center mb-5">
                                                    <span class="loading loading-ring loading-lg text-primary scale-150"></span>
                                                </div>
                                                <h3 class="text-lg font-semibold text-base-content mb-2">Confirming Your Payment
                                                </h3>
                                                <p
                                                    class="text-[14px] text-base-content/50 font-medium max-w-sm mx-auto mb-6 leading-relaxed">
                                                    If you've completed payment on the checkout page, please wait a moment while
                                                    we confirm your transaction.
                                                </p>

                                                @if ($statusMessage)
                                                    <div class="mb-4 px-4 py-3 bg-base-200 border border-base-content/10 rounded-lg inline-flex items-center gap-2 text-[13px] text-base-content/70 font-medium">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/40 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $statusMessage }}
                                                    </div>
                                                @endif

                                                <div class="flex items-center justify-center gap-3">
                                                    <x-app.button wire:click="checkPaymentStatus" wire:loading.attr="disabled"
                                                        wire:target="checkPaymentStatus" variant="primary" size="md">
                                                        <span wire:loading.remove wire:target="checkPaymentStatus"
                                                            class="flex items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                                                            </svg>
                                                            Check Status Now
                                                        </span>
                                                        <span wire:loading wire:target="checkPaymentStatus"
                                                            class="flex items-center gap-2">
                                                            <span class="loading loading-spinner loading-xs"></span>
                                                            Checking...
                                                        </span>
                                                    </x-app.button>
                                                    <x-app.button wire:click="cancelPayment" wire:loading.attr="disabled"
                                                        wire:target="cancelPayment" variant="secondary" size="md"
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
                                                <label class="text-dp-sm font-medium text-base-content block mb-3">Your Payment
                                                    Methods</label>
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
                                                                <div
                                                                    class="size-8 bg-base-200 rounded-md flex items-center justify-center shrink-0">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="size-4 text-base-content/40" fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                            <div class="flex-1 min-w-0">
                                                                <span
                                                                    class="text-[15px] font-medium text-base-content block truncate">{{ $method->label }}</span>
                                                                <span
                                                                    class="text-[12px] text-base-content/50">{{ $method->account_number }}</span>
                                                            </div>
                                                            @if($method->is_default)
                                                                <span
                                                                    class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[9px] font-black uppercase tracking-wider shrink-0">Default</span>
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
                                                </div>

                                                {{-- Use a different number toggle --}}
                                                <button type="button" wire:click="toggleNewMomoForm"
                                                    class="mt-3 flex items-center gap-2 px-2 py-2 text-[13px] font-medium text-primary hover:text-primary/70 transition-colors">
                                                    @if($showNewMomoForm)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Cancel
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        Use a different number
                                                    @endif
                                                </button>

                                                @if($showNewMomoForm)
                                                    {{-- Inline new MoMo form --}}
                                                    <div class="mt-4 space-y-6 pb-2 border-t border-base-content/5 pt-4">
                                                        <div>
                                                            <label class="text-dp-sm font-medium text-base-content block mb-3">Select
                                                                Network</label>
                                                            <div class="divide-y divide-base-content/5">
                                                                @foreach($networks as $network)
                                                                    <button type="button"
                                                                        wire:click="$set('momoNetwork', '{{ $network['id'] }}')"
                                                                        class="w-full flex items-center gap-4 px-2 py-4 text-left transition-colors hover:bg-base-200/50 group">
                                                                        <img src="{{ asset($network['logo']) }}"
                                                                            class="size-8 object-contain rounded-md shrink-0"
                                                                            alt="{{ $network['name'] }}">
                                                                        <span
                                                                            class="text-[15px] font-medium text-base-content flex-1">{{ $network['name'] }}</span>
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
                                                            @error('momoNetwork') <p
                                                                class="text-xs text-error flex items-center gap-1 mt-2"><span>⚠</span>
                                                            {{ $message }}</p> @enderror
                                                        </div>

                                                        <x-app.input name="momoNumber" type="tel" label="Mobile Money Number"
                                                            wire:model.live="momoNumber" inputmode="numeric" pattern="[0-9]*"
                                                            maxlength="10" :placeholder="match ($momoNetwork) { '13' => '024 / 054 / 055 / 059', '6' => '020 / 050', '7' => '026 / 056 / 027 / 057', default => 'Select a network first'}" :disabled="empty($momoNetwork)" :hint="($momoNetwork && strlen($momoNumber) > 0 && strlen($momoNumber) < 10) ? match ($momoNetwork) { '13' => 'Accepted prefixes: 024, 054, 055, 059', '6' => 'Accepted prefixes: 020, 050', '7' => 'Accepted prefixes: 026, 056, 027, 057', default => null} : null">
                                                            <x-slot:icon>
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                </svg>
                                                            </x-slot:icon>
                                                        </x-app.input>

                                                        @error('momoNumber') <p
                                                            class="text-xs text-error flex items-center gap-1 -mt-4"><span>⚠</span>
                                                        {{ $message }}</p> @enderror
                                                        @if(!$errors->has('momoNumber') && $momoNumber && strlen($momoNumber) === 10 && !$this->isMomoFormValid)
                                                            <p class="text-xs text-error flex items-center gap-1 -mt-4"><span>⚠</span> This
                                                                number doesn't match the selected network</p>
                                                        @endif

                                                        <label class="flex items-center gap-3 cursor-pointer">
                                                            <input type="checkbox" wire:model="saveNewMethod"
                                                                class="checkbox checkbox-primary checkbox-sm">
                                                            <span class="text-[13px] text-base-content/70">Save this number to my
                                                                account</span>
                                                        </label>
                                                    </div>
                                                @endif

                                                {{-- Pay by card --}}
                                                <div class="divide-y divide-base-content/5 mt-3 border-t border-base-content/5">
                                                    <button type="button" wire:click="selectCard"
                                                        class="w-full flex items-center gap-4 px-2 py-4 text-left transition-colors hover:bg-base-200/50 group">
                                                        <div
                                                            class="size-8 bg-base-200 rounded-md flex items-center justify-center shrink-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="size-4 text-base-content/40" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                            </svg>
                                                        </div>
                                                        <span class="text-[15px] font-medium text-base-content flex-1">Pay by
                                                            card</span>
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
                                                <label class="text-dp-sm font-medium text-base-content block mb-3">Payment
                                                    Method</label>
                                                <div class="flex gap-2 mb-6">
                                                    <button type="button" wire:click="$set('paymentChoice', 'new_momo')" @class([
                                                        'flex-1 py-2.5 px-4 rounded-lg text-[14px] font-semibold transition-all border',
                                                        'bg-primary text-white border-primary' => $paymentChoice === 'new_momo' || $paymentChoice === '',
                                                        'bg-base-100 text-base-content/60 border-base-content/10 hover:border-base-content/20' => $paymentChoice === 'card',
                                                    ])>
                                                        Mobile Money
                                                    </button>
                                                    <button type="button" wire:click="$set('paymentChoice', 'card')" @class([
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
                                        @if (($paymentChoice === 'new_momo' || $paymentChoice === '') && $savedMethods->isEmpty())
                                            <div class="space-y-6">
                                                <!-- Network Selection -->
                                                <div>
                                                    <label class="text-dp-sm font-medium text-base-content block mb-3">Select
                                                        Network</label>
                                                    <div class="divide-y divide-base-content/5">
                                                        @foreach($networks as $network)
                                                            <button type="button"
                                                                wire:click="$set('momoNetwork', '{{ $network['id'] }}')"
                                                                class="w-full flex items-center gap-4 px-2 py-4 text-left transition-colors hover:bg-base-200/50 group">
                                                                <img src="{{ asset($network['logo']) }}"
                                                                    class="size-8 object-contain rounded-md shrink-0"
                                                                    alt="{{ $network['name'] }}">
                                                                <span
                                                                    class="text-[15px] font-medium text-base-content flex-1">{{ $network['name'] }}</span>
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
                                                    @error('momoNetwork') <p
                                                        class="text-xs text-error flex items-center gap-1 mt-2"><span>⚠</span>
                                                    {{ $message }}</p> @enderror
                                                </div>

                                                <!-- Phone Number -->
                                                <x-app.input name="momoNumber" type="tel" label="Mobile Money Number"
                                                    wire:model.live="momoNumber" inputmode="numeric" pattern="[0-9]*" maxlength="10"
                                                    :placeholder="match ($momoNetwork) { '13' => '024 / 054 / 055 / 059', '6' => '020 / 050', '7' => '026 / 056 / 027 / 057', default => 'Select a network first'}"
                                                    :disabled="empty($momoNetwork)" :hint="($momoNetwork && strlen($momoNumber) > 0 && strlen($momoNumber) < 10) ? match ($momoNetwork) { '13' => 'Accepted prefixes: 024, 054, 055, 059', '6' => 'Accepted prefixes: 020, 050', '7' => 'Accepted prefixes: 026, 056, 027, 057', default => null} : null">
                                                    <x-slot:icon>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                        </svg>
                                                    </x-slot:icon>
                                                </x-app.input>

                                                @error('momoNumber') <p class="text-xs text-error flex items-center gap-1 -mt-4">
                                                <span>⚠</span> {{ $message }}</p> @enderror
                                                @if(!$errors->has('momoNumber') && $momoNumber && strlen($momoNumber) === 10 && !$this->isMomoFormValid)
                                                    <p class="text-xs text-error flex items-center gap-1 -mt-4"><span>⚠</span> This
                                                        number doesn't match the selected network</p>
                                                @endif

                                                @auth
                                                    <label class="flex items-center gap-3 cursor-pointer">
                                                        <input type="checkbox" wire:model="saveNewMethod"
                                                            class="checkbox checkbox-primary checkbox-sm">
                                                        <span class="text-[13px] text-base-content/70">Save this number to my
                                                            account</span>
                                                    </label>
                                                @endauth
                                            </div>
                                        @endif

                                        @if ($paymentChoice === 'card')
                                            <div
                                                class="p-4 bg-base-200/60 rounded-lg text-[13px] text-base-content/60 leading-relaxed">
                                                You'll be redirected to a secure payment page to complete your card payment. Visa
                                                and Mastercard are accepted.
                                            </div>
                                        @endif

                                        {{-- Pay button --}}
                                        <div class="pt-4 border-t border-base-content/5">
                                            @php
                                                $canPay = match (true) {
                                                    $paymentChoice === 'card' => true,
                                                    $paymentChoice === 'saved' => $selectedMethodId !== null,
                                                    $paymentChoice === 'new_momo' || $paymentChoice === '' => $this->isMomoFormValid,
                                                    default => false,
                                                };
                                            @endphp
                                            <button type="button" wire:click="initiateCheckout" wire:loading.attr="disabled"
                                                wire:target="initiateCheckout" @disabled(!$canPay) @class([
                                                    'w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold text-[14px] transition-all',
                                                    'bg-primary text-white hover:bg-primary/90 shadow-sm' => $canPay,
                                                    'bg-primary/30 text-white cursor-not-allowed' => !$canPay,
                                                ])>
                                                <span wire:loading.remove wire:target="initiateCheckout">
                                                    Pay GH₵ {{ number_format($booking->total_amount, 2) }} — Proceed to Pay
                                                </span>
                                                <span wire:loading wire:target="initiateCheckout"
                                                    class="flex items-center gap-2">
                                                    <span class="loading loading-spinner loading-sm"></span>
                                                    Redirecting...
                                                </span>
                                            </button>
                                        </div>

                                    @endif {{-- end form step --}}
                                </div>
                            </div>
                        @endif {{-- !$fatalError --}}
                    </div>
                </div>
            </div> {{-- end x-show="ready" wrapper --}}

            <!-- Sidebar Summary — desktop only -->
            <div class="hidden lg:block lg:col-span-1 space-y-6 order-1 lg:order-2">
                <div class="bg-base-100 border border-base-content/10 rounded-lg p-6 lg:p-8 shadow-dp-lg">
                    <h4 class="text-lg font-semibold text-base-content mb-6 pb-4 border-b border-base-content/10">Order
                        Summary</h4>

                    <div class="space-y-4 mb-6">
                        @foreach($booking->items as $item)
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex-1">
                                    <div class="text-[14px] font-semibold text-base-content line-clamp-1">
                                        {{ $item->package->name }}
                                    </div>
                                    @if($booking->booking_type !== \App\Enums\BookingType::Event)
                                        <div class="text-[12px] text-base-content/50 font-medium">GH₵
                                            {{ number_format($item->price, 0) }} × {{ $item->quantity }}</div>
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
                            @php $subtotal = (float) $booking->total_amount + (float) $booking->discount_amount; @endphp
                            <div class="flex justify-between items-center text-[14px] text-base-content/50 font-medium">
                                <span>Subtotal</span>
                                <span>GH₵ {{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($booking->discount_amount > 0)
                                <div class="flex justify-between items-center text-[14px] font-medium text-success">
                                    <span>Loyalty Discount</span>
                                    <span>− GH₵ {{ number_format((float) $booking->discount_amount, 2) }}</span>
                                </div>
                            @endif
                        @endif
                        <div class="flex justify-between items-center pt-3">
                            <span class="text-[15px] font-semibold text-base-content">Total Due</span>
                            <span class="text-xl font-bold text-primary">GH₵
                                {{ number_format((float) $booking->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-base-200 border border-base-content/10 rounded-lg flex items-center gap-3">
                        <div
                            class="size-8 bg-base-100 rounded-full flex items-center justify-center shadow-sm shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A3.333 3.333 0 0010 15V5.748a3.333 3.333 0 005.338 2.668 3.333 3.333 0 011.045 4.542 3.333 3.333 0 01-4.765 2.027z" />
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