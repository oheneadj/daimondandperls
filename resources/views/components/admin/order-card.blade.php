@props([
    'reference',
    'customerName',
    'customerPhone' => null,
    'status',
    'amount',
    'method',
    'date',
    'items' => [],
    'showActions' => true,
    'detailRoute' => '#'
])

@php
    $type = match($status) {
        'confirmed', 'paid' => 'success',
        'completed' => 'info',
        'cancelled', 'failed' => 'danger',
        'in_preparation' => 'info',
        default => 'warning'
    };

    // Transform status for display
    $displayStatus = str_replace('_', ' ', $status);
@endphp

<x-ui.card padding="none" {{ $attributes->merge(['class' => 'flex flex-col group h-full']) }}>
    <!-- Header -->
    <div class="p-5 border-b border-base-content/10 flex justify-between items-start bg-base-200/30">
        <div class="space-y-1">
            <h3 class=" text-[13px] font-bold text-base-content tracking-tight">REF: {{ $reference }}</h3>
            <p class=" text-[12px] text-base-content/60 font-medium opacity-70">
                {{ $customerName }}
                @if($customerPhone)
                    <span class="mx-1.5 opacity-30">|</span>
                    <span class="text-[11px]">{{ $customerPhone }}</span>
                @endif
            </p>
        </div>
        <x-ui.badge :type="$type" dot>{{ $displayStatus }}</x-ui.badge>
    </div>

    <!-- Items Section -->
    <div class="p-5 flex-1 bg-base-100">
        <div class="space-y-2.5">
            @forelse($items as $item)
                <div class="flex items-start justify-between gap-3  text-[12px]">
                    <span class="text-base-content leading-snug">
                         <span class="text-primary font-bold">{{ $item->quantity }}×</span> {{ $item->package->name ?? 'Package' }}
                    </span>
                </div>
            @empty
                <p class=" text-[11px] text-base-content/60/50 italic py-2 text-center">{{ __('No packages selected') }}</p>
            @endforelse
        </div>
    </div>

    <!-- Meta Info -->
    <div class="px-5 py-4 border-t border-base-content/10/50 bg-base-200/10 flex flex-wrap gap-x-5 gap-y-2  text-[10px] font-bold uppercase tracking-widest text-base-content/60/60">
        <div class="flex items-center gap-1.5">
            <span class="text-base-content font-bold">GH₵{{ number_format($amount, 2) }}</span>
        </div>
        <div>
            {{ $method ?? 'TBD' }}
        </div>
        <div>
             {{ $date ? \Carbon\Carbon::parse($date)->format('M d, Y') : 'TBD' }}
        </div>
    </div>

    <!-- Actions -->
    @if($showActions)
        <div class="p-4 border-t border-base-content/10 bg-base-200/30 flex justify-end gap-2.5">
            <x-ui.button variant="ghost" size="sm" class="text-base-content/60 hover:text-primary" :href="$detailRoute" wire:navigate>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{ __('Details') }}
            </x-ui.button>
            
            {{ $slot }}
        </div>
    @endif
</x-ui.card>
