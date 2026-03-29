@props([
    'wireResend' => 'resendOtp',
    'seconds' => 60,
])

<div x-data="{
    remaining: {{ $seconds }},
    running: true,
    interval: null,
    init() {
        this.startTimer();
    },
    startTimer() {
        this.remaining = {{ $seconds }};
        this.running = true;
        clearInterval(this.interval);
        this.interval = setInterval(() => {
            this.remaining--;
            if (this.remaining <= 0) {
                this.running = false;
                clearInterval(this.interval);
            }
        }, 1000);
    },
    async resend() {
        await $wire.call('{{ $wireResend }}');
        this.startTimer();
    },
    destroy() {
        clearInterval(this.interval);
    }
}" class="text-center">
    <template x-if="running">
        <p class="text-[12px] text-base-content/40 font-medium">
            Resend code in <span class="font-black text-base-content/60" x-text="remaining + 's'"></span>
        </p>
    </template>
    <template x-if="!running">
        <button type="button" @click="resend()" class="text-[12px] font-bold text-primary hover:text-primary/80 transition-colors">
            Resend verification code
        </button>
    </template>
</div>
