@props([
    'label' => null,
    'id' => \Illuminate\Support\Str::random(8),
    'value' => false,
])

<div x-data="{ on: @if($attributes->wire('model')->value()) @entangle($attributes->wire('model')) @else {{ $value ? 'true' : 'false' }} @endif }" class="flex items-center gap-3">
    <button 
        type="button"
        @click="on = !on"
        :class="on ? 'bg-secondary' : 'border-base-content/10'"
        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out outline-none focus:ring-3 focus:ring-dp-rose-soft"
        role="switch"
        :aria-checked="on.toString()"
    >
        <span 
            aria-hidden="true" 
            :class="on ? 'translate-x-5' : 'translate-x-1'"
            class="pointer-events-none inline-block h-[18px] w-[18px] transform rounded-full bg-base-100 shadow-sm ring-0 transition duration-200 ease-in-out mt-[3px]"
        ></span>
    </button>
    
    @if($label)
        <span 
            :class="on ? 'text-base-content' : 'text-base-content/60'"
            class="text-[13px] font-medium transition-colors duration-200"
        >
            {{ $label }}
            <span x-show="on" class="text-[12px] opacity-60">(on)</span>
            <span x-show="!on" class="text-[12px] opacity-60">(off)</span>
        </span>
    @endif
</div>
