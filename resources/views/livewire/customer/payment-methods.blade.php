<div>
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-semibold text-base-content mb-2">Payment Methods</h1>
            <p class="text-base-content/60 text-[15px] font-medium">Manage your saved payment methods for faster checkout.</p>
        </div>
        @if(!$showForm)
            <button wire:click="openForm" class="btn btn-primary btn-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Payment Method
            </button>
        @endif
    </div>

    <!-- Add/Edit Form -->
    @if($showForm)
        <div class="bg-white border border-base-content/10 rounded-[24px] p-6 md:p-10 shadow-sm mb-12 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-2xl font-semibold text-base-content">{{ $editingId ? 'Edit Payment Method' : 'Add New MoMo Account' }}</h2>
                    <p class="text-[13px] text-base-content/40 font-medium mt-1">Securely save your mobile money details for faster checkout.</p>
                </div>
                <button wire:click="cancel" class="size-10 rounded-full bg-base-200 hover:bg-base-300 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form wire:submit="save" class="space-y-8 max-w-2xl">
                <!-- Label -->
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-base-content/60 uppercase tracking-widest ml-1">Account Label</label>
                    <input wire:model="label" type="text" placeholder="e.g. My Primary MTN, Business MoMo" class="w-full bg-base-100 border border-base-content/10 px-5 py-4 rounded-xl text-sm font-medium focus:border-primary focus:ring-4 focus:ring-primary/20 transition-all" />
                    @error('label') <span class="text-[11px] font-bold text-error mt-1 block ml-1 uppercase tracking-wider">{{ $message }}</span> @enderror
                </div>

                <!-- Network Selection -->
                <div>
                    <div class="text-[11px] font-bold text-base-content/60 uppercase tracking-widest mb-4 ml-1">Select Network</div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        {{-- MTN --}}
                        <button type="button" wire:click="$set('provider', '13')" @class([
                            'flex items-center gap-3 px-4 py-3 rounded-xl border-2 transition-all text-left group',
                            'bg-primary/5 border-primary shadow-sm' => $provider == '13',
                            'bg-base-100 border-base-content/10 hover:border-primary/40' => $provider != '13',
                        ])>
                            <img src="{{ asset('logos/mtn-momo.png') }}" class="size-7 object-contain rounded-md" alt="MTN">
                            <span @class(['text-[13px] font-bold', 'text-primary' => $provider == '13', 'text-base-content/60' => $provider != '13'])>MTN</span>
                            @if($provider == '13')
                                <div class="ml-auto size-4 rounded-full bg-primary flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                            @endif
                        </button>

                        {{-- Telecel --}}
                        <button type="button" wire:click="$set('provider', '6')" @class([
                            'flex items-center gap-3 px-4 py-3 rounded-xl border-2 transition-all text-left group',
                            'bg-primary/5 border-primary shadow-sm' => $provider == '6',
                            'bg-base-100 border-base-content/10 hover:border-primary/40' => $provider != '6',
                        ])>
                            <img src="{{ asset('logos/Telecel-Cash.jpg') }}" class="size-7 object-contain rounded-md" alt="Telecel">
                            <span @class(['text-[13px] font-bold', 'text-primary' => $provider == '6', 'text-base-content/60' => $provider != '6'])>Telecel</span>
                            @if($provider == '6')
                                <div class="ml-auto size-4 rounded-full bg-primary flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                            @endif
                        </button>

                        {{-- AT --}}
                        <button type="button" wire:click="$set('provider', '7')" @class([
                            'flex items-center gap-3 px-4 py-3 rounded-xl border-2 transition-all text-left group',
                            'bg-primary/5 border-primary shadow-sm' => $provider == '7',
                            'bg-base-100 border-base-content/10 hover:border-primary/40' => $provider != '7',
                        ])>
                            <img src="{{ asset('logos/airteltigo-money.png') }}" class="size-7 object-contain rounded-md" alt="AT">
                            <span @class(['text-[13px] font-bold', 'text-primary' => $provider == '7', 'text-base-content/60' => $provider != '7'])>AT</span>
                            @if($provider == '7')
                                <div class="ml-auto size-4 rounded-full bg-primary flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                            @endif
                        </button>
                    </div>
                    @error('provider') <span class="text-[11px] font-bold text-error mt-2 block ml-1 uppercase tracking-wider">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Phone Number -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Phone Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <input wire:model.live="accountNumber" type="tel" inputmode="numeric" maxlength="10" placeholder="{{ $this->momoPlaceholder }}" class="w-full pl-12 pr-5 py-4 bg-base-100 border border-base-content/10 rounded-xl text-[17px] font-bold tracking-widest focus:border-primary focus:ring-4 focus:ring-primary/20 transition-all placeholder:font-medium placeholder:tracking-normal placeholder:text-sm" />
                        </div>
                        
                        {{-- Validation Hint --}}
                        @if($provider && strlen($accountNumber) > 0 && strlen($accountNumber) < 10)
                            <p class="text-[11px] font-medium text-base-content/40 ml-1 italic animate-pulse">
                                Matches: 024, 054, 055...
                            </p>
                        @endif

                        @error('accountNumber') <span class="text-[11px] font-bold text-error mt-1 block ml-1 uppercase tracking-wider">{{ $message }}</span> @enderror
                    </div>

                    <!-- Account Name -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Account Name <span class="text-base-content/30 lowercase">(optional)</span></label>
                        <input wire:model="accountName" type="text" placeholder="e.g. John Doe" class="w-full bg-base-100 border border-base-content/10 px-5 py-4 rounded-xl text-sm font-medium focus:border-primary focus:ring-4 focus:ring-primary/20 transition-all" />
                        @error('accountName') <span class="text-[11px] font-bold text-error mt-1 block ml-1 uppercase tracking-wider">{{ $message }}</span> @enderror
                    </div>
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
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update Account' : 'Save MoMo Account' }}</span>
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
        <div class="bg-white border border-base-content/10 rounded-[32px] p-16 md:p-24 text-center shadow-sm">
            <div class="size-24 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-8 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-base-content mb-3">No saved accounts</h3>
            <p class="text-base-content/50 text-[15px] max-w-sm mx-auto font-medium mb-10 leading-relaxed">
                Save your mobile money accounts here to speed up your future bookings.
            </p>
            <button wire:click="openForm" class="bg-primary text-white px-8 py-4 rounded-full font-black uppercase tracking-widest text-[13px] shadow-xl shadow-primary/20 hover:-translate-y-1 transition-all">
                Add Your First Account
            </button>
        </div>
    @elseif($methods->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($methods as $method)
                <div wire:key="pm-{{ $method->id }}" @class([
                    'bg-white border-2 rounded-[28px] p-6 shadow-sm flex items-start gap-5 transition-all hover:shadow-lg hover:-translate-y-1',
                    'border-primary/20 ring-4 ring-primary/5' => $method->is_default,
                    'border-base-content/5' => !$method->is_default,
                ])>
                    <!-- Icon/Logo -->
                    <div class="flex-shrink-0">
                        <div class="size-16 rounded-2xl bg-base-100 border border-base-content/5 flex items-center justify-center p-3 shadow-inner">
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

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-lg font-bold text-base-content truncate">{{ $method->label }}</span>
                            @if($method->is_default)
                                <span class="px-2 py-0.5 rounded-full bg-primary text-white text-[9px] font-black uppercase tracking-wider">Default</span>
                            @endif
                        </div>
                        <div class="space-y-1.5">
                            <div class="text-[15px] font-mono font-bold tracking-widest text-base-content/80">
                                {{ $method->account_number }}
                            </div>
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] font-bold text-base-content/40 uppercase tracking-widest leading-none">
                                <span>
                                    @switch($method->provider)
                                        @case('13') MTN MoMo @break
                                        @case('6') Telecel Cash @break
                                        @case('7') AT Money @break
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

                    <!-- Actions -->
                    <div class="flex flex-col gap-2 ml-2">
                        @if(!$method->is_default)
                            <button wire:click="setDefault({{ $method->id }})" class="size-10 rounded-xl bg-primary/10 text-primary hover:bg-primary hover:text-white flex items-center justify-center transition-all group shadow-sm active:scale-95" title="Set Default">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            </button>
                        @endif
                        <button wire:click="edit({{ $method->id }})" class="size-10 rounded-xl bg-orange-500/10 text-orange-600 hover:bg-orange-600 hover:text-white flex items-center justify-center transition-all group shadow-sm active:scale-95" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" /></svg>
                        </button>
                        <button wire:click="delete({{ $method->id }})" wire:confirm="Are you sure you want to remove this account?" class="size-10 rounded-xl bg-error/10 text-error hover:bg-error hover:text-white flex items-center justify-center transition-all group shadow-sm active:scale-95" title="Remove">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

