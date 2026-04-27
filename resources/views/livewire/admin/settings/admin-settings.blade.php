<div class="space-y-6 pb-10">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-base-content">{{ __('Settings') }}</h1>
        <p class="text-sm text-base-content/50 mt-0.5">{{ __('Manage your business profile, payment gateway, and system preferences.') }}</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex items-center gap-1 bg-base-200 p-1 rounded-xl w-full sm:w-fit overflow-x-auto">
        @foreach([
            'company' => ['label' => 'Company',       'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5'],
            'app'     => ['label' => 'App Settings',  'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
            'system'  => ['label' => 'System',        'icon' => 'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18'],
        ] as $key => $tabInfo)
            <button wire:click="setTab('{{ $key }}')"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-200 whitespace-nowrap
                    {{ $tab === $key
                        ? 'bg-white text-primary shadow-sm'
                        : 'text-base-content/50 hover:text-base-content hover:bg-base-200' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="{{ $tabInfo['icon'] }}"/>
                </svg>
                {{ __($tabInfo['label']) }}
            </button>
        @endforeach
    </div>

    {{-- ── Company Tab ─────────────────────────────────────────────────────── --}}
    @if($tab === 'company')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 animate-in fade-in slide-in-from-bottom-2 duration-300">

            {{-- Business Identity (left) --}}
            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl border border-base-content/5 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-base-content/5">
                        <h3 class="text-sm font-bold text-base-content">{{ __('Business Identity') }}</h3>
                        <p class="text-xs text-base-content/40 mt-0.5">{{ __('Your public-facing business name, contact info, and branding.') }}</p>
                    </div>
                    <form class="px-6 py-5 space-y-5"
                        x-data="{ uploading: false }"
                        @filepond-start.document="uploading = true"
                        @filepond-end.document="uploading = false"
                        @submit.prevent="if (!uploading) $wire.saveBusinessInfo()"
                    >
                        {{-- Logo Upload --}}
                        <div class="flex flex-col sm:flex-row items-center gap-6 pb-5 border-b border-base-content/5">
                            <div class="w-28 h-28 shrink-0"
                                data-logo-url="{{ $current_logo_path ? Storage::url($current_logo_path) : '' }}"
                                x-data="{
                                    pond: null,
                                    initPond() {
                                        if (!window.FilePond) { setTimeout(() => this.initPond(), 100); return; }
                                        if (this.pond) return;
                                        this.pond = window.FilePond.create($refs.logoPond, {
                                            labelIdle: '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-7 h-7 opacity-20\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\' /></svg>',
                                            imagePreviewHeight: 112,
                                            imageCropAspectRatio: '1:1',
                                            imageResizeTargetWidth: 256,
                                            imageResizeTargetHeight: 256,
                                            stylePanelLayout: 'compact circle',
                                            styleLoadIndicatorPosition: 'center bottom',
                                            styleProgressIndicatorPosition: 'right bottom',
                                            styleButtonRemoveItemPosition: 'left bottom',
                                            styleButtonProcessItemPosition: 'right bottom',
                                            allowImagePreview: true,
                                            credits: false,
                                            acceptedFileTypes: ['image/png', 'image/webp', 'image/jpeg'],
                                            server: {
                                                process: (fieldName, file, metadata, load, error, progress, abort) => {
                                                    document.dispatchEvent(new CustomEvent('filepond-start'));
                                                    $wire.upload('business_logo', file,
                                                        (uploadedFilename) => { load(uploadedFilename); document.dispatchEvent(new CustomEvent('filepond-end')); },
                                                        () => { error('Upload failed'); document.dispatchEvent(new CustomEvent('filepond-end')); },
                                                        (progressValue) => { progress(true, progressValue, 100); }
                                                    );
                                                }
                                            }
                                        });
                                        const logoUrl = $el.dataset.logoUrl;
                                        if (logoUrl) {
                                            fetch(logoUrl).then(res => res.blob()).then(blob => {
                                                const parts = logoUrl.split('/');
                                                const file = new File([blob], parts[parts.length - 1], { type: blob.type });
                                                this.pond.addFile(file, { index: 0 });
                                            }).catch(err => console.warn('Logo preview failed:', err));
                                        }
                                    }
                                }"
                                x-init="initPond"
                                wire:ignore
                            >
                                <div class="filepond-container-refined profile-pond-wrapper">
                                    <input type="file" x-ref="logoPond" />
                                </div>
                            </div>
                            <div class="flex-1 space-y-2">
                                <p class="text-sm font-semibold text-base-content">{{ __('Business Logo') }}</p>
                                <p class="text-xs text-base-content/40 leading-relaxed">PNG, WebP, or JPEG — max 2 MB. Used on invoices and public pages.</p>
                                @error('business_logo')
                                    <p class="text-xs text-error font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <x-ui.input label="Business Name" wire:model="business_name" required placeholder="e.g. Diamonds & Pearls" :error="$errors->first('business_name')" />

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-ui.input label="Phone Number" wire:model="business_phone" required placeholder="+233 ..." :error="$errors->first('business_phone')" />
                            <x-ui.input label="WhatsApp Number" wire:model="business_whatsapp" placeholder="233244... (no +)" :error="$errors->first('business_whatsapp')" />
                        </div>

                        <x-ui.input label="Email Address" wire:model="business_email" type="email" required placeholder="contact@example.com" :error="$errors->first('business_email')" />

                        <x-ui.textarea label="Business Address" wire:model="business_address" rows="2" required placeholder="House No, Street, City" :error="$errors->first('business_address')" />

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                x-bind:disabled="uploading"
                                wire:loading.attr="disabled"
                                class="btn border-none inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl bg-primary text-primary-content hover:brightness-110 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span x-show="uploading" class="flex items-center gap-2">
                                    <span class="loading loading-spinner loading-xs"></span> {{ __('Uploading...') }}
                                </span>
                                <span x-show="!uploading" wire:loading.remove wire:target="saveBusinessInfo">{{ __('Save Changes') }}</span>
                                <span wire:loading wire:target="saveBusinessInfo" class="flex items-center gap-2">
                                    <span class="loading loading-spinner loading-xs"></span> {{ __('Saving...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right column --}}
            <div class="lg:col-span-5 space-y-6">

                {{-- Bank Details --}}
                <div class="bg-white rounded-2xl border border-base-content/5 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-base-content/5">
                        <h3 class="text-sm font-bold text-base-content">{{ __('Bank Transfer Details') }}</h3>
                        <p class="text-xs text-base-content/40 mt-0.5">{{ __('Shown on invoices for manual bank payments.') }}</p>
                    </div>
                    <form wire:submit.prevent="saveBankDetails" class="px-6 py-5 space-y-4">
                        <x-ui.input label="Bank Name" wire:model="bank_name" placeholder="e.g. Ecobank Ghana" required :error="$errors->first('bank_name')" />
                        <x-ui.input label="Account Name" wire:model="account_name" placeholder="e.g. Diamonds & Pearls Ltd" required :error="$errors->first('account_name')" />
                        <div class="grid grid-cols-2 gap-4">
                            <x-ui.input label="Account Number" wire:model="account_number" placeholder="0012345678" required :error="$errors->first('account_number')" />
                            <x-ui.input label="Branch Code" wire:model="branch_code" placeholder="Optional" :error="$errors->first('branch_code')" />
                        </div>
                        <div class="flex justify-end pt-2">
                            <x-ui.button type="submit" variant="secondary" size="sm" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="saveBankDetails">{{ __('Save Details') }}</span>
                                <span wire:loading wire:target="saveBankDetails">{{ __('Saving...') }}</span>
                            </x-ui.button>
                        </div>
                    </form>
                </div>

            </div>

            {{-- Social Media (full width) --}}
            <div class="lg:col-span-12">
                <div class="bg-white rounded-2xl border border-base-content/5 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-base-content/5">
                        <h3 class="text-sm font-bold text-base-content">{{ __('Social Media') }}</h3>
                        <p class="text-xs text-base-content/40 mt-0.5">{{ __('Links displayed on the public About page.') }}</p>
                    </div>
                    <form wire:submit.prevent="saveSocialLinks" class="px-6 py-5">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            {{-- Facebook --}}
                            <div class="flex items-center gap-3 bg-base-100 border border-base-content/8 rounded-xl px-4 py-3 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#1877F2] shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.696 4.533-4.696 1.313 0 2.686.236 2.686.236v2.97h-1.513c-1.491 0-1.956.93-1.956 1.886v2.267h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                                </svg>
                                <input type="url" wire:model="social_facebook" placeholder="facebook.com/yourpage"
                                    class="flex-1 bg-transparent text-sm text-base-content placeholder:text-base-content/30 outline-none min-w-0" />
                            </div>
                            {{-- Instagram --}}
                            <div class="flex items-center gap-3 bg-base-100 border border-base-content/8 rounded-xl px-4 py-3 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none">
                                    <defs>
                                        <linearGradient id="ig" x1="0%" y1="100%" x2="100%" y2="0%">
                                            <stop offset="0%" stop-color="#FED373"/>
                                            <stop offset="30%" stop-color="#F15245"/>
                                            <stop offset="60%" stop-color="#D92E7F"/>
                                            <stop offset="100%" stop-color="#9B36B7"/>
                                        </linearGradient>
                                    </defs>
                                    <rect width="24" height="24" rx="6" fill="url(#ig)"/>
                                    <circle cx="12" cy="12" r="4" stroke="white" stroke-width="1.8" fill="none"/>
                                    <circle cx="17.5" cy="6.5" r="1.2" fill="white"/>
                                </svg>
                                <input type="url" wire:model="social_instagram" placeholder="instagram.com/yourhandle"
                                    class="flex-1 bg-transparent text-sm text-base-content placeholder:text-base-content/30 outline-none min-w-0" />
                            </div>
                            {{-- TikTok --}}
                            <div class="flex items-center gap-3 bg-base-100 border border-base-content/8 rounded-xl px-4 py-3 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-base-content shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.37 6.37 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.27 8.27 0 004.83 1.54V6.79a4.85 4.85 0 01-1.06-.1z"/>
                                </svg>
                                <input type="url" wire:model="social_tiktok" placeholder="tiktok.com/@yourhandle"
                                    class="flex-1 bg-transparent text-sm text-base-content placeholder:text-base-content/30 outline-none min-w-0" />
                            </div>
                        </div>
                        @foreach(['social_facebook', 'social_instagram', 'social_tiktok'] as $field)
                            @error($field) <p class="text-xs text-error mt-2">{{ $message }}</p> @enderror
                        @endforeach
                        <div class="flex justify-end pt-4">
                            <x-ui.button type="submit" variant="secondary" size="sm" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="saveSocialLinks">{{ __('Save Links') }}</span>
                                <span wire:loading wire:target="saveSocialLinks">{{ __('Saving...') }}</span>
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    @endif

    {{-- ── App Settings Tab ─────────────────────────────────────────────────── --}}
    @if($tab === 'app')
        <div class="space-y-6 animate-in fade-in slide-in-from-bottom-2 duration-300">

            {{-- Payment Gateway --}}
            <div class="bg-white rounded-2xl border border-base-content/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-base-content/5">
                    <h3 class="text-sm font-bold text-base-content">{{ __('Payment Gateway') }}</h3>
                    <p class="text-xs text-base-content/40 mt-0.5">{{ __('Select which gateway processes customer payments. Changes take effect immediately.') }}</p>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Transflow option --}}
                        <button type="button" wire:click="$set('active_payment_gateway', 'transflow')"
                            @class([
                                'relative text-left rounded-xl border-2 p-5 transition-all duration-150',
                                'border-primary bg-primary/5 shadow-sm' => $active_payment_gateway === 'transflow',
                                'border-base-content/10 hover:border-base-content/20 bg-base-100' => $active_payment_gateway !== 'transflow',
                            ])>
                            @if($active_payment_gateway === 'transflow')
                                <span class="absolute top-3 right-3 inline-flex items-center gap-1 text-[10px] font-bold text-primary uppercase tracking-wide bg-primary/10 px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary inline-block"></span> Active
                                </span>
                            @endif
                            <div class="flex items-center gap-3 mb-3">
                                <div @class([
                                    'w-9 h-9 rounded-lg flex items-center justify-center shrink-0',
                                    'bg-primary/15' => $active_payment_gateway === 'transflow',
                                    'bg-base-200' => $active_payment_gateway !== 'transflow',
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $active_payment_gateway === 'transflow' ? 'text-primary' : 'text-base-content/40' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-base-content">Transflow</p>
                                    <p class="text-[10px] text-base-content/40 font-medium">by ITConsortium</p>
                                </div>
                            </div>
                            <p class="text-xs text-base-content/60 leading-relaxed">Hosted checkout page. Supports MoMo (all networks) and card payments. Customer is redirected to Transflow's secure page to pay.</p>
                        </button>

                        {{-- Moolre option --}}
                        <button type="button" wire:click="$set('active_payment_gateway', 'moolre')"
                            @class([
                                'relative text-left rounded-xl border-2 p-5 transition-all duration-150',
                                'border-primary bg-primary/5 shadow-sm' => $active_payment_gateway === 'moolre',
                                'border-base-content/10 hover:border-base-content/20 bg-base-100' => $active_payment_gateway !== 'moolre',
                            ])>
                            @if($active_payment_gateway === 'moolre')
                                <span class="absolute top-3 right-3 inline-flex items-center gap-1 text-[10px] font-bold text-primary uppercase tracking-wide bg-primary/10 px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary inline-block"></span> Active
                                </span>
                            @endif
                            <div class="flex items-center gap-3 mb-3">
                                <div @class([
                                    'w-9 h-9 rounded-lg flex items-center justify-center shrink-0',
                                    'bg-primary/15' => $active_payment_gateway === 'moolre',
                                    'bg-base-200' => $active_payment_gateway !== 'moolre',
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $active_payment_gateway === 'moolre' ? 'text-primary' : 'text-base-content/40' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-base-content">Moolre</p>
                                    <p class="text-[10px] text-base-content/40 font-medium">Direct MoMo push</p>
                                </div>
                            </div>
                            <p class="text-xs text-base-content/60 leading-relaxed">Customer enters their MoMo number directly on our checkout. A payment prompt is pushed to their phone. MoMo only — no card support.</p>
                        </button>

                    </div>

                    <div class="flex justify-end mt-4">
                        <x-ui.button type="button" variant="primary" size="sm" wire:click="savePaymentGateway" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="savePaymentGateway">{{ __('Apply Gateway') }}</span>
                            <span wire:loading wire:target="savePaymentGateway">{{ __('Saving...') }}</span>
                        </x-ui.button>
                    </div>
                </div>
            </div>

            {{-- Notification Preference --}}
            <div class="bg-white rounded-2xl border border-base-content/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-base-content/5">
                    <h3 class="text-sm font-bold text-base-content">{{ __('System Notification Preference') }}</h3>
                    <p class="text-xs text-base-content/40 mt-0.5">{{ __('Channels for admin notifications') }}</p>
                </div>
                <div class="px-6 py-5 space-y-3">
                    @foreach([
                        ['value' => 'email', 'label' => 'Email Only',    'desc' => 'Receive alerts via email only'],
                        ['value' => 'sms',   'label' => 'SMS Only',      'desc' => 'Receive alerts via SMS only'],
                        ['value' => 'both',  'label' => 'Email & SMS',   'desc' => 'Receive alerts via both channels'],
                    ] as $option)
                        <label class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-all
                            {{ $notification_preference === $option['value'] ? 'border-primary/25 bg-primary/[0.03]' : 'border-base-content/8 hover:border-base-content/15' }}">
                            <input type="radio" wire:model.live="notification_preference" value="{{ $option['value'] }}" class="radio radio-primary radio-sm">
                            <div>
                                <p class="text-sm font-semibold text-base-content">{{ __($option['label']) }}</p>
                                <p class="text-xs text-base-content/50 mt-0.5">{{ __($option['desc']) }}</p>
                            </div>
                        </label>
                    @endforeach
                    @error('notification_preference')
                        <p class="text-xs text-error font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="px-6 py-4 border-t border-base-content/5 bg-base-200/30 flex justify-end">
                    <button wire:click="saveNotificationPreference" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-bold text-[13px] hover:bg-primary/90 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveNotificationPreference">{{ __('Save Preference') }}</span>
                        <span wire:loading wire:target="saveNotificationPreference" class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-xs"></span>
                            {{ __('Saving...') }}
                        </span>
                    </button>
                </div>
            </div>

            {{-- Delivery Locations --}}
            <div class="bg-white rounded-2xl border border-base-content/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-base-content/5 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-bold text-base-content">{{ __('Delivery Locations') }}</h3>
                        <p class="text-xs text-base-content/40 mt-0.5">{{ __('Zones customers choose from when placing a meal order.') }}</p>
                    </div>
                    <x-ui.button type="button" variant="outline" size="sm" wire:click="openAddLocationModal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('Add') }}
                    </x-ui.button>
                </div>
                <div class="px-6 py-5">
                    @if(empty($delivery_locations))
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <div class="w-12 h-12 bg-base-200 rounded-full flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-base-content/50">No delivery zones yet</p>
                            <p class="text-xs text-base-content/35 mt-1 max-w-xs">Customers won't be asked to pick a delivery location until you add one.</p>
                            <x-ui.button type="button" variant="outline" size="sm" class="mt-4" wire:click="openAddLocationModal">
                                Add first location
                            </x-ui.button>
                        </div>
                    @else
                        <div class="divide-y divide-base-content/5">
                            @foreach($delivery_locations as $i => $location)
                                <div class="flex items-center gap-3 py-3 group" wire:key="loc-{{ $i }}">
                                    <div class="flex flex-col gap-0.5 shrink-0">
                                        <button type="button" wire:click="moveLocationUp({{ $i }})"
                                            @class(['p-1 rounded text-base-content/25 hover:text-base-content transition-colors', 'opacity-20 pointer-events-none' => $i === 0])>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                        </button>
                                        <button type="button" wire:click="moveLocationDown({{ $i }})"
                                            @class(['p-1 rounded text-base-content/25 hover:text-base-content transition-colors', 'opacity-20 pointer-events-none' => $i === count($delivery_locations) - 1])>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                    </div>
                                    <span class="flex-1 text-sm text-base-content font-medium">{{ $location }}</span>
                                    <span class="text-[10px] font-bold text-base-content/25 tabular-nums">#{{ $i + 1 }}</span>
                                    <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" wire:click="openEditLocationModal({{ $i }})"
                                            class="p-1.5 rounded-lg text-base-content/40 hover:text-primary hover:bg-primary/5 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button type="button" wire:click="removeDeliveryLocation({{ $i }})"
                                            wire:confirm="Remove '{{ $location }}'?"
                                            class="p-1.5 rounded-lg text-base-content/40 hover:text-error hover:bg-error/5 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Event Booking Rules --}}
            <div class="bg-white rounded-2xl border border-base-content/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-base-content/5">
                    <h3 class="text-sm font-bold text-base-content">{{ __('Event Booking Rules') }}</h3>
                    <p class="text-xs text-base-content/40 mt-0.5">{{ __('Minimum notice required before an event can be booked.') }}</p>
                </div>
                <form wire:submit.prevent="saveEventSettings" class="px-6 py-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-base-content/60 uppercase tracking-wide">{{ __('Minimum Lead Time') }}</label>
                            <select wire:model="event_lead_days"
                                class="w-full px-4 py-2.5 bg-base-100 border border-base-content/10 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl text-sm font-medium transition-all">
                                <option value="0">No minimum (any future date)</option>
                                <optgroup label="Days">
                                    <option value="1">1 day ahead</option>
                                    <option value="2">2 days ahead</option>
                                    <option value="3">3 days ahead</option>
                                    <option value="5">5 days ahead</option>
                                </optgroup>
                                <optgroup label="Weeks">
                                    <option value="7">1 week ahead</option>
                                    <option value="14">2 weeks ahead</option>
                                    <option value="21">3 weeks ahead</option>
                                    <option value="28">4 weeks ahead</option>
                                </optgroup>
                            </select>
                            @error('event_lead_days') <p class="text-xs text-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="bg-base-200/60 rounded-xl p-4 border border-base-content/5">
                            <p class="text-xs font-semibold text-base-content/50 uppercase tracking-wide mb-1">{{ __('Current effect') }}</p>
                            @if($event_lead_days > 0)
                                <p class="text-xs text-base-content/70 leading-relaxed">
                                    Earliest bookable date is <strong class="text-primary">{{ now()->addDays($event_lead_days)->format('M j, Y') }}</strong>
                                    (today + {{ $event_lead_days }} {{ $event_lead_days === 1 ? 'day' : 'days' }}).
                                </p>
                            @else
                                <p class="text-xs text-base-content/50">Any future date is accepted — no minimum enforced.</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <x-ui.button type="submit" variant="secondary" size="sm" wire:loading.attr="disabled" wire:target="saveEventSettings">
                            <span wire:loading.remove wire:target="saveEventSettings">{{ __('Save Rules') }}</span>
                            <span wire:loading wire:target="saveEventSettings">{{ __('Saving...') }}</span>
                        </x-ui.button>
                    </div>
                </form>
            </div>

        </div>
    @endif

    {{-- ── System Tab ───────────────────────────────────────────────────────── --}}
    @if($tab === 'system')
        <div class="space-y-6 animate-in fade-in slide-in-from-bottom-2 duration-300">

            {{-- KPI row --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach([
                    ['label' => 'PHP Version',    'value' => $this->systemStats['server']['php'],         'color' => 'text-[#9ABC05]', 'bg' => 'bg-[#9ABC05]/10',  'icon' => 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01'],
                    ['label' => 'Database',       'value' => strtoupper($this->systemStats['database']['driver']), 'color' => 'text-[#F96015]', 'bg' => 'bg-[#F96015]/10', 'icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4'],
                    ['label' => 'Queue Driver',   'value' => strtoupper($this->systemStats['queue']['driver']),   'color' => 'text-primary',   'bg' => 'bg-primary/10',   'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    ['label' => 'Server Uptime',  'value' => $this->systemStats['server']['uptime'],      'color' => 'text-[#FFC926]', 'bg' => 'bg-[#FFC926]/10', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ] as $stat)
                    <div class="bg-white border border-base-content/5 rounded-2xl p-4 flex items-center gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-xl {{ $stat['bg'] }} flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $stat['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-bold text-base-content">{{ $stat['value'] }}</p>
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-base-content/40">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Environment --}}
                <div class="bg-white rounded-2xl border border-base-content/5 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-base-content/5 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-base-content">{{ __('Environment') }}</h3>
                        <span class="badge badge-success badge-sm font-semibold">Live</span>
                    </div>
                    <div class="divide-y divide-base-content/5">
                        @foreach([
                            ['label' => 'Laravel',    'value' => $this->systemStats['app']['version']],
                            ['label' => 'Environment','value' => $this->systemStats['app']['env'],   'pill' => true, 'color' => $this->systemStats['app']['env'] === 'production' ? 'badge-error' : 'badge-info'],
                            ['label' => 'Debug Mode', 'value' => $this->systemStats['app']['debug'] ? 'On' : 'Off', 'pill' => true, 'color' => $this->systemStats['app']['debug'] ? 'badge-warning' : 'badge-ghost'],
                            ['label' => 'App URL',    'value' => $this->systemStats['app']['url']],
                            ['label' => 'OS',         'value' => $this->systemStats['server']['os']],
                        ] as $row)
                            <div class="px-6 py-3.5 flex items-center justify-between">
                                <span class="text-xs font-semibold text-base-content/40 uppercase tracking-wide">{{ $row['label'] }}</span>
                                @isset($row['pill'])
                                    <span class="badge {{ $row['color'] }} badge-sm font-bold">{{ $row['value'] }}</span>
                                @else
                                    <span class="text-sm font-medium text-base-content">{{ $row['value'] }}</span>
                                @endisset
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Infrastructure --}}
                <div class="bg-white rounded-2xl border border-base-content/5 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-base-content/5">
                        <h3 class="text-sm font-bold text-base-content">{{ __('Infrastructure Health') }}</h3>
                    </div>
                    <div class="divide-y divide-base-content/5">
                        <div class="px-6 py-5">
                            <p class="text-xs font-semibold text-base-content/40 uppercase tracking-wide mb-3">{{ __('Queue Jobs') }}</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-base-200/60 rounded-xl text-center">
                                    <p class="text-xl font-bold text-base-content">{{ $this->systemStats['queue']['pending'] }}</p>
                                    <p class="text-[10px] font-semibold text-base-content/40 uppercase mt-0.5">Pending</p>
                                </div>
                                <div @class(['p-3 rounded-xl text-center', $this->systemStats['queue']['failed'] > 0 ? 'bg-error/5' : 'bg-base-200/60'])>
                                    <p @class(['text-xl font-bold', $this->systemStats['queue']['failed'] > 0 ? 'text-error' : 'text-base-content'])>{{ $this->systemStats['queue']['failed'] }}</p>
                                    <p class="text-[10px] font-semibold text-base-content/40 uppercase mt-0.5">Failed</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-5">
                            <p class="text-xs font-semibold text-base-content/40 uppercase tracking-wide mb-3">{{ __('Database') }}</p>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                                    <span class="text-xs font-medium text-base-content/60">Connection</span>
                                </div>
                                <span class="text-xs font-bold text-success">{{ $this->systemStats['database']['status'] }}</span>
                            </div>
                            <p class="text-xs text-base-content/40 font-mono bg-base-200/60 px-3 py-2 rounded-lg truncate">
                                {{ $this->systemStats['database']['version'] }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endif

    {{-- Delivery Location Modal --}}
    <x-ui.modal wire:model="locationModalOpen" maxWidth="sm">
        <div class="p-6">
            <h3 class="text-base font-bold text-base-content mb-0.5">
                {{ $editingLocationIndex !== null ? __('Edit Location') : __('Add Location') }}
            </h3>
            <p class="text-xs text-base-content/50 mb-5">
                {{ $editingLocationIndex !== null ? __('Update the name for this delivery zone.') : __('Enter the name of a delivery zone customers can select.') }}
            </p>
            <form wire:submit.prevent="saveLocationModal" class="space-y-4">
                <x-app.input name="locationName" type="text" label="Location Name"
                    wire:model="locationName" placeholder="e.g. East Legon, Accra Mall" autofocus />
                <div class="flex justify-end gap-3 pt-1">
                    <x-ui.button type="button" variant="ghost" size="md" wire:click="$set('locationModalOpen', false)">
                        {{ __('Cancel') }}
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary" size="md"
                        wire:loading.attr="disabled" wire:target="saveLocationModal">
                        <span wire:loading.remove wire:target="saveLocationModal">
                            {{ $editingLocationIndex !== null ? __('Save Changes') : __('Add Location') }}
                        </span>
                        <span wire:loading wire:target="saveLocationModal">{{ __('Saving...') }}</span>
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.modal>

</div>
