@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'icon' => null,
    'disabled' => false,
    'loading' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center  font-medium rounded transition-all duration-200 focus:outline-none focus:ring-3 focus:ring-dp-rose-soft disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none';
    
    $variants = [
        'primary' => 'bg-primary text-white border border-dp-rose hover:bg-primary-light',
        'secondary' => 'bg-secondary text-white border border-dp-green hover:bg-secondary-light',
        'outline' => 'bg-transparent text-primary border border-dp-rose-border hover:bg-primary/5',
        'ghost' => 'bg-transparent text-base-content/80 border border-base-content/10 hover:bg-base-200',
        'danger' => 'bg-dp-danger text-white border border-dp-danger hover:bg-[#991B1B]', /* Manually darkened for danger hover */
    ];

    $sizes = [
        'sm' => 'py-1.5 px-3 text-[11px]',
        'md' => 'py-2.5 px-4.5 text-[13px]',
        'lg' => 'py-3 px-6 text-[15px]',
        'icon' => 'p-2',
    ];

    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    
    // Wire loading logic
    $loadingTarget = $attributes->get('wire:target');
@endphp

<button 
    {{ $type === 'submit' ? 'type=submit' : 'type=button' }}
    {{ $disabled || $loading ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => "{$baseClasses} {$variantClass} {$sizeClass}"]) }}
>
    {{-- Loading Spinner --}}
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($loadingTarget)
        <svg wire:loading wire:target="{{ $loadingTarget }}" class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif

    @if($icon && !$loading)
        <span @if($loadingTarget) wire:loading.remove wire:target="{{ $loadingTarget }}" @endif class="{{ $size === 'icon' ? '' : 'mr-2' }}">
            @include('layouts.partials.icons.' . $icon, ['class' => ($size === 'sm' ? 'w-3.5 h-3.5' : 'w-4 h-4')])
        </span>
    @endif

    <span @if($loadingTarget) wire:loading.remove wire:target="{{ $loadingTarget }}" @endif>
        {{ $slot }}
    </span>
</button>
