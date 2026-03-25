@props([
    'name' => null,
    'title' => null,
    'maxWidth' => 'md',
    'show' => false,
    'persistent' => false, // If true, clicking overlay won't close
])

@php
    $maxWidths = [
        'sm' => 'max-w-[400px]',
        'md' => 'max-w-[640px]',
        'lg' => 'max-w-[768px]',
        'xl' => 'max-w-[1024px]',
    ];

    $maxWidthClass = $maxWidths[$maxWidth] ?? $maxWidths['md'];
@endphp

<div
    x-data="{ 
        show: @if($attributes->wire('model')->value()) @entangle($attributes->wire('model')) @else {{ $show ? 'true' : 'false' }} @endif,
        close() { if (!@json($persistent)) this.show = false }
    }"
    x-show="show"
    x-on:keydown.escape.window="close()"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <!-- Overlay -->
    <div 
        x-show="show"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="close()"
        class="fixed inset-0 bg-black/40 backdrop-blur-[2px]"
    ></div>

    <!-- Modal Panel -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="bg-base-100 w-full {{ $maxWidthClass }} rounded-lg shadow-dp-lg overflow-hidden relative z-10"
        >
            <!-- Header -->
            @if($title)
                <div class="px-6 py-5 border-b border-base-content/10 flex items-center justify-between">
                    <h3 class=" text-xl font-semibold text-base-content">
                        {{ $title }}
                    </h3>
                    @if(!$persistent)
                        <button @click="show = false" class="p-1 rounded-md transition-colors hover:bg-base-200-mid text-base-content/60 hover:text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            @endif

            <!-- Body -->
            <div class="p-6 bg-base-100">
                {{ $slot }}
            </div>

            <!-- Footer -->
            @if(isset($footer))
                <div class="px-6 py-4 bg-base-200 border-t border-base-content/10 flex justify-end gap-3">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
