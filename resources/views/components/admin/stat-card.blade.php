@props([
    'title',
    'value',
    'subtext' => null,
    'icon' => null,
    'trend' => null, // Up, Down
    'trendValue' => null,
    'color' => 'rose', // rose, green, info, warning
])

@php
    $colors = [
        'rose' => [
            'bg' => 'bg-primary-soft',
            'text' => 'text-primary',
        ],
        'green' => [
            'bg' => 'bg-secondary-soft',
            'text' => 'text-secondary',
        ],
        'info' => [
            'bg' => 'bg-info/10',
            'text' => 'text-dp-info',
        ],
        'warning' => [
            'bg' => 'bg-warning/10',
            'text' => 'text-dp-warning',
        ],
    ];

    $config = $colors[$color] ?? $colors['rose'];
@endphp

<x-ui.card :accent="$color" padding="compact" {{ $attributes->merge(['class' => 'hover:shadow-md transition-all']) }}>
    <div class="flex items-start justify-between">
        <div class="space-y-3">
            <span class="text-dp-xs text-base-content/60 block">
                {{ $title }}
            </span>
            
            <div class="flex items-baseline gap-3">
                <span class="text-dp-2xl text-base-content leading-none">
                    {{ $value }}
                </span>
                
                @if($trend)
                    <div @class([
                        'flex items-center gap-1 text-[12px] font-bold',
                        'text-dp-success' => $trend === 'up' || $trend === 'Up',
                        'text-error' => $trend === 'down' || $trend === 'Down',
                    ])>
                        @if($trend === 'up' || $trend === 'Up')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        @endif
                        <span>{{ $trendValue }}</span>
                    </div>
                @endif
            </div>

            @if($subtext)
                <span class="text-[11px] text-base-content/60 opacity-60 block">
                    {{ $subtext }}
                </span>
            @endif
        </div>

        @if($icon)
            <div class="{{ $config['bg'] }} {{ $config['text'] }} w-10 h-10 rounded-md flex items-center justify-center flex-shrink-0">
                {{ $icon }}
            </div>
        @endif
    </div>
</x-ui.card>
