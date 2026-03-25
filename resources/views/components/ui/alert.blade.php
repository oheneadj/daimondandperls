@props([
    'type' => 'info', // success, warning, danger, info
    'title' => null,
    'dismissible' => false,
])

@php
    $types = [
        'success' => [
            'bg' => 'bg-success-soft',
            'border' => 'border-l-3 border-l-dp-success',
            'text' => 'text-dp-success',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
        'warning' => [
            'bg' => 'bg-warning/10',
            'border' => 'border-l-3 border-l-dp-warning',
            'text' => 'text-dp-warning',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
        ],
        'danger' => [
            'bg' => 'bg-error/10',
            'border' => 'border-l-3 border-l-dp-danger',
            'text' => 'text-error',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
        'info' => [
            'bg' => 'bg-info/10',
            'border' => 'border-l-3 border-l-dp-info',
            'text' => 'text-dp-info',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
    ];

    $config = $types[$type] ?? $types['info'];
@endphp

<div x-data="{ open: true }" x-show="open" 
    {{ $attributes->merge(['class' => 'rounded-lg py-[14px] px-[16px] flex gap-3 ' . $config['bg'] . ' ' . $config['border'] . ' ' . $config['text']]) }}
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    {!! $config['icon'] !!}

    <div class="flex-1">
        @if($title)
            <h4 class=" text-[13px] font-semibold leading-tight mb-1">{{ $title }}</h4>
        @endif
        <div class=" text-[13px] font-normal leading-relaxed">
            {{ $slot }}
        </div>
    </div>

    @if($dismissible)
        <button @click="open = false" class="p-1 -mr-2 -mt-2 transition-colors hover:bg-black/5 rounded-md text-current opacity-60 hover:opacity-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>
