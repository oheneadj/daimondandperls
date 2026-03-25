@props([
    'icon' => '📋',
    'title' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-12 px-6']) }}>
    <div class="text-5xl text-dp-border mb-4">
        {{ $icon }}
    </div>
    
    @if($title)
        <h3 class=" text-xl font-semibold text-base-content mb-2">
            {{ $title }}
        </h3>
    @endif
    
    @if($description)
        <p class="text-[13px] text-base-content/60 mb-5 max-w-sm mx-auto">
            {{ $description }}
        </p>
    @endif
    
    @if($slot->isNotEmpty())
        <div class="flex justify-center">
            {{ $slot }}
        </div>
    @endif
</div>
