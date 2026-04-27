{{--
    Checkout progress indicator — used on /checkout (step 1) and /booking/payment (step 2).
    Steps always reflect the actual pages in the flow: Details → Payment → Done.

    $currentStep: 1 = checkout, 2 = payment, 3 = confirmation (done)
--}}
@php
    $progressSteps = [
        1 => ['label' => 'Details',  'backRoute' => null],
        2 => ['label' => 'Payment',  'backRoute' => 'checkout'],
        3 => ['label' => 'Done',     'backRoute' => null],
    ];
    $totalProgressSteps = count($progressSteps);
    $progressWidth = round(($currentStep - 1) / ($totalProgressSteps - 1) * 100);
@endphp
<div class="mb-8">
    {{-- Back link — shown on payment page only (step 2+); step 1 has the order summary sidebar instead --}}
    @if($currentStep > 1)
        <a href="{{ route($progressSteps[$currentStep]['backRoute']) }}" wire:navigate
           class="inline-flex items-center gap-2 text-[13px] font-semibold text-base-content/50 hover:text-primary transition-colors group mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ __('Back to :step', ['step' => $progressSteps[$currentStep - 1]['label']]) }}
        </a>
    @endif

    <div class="flex items-center justify-between relative max-w-xs mx-auto sm:max-w-sm">
        {{-- Track --}}
        <div class="absolute top-5 left-0 w-full h-0.5 bg-base-content/10 -z-10"></div>
        <div class="absolute top-5 left-0 h-0.5 bg-primary -z-10 transition-all duration-700"
             style="width: {{ $progressWidth }}%"></div>

        @foreach($progressSteps as $num => $step)
            @php
                $isDone    = $currentStep > $num;
                $isActive  = $currentStep === $num;
                $isFuture  = $currentStep < $num;
            @endphp

            {{-- Completed steps are clickable (go back); active and future are not --}}
            @if($isDone && $step['backRoute'])
                <a href="{{ route($step['backRoute']) }}" wire:navigate class="flex flex-col items-center gap-3 group">
            @else
                <div class="flex flex-col items-center gap-3">
            @endif
                <div @class([
                    'size-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-500',
                    'bg-primary text-white shadow-xl scale-110 ring-4 ring-primary/20'                          => $isActive,
                    'bg-primary text-white group-hover:ring-4 group-hover:ring-primary/20 group-hover:scale-105' => $isDone && $step['backRoute'],
                    'bg-primary text-white'                                                                      => $isDone && !$step['backRoute'],
                    'bg-base-100 text-base-content/30 border-2 border-base-content/10'                          => $isFuture,
                ])>
                    @if($isDone)
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        {{ $num }}
                    @endif
                </div>
                <span @class([
                    'text-[10px] uppercase tracking-[0.15em] font-bold transition-colors',
                    'text-primary'                                        => $isActive,
                    'text-base-content/60 group-hover:text-primary'       => $isDone && $step['backRoute'],
                    'text-base-content/60'                                => $isDone && !$step['backRoute'],
                    'text-base-content/30'                                => $isFuture,
                ])>{{ $step['label'] }}</span>
            @if($isDone && $step['backRoute'])
                </a>
            @else
                </div>
            @endif
        @endforeach
    </div>
</div>
