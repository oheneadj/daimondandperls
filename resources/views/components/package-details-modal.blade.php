@props(['wire' => '$wire'])

<template x-if="true">
    <div
        x-show="showDetails"
        class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center sm:p-4 lg:p-8"
        x-cloak
    >
        {{-- Backdrop --}}
        <div
            x-show="showDetails"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="showDetails = false"
            class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        ></div>

        {{-- Panel --}}
        <div
            x-show="showDetails"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95"
            class="bg-base-100 w-full sm:max-w-lg rounded-t-[32px] sm:rounded-[32px] overflow-hidden shadow-2xl relative z-10 flex flex-col max-h-[92vh]"
        >
            {{-- Image --}}
            <div class="relative h-52 bg-base-200 flex items-center justify-center overflow-hidden shrink-0">
                <template x-if="selectedPackage?.image_path">
                    <img :src="'/storage/' + selectedPackage.image_path" :alt="selectedPackage.name + ' catering package — Diamonds & Pearls Catering Accra'" class="absolute inset-0 w-full h-full object-cover" loading="lazy" decoding="async">
                </template>
                <template x-if="!selectedPackage?.image_path">
                    <span class="text-7xl">🥘</span>
                </template>

                {{-- Close --}}
                <button @click="showDetails = false" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center backdrop-blur-md transition-all z-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                {{-- Category pill --}}
                <div class="absolute bottom-4 left-4">
                    <div class="bg-primary text-white text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg">
                        <span x-text="selectedPackage?.category?.name || 'Package'"></span>
                    </div>
                </div>

                {{-- In-cart check --}}
                <template x-if="packageInCart">
                    <div class="absolute top-4 left-4 z-20">
                        <div class="bg-success text-white size-9 rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Body --}}
            <div class="p-6 lg:p-8 flex-1 overflow-y-auto space-y-6">
                {{-- Name + price --}}
                <div class="flex items-start justify-between gap-4">
                    <h2 class="text-2xl font-black text-base-content leading-tight" x-text="selectedPackage?.name"></h2>
                    <span class="text-2xl font-black text-primary shrink-0" x-text="'GH₵' + Number(selectedPackage?.price || 0).toLocaleString()"></span>
                </div>

                {{-- Description --}}
                <p class="text-[14px] text-base-content/60 leading-relaxed" x-text="selectedPackage?.description"></p>

                {{-- Delivery info --}}
                <template x-if="selectedWindowInfo">
                    <div
                        :class="selectedWindowInfo.open ? 'bg-[#121212] text-white' : 'bg-error text-white'"
                        class="rounded-2xl px-5 py-4 flex items-center justify-between gap-4"
                    >
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest opacity-60 mb-0.5" x-text="selectedWindowInfo.open ? 'Book by' : 'Next delivery'"></p>
                            <p class="text-[14px] font-black" x-text="selectedWindowInfo.open ? selectedWindowInfo.cutoffLabel + ', ' + selectedWindowInfo.cutoffTime : selectedWindowInfo.deliveryDate"></p>
                        </div>
                        <template x-if="selectedWindowInfo.open">
                            <div
                                class="text-right"
                                x-data="{
                                    label: '',
                                    tick() {
                                        const diff = selectedWindowInfo.cutoffTs - Date.now();
                                        if (diff <= 0) { this.label = 'Closed'; return; }
                                        const h = Math.floor(diff / 3600000);
                                        const m = Math.floor((diff % 3600000) / 60000);
                                        const s = Math.floor((diff % 60000) / 1000);
                                        this.label = h > 0 ? `${h}h ${m}m left` : `${m}m ${s}s left`;
                                    }
                                }"
                                x-init="tick(); setInterval(() => tick(), 1000)"
                            >
                                <p class="text-[10px] font-bold uppercase tracking-widest opacity-60 mb-0.5">Time left</p>
                                <p class="text-[14px] font-black flex items-center gap-1.5 justify-end">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse shrink-0"></span>
                                    <span x-text="label"></span>
                                </p>
                            </div>
                        </template>
                        <template x-if="!selectedWindowInfo.open">
                            <div class="text-right">
                                <p class="text-[10px] font-bold uppercase tracking-widest opacity-60 mb-0.5">Delivery day</p>
                                <p class="text-[14px] font-black" x-text="selectedWindowInfo.deliveryLabel"></p>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- What's included --}}
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.2em] text-primary mb-3">{{ __("What's included") }}</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="item in (selectedPackage?.features || [])">
                            <span class="include-pill" x-text="item"></span>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 pb-6 pt-4 bg-base-100 border-t border-base-content/5 flex flex-col-reverse sm:flex-row gap-3 shrink-0">
                <button @click="showDetails = false" class="px-5 py-3.5 border border-base-content/15 text-base-content font-bold text-[13px] rounded-2xl hover:bg-base-200 transition-colors">
                    {{ __('Close') }}
                </button>
                <button
                    @click="{{ $wire }}.toggleSelection(selectedPackage.id); packageInCart = !packageInCart"
                    :class="packageInCart ? 'bg-[#121212] border-[#121212]' : 'bg-primary border-primary'"
                    class="flex-1 py-3.5 text-white font-black text-[13px] uppercase tracking-wider rounded-2xl border shadow-lg transition-all hover:opacity-90 active:scale-95"
                >
                    <span x-show="!packageInCart">{{ __('Add to Basket') }}</span>
                    <span x-show="packageInCart" class="flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        {{ __('Added to Basket') }}
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>
