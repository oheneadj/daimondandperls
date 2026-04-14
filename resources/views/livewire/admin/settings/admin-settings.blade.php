<div class="space-y-6 pb-10">
    {{-- Page Header --}}
    <div>
        <h1 class="text-[24px] md:text-[28px] font-semibold text-base-content leading-tight">
            {{ __('Application Settings') }}
        </h1>
        <p class="text-[13px] md:text-[14px] text-base-content/50 mt-1">{{ __('Configure your business identity, integration keys, and monitor system health.') }}</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex items-center gap-2 bg-base-200 p-1.5 rounded-lg border border-base-content/5 w-full sm:w-fit shadow-sm overflow-x-auto">
        @foreach([
            'company' => ['label' => 'Company', 'icon' => '<path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'],
            'app' => ['label' => 'App & API', 'icon' => '<path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'],
            'system' => ['label' => 'System Overview', 'icon' => '<path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>']
        ] as $key => $tabInfo)
            <button wire:click="setTab('{{ $key }}')"
                    class="inline-flex items-center gap-2 sm:gap-2.5 px-3 sm:px-5 py-2 text-[11px] font-bold uppercase tracking-[0.1em] sm:tracking-[0.15em] rounded-md transition-all duration-200 whitespace-nowrap {{ $tab === $key ? 'bg-white text-primary shadow-sm border border-base-content/5' : 'text-base-content/40 hover:text-base-content' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 {{ $tab === $key ? 'text-primary' : 'text-base-content/20' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    {!! $tabInfo['icon'] !!}
                </svg>
                {{ __($tabInfo['label']) }}
            </button>
        @endforeach
    </div>

    <div class="mt-6">
        {{-- Company Tab --}}
        @if($tab === 'company')
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 animate-in fade-in slide-in-from-bottom-2 duration-300">
                {{-- Left: Identity & Branding --}}
                <div class="lg:col-span-7 space-y-6">
                    <x-ui.card class="shadow-sm overflow-hidden" accent="primary">
                        <div class="p-6 border-b border-base-content/5">
                            <h3 class="text-[16px] font-bold text-base-content uppercase tracking-[0.05em]">{{ __('Business Identity') }}</h3>
                            <p class="text-[11px] text-base-content/40 font-medium mt-1">{{ __('Manage your branding and public presence.') }}</p>
                        </div>
                        <form class="p-6 space-y-6"
                            x-data="{ uploading: false }"
                            @filepond-start.document="uploading = true"
                            @filepond-end.document="uploading = false"
                            @submit.prevent="if (!uploading) $wire.saveBusinessInfo()"
                        >
                            @if (session()->has('business_info_success'))
                                <x-ui.alert type="success" class="mb-4">
                                    {{ session('business_info_success') }}
                                </x-ui.alert>
                            @endif

                                {{-- Logo Upload (FilePond Profile Design) --}}
                                <div class="flex flex-col sm:flex-row items-center gap-8 pb-8 border-b border-base-content/5">
                                    <div class="w-32 h-32 flex-shrink-0" 
                                        data-logo-url="{{ $current_logo_path ? Storage::url($current_logo_path) : '' }}"
                                        x-data="{
                                            pond: null,
                                            initPond() {
                                                if (!window.FilePond) {
                                                    setTimeout(() => this.initPond(), 100);
                                                    return;
                                                }
                                                if (this.pond) return;

                                                this.pond = window.FilePond.create($refs.logoPond, {
                                                    labelIdle: '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-8 h-8 opacity-20\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\' /></svg>',
                                                    imagePreviewHeight: 128,
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
                                                    acceptedFileTypes: ['image/png', 'image/webp', 'image/jpeg', 'image/jpg'],
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
                                                    fetch(logoUrl)
                                                        .then(res => res.blob())
                                                        .then(blob => {
                                                            const parts = logoUrl.split('/');
                                                            const filename = parts[parts.length - 1];
                                                            const file = new File([blob], filename, { type: blob.type });
                                                            this.pond.addFile(file, { index: 0 });
                                                        })
                                                        .catch(err => console.warn('Logo preview failed:', err));
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

                                    <div class="flex-1 space-y-3">
                                        <div class="space-y-1">
                                            <h4 class="text-[14px] font-bold text-base-content">{{ __('Business Emblem') }}</h4>
                                            <p class="text-[11px] text-base-content/40 leading-relaxed max-w-xs">
                                                {{ __('Upload a high-resolution logo. We support PNG, WebP, and JPEG formats (max 2MB).') }}
                                            </p>
                                        </div>
                                        
                                        @if ($current_logo_path && !$business_logo)
                                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-base-200 rounded-full border border-base-content/5">
                                                <div class="w-4 h-4 rounded-full overflow-hidden shadow-sm">
                                                    <img src="{{ Storage::url($current_logo_path) }}" class="w-full h-full object-cover">
                                                </div>
                                                <span class="text-[10px] font-bold text-base-content/60 uppercase tracking-tighter">{{ __('Active Logo') }}</span>
                                            </div>
                                        @endif

                                        @error('business_logo') <p class="text-error text-[11px] font-bold mt-1 tracking-tight">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                            <div class="grid grid-cols-1 gap-5">
                                <x-ui.input label="Official Business Name" wire:model="business_name" required placeholder="e.g. Diamonds & Pearls" :error="$errors->first('business_name')" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <x-ui.input label="Contact Phone Number" wire:model="business_phone" required placeholder="+233 ..." :error="$errors->first('business_phone')" />
                                <x-ui.input label="WhatsApp Number" wire:model="business_whatsapp" placeholder="233244203181 (Optional)" :error="$errors->first('business_whatsapp')" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <x-ui.input label="Primary Email Address" wire:model="business_email" type="email" required placeholder="contact@example.com" :error="$errors->first('business_email')" />
                            </div>

                            <div class="grid grid-cols-1 gap-5">
                                <x-ui.textarea label="Physical/Head Office Address" wire:model="business_address" rows="3" required placeholder="House No, Street, City" :error="$errors->first('business_address')" />
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="submit"
                                    x-bind:disabled="uploading"
                                    x-bind:class="uploading ? 'opacity-60 cursor-not-allowed' : ''"
                                    wire:loading.attr="disabled"
                                    class="btn border-none inline-flex items-center justify-center font-medium rounded-xl transition-all duration-150 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed bg-primary text-primary-content hover:brightness-110 px-[18px] py-[10px] text-[13px] gap-2"
                                >
                                    <span x-show="uploading" class="flex items-center gap-2">
                                        <span class="loading loading-spinner loading-xs"></span>
                                        {{ __('Uploading logo...') }}
                                    </span>
                                    <span x-show="!uploading" wire:loading.remove wire:target="saveBusinessInfo">{{ __('Verify & Save Changes') }}</span>
                                    <span wire:loading wire:target="saveBusinessInfo" class="flex items-center gap-2">
                                        <span class="loading loading-spinner loading-xs"></span>
                                        {{ __('Persisting...') }}
                                    </span>
                                </button>
                            </div>
                        </form>
                    </x-ui.card>
                </div>

                {{-- Right: Banking --}}
                <div class="lg:col-span-5">
                    <x-ui.card class="shadow-sm">
                        <div class="p-6 border-b border-base-content/5">
                            <h3 class="text-[16px] font-bold text-base-content uppercase tracking-[0.05em]">{{ __('Bank Transfer Details') }}</h3>
                            <p class="text-[11px] text-base-content/40 font-medium mt-1">{{ __('Visible on invoices for manual payments.') }}</p>
                        </div>
                        <form wire:submit.prevent="saveBankDetails" class="p-6 space-y-5">
                            @if (session()->has('bank_details_success'))
                                <x-ui.alert type="success" class="mb-4">
                                    {{ session('bank_details_success') }}
                                </x-ui.alert>
                            @endif

                            <x-ui.input label="Financial Institution" wire:model="bank_name" placeholder="e.g. Ecobank Ghana" required :error="$errors->first('bank_name')" />
                            <x-ui.input label="Official Account Name" wire:model="account_name" placeholder="e.g. Diamonds & Pearls Ltd" required :error="$errors->first('account_name')" />
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-ui.input label="Account Number" wire:model="account_number" placeholder="Enter number" required :error="$errors->first('account_number')" />
                                <x-ui.input label="Branch Code" wire:model="branch_code" placeholder="Optional" :error="$errors->first('branch_code')" />
                            </div>

                            <div class="pt-4 flex justify-end">
                                <x-ui.button type="submit" variant="accent" size="sm" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="saveBankDetails">{{ __('Save Bank Setup') }}</span>
                                    <span wire:loading wire:target="saveBankDetails">...</span>
                                </x-ui.button>
                            </div>
                        </form>
                    </x-ui.card>
                </div>

                {{-- Social Media Links --}}
                <div class="lg:col-span-12">
                    <x-ui.card class="shadow-sm">
                        <div class="p-6 border-b border-base-content/5">
                            <h3 class="text-[16px] font-bold text-base-content uppercase tracking-[0.05em]">{{ __('Social Media Links') }}</h3>
                            <p class="text-[11px] text-base-content/40 font-medium mt-1">{{ __('These links appear on your public About page.') }}</p>
                        </div>
                        <form wire:submit.prevent="saveSocialLinks" class="p-6 space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <x-ui.input label="Facebook URL" wire:model="social_facebook" type="url" placeholder="https://facebook.com/yourpage" :error="$errors->first('social_facebook')" />
                                <x-ui.input label="Instagram URL" wire:model="social_instagram" type="url" placeholder="https://instagram.com/yourhandle" :error="$errors->first('social_instagram')" />
                                <x-ui.input label="Twitter / X URL" wire:model="social_twitter" type="url" placeholder="https://x.com/yourhandle" :error="$errors->first('social_twitter')" />
                                <x-ui.input label="TikTok URL" wire:model="social_tiktok" type="url" placeholder="https://tiktok.com/@yourhandle" :error="$errors->first('social_tiktok')" />
                            </div>
                            <div class="pt-2 flex justify-end">
                                <x-ui.button type="submit" variant="secondary" size="sm" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="saveSocialLinks">{{ __('Save Social Links') }}</span>
                                    <span wire:loading wire:target="saveSocialLinks">...</span>
                                </x-ui.button>
                            </div>
                        </form>
                    </x-ui.card>
                </div>
            </div>
        @endif

        {{-- App Tab --}}
        @if($tab === 'app')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-in fade-in slide-in-from-bottom-2 duration-300">

                {{-- Delivery Locations --}}
                <x-ui.card class="shadow-sm overflow-hidden lg:col-span-2" accent="secondary">
                    <div class="p-6 border-b border-base-content/5 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-[16px] font-bold text-base-content uppercase tracking-[0.05em]">{{ __('Delivery Locations') }}</h3>
                            <p class="text-[11px] text-base-content/40 font-medium mt-1">{{ __('Zones customers can select when placing a meal order.') }}</p>
                        </div>
                        <x-ui.button type="button" variant="secondary" size="sm" wire:click="openAddLocationModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Add Location') }}
                        </x-ui.button>
                    </div>

                    <div class="p-6">
                        @if(empty($delivery_locations))
                            <div class="flex flex-col items-center justify-center py-10 text-center">
                                <div class="size-14 bg-base-200 rounded-full flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <p class="text-[14px] font-semibold text-base-content/60">No delivery zones added yet</p>
                                <p class="text-[12px] text-base-content/40 mt-1">Customers will not be asked to pick a location until you add one.</p>
                                <x-ui.button type="button" variant="outline" size="sm" class="mt-5" wire:click="openAddLocationModal">
                                    Add your first location
                                </x-ui.button>
                            </div>
                        @else
                            <div class="divide-y divide-base-content/5">
                                @foreach($delivery_locations as $i => $location)
                                    <div class="flex items-center gap-3 py-3 group">
                                        {{-- Reorder --}}
                                        <div class="flex flex-col gap-0.5 shrink-0">
                                            <button type="button" wire:click="moveLocationUp({{ $i }})"
                                                @class(['p-1 rounded text-base-content/30 hover:text-base-content transition-colors', 'opacity-20 pointer-events-none' => $i === 0])>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="moveLocationDown({{ $i }})"
                                                @class(['p-1 rounded text-base-content/30 hover:text-base-content transition-colors', 'opacity-20 pointer-events-none' => $i === count($delivery_locations) - 1])>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- Location name --}}
                                        <div class="flex-1 min-w-0">
                                            <span class="text-[14px] font-medium text-base-content">{{ $location }}</span>
                                        </div>

                                        {{-- Position badge --}}
                                        <span class="text-[10px] font-bold text-base-content/30 tabular-nums shrink-0">#{{ $i + 1 }}</span>

                                        {{-- Actions --}}
                                        <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button type="button" wire:click="openEditLocationModal({{ $i }})"
                                                class="p-2 rounded-lg text-base-content/40 hover:text-primary hover:bg-primary/5 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="removeDeliveryLocation({{ $i }})"
                                                wire:confirm="Remove '{{ $location }}'?"
                                                class="p-2 rounded-lg text-base-content/40 hover:text-error hover:bg-error/5 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </x-ui.card>

                {{-- Event Booking Settings --}}
                <x-ui.card class="shadow-sm overflow-hidden lg:col-span-2" accent="primary">
                    <div class="p-6 border-b border-base-content/5">
                        <h3 class="text-[16px] font-bold text-base-content uppercase tracking-[0.05em]">{{ __('Event Booking Rules') }}</h3>
                        <p class="text-[11px] text-base-content/40 font-medium mt-1">{{ __('Set how far in advance customers must book events.') }}</p>
                    </div>
                    <form wire:submit.prevent="saveEventSettings" class="p-6 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
                            <div class="space-y-2">
                                <label class="text-[12px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Minimum Lead Time') }}</label>
                                <select wire:model="event_lead_days"
                                    class="w-full px-4 py-3 bg-base-200 border border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[14px] font-medium">
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
                                @error('event_lead_days') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="bg-base-200/60 rounded-xl p-4 border border-base-content/5 space-y-1.5">
                                <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/50">{{ __('How it works') }}</p>
                                <p class="text-[12px] text-base-content/60 leading-relaxed">
                                    When a customer opens the event booking form, the earliest selectable date will be automatically set to
                                    <strong class="text-base-content">today + {{ $event_lead_days }} {{ $event_lead_days === 1 ? 'day' : 'days' }}</strong>.
                                    Dates before this minimum will be blocked.
                                </p>
                                @if($event_lead_days > 0)
                                    <p class="text-[11px] font-semibold text-primary mt-2">
                                        Currently: earliest bookable date is {{ now()->addDays($event_lead_days)->format('M j, Y') }}
                                    </p>
                                @else
                                    <p class="text-[11px] font-semibold text-base-content/40 mt-2">
                                        Currently: any future date is accepted
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <x-ui.button type="submit" variant="secondary" size="md" wire:loading.attr="disabled" wire:target="saveEventSettings">
                                <span wire:loading.remove wire:target="saveEventSettings">{{ __('Save Event Rules') }}</span>
                                <span wire:loading wire:target="saveEventSettings">{{ __('Saving...') }}</span>
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card>
                {{-- Payment Gateway --}}
                <x-ui.card class="shadow-sm overflow-hidden" accent="warning">
                    <div class="p-6 border-b border-base-content/5">
                        <h3 class="text-[16px] font-bold text-base-content uppercase tracking-[0.05em]">{{ __('Financial Gateways') }}</h3>
                        <p class="text-[11px] text-base-content/40 font-medium mt-1">{{ __('Manage your Paystack integration keys.') }}</p>
                    </div>
                    <form wire:submit.prevent="savePaymentSettings" class="p-6 space-y-6">
                        @if (session()->has('payment_settings_success'))
                            <x-ui.alert type="success" class="mb-4">
                                {{ session('payment_settings_success') }}
                            </x-ui.alert>
                        @endif

                        <div class="bg-base-200-mid/30 p-4 rounded-lg flex items-start gap-4 mb-2 border border-base-content/5">
                            <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <div>
                                <p class="text-[12px] font-bold text-base-content">{{ __('Paystack Active Integration') }}</p>
                                <p class="text-[10px] text-base-content/60 leading-relaxed">{{ __('Ensure keys matching your current environment (Test vs Live) are used.') }}</p>
                            </div>
                        </div>

                        <x-ui.input label="Public Key" wire:model="paystack_public_key" placeholder="pk_test_..." required :error="$errors->first('paystack_public_key')" />
                        <x-ui.input label="Secret Key" wire:model="paystack_secret_key" type="password" placeholder="sk_test_..." required :error="$errors->first('paystack_secret_key')" />

                        <div class="pt-2 flex justify-end">
                            <x-ui.button type="submit" variant="secondary" size="md" wire:loading.attr="disabled">
                                {{ __('Update API Configuration') }}
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card>

                {{-- Notifications --}}
                <x-ui.card class="shadow-sm">
                    <div class="p-6 border-b border-base-content/5">
                        <h3 class="text-[16px] font-bold text-base-content uppercase tracking-[0.05em]">{{ __('Notification Channels') }}</h3>
                        <p class="text-[11px] text-base-content/40 font-medium mt-1">{{ __('Control how the system communicates with clients.') }}</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="flex items-center justify-between p-4 bg-base-200-mid/20 rounded-lg border border-base-content/5">
                            <div class="space-y-0.5">
                                <label class="text-[13px] font-semibold text-base-content">{{ __('Email Dispatch') }}</label>
                                <p class="text-[11px] text-base-content/60">Automated booking confirmations and invoices.</p>
                            </div>
                            <input type="checkbox" wire:model.live="email_notifications" class="toggle toggle-success" />
                        </div>

                        <div class="flex items-center justify-between p-4 bg-base-200-mid/20 rounded-lg border border-base-content/5">
                            <div class="space-y-0.5">
                                <label class="text-[13px] font-semibold text-base-content">{{ __('SMS Alerts') }}</label>
                                <p class="text-[11px] text-base-content/60">Short-form urgency notifications for payments.</p>
                            </div>
                            <input type="checkbox" wire:model.live="sms_notifications" class="toggle toggle-success" />
                        </div>
                        
                        <div class="p-4 bg-dp-rose/5 rounded-lg border border-dp-rose/10 flex gap-3 mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-dp-rose flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p class="text-[10px] text-dp-rose leading-relaxed font-semibold uppercase tracking-wide">
                                {{ __('Critical security alerts cannot be disabled.') }}
                            </p>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        @endif

        {{-- System Tab --}}
        @if($tab === 'system')
            <div class="space-y-6 animate-in fade-in slide-in-from-bottom-2 duration-300">
                {{-- KPI Stats Row --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-lg bg-[#9ABC05]/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#9ABC05]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        </div>
                        <div>
                            <p class="text-[18px] font-bold text-base-content">{{ $this->systemStats['server']['php'] }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('PHP Version') }}</p>
                        </div>
                    </div>
                    <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-lg bg-[#F96015]/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                        </div>
                        <div>
                            <p class="text-[18px] font-bold text-base-content uppercase">{{ $this->systemStats['database']['driver'] }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Database Engine') }}</p>
                        </div>
                    </div>
                    <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-lg bg-[#A31C4E]/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#A31C4E]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div>
                            <p class="text-[18px] font-bold text-base-content uppercase">{{ $this->systemStats['queue']['driver'] }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Queue System') }}</p>
                        </div>
                    </div>
                    <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-lg bg-[#FFC926]/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FFC926]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[18px] font-bold text-base-content uppercase">{{ $this->systemStats['server']['uptime'] }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Server Uptime') }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Environment & Specs --}}
                    <x-ui.card padding="none" class="shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-base-content/5 flex items-center justify-between">
                            <h3 class="text-[15px] font-bold text-base-content uppercase tracking-widest">{{ __('Environment Metrics') }}</h3>
                            <span class="badge badge-success badge-sm font-bold opacity-80 uppercase tracking-tighter">Live Monitor</span>
                        </div>
                        <div class="divide-y divide-base-content/5">
                            @foreach([
                                ['label' => 'Laravel Version', 'value' => $this->systemStats['app']['version']],
                                ['label' => 'Environment', 'value' => $this->systemStats['app']['env'], 'pill' => true, 'color' => $this->systemStats['app']['env'] === 'production' ? 'badge-rose' : 'badge-info'],
                                ['label' => 'Debug Mode', 'value' => $this->systemStats['app']['debug'] ? 'ACTIVE' : 'DISABLED', 'pill' => true, 'color' => $this->systemStats['app']['debug'] ? 'badge-warning' : 'badge-ghost'],
                                ['label' => 'App Host', 'value' => $this->systemStats['app']['url']],
                                ['label' => 'OS Family', 'value' => $this->systemStats['server']['os']],
                            ] as $spec)
                                <div class="px-6 py-4 flex items-center justify-between hover:bg-base-200-mid/20 transition-colors">
                                    <span class="text-[12px] font-bold text-base-content/40 uppercase tracking-wide">{{ __($spec['label']) }}</span>
                                    @isset($spec['pill'])
                                        <span class="badge {{ $spec['color'] }} badge-sm font-bold">{{ $spec['value'] }}</span>
                                    @else
                                        <span class="text-[13px] font-semibold text-base-content">{{ $spec['value'] }}</span>
                                    @endisset
                                </div>
                            @endforeach
                        </div>
                    </x-ui.card>

                    {{-- Operations & Infrastructure --}}
                    <x-ui.card padding="none" class="shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-base-content/5 flex items-center justify-between">
                            <h3 class="text-[15px] font-bold text-base-content uppercase tracking-widest">{{ __('Infrastructure Health') }}</h3>
                            <span class="text-[10px] font-bold text-base-content/20 uppercase tracking-tighter">Real-time Stats</span>
                        </div>
                        <div class="divide-y divide-base-content/5">
                            <div class="px-6 py-5">
                                <p class="text-[10px] font-bold text-base-content/25 uppercase tracking-widest mb-3">{{ __('Background Operations') }}</p>
                                <div class="flex items-center gap-6">
                                    <div class="flex-1 p-3 bg-base-200 rounded-lg text-center border border-base-content/5">
                                        <div class="text-[18px] font-bold text-base-content">{{ $this->systemStats['queue']['pending'] }}</div>
                                        <div class="text-[9px] font-bold text-base-content/40 uppercase">{{ __('Pending Jobs') }}</div>
                                    </div>
                                    <div class="flex-1 p-3 bg-dp-rose/5 rounded-lg text-center border border-dp-rose/10">
                                        <div class="text-[18px] font-bold text-dp-rose">{{ $this->systemStats['queue']['failed'] }}</div>
                                        <div class="text-[9px] font-bold text-dp-rose/40 uppercase">{{ __('Failed Executions') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-5">
                                <p class="text-[10px] font-bold text-base-content/25 uppercase tracking-widest mb-3">{{ __('Database Topology') }}</p>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></div>
                                            <span class="text-[12px] font-bold text-base-content/60">{{ __('Connection Pulse') }}</span>
                                        </div>
                                        <span class="text-[13px] font-bold text-success uppercase">{{ $this->systemStats['database']['status'] }}</span>
                                    </div>
                                    <div class="text-[11px] text-base-content/50 bg-base-200-mid/30 p-3 rounded border border-base-content/5 font-mono truncate">
                                        {{ $this->systemStats['database']['version'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-ui.card>
                </div>
            </div>
        @endif
    </div>

    {{-- Delivery Location Modal (Add / Edit) --}}
    <x-ui.modal wire:model="locationModalOpen" maxWidth="sm">
        <div class="p-6">
            <h3 class="text-[16px] font-bold text-base-content mb-1">
                {{ $editingLocationIndex !== null ? __('Edit Location') : __('Add Delivery Location') }}
            </h3>
            <p class="text-[12px] text-base-content/50 mb-6">
                {{ $editingLocationIndex !== null ? __('Update the name for this delivery zone.') : __('Enter the name of a zone customers can select.') }}
            </p>

            <form wire:submit.prevent="saveLocationModal" class="space-y-5">
                <x-app.input
                    name="locationName"
                    type="text"
                    label="Location Name"
                    wire:model="locationName"
                    placeholder="e.g. Accra Mall, East Legon"
                    autofocus
                />

                <div class="flex justify-end gap-3 pt-2">
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
