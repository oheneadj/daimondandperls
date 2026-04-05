@props([
    'wireModel' => 'otp',
    'wireSubmit' => 'verifyOtp',
    'wireResend' => 'resendOtp',
    'label' => '6-Digit Code',
    'compact' => false,
])

@php
    $boxClass = $compact
        ? 'w-9 sm:w-12 h-11 sm:h-13 text-sm sm:text-lg rounded-lg bg-base-100 border border-base-content/10'
        : 'w-10 sm:w-13 h-12 sm:h-14 text-base sm:text-xl rounded-lg bg-base-100 border border-base-content/10';
    $sharedClass = $boxClass . ' text-center font-bold focus:border-primary focus:ring-3 focus:ring-primary/20 transition-all duration-120 outline-none px-0';
    $gapClass = $compact ? 'gap-1 sm:gap-2' : 'gap-1.5 sm:gap-3';
@endphp

<div class="space-y-1.5">
    <label class="text-dp-sm font-medium text-base-content block">{{ $label }}</label>
    <div x-data="{
        sync() {
            const code = Array.from({length: 6}, (_, i) => this.$refs['d' + i].value).join('');
            $wire.set('{{ $wireModel }}', code);
            if (code.length === 6) {
                $nextTick(() => $wire.call('{{ $wireSubmit }}'));
            }
        },
        type(i, key) {
            this.$refs['d' + i].value = key;
            if (i < 5) this.$refs['d' + (i + 1)].focus();
            this.sync();
        },
        back(i) {
            if (this.$refs['d' + i].value) {
                this.$refs['d' + i].value = '';
            } else if (i > 0) {
                this.$refs['d' + (i - 1)].value = '';
                this.$refs['d' + (i - 1)].focus();
            }
            this.sync();
        },
        onkey(i, e) {
            if (/^\d$/.test(e.key)) { e.preventDefault(); this.type(i, e.key); return; }
            if (e.key === 'Backspace') { e.preventDefault(); this.back(i); return; }
            if (e.key === 'ArrowLeft' && i > 0) { e.preventDefault(); this.$refs['d' + (i - 1)].focus(); return; }
            if (e.key === 'ArrowRight' && i < 5) { e.preventDefault(); this.$refs['d' + (i + 1)].focus(); return; }
            if (e.key !== 'Tab') e.preventDefault();
        },
        onpaste(e) {
            e.preventDefault();
            const chars = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            for (let i = 0; i < 6; i++) {
                this.$refs['d' + i].value = chars[i] || '';
            }
            this.$refs['d' + Math.min(chars.length, 5)].focus();
            this.sync();
        }
    }" class="flex {{ $gapClass }} justify-center">
        <input x-ref="d0" type="text" inputmode="numeric" autocomplete="one-time-code" autofocus
            x-on:keydown="onkey(0, $event)" x-on:paste="onpaste($event)" x-on:focus="$event.target.select()"
            class="{{ $sharedClass }}">
        <input x-ref="d1" type="text" inputmode="numeric"
            x-on:keydown="onkey(1, $event)" x-on:paste="onpaste($event)" x-on:focus="$event.target.select()"
            class="{{ $sharedClass }}">
        <input x-ref="d2" type="text" inputmode="numeric"
            x-on:keydown="onkey(2, $event)" x-on:paste="onpaste($event)" x-on:focus="$event.target.select()"
            class="{{ $sharedClass }}">
        <input x-ref="d3" type="text" inputmode="numeric"
            x-on:keydown="onkey(3, $event)" x-on:paste="onpaste($event)" x-on:focus="$event.target.select()"
            class="{{ $sharedClass }}">
        <input x-ref="d4" type="text" inputmode="numeric"
            x-on:keydown="onkey(4, $event)" x-on:paste="onpaste($event)" x-on:focus="$event.target.select()"
            class="{{ $sharedClass }}">
        <input x-ref="d5" type="text" inputmode="numeric"
            x-on:keydown="onkey(5, $event)" x-on:paste="onpaste($event)" x-on:focus="$event.target.select()"
            class="{{ $sharedClass }}">
    </div>
    @error($wireModel) <p class="text-xs text-error flex items-center gap-1"><span>⚠</span> {{ $message }}</p> @enderror
</div>
