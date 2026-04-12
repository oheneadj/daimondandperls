@props([
    'wireModel' => 'otp',
    'wireSubmit' => 'verifyOtp',
    'wireResend' => 'resendOtp',
    'label' => '6-Digit Code',
    'compact' => false,
])

<div class="space-y-1.5"
    x-data="{
        handleInput(e) {
            const val = e.target.value.replace(/\D/g, '').slice(0, 6);
            e.target.value = val;
            $wire.set('{{ $wireModel }}', val);
            if (val.length === 6) {
                $nextTick(() => $wire.call('{{ $wireSubmit }}'));
            }
        }
    }"
>
    <label class="text-dp-sm font-medium text-base-content block">{{ $label }}</label>
    <input
        type="text"
        inputmode="numeric"
        pattern="[0-9]*"
        maxlength="6"
        autocomplete="one-time-code"
        autofocus
        wire:model="{{ $wireModel }}"
        x-on:input="handleInput($event)"
        placeholder="000000"
        class="w-full px-[14px] {{ $compact ? 'py-[10px] text-xl' : 'py-[14px] text-2xl' }} text-center font-bold tracking-[0.4em] bg-base-100 border border-base-content/10 rounded-lg transition-all duration-120 outline-none placeholder:text-base-content/20 focus:border-primary focus:ring-3 focus:ring-primary/20 @error('{{ $wireModel }}') border-error focus:ring-error/20 @enderror"
    />
    @error($wireModel)
        <p class="text-xs text-error flex items-center gap-1"><span>⚠</span> {{ $message }}</p>
    @enderror
</div>
