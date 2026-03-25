@props([
    'type' => 'info',
    'dot' => false,
])

@php
    $baseClasses = 'badge gap-1.5';
    
    $types = [
        'info' => 'badge-info',
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger' => 'badge-error',
        'primary' => 'badge-primary',
        'secondary' => 'badge-secondary',
        'accent' => 'badge-accent',
        'neutral' => 'badge-neutral',
        'outline' => 'badge-outline',
        // Semantic aliases
        'new' => 'badge-info',
        'confirmed' => 'badge-success',
        'preparing' => 'badge-warning',
        'completed' => 'badge-success',
        'cancelled' => 'badge-error',
        'paid' => 'badge-success',
        'pending' => 'badge-warning',
        'failed' => 'badge-error',
        'brand' => 'badge-primary',
        'ghost' => 'badge-ghost',
    ];

    $dotColors = [
        'info' => 'bg-white',
        'success' => 'bg-white',
        'warning' => 'bg-white',
        'danger' => 'bg-error',
        'primary' => 'bg-primary',
        'secondary' => 'bg-secondary',
        'accent' => 'bg-accent',
        'neutral' => 'bg-white',
        'new' => 'bg-info',
        'confirmed' => 'bg-white',
        'preparing' => 'bg-white',
        'completed' => 'bg-success',
        'cancelled' => 'bg-error',
    ];

    $classes = $baseClasses . ' ' . ($types[$type] ?? $types['info']);
    
    // Dot: 6x6px rounded-full
    $dotClasses = 'w-1.5 h-1.5 rounded-full ' . ($dotColors[$type] ?? 'bg-current');
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="{{ $dotClasses }}"></span>
    @endif
    {{ $slot }}
</span>
