<div>
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">Payment Methods</h1>
            <p class="text-base-content/50 text-[14px] font-medium mt-1">Manage your saved payment methods for faster checkout.</p>
        </div>
        @if(!$showForm && $methods->isNotEmpty())
            <button wire:click="openForm" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl font-semibold text-[13px] hover:bg-primary/90 transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Payment Method
            </button>
        @endif
    </div>

    <!-- Add/Edit Form -->
    @if($showForm)
        <div class="bg-white border border-base-content/10 rounded-2xl p-6 md:p-10 shadow-sm mb-8 animate-in fade-in slide-in-from-top-4 duration-300">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-2xl font-semibold text-base-content">{{ $editingId ? 'Edit Payment Method' : 'Add New Payment Method' }}</h2>
                    <p class="text-[13px] text-base-content/40 font-medium mt-1">Securely save your payment method details for faster checkout.</p>
                </div>
                <button wire:click="cancel" class="size-10 rounded-full bg-base-200 hover:bg-base-300 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form wire:submit="save" class="space-y-8 max-w-2xl">
                <!-- Label -->
                <x-app.input
                    name="label"
                    type="text"
                    label="Account Label"
                    wire:model="label"
                    placeholder="e.g. My Primary MTN, Business Telecel"
                />

                <!-- Network Selection -->
                <div>
                    <label class="text-dp-sm font-medium text-base-content block mb-3">Select Network</label>
                    <div class="divide-y divide-base-content/5">
                        @php
                            $networks = [
                                ['id' => '13', 'name' => 'MTN Mobile Money', 'logo' => 'logos/mtn-momo.png'],
                                ['id' => '6',  'name' => 'Telecel Cash',      'logo' => 'logos/Telecel-Cash.jpg'],
                                ['id' => '7',  'name' => 'AirtelTigo Money',  'logo' => 'logos/airteltigo-money.png'],
                            ];
                        @endphp
                        @foreach($networks as $network)
                            <button type="button" wire:click="$set('provider', '{{ $network['id'] }}')"
                                class="w-full flex items-center gap-4 px-2 py-4 text-left transition-colors hover:bg-base-200/50 group">
                                <img src="{{ asset($network['logo']) }}" class="size-8 object-contain rounded-md shrink-0" alt="{{ $network['name'] }}">
                                <span class="text-[15px] font-medium text-base-content flex-1">{{ $network['name'] }}</span>
                                <div @class([
                                    'size-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all',
                                    'border-primary' => $provider == $network['id'],
                                    'border-base-content/20 group-hover:border-base-content/40' => $provider != $network['id'],
                                ])>
                                    @if($provider == $network['id'])
                                        <div class="size-2.5 rounded-full bg-primary"></div>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>
                    @error('provider') <p class="text-xs text-error flex items-center gap-1 mt-2"><span>⚠</span> {{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Phone Number -->
                    <x-app.input
                        name="accountNumber"
                        type="tel"
                        label="Phone Number"
                        wire:model.live="accountNumber"
                        inputmode="numeric"
                        maxlength="10"
                        :placeholder="$this->momoPlaceholder"
                        :hint="($provider && strlen($accountNumber) > 0 && strlen($accountNumber) < 10) ? 'Expected: ' . $this->momoPlaceholder : null"
                    >
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </x-slot:icon>
                    </x-app.input>

                    <!-- Account Name -->
                    <x-app.input
                        name="accountName"
                        type="text"
                        label="Account Name (optional)"
                        wire:model="accountName"
                        placeholder="e.g. John Doe"
                    />
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pt-4 border-t border-base-content/5">
                    <!-- Default Toggle -->
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input wire:model="isDefault" type="checkbox" class="peer sr-only" />
                            <div class="w-10 h-6 bg-base-300 rounded-full peer peer-checked:bg-primary transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-4"></div>
                        </div>
                        <span class="text-[13px] font-bold text-base-content/60 group-hover:text-base-content transition-colors">Set as default payment method</span>
                    </label>

                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <button type="submit" @class([
                            'px-8 py-3.5 rounded-xl font-bold text-[13px] uppercase tracking-widest transition-all shadow-lg',
                            'bg-primary text-white hover:bg-primary/90 hover:-translate-y-0.5 active:translate-y-0' => $this->isMomoFormValid,
                            'bg-base-content/10 text-base-content/30 cursor-not-allowed' => !$this->isMomoFormValid,
                        ]) @if(!$this->isMomoFormValid) disabled @endif wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update Payment Method' : 'Save Payment Method' }}</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-2">
                                <span class="loading loading-spinner loading-xs"></span>
                                Saving...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- Payment Methods List -->
    @if($methods->isEmpty() && !$showForm)
        <div class="bg-white border border-base-content/10 rounded-2xl p-12 text-center shadow-sm">
            <div class="size-20 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-6 text-primary/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-base-content mb-3">No saved payment methods</h3>
            <p class="text-base-content/60 text-[14px] max-w-sm mx-auto font-medium mb-8">
                Save your MoMo number here to speed up checkout on future bookings.
            </p>
            <button wire:click="openForm" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-[14px] hover:bg-primary/90 transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Payment Method
            </button>
        </div>
    @elseif($methods->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($methods as $method)
                <div wire:key="pm-{{ $method->id }}" @class([
                    'bg-white border-2 rounded-xl p-6 flex flex-col transition-all hover:shadow-lg hover:-translate-y-1',
                    'border-primary ring-4 ring-primary/5' => $method->is_default,
                    'border-base-content/5' => !$method->is_default,
                ])>
                    <!-- Details Section -->
                    <div class="flex items-start gap-5 flex-1">
                        <!-- Icon/Logo -->
                        <div class="flex-shrink-0">
                            <div class="size-16 rounded-2xl flex items-center justify-center p-3">
                                @if($method->type === \App\Enums\PaymentMethod::MobileMoney)
                                    @php
                                        $logo = match($method->provider) {
                                            '13' => asset('logos/mtn-momo.png'),
                                            '6' => asset('logos/Telecel-Cash.jpg'),
                                            '7' => asset('logos/airteltigo-money.png'),
                                            default => null
                                        };
                                    @endphp
                                    @if($logo)
                                        <img src="{{ $logo }}" class="size-full object-contain" alt="MoMo">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" /></svg>
                                    @endif
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                                @endif
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center flex-wrap gap-2 mb-2">
                                <span class="text-lg font-bold text-base-content truncate">{{ $method->label }}</span>
                                @if($method->is_default)
                                    <span class="px-2 py-0.5 rounded-full bg-black text-white text-[9px] font-black uppercase tracking-wider">Default</span>
                                @endif

                                {{-- Verification Badge --}}
                                @if($method->isVerified())
                                    <span class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-success/10 text-success text-[9px] font-black uppercase tracking-wider">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-warning/10 text-warning text-[9px] font-black uppercase tracking-wider">Unverified</span>
                                @endif
                            </div>
                            <div class="space-y-1.5">
                                <div class="text-[15px] font-mono font-bold tracking-widest text-base-content/80">
                                    {{ $method->account_number }}
                                </div>
                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] font-bold text-base-content/40 uppercase tracking-widest leading-none">
                                    <span>
                                        @switch($method->provider)
                                            @case('13') MTN Mobile Money @break
                                            @case('6') Telecel Cash @break
                                            @case('7') AirtelTigo Money @break
                                            @default Mobile Money
                                        @endswitch
                                    </span>
                                    @if($method->account_name)
                                        <span>&bull;</span>
                                        <span class="truncate">{{ $method->account_name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Section -->
                    <div class="flex flex-wrap gap-2 mt-6 pt-5 border-t border-base-content/5">
                        @if(!$method->isVerified())
                            <button wire:click="resendOtp({{ $method->id }})" class="btn btn-sm border-2 border-primary rounded-lg bg-primary text-white hover:bg-primary hover:text-white flex items-center justify-center transition-all group shadow-sm active:scale-95" title="Verify Now">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A3.333 3.333 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                Verify
                            </button>
                        @elseif(!$method->is_default)
                            <button wire:click="setDefault({{ $method->id }})" class="btn btn-sm border-2 rounded-lg bg-black text-white hover:bg-black/80 hover:text-white flex items-center justify-center transition-all group shadow-sm active:scale-95" title="Set Default">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Set Default
                            </button>
                        @endif
                        <button wire:click="edit({{ $method->id }})" class="btn btn-sm border-2 border-green-600 rounded-lg bg-green-600 text-white hover:bg-green-600 hover:text-white flex items-center justify-center transition-all group shadow-sm active:scale-95" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" /></svg>
                       Edit
                        </button>
                        <button wire:click="delete({{ $method->id }})" wire:confirm="Are you sure you want to remove this account?" class="btn btn-sm border-2 border-primary rounded-lg bg-primary text-white hover:bg-primary hover:text-white flex items-center justify-center transition-all group shadow-sm active:scale-95" title="Remove">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                        Remove
                        </button>
                    </div>
                </div>
                
            @endforeach
        </div>
    @endif
 
    {{-- OTP Verification Modal --}}
    @if($showOtpModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-[2px] animate-in fade-in duration-200">
            <div class="bg-base-100 w-full max-w-[400px] rounded-lg shadow-dp-lg overflow-hidden animate-in zoom-in-95 slide-in-from-bottom-4 duration-300">
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-base-content/10 flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-base-content">Verify Payment Account</h3>
                    <button wire:click="cancel" class="p-1 rounded-md transition-colors hover:bg-base-200 text-base-content/60 hover:text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-6">
                    <div class="text-center mb-8">
                        <div class="size-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-5 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                        </div>
                        <p class="text-[14px] text-base-content/50 font-medium leading-relaxed max-w-xs mx-auto">
                            Enter the 6-digit code sent to your mobile number to verify this payment account.
                        </p>
                    </div>

                    <form wire:submit="verifyOtp" class="space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-dp-sm font-medium text-base-content block">Verification Code</label>
                            <input
                                wire:model="otpCode"
                                type="text"
                                inputmode="numeric"
                                maxlength="6"
                                autofocus
                                placeholder="000000"
                                class="w-full px-[14px] py-[10px] text-center text-2xl font-bold tracking-[0.4em] bg-base-100 border border-base-content/10 rounded-lg transition-all duration-120 outline-none placeholder:text-base-content/20 placeholder:tracking-[0.4em] focus:border-primary focus:ring-3 focus:ring-primary/20"
                            />
                            @error('otpCode')
                                <p class="text-xs text-error flex items-center gap-1">
                                    <span>⚠</span> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <div
                                x-data="{
                                    countdown: 60,
                                    timer: null,
                                    start() {
                                        this.countdown = 60;
                                        clearInterval(this.timer);
                                        this.timer = setInterval(() => {
                                            if (this.countdown > 0) { this.countdown--; }
                                            else { clearInterval(this.timer); }
                                        }, 1000);
                                    }
                                }"
                                x-init="start()"
                            >
                                {{-- Loading state while wire call is in-flight --}}
                                <span wire:loading wire:target="resendOtp" class="text-[13px] text-base-content/50 inline-flex items-center gap-1.5">
                                    <span class="loading loading-spinner loading-xs"></span>
                                    Sending...
                                </span>

                                {{-- Countdown / resend button --}}
                                <button
                                    type="button"
                                    wire:loading.remove wire:target="resendOtp"
                                    wire:click="resendOtp({{ $verifyingId }})"
                                    x-on:click="start()"
                                    :disabled="countdown > 0"
                                    x-bind:class="countdown > 0 ? 'text-base-content/30 cursor-not-allowed' : 'text-primary hover:text-primary/80 cursor-pointer'"
                                    class="text-[13px] font-medium transition-colors inline-flex items-center gap-1.5"
                                >
                                    <template x-if="countdown > 0">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                                            </svg>
                                            <span x-text="`Resend in ${countdown}s`"></span>
                                        </span>
                                    </template>
                                    <template x-if="countdown === 0">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                                            </svg>
                                            Resend Code
                                        </span>
                                    </template>
                                </button>
                            </div>
                            <x-ui.button type="submit" variant="primary" size="md" :loading="false" wire:loading.attr="disabled" wire:target="verifyOtp">
                                <span wire:loading.remove wire:target="verifyOtp">Verify Account</span>
                                <span wire:loading wire:target="verifyOtp" class="flex items-center gap-2">
                                    <span class="loading loading-spinner loading-xs"></span>
                                    Verifying...
                                </span>
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

