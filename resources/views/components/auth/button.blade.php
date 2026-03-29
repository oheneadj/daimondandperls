@props([
    'type' => 'submit',
    'variant' => 'dark',
    'wireClick' => null,
    'wireTarget' => null,
    'loadingText' => null,
])

@php
    $variants = [
        'dark' => 'bg-[#121212] hover:bg-black text-white shadow-xl shadow-black/10',
        'primary' => 'bg-primary hover:bg-primary/90 text-white shadow-xl shadow-primary/20',
        'ghost' => 'bg-transparent text-base-content/60 hover:text-base-content shadow-none',
    ];
    $variantClass = $variants[$variant] ?? $variants['dark'];
    $baseClass = $variant === 'ghost'
        ? 'w-full text-[13px] font-bold transition-colors flex items-center justify-center gap-2'
        : 'w-full h-15 rounded-full font-black text-[13px] uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3 group';
@endphp

<button
    type="{{ $type }}"
    @if($wireClick) wire:click="{{ $wireClick }}" @endif
    @if($wireClick) wire:loading.attr="disabled" @endif
    {{ $attributes->merge(['class' => $baseClass . ' ' . $variantClass]) }}
>
    @if($wireTarget && $loadingText)
        <span wire:loading.remove wire:target="{{ $wireTarget }}">{{ $slot }}</span>
        <span wire:loading wire:target="{{ $wireTarget }}" class="flex items-center gap-2">
            <span class="loading loading-spinner loading-sm"></span>
            {{ $loadingText }}
        </span>
        <svg wire:loading.remove wire:target="{{ $wireTarget }}" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
        </svg>
    @else
        {{ $slot }}
        @if($variant !== 'ghost')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        @endif
    @endif
</button>
