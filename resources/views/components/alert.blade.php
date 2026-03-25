@props([
    'type' => 'info',
    'message' => '',
    'title' => null,
    'dismissible' => true,
])

@php
    $variants = [
        'success' => [
            'border' => 'border-dp-success',
            'bg' => 'bg-success-soft',
            'text' => 'text-dp-success',
            'icon' => 'check-circle-solid',
        ],
        'warning' => [
            'border' => 'border-dp-warning',
            'bg' => 'bg-warning/10',
            'text' => 'text-dp-warning',
            'icon' => 'exclamation-triangle-solid',
        ],
        'danger' => [
            'border' => 'border-dp-danger',
            'bg' => 'bg-error/10',
            'text' => 'text-error',
            'icon' => 'x-circle-solid',
        ],
        'info' => [
            'border' => 'border-dp-info',
            'bg' => 'bg-info/10',
            'text' => 'text-dp-info',
            'icon' => 'information-circle-solid',
        ],
    ];

    $variant = $variants[$type] ?? $variants['info'];
    $autoDismiss = in_array($type, ['success', 'info']);
@endphp

<div x-data="{ 
        show: true,
        init() {
            if ({{ $autoDismiss ? 'true' : 'false' }}) {
                setTimeout(() => this.show = false, 5000);
            }
        }
     }" 
     x-show="show"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     {{ $attributes->merge(['class' => "{$variant['bg']} border-l-[3px] {$variant['border']} rounded-md p-4 relative flex items-start gap-3"]) }}
>
    {{-- Icon --}}
    <div class="flex-shrink-0 mt-0.5">
        @include('layouts.partials.icons.' . $variant['icon'], ['class' => "w-5 h-5 {$variant['text']}"])
    </div>

    {{-- Content --}}
    <div class="flex-1">
        @if($title)
            <h4 class=" text-[13px] font-bold {{ $variant['text'] }} leading-tight mb-1">{{ $title }}</h4>
        @endif
        <div class=" text-[13px] {{ $variant['text'] }} opacity-90 leading-normal">
            {{ $message ?: $slot }}
        </div>
    </div>

    {{-- Dismiss Button --}}
    @if($dismissible)
        <button @click="show = false" class="flex-shrink-0 {{ $variant['text'] }} opacity-50 hover:opacity-100 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>
