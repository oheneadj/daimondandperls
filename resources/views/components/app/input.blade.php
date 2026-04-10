@props([
    'name',
    'label' => null,
    'as' => 'input',
    'type' => 'text',
    'icon' => null,
    'hint' => null,
    'required' => false,
    'rows' => 4,
])

@php
    $hasError = $errors->has($name);
    $baseClasses = 'w-full ' . ($icon ? 'pl-12 pr-[14px]' : 'px-[14px]') . ' py-[10px] text-[15px] bg-base-100 border rounded-lg transition-all duration-120 outline-none placeholder:text-base-content/40 disabled:bg-base-200 disabled:cursor-not-allowed';
    $stateClasses = $hasError
        ? 'border-error focus:border-error focus:ring-3 focus:ring-error/20'
        : 'border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20';
    $inputClasses = $baseClasses . ' ' . $stateClasses;
@endphp

<div class="form-control w-full space-y-1.5">
    @if($label)
        <label for="{{ $name }}" class="text-dp-sm font-medium text-base-content block">
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

        @if($as === 'select')
            <div class="relative">
                <select
                    {{ $attributes->merge([
                        'class' => $inputClasses . ' appearance-none pr-10',
                        'name' => $name,
                        'id' => $name,
                    ]) }}
                >
                    {{ $slot }}
                </select>
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-base-content/40">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
        @elseif($as === 'textarea')
            <textarea
                rows="{{ $rows }}"
                {{ $attributes->merge([
                    'class' => $inputClasses . ' resize-none',
                    'name' => $name,
                    'id' => $name,
                ]) }}
            >{{ $slot }}</textarea>
        @else
            <input
                type="{{ $type }}"
                {{ $attributes->merge([
                    'class' => $inputClasses,
                    'name' => $name,
                    'id' => $name,
                ]) }}
            >
        @endif
    </div>

    @if($hint)
        <p class="text-xs text-base-content/60">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-xs text-error flex items-center gap-1">
            <span>⚠</span> {{ $message }}
        </p>
    @enderror
</div>
