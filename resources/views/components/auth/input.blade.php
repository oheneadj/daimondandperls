@props([
    'name',
    'label' => null,
    'type' => 'text',
    'icon' => null,
    'hint' => null,
    'required' => false,
    'wireModel' => null,
])

<div class="space-y-2.5">
    @if($label)
        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 ml-1">
            {{ $label }}
            @if($required)
                <span class="text-error">*</span>
            @endif
        </label>
    @endif

    <div class="relative group">
        @if($icon)
            <span class="absolute inset-y-0 left-5 flex items-center text-base-content/50 group-focus-within:text-primary transition-colors">
                {!! $icon !!}
            </span>
        @endif

        @if($wireModel)
            <input
                wire:model="{{ $wireModel }}"
                type="{{ $type }}"
                {{ $attributes->merge([
                    'class' => 'block w-full ' . ($icon ? 'pl-16' : 'px-6') . ' rounded-full h-14 bg-[#F4F4F6]/70 border-transparent text-[15px] font-medium focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary/20 shadow-inner group-focus-within:shadow-none transition-all placeholder:text-base-content/30',
                    'name' => $name,
                ]) }}
            >
        @else
            <input
                type="{{ $type }}"
                {{ $attributes->merge([
                    'class' => 'block w-full ' . ($icon ? 'pl-16' : 'px-6') . ' rounded-full h-14 bg-[#F4F4F6]/70 border-transparent text-[15px] font-medium focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary/20 shadow-inner group-focus-within:shadow-none transition-all placeholder:text-base-content/30',
                    'name' => $name,
                ]) }}
            >
        @endif
    </div>

    @if($hint)
        <p class="text-[10px] text-base-content/30 font-medium ml-1 italic">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-[11px] font-bold text-error mt-2 ml-1">{{ $message }}</p>
    @enderror
</div>
