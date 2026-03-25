@props([
    'name' => null,
    'show' => false,
    'maxWidth' => '2xl', // sm, md, lg, xl, 2xl
    'title' => null,
    'subtitle' => null,
])

@php
    $maxWidthClass = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ][$maxWidth] ?? 'max-w-2xl';
@endphp

<div x-data="{ 
        show: @entangle($attributes->wire('model')),
        close() { this.show = false }
    }"
    x-show="show"
    x-on:keydown.escape.window="close()"
    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
    style="display: none;"
>
    <!-- Backdrop -->
    <div x-show="show" 
         x-on:click="close()" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 transform transition-all"
    >
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    </div>

    <!-- Modal Panel -->
    <div x-show="show" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         class="bg-white rounded-xl overflow-hidden shadow-dp-lg transform transition-all sm:w-full {{ $maxWidthClass }} relative z-10"
    >
        @if($title)
            <div class="px-6 py-5 border-b border-base-content/10 bg-white flex items-center justify-between">
                <div>
                    <h3 class=" text-[20px] font-semibold text-base-content leading-tight">
                        {{ $title }}
                    </h3>
                    @if($subtitle)
                        <p class=" text-[12px] text-base-content/60 mt-0.5">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
                <button x-on:click="close()" class="text-base-content/60 hover:text-base-content transition-colors p-1 rounded-md hover:bg-base-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="px-6 py-6  text-[13px] text-base-content/80">
            {{ $slot }}
        </div>

        @if(isset($footer))
            <div class="px-6 py-4 bg-base-200 border-t border-base-content/10 flex items-center justify-end gap-3">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
