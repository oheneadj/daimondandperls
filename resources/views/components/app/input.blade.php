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
    $isPassword = $type === 'password';
    $hasError = $errors->has($name);
    $baseClasses = 'w-full ' . ($icon ? 'pl-12' : 'px-[14px]') . ($isPassword ? ' pr-10' : ' pr-[14px]') . ' py-[10px] text-[15px] bg-base-100 border rounded-lg transition-all duration-120 outline-none placeholder:text-base-content/40 disabled:bg-base-200 disabled:cursor-not-allowed';
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

    <div class="relative" @if($isPassword) x-data="{ show: false }" @endif>
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
        @elseif($isPassword)
            <input
                {{ $attributes->except('type')->merge([
                    'class' => $inputClasses,
                    'name' => $name,
                    'id' => $name,
                ]) }}
                :type="show ? 'text' : 'password'"
            >
            <button type="button" @click="show = !show" tabindex="-1"
                class="absolute inset-y-0 right-0 flex items-center px-3 text-base-content/40 hover:text-base-content/70 transition-colors">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m2.343-2.524A9.955 9.955 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.965 9.965 0 01-1.588 2.905m-5.197-4.51a3 3 0 014.243 4.243M9.878 9.878l4.242 4.242M3 3l18 18" />
                </svg>
            </button>
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
