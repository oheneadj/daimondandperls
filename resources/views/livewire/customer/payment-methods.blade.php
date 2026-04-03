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
        <div class="bg-white border border-base-content/10 rounded-2xl p-6 md:p-8 shadow-sm mb-8">
            <h2 class="text-lg font-semibold text-base-content mb-6">
                {{ $editingId ? 'Edit Payment Method' : 'Add Payment Method' }}
            </h2>

            <form wire:submit="save" class="space-y-5">
                <!-- Type -->
                <div>
                    <label class="text-[13px] font-semibold text-base-content/70 mb-1.5 block">Payment Type</label>
                    <select wire:model.live="type" class="w-full bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none">
                        <option value="">Select type...</option>
                        @foreach($allowedTypes as $t)
                            <option value="{{ $t->value }}">
                                @switch($t)
                                    @case(\App\Enums\PaymentMethod::MobileMoney) Mobile Money @break
                                    @case(\App\Enums\PaymentMethod::Card) Card @break
                                    @case(\App\Enums\PaymentMethod::BankTransfer) Bank Transfer @break
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                    @error('type') <span class="text-error text-[12px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Label -->
                <div>
                    <label class="text-[13px] font-semibold text-base-content/70 mb-1.5 block">Label</label>
                    <input wire:model="label" type="text" placeholder="e.g. My MTN MoMo, Visa ending 4242" class="w-full bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none" />
                    @error('label') <span class="text-error text-[12px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Provider -->
                    <div>
                        <label class="text-[13px] font-semibold text-base-content/70 mb-1.5 block">
                            @if($type === 'mobile_money') Network @elseif($type === 'card') Card Network @elseif($type === 'bank_transfer') Bank Name @else Provider @endif
                        </label>
                        <input wire:model="provider" type="text"
                            placeholder="@if($type === 'mobile_money')e.g. MTN, Vodafone, AirtelTigo @elseif($type === 'card')e.g. Visa, Mastercard @elseif($type === 'bank_transfer')e.g. GCB Bank, Ecobank @else Provider name @endif"
                            class="w-full bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none" />
                        @error('provider') <span class="text-error text-[12px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Account Number -->
                    <div>
                        <label class="text-[13px] font-semibold text-base-content/70 mb-1.5 block">
                            @if($type === 'mobile_money') Phone Number @elseif($type === 'card') Last 4 Digits @elseif($type === 'bank_transfer') Account Number @else Account / Number @endif
                        </label>
                        <input wire:model="accountNumber" type="text"
                            placeholder="@if($type === 'mobile_money')e.g. 024XXXXXXX @elseif($type === 'card')e.g. 4242 @elseif($type === 'bank_transfer')e.g. 1234567890 @else Account number @endif"
                            class="w-full bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none" />
                        @error('accountNumber') <span class="text-error text-[12px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Account Name -->
                <div>
                    <label class="text-[13px] font-semibold text-base-content/70 mb-1.5 block">Account Name</label>
                    <input wire:model="accountName" type="text" placeholder="Name on account (optional)" class="w-full bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none" />
                    @error('accountName') <span class="text-error text-[12px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Default Toggle -->
                <label class="flex items-center gap-3 cursor-pointer">
                    <input wire:model="isDefault" type="checkbox" class="checkbox checkbox-primary checkbox-sm" />
                    <span class="text-[13px] font-medium text-base-content/70">Set as default payment method</span>
                </label>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update' : 'Save' }}</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                    <button type="button" wire:click="cancel" class="btn btn-ghost btn-sm">Cancel</button>
                </div>
            </form>
        </div>
    @endif

    <!-- Payment Methods List -->
    @if($methods->isEmpty() && !$showForm)
        <div class="bg-white border border-base-content/10 rounded-2xl p-12 text-center shadow-sm">
            <div class="size-20 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-6 text-primary/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-base-content mb-3">No payment methods yet</h3>
            <p class="text-base-content/60 text-[14px] max-w-md mx-auto font-medium mb-6">
                Add a payment method to make future checkouts faster and easier.
            </p>
            <button wire:click="openForm" class="btn btn-primary btn-sm">Add Your First Payment Method</button>
        </div>
    @elseif($methods->isNotEmpty())
        <div class="space-y-4">
            @foreach($methods as $method)
                <div wire:key="pm-{{ $method->id }}" class="bg-white border border-base-content/10 rounded-2xl p-5 md:p-6 shadow-sm flex flex-col md:flex-row md:items-center gap-4 md:gap-6 transition-all hover:shadow-md">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <div class="size-12 rounded-xl flex items-center justify-center
                            @switch($method->type)
                                @case(\App\Enums\PaymentMethod::MobileMoney) bg-[#FFC926]/10 text-[#FFC926] @break
                                @case(\App\Enums\PaymentMethod::Card) bg-primary/10 text-primary @break
                                @case(\App\Enums\PaymentMethod::BankTransfer) bg-[#9ABC05]/10 text-[#9ABC05] @break
                                @default bg-base-200 text-base-content/40
                            @endswitch
                        ">
                            @switch($method->type)
                                @case(\App\Enums\PaymentMethod::MobileMoney)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                    @break
                                @case(\App\Enums\PaymentMethod::Card)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                    </svg>
                                    @break
                                @case(\App\Enums\PaymentMethod::BankTransfer)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[15px] font-semibold text-base-content truncate">{{ $method->label }}</span>
                            @if($method->is_default)
                                <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-wide">Default</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[13px] text-base-content/60 font-medium">
                            <span>
                                @switch($method->type)
                                    @case(\App\Enums\PaymentMethod::MobileMoney) Mobile Money @break
                                    @case(\App\Enums\PaymentMethod::Card) Card @break
                                    @case(\App\Enums\PaymentMethod::BankTransfer) Bank Transfer @break
                                @endswitch
                            </span>
                            @if($method->provider)
                                <span>&middot; {{ $method->provider }}</span>
                            @endif
                            <span>&middot; {{ $method->account_number }}</span>
                            @if($method->account_name)
                                <span>&middot; {{ $method->account_name }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if(!$method->is_default)
                            <button wire:click="setDefault({{ $method->id }})" class="btn btn-ghost btn-xs text-base-content/50 hover:text-primary" title="Set as default">
                                Set Default
                            </button>
                        @endif
                        <button wire:click="edit({{ $method->id }})" class="btn btn-ghost btn-xs text-base-content/50 hover:text-primary" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                            </svg>
                        </button>
                        <button wire:click="delete({{ $method->id }})" wire:confirm="Are you sure you want to remove this payment method?" class="btn btn-ghost btn-xs text-base-content/50 hover:text-error" title="Remove">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
