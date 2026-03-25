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

    <form wire:submit="save" class="max-w-4xl space-y-8">
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

                <!-- Group 2: Pricing -->
                <div class="space-y-6">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-[#FFC926]/15 text-[#FFC926] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Price & Order Quantity') }}</h2>
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

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Serving Size') }}</label>
                            <x-ui.input wire:model="serving_size" placeholder="e.g. 10 bowls" />
                            @error('serving_size') <p class="text-[11px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-base-content/5"></div>

                <!-- Group 3: Description & Media -->
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
                                            $wire.upload('image', file, 
                                                (uploadedFilename) => { load(uploadedFilename); },
                                                () => { error('Upload failed'); },
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

                <!-- Status Toggle -->
                <div class="flex items-center justify-between p-5 bg-base-content/[0.02] border border-base-content/10 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-success/10 text-success flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-base-content">{{ __('Package Availability') }}</p>
                            <p class="text-[11px] text-base-content/40">{{ __('If deactivated, this package will not be visible to customers.') }}</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input wire:model="is_active" type="checkbox" class="sr-only peer">
                        <div class="w-14 h-7 border-base-content/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-1 after:left-1 after:bg-base-100 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success shadow-sm"></div>
                    </label>
                </div>
            </div>
        </x-ui.card>

        <div class="flex items-center justify-between">
            <x-ui.button variant="black" href="{{ route('admin.manage-packages.index') }}" wire:navigate>
                {{ __('Cancel') }}
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" class="min-w-[200px] shadow-dp-lg">
                <span wire:loading.remove wire:target="save">
                    {{ $package ? __('Update Package') : __('Add Package') }}
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-3">
                    <span class="loading loading-spinner loading-sm"></span>
                    {{ __('Processing...') }}
                </span>
            </x-ui.button>
        </div>
    </form>
</div>
