<section class="max-w-2xl">
    <x-settings.layout :title="__('Appearance')" :description="__('Update the appearance settings for your account')">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6" x-data="{ appearance: @entangle('appearance') }">
            <!-- Light -->
            <button type="button" @click="appearance = 'light'; $dispatch('appearance-changed', 'light')"
                :class="appearance === 'light' ? 'border-dp-rose bg-primary-soft/30 text-primary shadow-sm' : 'border-base-content/10/40 bg-base-100 text-base-content/60 hover:border-base-content/10'"
                class="flex flex-col items-center gap-5 p-8 rounded-3xl border transition-all group relative overflow-hidden">
                <div class="p-4 rounded-2xl bg-base-200-mid group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.036 16.036l.707.707M7.757 7.757l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                </div>
                <span class=" font-bold uppercase tracking-[0.2em] text-[10px]">{{ __('Luminous') }}</span>
                <div x-show="appearance === 'light'" class="absolute top-3 right-3">
                    <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                </div>
            </button>

            <!-- Dark -->
            <button type="button" @click="appearance = 'dark'; $dispatch('appearance-changed', 'dark')"
                :class="appearance === 'dark' ? 'border-dp-rose bg-primary-soft/30 text-primary shadow-sm' : 'border-base-content/10/40 bg-base-100 text-base-content/60 hover:border-base-content/10'"
                class="flex flex-col items-center gap-5 p-8 rounded-3xl border transition-all group relative overflow-hidden">
                <div class="p-4 rounded-2xl bg-base-200-mid group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </div>
                <span class=" font-bold uppercase tracking-[0.2em] text-[10px]">{{ __('Obsidian') }}</span>
                <div x-show="appearance === 'dark'" class="absolute top-3 right-3">
                    <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                </div>
            </button>

            <!-- System -->
            <button type="button" @click="appearance = 'system'; $dispatch('appearance-changed', 'system')"
                :class="appearance === 'system' ? 'border-dp-rose bg-primary-soft/30 text-primary shadow-sm' : 'border-base-content/10/40 bg-base-100 text-base-content/60 hover:border-base-content/10'"
                class="flex flex-col items-center gap-5 p-8 rounded-3xl border transition-all group relative overflow-hidden">
                <div class="p-4 rounded-2xl bg-base-200-mid group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l2-1h2l2 1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class=" font-bold uppercase tracking-[0.2em] text-[10px]">{{ __('Synchronous') }}</span>
                <div x-show="appearance === 'system'" class="absolute top-3 right-3">
                    <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                </div>
            </button>
        </div>
    </x-settings.layout>
</section>