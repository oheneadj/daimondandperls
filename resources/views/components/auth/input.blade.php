@props([
    'name',
    'label' => null,
    'type' => 'text',
    'icon' => null,
    'hint' => null,
    'required' => false,
    'wireModel' => null,
])

<div class="form-control w-full space-y-1.5">
    @if($label)
        <label class="text-dp-sm font-medium text-base-content block">
            {{ $label }}
            @if($required)
                <span class="text-error">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-base-content/40 pointer-events-none">
                {!! $icon !!}
            </span>
        @endif

        @if($wireModel)
            <input
                wire:model="{{ $wireModel }}"
                type="{{ $type }}"
                {{ $attributes->merge([
                    'class' => 'w-full ' . ($icon ? 'pl-12 pr-[14px]' : 'px-[14px]') . ' py-[10px] text-[15px] bg-base-100 border rounded-lg transition-all duration-120 outline-none placeholder:text-base-content/40 disabled:bg-base-200 disabled:cursor-not-allowed border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20',
                    'name' => $name,
                ]) }}
            >
        @else
            <input
                type="{{ $type }}"
                {{ $attributes->merge([
                    'class' => 'w-full ' . ($icon ? 'pl-12 pr-[14px]' : 'px-[14px]') . ' py-[10px] text-[15px] bg-base-100 border rounded-lg transition-all duration-120 outline-none placeholder:text-base-content/40 disabled:bg-base-200 disabled:cursor-not-allowed border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20',
                    'name' => $name,
                ]) }}
            >
        @endif
    </div>

    @if($hint)
        <p class="text-xs text-base-content/60 mt-1">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-xs text-error flex items-center gap-1">
            <span>⚠</span> {{ $message }}
        </p>
    @enderror
</div>
