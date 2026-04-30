<div class="space-y-10 pb-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content">
                {{ $package ? __('Edit Package') : __('Add New Package') }}
            </h1>
            <p class="text-[14px] text-base-content/50 mt-1">
                {{ __('Add and update the details of your package.') }}
            </p>
        </div>

        <x-ui.button variant="black" class="border-0" size="sm" href="{{ route('admin.manage-packages.index') }}" wire:navigate>
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </x-slot:icon>
            {{ __('Back') }}
        </x-ui.button>
    </div>

    <form class="max-w-4xl space-y-8"
        x-data="{ uploading: false }"
        @filepond-start.document="uploading = true"
        @filepond-end.document="uploading = false"
        @submit.prevent="if (!uploading) $wire.save()"
    >
        <x-ui.card>
            <div class="space-y-8">
                <!-- Group 1: Core Identity -->
                <div class="space-y-6">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-[#F96015]/10 text-[#F96015] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                        <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Core Identity') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Package Name') }} <span class="text-[#F96015]">*</span></label>
                            <x-ui.input wire:model="name" placeholder="e.g. Rice and Beans" />
                            @error('name') <p class="text-[11px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Collection/Category') }}</label>
                            <x-ui.select wire:model="category_id">
                                <option value="">{{ __('No Collection') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </x-ui.select>
                            @error('category_id') <p class="text-[11px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-base-content/5"></div>

                <!-- Group 2: Pricing & Guest Requirements -->
                <div class="space-y-6">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-[#FFC926]/15 text-[#FFC926] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Pricing') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Price') }} <span class="text-[#F96015]">*</span></label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-4 flex items-center text-[12px] font-bold text-base-content/40 group-focus-within:text-[#F96015] transition-colors">GHS</span>
                                <x-ui.input wire:model="price" type="number" step="0.01" min="0" placeholder="0.00" class="pl-14" />
                            </div>
                            @error('price') <p class="text-[11px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-base-content/5"></div>

                <!-- Group 3: Package Features -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Package Features') }}</h2>
                        </div>
                        <x-ui.button type="button" wire:click="addFeature" variant="ghost" size="sm" class="text-primary hover:bg-primary/5">
                            <x-slot:icon>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </x-slot:icon>
                            {{ __('Add Feature') }}
                        </x-ui.button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($features as $index => $feature)
                            <div class="flex items-center gap-3 group">
                                <div class="flex-1 relative">
                                    <x-ui.input wire:model="features.{{ $index }}" placeholder="e.g. Professional Waiters" class="pr-10" />
                                    <button type="button" wire:click="removeFeature({{ $index }})" class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content/20 hover:text-error transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if(empty($features))
                        <div class="p-8 border-2 border-dashed border-base-content/5 rounded-2xl text-center">
                            <p class="text-[12px] text-base-content/30 font-medium italic">
                                {{ __('No features added yet. Highlight what makes this package special.') }}
                            </p>
                        </div>
                    @endif
                </div>

                <div class="border-t border-base-content/5"></div>

                <!-- Group 4: Description & Media -->
                <div class="space-y-6">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-[#9ABC05]/10 text-[#9ABC05] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                        <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Description & Media') }}</h2>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Description') }}</label>
                        <x-ui.textarea wire:model="description" class="h-32" placeholder="Describe the package..." />
                        @error('description') <p class="text-[11px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="space-y-3" 
                        x-data="{
                            pond: null,
                            initPond() {
                                if (!window.FilePond) {
                                    setTimeout(() => this.initPond(), 100);
                                    return;
                                }
                                if (this.pond) return;

                                this.pond = window.FilePond.create(this.$refs.filepond, {
                                    allowMultiple: false,
                                    acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'],
                                    maxFileSize: '2MB',
                                    labelIdle: '{{ __('Package Image — Drag & drop or') }} <span class=&quot;filepond--label-action&quot;>{{ __('Select') }}</span>',
                                    imagePreviewHeight: 200,
                                    credits: false,
                                    server: {
                                        process: (fieldName, file, metadata, load, error, progress, abort) => {
                                            document.dispatchEvent(new CustomEvent('filepond-start'));
                                            $wire.upload('image', file,
                                                (uploadedFilename) => { load(uploadedFilename); document.dispatchEvent(new CustomEvent('filepond-end')); },
                                                () => { error('Upload failed'); document.dispatchEvent(new CustomEvent('filepond-end')); },
                                                (progressValue) => { progress(true, progressValue, 100); }
                                            );
                                        },
                                    },
                                });
                            },
                            init() { 
                                this.$nextTick(() => this.initPond()); 
                            }
                        }"
                    >
                        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Package Image') }}</label>
                        
                        @if($existing_image && !$image)
                            <div class="flex items-center gap-5 p-4 border border-base-content/10 rounded-xl bg-base-content/[0.02]">
                                <div class="w-20 h-20 rounded-xl overflow-hidden shadow-sm ring-1 ring-base-content/10">
                                    <img src="{{ Storage::url($existing_image) }}" alt="Current Image" class="w-full h-full object-cover" />
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[12px] font-bold text-base-content">{{ __('Current Image') }}</p>
                                    <p class="text-[11px] text-base-content/40">{{ __('Upload a new file to replace the current image.') }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="filepond-container-refined shadow-sm" wire:ignore>
                            <input type="file" x-ref="filepond" />
                        </div>
                        @error('image') <p class="text-[11px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="border-t border-base-content/5"></div>

                <!-- Status Toggles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Visibility Toggle -->
                    <div class="flex items-center justify-between p-5 bg-base-content/[0.02] border border-base-content/10 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-success/10 text-success flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[13px] font-bold text-base-content">{{ __('Active Status') }}</p>
                                <p class="text-[11px] text-base-content/40">{{ __('Visible to customers.') }}</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input wire:model="is_active" type="checkbox" class="sr-only peer">
                            <div class="w-14 h-7 bg-base-200 border border-base-content/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-1 after:left-1 after:bg-base-100 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success shadow-sm"></div>
                        </label>
                    </div>

                    <!-- Popularity Toggle -->
                    <div class="flex items-center justify-between p-5 bg-[#FFC926]/5 border border-[#FFC926]/20 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#FFC926]/20 text-[#FFC926] flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[13px] font-bold text-base-content">{{ __('Popular Choice') }}</p>
                                <p class="text-[11px] text-base-content/40">{{ __('Highlight as a top pick.') }}</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input wire:model="is_popular" type="checkbox" class="sr-only peer">
                            <div class="w-14 h-7 bg-base-200 border border-[#FFC926]/20 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-1 after:left-1 after:bg-base-100 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#FFC926] shadow-sm"></div>
                        </label>
                    </div>

                    {{-- Window Exempt --}}
                    <div class="flex items-center justify-between py-4 border-t border-base-content/5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-warning/10 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[13px] font-bold text-base-content">{{ __('Always Bookable') }}</p>
                                <p class="text-[11px] text-base-content/40">{{ __('This package can be ordered at any time, even after the collection\'s booking window closes.') }}</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input wire:model="window_exempt" type="checkbox" class="sr-only peer">
                            <div class="w-14 h-7 bg-base-200 border border-warning/20 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-1 after:left-1 after:bg-base-100 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-warning shadow-sm"></div>
                        </label>
                    </div>
                </div>
            </div>
        </x-ui.card>

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-ui.button variant="black" href="{{ route('admin.manage-packages.index') }}" wire:navigate>
                    {{ __('Cancel') }}
                </x-ui.button>

                @if($package)
                    <x-ui.button type="button" wire:click="$set('showDeleteModal', true)" class="bg-[#D52518] border-[#D52518] hover:bg-[#b01e14] text-white">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </x-slot:icon>
                        {{ __('Delete') }}
                    </x-ui.button>
                @endif
            </div>
            <button type="submit"
                x-bind:disabled="uploading"
                x-bind:class="uploading ? 'opacity-60 cursor-not-allowed' : ''"
                wire:loading.attr="disabled"
                class="btn border-none inline-flex items-center justify-center font-medium rounded-xl transition-all duration-150 focus:outline-none focus:ring-3 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed bg-primary text-primary-content hover:brightness-110 focus:ring-primary/30 px-[24px] py-[13px] text-[15px] gap-2 min-w-[200px] shadow-dp-lg"
            >
                <span x-show="uploading" class="flex items-center gap-3">
                    <span class="loading loading-spinner loading-sm"></span>
                    {{ __('Uploading image...') }}
                </span>
                <span x-show="!uploading" wire:loading.remove wire:target="save">
                    {{ $package ? __('Update Package') : __('Add Package') }}
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-3">
                    <span class="loading loading-spinner loading-sm"></span>
                    {{ __('Processing...') }}
                </span>
            </button>
        </div>

        @error('delete')
            <div class="p-4 rounded-xl bg-[#D52518]/10 border border-[#D52518]">
                <p class="text-[13px] font-bold text-[#D52518]">{{ $message }}</p>
            </div>
        @enderror
    </form>

    @if($package)
        <x-ui.modal wire:model="showDeleteModal" title="Confirm Deletion" icon="heroicon-o-exclamation-triangle" persistent>
            <div class="space-y-6">
                <p class="text-[14px] text-base-content leading-relaxed">
                    {{ __('Are you sure you want to retire this culinary masterpiece? This action will remove the package from the active menu.') }}
                </p>
                <div class="p-4 rounded-xl bg-[#D52518]/5 border border-[#D52518]">
                    <p class="text-[13px] font-bold text-base-content">{{ $package->name }}</p>
                </div>
            </div>

            <x-slot:footer>
                <x-ui.button variant="ghost" wire:click="$set('showDeleteModal', false)">{{ __('Cancel') }}</x-ui.button>
                <x-ui.button type="danger" variant="primary" wire:click="deletePackage" class="bg-[#D52518] border-[#D52518] hover:bg-[#b01e14]">
                    {{ __('Retire Package') }}
                </x-ui.button>
            </x-slot:footer>
        </x-ui.modal>
    @endif
</div>
