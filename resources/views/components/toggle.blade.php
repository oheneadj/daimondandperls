@props([
    'model' => null,
])

<div x-data="{ 
    enabled: @entangle($attributes->wire('model'))
}" class="flex items-center gap-3">
    <button type="button" 
            @click="enabled = !enabled"
            :class="enabled ? 'bg-secondary' : 'border-base-content/10'"
            {{ $attributes->merge(['class' => 'relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-dp-rose/20']) }}
            role="switch" 
            :aria-checked="enabled">
        <span aria-hidden="true" 
              :class="enabled ? 'translate-x-5' : 'translate-x-0'"
              class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
        </span>
    </button>
    
    <span :class="enabled ? 'text-base-content/80' : 'text-base-content/60'"
          class=" text-[13px] font-medium transition-colors duration-200">
        <span x-text="enabled ? 'Active' : 'Inactive'"></span>
    </span>
</div>
