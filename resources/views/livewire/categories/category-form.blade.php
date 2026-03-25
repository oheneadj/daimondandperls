<div class="space-y-10 pb-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class=" text-4xl font-semibold tracking-tight text-base-content leading-tight">
                {{ $category ? __('Refine Collection') : __('Define Collection') }}
            </h1>
            <p class=" text-base-content/60 font-normal mt-2">
                {{ __('Evolve the thematic identity of your culinary groupings.') }}
            </p>
        </div>
        
        <x-ui.button variant="outline" size="sm" href="{{ route('admin.categories.index') }}" wire:navigate title="{{ __('Back to Collections') }}">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </x-slot:icon>
            {{ __('Back to Collections') }}
        </x-ui.button>
    </div>

    <form wire:submit="save" class="max-w-2xl space-y-8">
        <x-ui.card>
            <div class="space-y-6">
                <div class="flex items-center gap-2.5 mb-2">
                    <div class="w-8 h-8 rounded-full bg-primary-soft text-primary flex items-center justify-center">
                        <span class="icon-[tabler--category] w-4 h-4"></span>
                    </div>
                    <h2 class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Collection Definition') }}</h2>
                </div>

                <div class="space-y-2">
                    <label class=" text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Collection Name') }} <span class="text-primary">*</span></label>
                    <x-ui.input 
                        wire:model="name" 
                        placeholder="e.g. Traditional Ghanaian Heritage, Contemporary Fusion" 
                        required
                    />
                    @error('name') 
                        <p class=" text-[11px] text-error font-bold mt-1.5 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>
            </div>
        </x-ui.card>

        <div class="flex items-center justify-between pt-4">
            <x-ui.button variant="ghost" href="{{ route('admin.categories.index') }}" wire:navigate>
                {{ __('Relinquish Changes') }}
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" class="min-w-[180px] shadow-dp-lg">
                <span wire:loading.remove wire:target="save">
                    {{ $category ? __('Finalize Collection') : __('Execute Definition') }}
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-3">
                    <span class="loading loading-spinner loading-sm"></span>
                    {{ __('Processing...') }}
                </span>
            </x-ui.button>
        </div>
    </form>
</div>
