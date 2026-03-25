<div>
@if($style === 'small')
    <div class="flex items-center gap-3 w-full">
        <x-ui.button 
            wire:click.prevent="addToCart" 
            wire:loading.attr="disabled"
            :loading="$loading === 'addToCart'"
            variant="{{ $isAdded ? 'secondary' : 'outline' }}"
            size="sm"
            class="flex-1 sm:flex-none h-10 px-4 font-bold text-[12px] uppercase tracking-widest shadow-sm border-base-content/10"
        >
            {{ $isAdded ? 'Added' : 'Add to Selection' }}
        </x-ui.button>

        <x-ui.button 
            wire:click.prevent="bookNow" 
            wire:loading.attr="disabled"
            :loading="$loading === 'bookNow'"
            variant="primary"
            size="sm"
            class="flex-1 sm:flex-none h-10 px-6 font-bold text-[12px] uppercase tracking-widest shadow-md"
        >
            {{ __('Book Now') }}
        </x-ui.button>
    </div>
@else
    <div class="flex flex-col sm:flex-row gap-4 w-full">
        <x-ui.button 
            wire:click.prevent="addToCart" 
            wire:loading.attr="disabled"
            :loading="$loading === 'addToCart'"
            variant="{{ $isAdded ? 'secondary' : 'outline' }}"
            size="lg"
            class="flex-1 shadow-sm h-16 text-[15px] font-bold"
        >
            {{ $isAdded ? __('Added to Selection') : __('Add to Selection') }}
        </x-ui.button>

        <x-ui.button 
            wire:click.prevent="bookNow" 
            wire:loading.attr="disabled"
            :loading="$loading === 'bookNow'"
            variant="primary"
            size="lg"
            class="flex-1 shadow-md h-16 text-[15px] font-bold"
        >
            {{ __('Book Now') }}
        </x-ui.button>
    </div>
@endif
</div>
