@props([
    'status',
])
@if ($status)
    <div {{ $attributes->merge(['class' => 'p-4 bg-success/10 border border-success/15 rounded-lg flex items-center gap-3']) }}>
        <div class="size-8 bg-success/10 rounded-full flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <span class="text-[13px] font-medium text-success">{{ $status }}</span>
    </div>
@endif
