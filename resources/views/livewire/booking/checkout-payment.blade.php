<div class="bg-base-200 min-h-screen py-10 lg:py-20 px-4">
    <div class="container mx-auto max-w-6xl">
        <!-- Progress Bar (5 Steps) -->
        <div class="mb-12 lg:mb-16 max-w-4xl mx-auto">
            <div class="flex items-center justify-between relative max-w-3xl mx-auto">
                {{-- Line connector --}}
                <div class="absolute top-5 left-0 w-full h-0.5 border-base-content/10 -z-10"></div>
                <div class="absolute top-5 left-0 h-0.5 bg-primary -z-10 transition-all duration-700" style="width: 75%"></div>

                @foreach(['Review', 'Contact', 'Event', 'Payment', 'Done'] as $index => $label)
                    @php $stepNum = $index + 1; @endphp
                    <div class="flex flex-col items-center gap-3">
                        <div @class([
                            'size-10 rounded-full flex items-center justify-center  text-sm font-bold transition-all duration-500',
                            'bg-primary text-white shadow-xl scale-110 ring-4 ring-dp-rose-soft' => 4 === $stepNum,
                            'bg-primary text-white' => 4 > $stepNum,
                            'bg-base-100 text-dp-text-disabled border-2 border-base-content/10' => 4 < $stepNum,
                        ])>
                            @if(4 > $stepNum)
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @else
                                {{ $stepNum }}
                            @endif
                        </div>
                        <span @class([
                            'text-[10px] uppercase tracking-[0.15em] font-bold hidden sm:block',
                            'text-primary' => 4 === $stepNum,
                            'text-base-content/60' => 4 !== $stepNum,
                        ])>{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <!-- Main Content Column -->
            <div class="lg:col-span-8">
                <div class="bg-base-100 border border-base-content/10 rounded-[24px] overflow-hidden shadow-sm">
                    <div class="p-8 lg:p-12">
                        <div class="mb-10">
                            <h1 class=" text-3xl font-semibold text-base-content mb-2">Secure Payment</h1>
                            <p class="text-base-content/60 text-[14px] font-medium">Coordinate your catering preference for: <span class="text-primary font-bold">{{ $booking->reference }}</span></p>
                        </div>

                        @if ($errorMessage)
                            <div class="mb-10 animate-fade-in">
                                <div class="bg-dp-danger/5 border border-dp-danger/20 p-6 rounded-2xl flex items-start gap-4">
                                    <div class="bg-white size-10 rounded-full shadow-sm shrink-0 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xs font-bold uppercase tracking-widest text-error mb-1">Transaction Failed</h3>
                                        <p class="text-[14px] font-medium text-base-content">{{ $errorMessage }}</p>
                                        <x-ui.button wire:click="retry" variant="ghost" size="sm" class="text-error hover:text-error font-bold !p-0 underline mt-3">
                                            {{ __('Retry with another method') }}
                                        </x-ui.button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Method Selector -->
                        <div class="flex items-center gap-3 p-1.5 bg-base-200 border border-base-content/10 rounded-2xl mb-12 overflow-x-auto scrollbar-hide">
                            @foreach([
                                'mobile_money' => 'Mobile Money',
                                'card' => 'Credit/Debit Card',
                                'bank_transfer' => 'Bank Transfer'
                            ] as $value => $label)
                                <button wire:click="$set('paymentMethod', '{{ $value }}')" @class([
                                    'flex-1 min-w-[140px] py-4 px-6 rounded-xl font-bold text-xs whitespace-nowrap transition-all uppercase tracking-widest',
                                    'bg-base-100 text-primary shadow-sm border border-base-content/10' => $paymentMethod === $value,
                                    'text-base-content/60 hover:text-base-content' => $paymentMethod !== $value,
                                ])>
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>

                        <div class="min-h-[400px]">
                            @if ($paymentMethod === 'mobile_money')
                                <div class="animate-fade-in space-y-12">
                                    <div class="flex justify-center gap-12 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
                                        <span class="font-black text-xl italic tracking-tighter">MTN MoMo</span>
                                        <span class="font-black text-xl italic tracking-tighter">Telecel</span>
                                        <span class="font-black text-xl italic tracking-tighter">AT Money</span>
                                    </div>

                                    <div class="bg-base-200/50 border border-base-content/10 rounded-3xl p-10 text-center">
                                        <div class="size-20 bg-base-100 rounded-full flex items-center justify-center mx-auto mb-8 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                        </div>
                                        <p class="text-base-content font-bold text-xl mb-3">Handset Verification</p>
                                        <p class="text-base-content/60 font-medium text-[15px] max-w-xs mx-auto mb-10 leading-relaxed">Please initiate the transaction. You will receive a prompt on your handset to authorize the payment.</p>
                                        
                                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                            <x-ui.button wire:click="processMobileMoney" wire:loading.attr="disabled" :loading="$loading === 'processMobileMoney'" variant="primary" size="lg" class="shadow-md">
                                                {{ __('Simulate Handset Success') }}
                                            </x-ui.button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($paymentMethod === 'card')
                                <div class="animate-fade-in space-y-10">
                                    <div class="bg-base-200 rounded-3xl p-8 lg:p-10 border border-base-content/10 max-w-md mx-auto relative overflow-hidden">
                                        {{-- Simulated Card Graphic --}}
                                        <div class="absolute -right-8 -bottom-8 size-40 bg-primary/5 rounded-full blur-3xl"></div>
                                        <div class="absolute -left-8 -top-8 size-40 bg-primary/5 rounded-full blur-3xl"></div>

                                        <div class="space-y-6 relative z-10">
                                            <div class="flex justify-between items-center mb-8">
                                                <div class="size-12 bg-base-200-mid rounded-lg"></div>
                                                <div class="font-black text-base-content/60/30 italic">VISA</div>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest">Card Number</label>
                                                <div class="bg-base-100 border border-base-content/10 rounded-xl px-5 py-4 font-mono text-lg tracking-widest text-dp-text-disabled">4242 4242 4242 4242</div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-6">
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest">Expiry</label>
                                                    <div class="bg-base-100 border border-base-content/10 rounded-xl px-5 py-4 font-mono text-lg text-dp-text-disabled text-center">12 / 28</div>
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest">CVC</label>
                                                    <div class="bg-base-100 border border-base-content/10 rounded-xl px-5 py-4 font-mono text-lg text-dp-text-disabled text-center">***</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center max-w-sm mx-auto">
                                        <x-ui.button wire:click="processCard" wire:loading.attr="disabled" :loading="$loading === 'processCard'" variant="primary" size="lg" class="w-full shadow-xl text-lg h-16">
                                            {{ __('Securely Pay GH₵') }} {{ number_format($booking->total_amount, 0) }}
                                        </x-ui.button>
                                        <p class="mt-4 text-[11px] text-base-content/60 font-medium uppercase tracking-[0.2em]">Ensuring 256-bit AES encryption</p>
                                    </div>
                                </div>
                            @endif

                            @if ($paymentMethod === 'bank_transfer')
                                <div class="animate-fade-in space-y-10">
                                    <div class="bg-primary/5 border border-dp-rose/20 rounded-3xl p-8 lg:p-10">
                                        <div class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6">Transfer Credentials</div>
                                        <div class="grid gap-6">
                                            <div class="flex justify-between items-center bg-white/50 p-4 rounded-xl">
                                                <span class="text-base-content/60 font-bold text-xs uppercase tracking-wide">Institution</span>
                                                <span class="text-base-content font-bold">{{ $bankName }}</span>
                                            </div>
                                            <div class="flex justify-between items-center bg-white/50 p-4 rounded-xl">
                                                <span class="text-base-content/60 font-bold text-xs uppercase tracking-wide">Account Name</span>
                                                <span class="text-base-content font-bold">{{ $accountName }}</span>
                                            </div>
                                            @if($branchCode)
                                            <div class="flex justify-between items-center bg-white/50 p-4 rounded-xl">
                                                <span class="text-base-content/60 font-bold text-xs uppercase tracking-wide">Branch/Sort Code</span>
                                                <span class="text-base-content font-bold">{{ $branchCode }}</span>
                                            </div>
                                            @endif
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-primary text-white p-6 rounded-2xl shadow-md">
                                                <span class="font-bold text-xs uppercase tracking-[0.2em] opacity-80 mb-2 sm:mb-0">Account Number</span>
                                                <span class="text-2xl font-black tracking-[0.3em]">{{ $accountNumber }}</span>
                                            </div>

                                        </div>
                                    </div>

                                    <form wire:submit.prevent="submitBankTransfer" class="space-y-6 max-w-lg mx-auto">
                                        <div class="space-y-2">
                                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Sender's Full Name</label>
                                            <input type="text" wire:model="senderName" class="w-full px-5 py-4 bg-base-100 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-sm font-medium" placeholder="As it appears on your account">
                                            @error('senderName') <span class="text-xs font-bold text-error">{{ $message }}</span> @enderror
                                        </div>

                                        <x-ui.button type="submit" variant="primary" size="lg" :loading="$loading === 'submitBankTransfer'" class="w-full shadow-xl text-lg h-16">
                                            {{ __('Confirm Transfer Initiation') }}
                                        </x-ui.button>
                                        <p class="text-[11px] text-base-content/60 font-medium text-center italic">Transfer verification is handled manually by our concierge team.</p>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Summary -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-base-100 border border-base-content/10 rounded-[24px] p-8 lg:p-10 shadow-sm">
                    <h4 class=" text-2xl font-semibold text-base-content mb-8 pb-4 border-b border-base-content/10">Order Summary</h4>
                    
                    <div class="space-y-6 mb-10">
                        @foreach($booking->items as $item)
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex-1">
                                <div class="text-[14px] font-bold text-base-content line-clamp-1">{{ $item->package->name }}</div>
                                @if($booking->booking_type !== \App\Enums\BookingType::Event)
                                    <div class="text-[11px] text-base-content/60 font-medium">GH₵ {{ number_format($item->price, 0) }} × {{ $item->quantity }}</div>
                                @endif
                            </div>
                            @if($booking->booking_type !== \App\Enums\BookingType::Event)
                                <div class="text-[14px] font-bold text-base-content whitespace-nowrap">
                                    GH₵ {{ number_format($item->price * $item->quantity, 0) }}
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-4 pt-8 border-t-2 border-dashed border-base-content/10">
                        @if($booking->booking_type !== \App\Enums\BookingType::Event)
                            <div class="flex justify-between items-center text-[14px] text-base-content/60 font-medium">
                                <span>Subtotal</span>
                                <span>GH₵ {{ number_format((float) $booking->total_amount, 0) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center pt-4">
                            <span class=" text-xl font-bold text-base-content">Total Due</span>
                            <span class=" text-3xl font-bold text-primary">GH₵ {{ number_format((float) $booking->total_amount, 0) }}</span>
                        </div>
                    </div>

                    <div class="mt-10 p-5 bg-base-200 border border-base-content/10 rounded-2xl flex items-center gap-4">
                        <div class="size-10 bg-base-100 rounded-full flex items-center justify-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A3.333 3.333 0 0010 15V5.748a3.333 3.333 0 005.338 2.668 3.333 3.333 0 011.045 4.542 3.333 3.333 0 01-4.765 2.027z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 01-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <div class="text-[11px] text-base-content/60 font-medium leading-relaxed uppercase tracking-widest">
                            Transaction protected by industry-standard encryption
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
