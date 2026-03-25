@props([
    'steps' => [], // Array of strings: ['New Booking', 'Confirmed', 'In Preparation', 'Completed']
    'currentStep' => 1, // 1-indexed
])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    @foreach($steps as $index => $label)
        @php
            $stepNum = $index + 1;
            $isDone = $stepNum < $currentStep;
            $isActive = $stepNum == $currentStep;
            $isFuture = $stepNum > $currentStep;
            
            $dotBase = 'w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-colors duration-200';
            if ($isDone) {
                $dotClasses = "$dotBase bg-success border-dp-success text-dp-white";
            } elseif ($isActive) {
                $dotClasses = "$dotBase bg-primary border-dp-rose text-dp-white";
            } else {
                $dotClasses = "$dotBase bg-base-100 border-base-content/10 text-base-content/40";
            }
        @endphp

        <div class="flex flex-col items-center gap-1">
            <div class="{{ $dotClasses }}">
                @if($isDone)
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                @else
                    {{ $stepNum }}
                @endif
            </div>
            <span class="text-[10px] font-medium text-base-content/60 text-center whitespace-nowrap">
                {{ $label }}
            </span>
        </div>

        @if(!$loop->last)
            <div class="h-[2px] flex-1 min-w-[24px] mb-4 {{ $isDone ? 'bg-success' : 'border-base-content/10' }}"></div>
        @endif
    @endforeach
</div>
