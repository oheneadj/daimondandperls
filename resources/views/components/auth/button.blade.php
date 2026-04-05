@props([
    'type' => 'submit',
    'variant' => 'dark',
    'wireClick' => null,
    'wireTarget' => null,
    'loadingText' => null,
])

@php
    $variants = [
        'dark' => 'bg-black text-white hover:brightness-110 focus:ring-black/30',
        'primary' => 'bg-primary text-primary-content hover:brightness-110 focus:ring-primary/30',
        'ghost' => 'bg-transparent text-base-content/60 hover:bg-base-200 focus:ring-base-content/10',
    ];
    $variantClass = $variants[$variant] ?? $variants['dark'];
    $baseClass = $variant === 'ghost'
        ? 'w-full px-[18px] py-[10px] text-[13px] font-medium rounded-xl transition-all duration-150 flex items-center justify-center gap-2 focus:outline-none focus:ring-3 focus:ring-offset-1'
        : 'w-full px-[24px] py-[13px] text-[15px] font-medium rounded-xl transition-all duration-150 flex items-center justify-center gap-2 focus:outline-none focus:ring-3 focus:ring-offset-1';
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
    @else
        {{ $slot }}
    @endif
</button>
