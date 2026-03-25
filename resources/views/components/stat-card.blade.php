@props([
    'title',
    'value',
    'trend' => null, // up, down
    'trendValue' => null,
    'subtext' => null,
    'color' => 'primary', // primary, success, error, accent
    'featured' => false,
])

@php
    $variants = [
        'primary' => [
            'bg-soft'  => 'bg-primary/5',
            'text'     => 'text-primary',
            'icon-bg'  => 'bg-primary/15',
            'border'   => 'border-primary/12',
        ],
        'success' => [
            'bg-soft'  => 'bg-success/5',
            'text'     => 'text-success',
            'icon-bg'  => 'bg-success/15',
            'border'   => 'border-success/12',
        ],
        'error' => [
            'bg-soft'  => 'bg-error/5',
            'text'     => 'text-error',
            'icon-bg'  => 'bg-error/15',
            'border'   => 'border-error/12',
        ],
        'accent' => [
            'bg-soft'  => 'bg-accent/5',
            'text'     => 'text-accent',
            'icon-bg'  => 'bg-accent/15',
            'border'   => 'border-accent/12',
        ],
    ];

    // Maintain legacy aliases
    $aliases = [
        'rose' => 'primary',
        'green' => 'success',
        'warning' => 'primary',
        'info' => 'success',
    ];
    
    $colorKey = $aliases[$color] ?? $color;
    $v = $variants[$colorKey] ?? $variants['primary'];
@endphp

<div {{ $attributes->merge(['class' => ($featured ? $v['bg-soft'] : "bg-white") . " rounded-2xl shadow-sm p-6 relative overflow-hidden transition-all hover:shadow-md hover:-translate-y-0.5 group border " . ($featured ? $v['border'] : "border-base-content/[0.03]")]) }}>
    <div class="flex flex-col h-full relative z-10">
        {{-- Header: Label + Icon --}}
        <div class="flex items-start justify-between mb-5">
            <span class="text-[12px] font-bold tracking-widest text-[#18542A] uppercase opacity-90">
                {{ $title }}
            </span>
            
            <div class="{{ $v['icon-bg'] }} {{ $v['text'] }} w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110 shadow-sm shadow-black/5">
                @if(isset($icon))
                    {{ $icon }}
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                @endif
            </div>
        </div>

        {{-- Value + Trend --}}
        <div class="flex flex-col gap-1.5">
            <div class="flex items-baseline gap-2">
                <span class="text-[34px] font-bold text-base-content leading-none tracking-tight">
                    {{ $value }}
                </span>
                
                @if($trend)
                    <div @class([
                        'flex items-center text-[11px] font-bold px-2 py-0.5 rounded-full',
                        'bg-success/10 text-success' => strtolower($trend) === 'up',
                        'bg-error/10 text-error' => strtolower($trend) === 'down',
                    ])>
                        @if(strtolower($trend) === 'up')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <span>{{ $trendValue }}</span>
                    </div>
                @endif
            </div>

            {{-- Subtext --}}
            @if($subtext)
                <p class="text-[12px] text-base-content/40 font-medium">
                    {{ $subtext }}
                </p>
            @endif
        </div>
    </div>
</div>
