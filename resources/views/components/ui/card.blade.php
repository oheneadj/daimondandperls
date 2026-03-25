@props([
    'padding' => 'default',
    'accent' => null, // rose, green, warning, info
])

@php
    $paddings = [
        'default' => 'p-6',
        'compact' => 'p-4',
        'none' => 'p-0',
    ];

    $accents = [
        'rose' => 'border-t-3 border-t-dp-rose',
        'green' => 'border-t-3 border-t-dp-green',
        'warning' => 'border-t-3 border-t-dp-warning',
        'info' => 'border-t-3 border-t-dp-info',
        'primary' => 'border-t-3 border-t-primary',
    ];

    $classes = 'bg-base-100 border border-base-content/10 rounded-lg shadow-sm transition-all duration-200 ' . ($paddings[$padding] ?? $paddings['default']);
    if ($accent) {
        $classes .= ' ' . ($accents[$accent] ?? '');
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
