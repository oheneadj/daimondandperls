@props([
    'href' => null,
    'icon' => null,
])

@php
    $classes = 'dropdown-item flex items-center gap-2.5 px-3 py-2 text-[13px] font-medium text-base-content hover:bg-base-200 rounded-lg transition-colors cursor-pointer w-full text-left';
@endphp

@if($href)
    <a {{ $attributes->merge(['class' => $classes, 'href' => $href]) }} role="menuitem">
        @if($icon)
            <span class="size-4 text-base-content/50 flex items-center justify-center shrink-0">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }} role="menuitem">
        @if($icon)
            <span class="size-4 text-base-content/50 flex items-center justify-center shrink-0">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </button>
@endif
