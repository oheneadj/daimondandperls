@props([
    'icon' => 'squares-2x2', // Default icon
    'title' => 'No items found',
    'description' => 'Try adjusting your search or filters to find what you\'re looking for.',
    'actionLabel' => null,
    'actionRoute' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-20 text-center px-6']) }}>
    {{-- Icon --}}
    <div class="text-dp-border mb-6">
        @include('layouts.partials.icons.' . $icon, ['class' => 'w-16 h-16'])
    </div>

    {{-- Text --}}
    <h3 class=" text-[20px] font-semibold text-base-content mb-2">
        {{ $title }}
    </h3>
    <p class=" text-[13px] text-base-content/60 max-w-[280px] mx-auto leading-relaxed">
        {{ $description }}
    </p>

    {{-- Action --}}
    @if($actionLabel && $actionRoute)
        <div class="mt-8">
            <x-button variant="outline" href="{{ $actionRoute }}" wire:navigate>
                {{ $actionLabel }}
            </x-button>
        </div>
    @elseif(isset($action))
        <div class="mt-8">
            {{ $action }}
        </div>
    @endif
</div>
