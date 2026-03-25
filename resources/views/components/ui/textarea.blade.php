@props([
    'label' => null,
    'hint' => null,
    'error' => null,
    'required' => false,
    'rows' => 3,
])

<div class="form-control w-full space-y-1.5">
    @if($label)
        <label class=" text-[13px] font-medium text-base-content block">
            {{ $label }}
            @if($required)
                <span class="text-error">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <textarea {{ $attributes->merge([
            'class' => 'w-full px-[14px] py-[10px]  text-[15px] bg-base-100 border rounded-md transition-all duration-120 outline-none placeholder:text-dp-text-disabled disabled:bg-base-200 disabled:cursor-not-allowed ' . 
            ($error 
                ? 'border-dp-danger focus:ring-3 focus:ring-dp-danger-soft' 
                : 'border-base-content/10 focus:border-dp-rose focus:ring-3 focus:ring-dp-rose-soft')
        ]) }} rows="{{ $rows }}">{{ $slot }}</textarea>
    </div>

    @if($error)
        <p class=" text-[11px] text-error flex items-center gap-1">
            <span>⚠</span> {{ $error }}
        </p>
    @elseif($hint)
        <p class=" text-[11px] text-base-content/60 mt-1">{{ $hint }}</p>
    @endif
</div>
