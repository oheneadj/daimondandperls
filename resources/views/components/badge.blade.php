@props([
    'type' => 'default',
    'dot' => false,
])

@php
    $variants = [
        // Booking Status (with dot)
        'new' => ['bg' => 'bg-info/10', 'text' => 'text-dp-info', 'dot' => 'bg-dp-info'],
        'pending' => ['bg' => 'bg-[#FFC926] shadow-sm', 'text' => 'text-black', 'dot' => 'hidden'],
        'confirmed' => ['bg' => 'bg-[#18542A] shadow-sm', 'text' => 'text-white', 'dot' => 'hidden'],
        'in_preparation' => ['bg' => 'bg-[#9ABC05] shadow-sm', 'text' => 'text-white', 'dot' => 'hidden'],
        'preparation' => ['bg' => 'bg-[#9ABC05] shadow-sm', 'text' => 'text-white', 'dot' => 'hidden'],
        'completed' => ['bg' => 'bg-base-content/20 shadow-sm', 'text' => 'text-base-content/60', 'dot' => 'hidden'],
        'cancelled' => ['bg' => 'bg-[#D52518] shadow-sm', 'text' => 'text-white', 'dot' => 'hidden'],
        
        // Payment Status (no dot)
        'paid' => ['bg' => 'bg-success-soft', 'text' => 'text-dp-success'],
        'pending' => ['bg' => 'bg-warning/10', 'text' => 'text-dp-warning'],
        'failed' => ['bg' => 'bg-error/10', 'text' => 'text-error'],
        'verification' => ['bg' => 'bg-primary-soft', 'text' => 'text-primary'],
        
        // Event Type
        'wedding' => ['bg' => 'bg-primary-soft', 'text' => 'text-primary'],
        'birthday' => ['bg' => 'bg-success-soft', 'text' => 'text-dp-success'],
        'corporate' => ['bg' => 'bg-info/10', 'text' => 'text-dp-info'],
        'funeral' => ['bg' => 'bg-warning/10', 'text' => 'text-dp-warning'],
        'party' => ['bg' => 'bg-secondary-soft', 'text' => 'text-secondary'],
        'other' => ['bg' => 'bg-base-200-mid', 'text' => 'text-base-content/60'],
        
        'default' => ['bg' => 'bg-base-200-mid', 'text' => 'text-base-content/60'],
    ];

    $variant = $variants[$type] ?? $variants['default'];
    $hasDot = $dot || isset($variant['dot']);
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 py-0.5 px-2.5 rounded-full  text-[11px] font-bold uppercase tracking-wider {$variant['bg']} {$variant['text']}"]) }}>
    @if($hasDot)
        <span class="w-1.5 h-1.5 rounded-full {{ $variant['dot'] ?? str_replace('text-', 'bg-', $variant['text']) }}"></span>
    @endif
    {{ $slot }}
</span>
