@props([
    'label' => null,
    'type' => 'text',
    'name' => null,
    'id' => null,
    'hint' => null,
    'required' => false,
    'error' => null,
    'placeholder' => null,
    'wrapperClass' => '',
])

@php
    $id = $id ?? $name ?? 'input-' . Str::random(8);
    $hasError = $error || ($name && $errors->has($name));
    $errorMessage = $error ?? ($name ? $errors->first($name) : null);
    
    $baseClasses = "w-full bg-white border rounded-lg py-2.5 px-3.5  text-[15px] font-normal leading-normal text-base-content/80 transition-all duration-200 outline-none";
    $stateClasses = $hasError 
        ? "border-dp-danger focus:ring-4 focus:ring-dp-danger-soft" 
        : "border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-dp-rose-soft";
    $disabledClasses = "disabled:bg-base-200 disabled:cursor-not-allowed disabled:opacity-60";
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $id }}" class="block  text-[13px] font-semibold text-base-content mb-2">
            {{ $label }}
            @if($required)
                <span class="text-error ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($type === 'textarea')
            <textarea 
                id="{{ $id }}"
                name="{{ $name }}"
                {{ $attributes->merge(['class' => "{$baseClasses} {$stateClasses} {$disabledClasses} min-h-[96px] resize-y"]) }}
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($required) required @endif
            >{{ $slot }}</textarea>
        @elseif($type === 'select')
            <select 
                id="{{ $id }}"
                name="{{ $name }}"
                {{ $attributes->merge(['class' => "{$baseClasses} {$stateClasses} {$disabledClasses} appearance-none bg-no-repeat bg-[right_1rem_center]"]) }}
                style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%237A746C' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m19.5 8.25-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E\"); background-size: 1.25rem;"
                @if($required) required @endif
            >
                {{ $slot }}
            </select>
        @elseif($type === 'search')
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                @include('layouts.partials.icons.magnifying-glass', ['class' => 'w-4 h-4 text-base-content/60'])
            </div>
            <input 
                type="text"
                id="{{ $id }}"
                name="{{ $name }}"
                {{ $attributes->merge(['class' => "{$baseClasses} {$stateClasses} {$disabledClasses} pl-10"]) }}
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($required) required @endif
            >
        @else
            <input 
                type="{{ $type }}"
                id="{{ $id }}"
                name="{{ $name }}"
                {{ $attributes->merge(['class' => "{$baseClasses} {$stateClasses} {$disabledClasses}"]) }}
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($required) required @endif
            >
        @endif
    </div>

    @if($hasError)
        <p class="mt-2  text-[12px] text-error flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            {{ $errorMessage }}
        </p>
    @elseif($hint)
        <p class="mt-2  text-[12px] text-base-content/60">{{ $hint }}</p>
    @endif
</div>
