@props([
    'steps' => [],
    'currentStep' => 1,
])

<div class="mb-12 lg:mb-16">
    <div class="flex items-center justify-between relative max-w-3xl mx-auto">
        @php
            $totalSteps = count($steps);
            $progressWidth = $totalSteps > 1 ? (($currentStep - 1) / ($totalSteps - 1)) * 100 : 0;
        @endphp

        {{-- Line connector --}}
        <div class="absolute top-5 left-0 w-full h-0.5 border-base-content/10 -z-10"></div>
        <div class="absolute top-5 left-0 h-0.5 bg-primary -z-10 transition-all duration-700" style="width: {{ $progressWidth }}%"></div>

        @foreach($steps as $index => $label)
            @php $stepNum = $index + 1; @endphp
            <div wire:key="step-nav-{{ $label }}" class="flex flex-col items-center gap-3">
                <div @class([
                    'size-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-500',
                    'bg-primary text-white shadow-xl scale-110 ring-4 ring-primary/20' => $currentStep === $stepNum,
                    'bg-primary text-white' => $currentStep > $stepNum,
                    'bg-base-100 text-base-content/30 border-2 border-base-content/10' => $currentStep < $stepNum,
                ])>
                    @if($currentStep > $stepNum)
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @else
                        {{ $stepNum }}
                    @endif
                </div>
                <span @class([
                    'text-[10px] uppercase tracking-[0.15em] font-bold hidden sm:block',
                    'text-primary' => $currentStep === $stepNum,
                    'text-base-content/60' => $currentStep !== $stepNum,
                ])>{{ $label }}</span>
            </div>
        @endforeach
    </div>
</div>
