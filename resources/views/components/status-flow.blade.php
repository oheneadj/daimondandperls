@props([
    'status' => 'pending', // pending, confirmed, preparation, completed
])

@php
    $steps = [
        ['id' => 'pending', 'label' => 'New Booking'],
        ['id' => 'confirmed', 'label' => 'Confirmed'],
        ['id' => 'preparation', 'label' => 'In Prep'],
        ['id' => 'completed', 'label' => 'Completed'],
    ];

    $currentIndex = collect($steps)->search(fn($step) => $step['id'] === $status);
    if ($currentIndex === false) $currentIndex = -1;
@endphp

<div class="w-full py-6">
    <div class="flex items-center">
        @foreach($steps as $index => $step)
            @php
                $isCompleted = $index < $currentIndex;
                $isActive = $index === $currentIndex;
                $isFuture = $index > $currentIndex;
            @endphp

            <div class="relative flex flex-col items-center flex-1">
                {{-- Connector Line (Except First) --}}
                @if($index > 0)
                    <div class="absolute w-full h-0.5 top-4 -left-1/2 {{ $isCompleted || $isActive ? 'bg-success' : 'border-base-content/10' }}"></div>
                @endif

                {{-- Step Dot --}}
                <div @class([
                    'w-8 h-8 rounded-full border-2 flex items-center justify-center z-10 transition-colors duration-300 shadow-sm',
                    'bg-success border-dp-success text-white' => $isCompleted,
                    'bg-primary border-dp-rose text-white' => $isActive,
                    'bg-white border-base-content/10 text-base-content/60' => $isFuture,
                ])>
                    @if($isCompleted)
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <span class=" text-[11px] font-bold">{{ $index + 1 }}</span>
                    @endif
                </div>

                {{-- Label --}}
                <span @class([
                    'mt-3  text-[11px] font-bold uppercase tracking-widest text-center',
                    'text-base-content' => $isActive || $isCompleted,
                    'text-base-content/60 opacity-60' => $isFuture,
                ])>
                    {{ $step['label'] }}
                </span>
            </div>
        @endforeach
    </div>
</div>
