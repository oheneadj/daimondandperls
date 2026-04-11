@props([
    'label' => null,
    'hint' => null,
    'error' => null,
    'required' => false,
])

@php
    $isPassword = ($attributes->get('type') === 'password');
@endphp

<div class="form-control w-full space-y-1.5">
    @if($label)
        <label class="text-dp-sm font-medium text-base-content block">
            {{ $label }}
            @if($required)
                <span class="text-error">*</span>
            @endif
        </label>
    @endif

    @if($isPassword)
        <div class="relative" x-data="{ show: false }">
            <input {{ $attributes->except('type')->merge([
                'class' => 'w-full px-[14px] py-[10px] pr-10 text-[15px] bg-base-100 border rounded-lg transition-all duration-120 outline-none placeholder:text-base-content/40 disabled:bg-base-200 disabled:cursor-not-allowed ' .
                ($error
                    ? 'border-dp-danger focus:ring-3 focus:ring-dp-danger-soft'
                    : 'border-base-content/10 focus:border-dp-rose focus:ring-3 focus:ring-dp-rose-soft')
            ]) }} :type="show ? 'text' : 'password'">
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
        </div>
    @else
        <div class="relative">
            <input {{ $attributes->merge([
                'class' => 'w-full px-[14px] py-[10px] text-[15px] bg-base-100 border rounded-lg transition-all duration-120 outline-none placeholder:text-base-content/40 disabled:bg-base-200 disabled:cursor-not-allowed ' .
                ($error
                    ? 'border-dp-danger focus:ring-3 focus:ring-dp-danger-soft'
                    : 'border-base-content/10 focus:border-dp-rose focus:ring-3 focus:ring-dp-rose-soft')
            ]) }}>
        </div>
    @endif

    @if($error)
        <p class="text-xs text-error flex items-center gap-1">
            <span>⚠</span> {{ $error }}
        </p>
    @elseif($hint)
        <p class="text-xs text-base-content/60 mt-1">{{ $hint }}</p>
    @endif
</div>
