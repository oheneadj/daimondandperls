@props([
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'icon' => null, // Heroicon SVG
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-xl transition-all duration-150 focus:outline-none focus:ring-3 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap no-underline';
    
    $variants = [
        'primary' => 'bg-primary text-primary-content hover:brightness-110 focus:ring-primary/30',
        'secondary' => 'bg-base-200 text-base-content hover:bg-base-300 focus:ring-base-content/10',
        'accent' => 'bg-accent text-neutral hover:brightness-110 focus:ring-accent/30',
        'success' => 'bg-success text-white hover:brightness-110 focus:ring-success/30',
        'danger' => 'bg-error text-white hover:brightness-110 focus:ring-error/30',
        'outline' => 'bg-transparent text-base-content hover:bg-base-content/5 focus:ring-base-content/10',
        'ghost' => 'bg-transparent text-base-content/80 hover:bg-base-200 focus:ring-base-content/10',
        'black' => 'bg-black text-white hover:brightness-110 focus:ring-black/30',
        'green' => 'bg-[#18542A] text-white hover:brightness-110 focus:ring-[#18542A]/30',
    ];

    $sizes = [
        'sm' => 'px-[12px] py-[6px] text-[11px] gap-2',
        'md' => 'px-[18px] py-[10px] text-[13px] gap-2',
        'lg' => 'px-[24px] py-[13px] text-[15px] gap-2',
        'icon' => 'p-[8px]',
        'icon-sm' => 'p-[5px]',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
    
    // Spec: Heroicons w-4 h-4 (16px) for inline, w-5 h-5 (20px) for sidebar/stat
    $iconSize = 'w-4 h-4';
    if ($size === 'lg') $iconSize = 'w-5 h-5';
    if ($size === 'sm') $iconSize = 'w-3.5 h-3.5';
@endphp

@if($href)
    <a {{ $attributes->merge(['class' => $classes, 'href' => $href]) }}>
        @if($icon)
            <span class="{{ $iconSize }} flex items-center justify-center shrink-0">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => 'button', 'disabled' => $loading]) }}>
        <div class="relative flex items-center justify-center gap-2">
            @if($loading)
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="animate-spin {{ $iconSize }} text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div class="invisible flex items-center justify-center gap-2">
                    @if($icon)
                        <span class="{{ $iconSize }} flex items-center justify-center shrink-0"></span>
                    @endif
                    <span>{{ $slot }}</span>
                </div>
            @else
                @if($icon)
                    <span class="{{ $iconSize }} flex items-center justify-center shrink-0">{!! $icon !!}</span>
                @endif
                {{ $slot }}
            @endif
        </div>
    </button>
@endif
