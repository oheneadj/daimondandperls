@props([
    'type' => 'submit',
    'variant' => 'dark',
    'size' => 'lg',
    'wireClick' => null,
    'wireTarget' => null,
    'loadingText' => null,
    'loading' => false,
    'href' => null,
    'icon' => null,
])

@php
    $variants = [
        'dark' => 'bg-black text-white hover:brightness-110 focus:ring-black/30',
        'primary' => 'bg-primary text-primary-content hover:brightness-110 focus:ring-primary/30',
        'ghost' => 'bg-transparent text-base-content/60 hover:bg-base-200 focus:ring-base-content/10',
        'secondary' => 'bg-base-200 text-base-content hover:bg-base-300 focus:ring-base-content/10',
        'danger' => 'bg-error text-white hover:brightness-110 focus:ring-error/30',
        'outline' => 'bg-transparent text-base-content hover:bg-base-content/5 focus:ring-base-content/10 border border-base-content/10',
        'black' => 'bg-black text-white hover:brightness-110 focus:ring-black/30',
        'green' => 'bg-[#18542A] text-white hover:brightness-110 focus:ring-[#18542A]/30',
    ];

    $sizes = [
        'sm' => 'px-[12px] py-[6px] text-[11px]',
        'md' => 'px-[18px] py-[10px] text-[13px]',
        'lg' => 'px-[24px] py-[13px] text-[15px]',
    ];

    $baseClasses = 'rounded-xl font-medium transition-all duration-150 inline-flex items-center justify-center gap-2 focus:outline-none focus:ring-3 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed';
    $variantClass = $variants[$variant] ?? $variants['dark'];
    $sizeClass = $sizes[$size] ?? $sizes['lg'];
    $classes = $baseClasses . ' ' . $variantClass . ' ' . $sizeClass;

    $iconSize = match($size) {
        'sm' => 'w-3.5 h-3.5',
        'md' => 'w-4 h-4',
        default => 'w-5 h-5',
    };
@endphp

@if($href)
    <a {{ $attributes->merge(['class' => $classes, 'href' => $href]) }}>
        @if($icon)
            <span class="{{ $iconSize }} flex items-center justify-center shrink-0">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        @if($wireClick) wire:click="{{ $wireClick }}" @endif
        @if($wireClick) wire:loading.attr="disabled" @endif
        {{ $attributes->merge(['class' => $classes, 'disabled' => $loading]) }}
    >
        @if($wireTarget && $loadingText)
            <span wire:loading.remove wire:target="{{ $wireTarget }}">
                @if($icon)
                    <span class="{{ $iconSize }} inline-flex items-center justify-center shrink-0">{!! $icon !!}</span>
                @endif
                {{ $slot }}
            </span>
            <span wire:loading wire:target="{{ $wireTarget }}" class="flex items-center gap-2">
                <span class="loading loading-spinner loading-sm"></span>
                {{ $loadingText }}
            </span>
        @elseif($loading)
            <span class="loading loading-spinner loading-sm"></span>
        @else
            @if($icon)
                <span class="{{ $iconSize }} flex items-center justify-center shrink-0">{!! $icon !!}</span>
            @endif
            {{ $slot }}
        @endif
    </button>
@endif
