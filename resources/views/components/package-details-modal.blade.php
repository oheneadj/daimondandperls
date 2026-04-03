@props(['wire' => '$wire'])

<template x-if="true">
    <div 
        x-show="showDetails" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 lg:p-8"
        x-cloak
    >
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

        <div 
            x-show="showDetails"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="bg-base-100 w-full max-w-lg rounded-[32px] overflow-hidden shadow-2xl relative z-10 flex flex-col max-h-[90vh]"
        >
            <div class="relative h-56 bg-base-200 flex items-center justify-center overflow-hidden shrink-0">
                <template x-if="selectedPackage?.image_path">
                    <img :src="'/storage/' + selectedPackage.image_path" class="absolute inset-0 w-full h-full object-cover">
                </template>
                <template x-if="!selectedPackage?.image_path">
                    <span class="text-7xl">🥘</span>
                </template>
                
                <button @click="showDetails = false" class="absolute top-6 right-6 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 text-white flex items-center justify-center backdrop-blur-md transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                
                <div class="absolute bottom-6 left-6">
                    <div class="bg-primary text-white text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg">
                        <span x-text="selectedPackage?.category?.name || 'Package'"></span>
                    </div>
                </div>
            </div>

            <div class="p-8 lg:p-10 flex-1 overflow-y-auto">
                <h2 class="text-3xl font-black text-base-content mb-3" x-text="selectedPackage?.name"></h2>
                <p class="text-[15px] text-base-content/60 leading-relaxed italic mb-8" x-text="selectedPackage?.description"></p>

                <div class="space-y-8">
                    <div>
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-primary mb-4">{{ __('What\'s Included') }}</div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="item in (selectedPackage?.features || [])">
                                <span class="include-pill" x-text="item"></span>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 pt-8 border-t border-base-content/5">
                        <div>
                            <div class="text-[11px] font-black uppercase tracking-[0.2em] text-primary mb-2">{{ __('Price') }}</div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-black text-base-content" x-text="'GH₵ ' + Number(selectedPackage?.price || 0).toLocaleString()"></span>
                            </div>
                        </div>
                        <div>
                            <div class="text-[11px] font-black uppercase tracking-[0.2em] text-primary mb-2">{{ __('Min Capacity') }}</div>
                            <div class="flex items-center gap-2">
                                <span class="text-2xl font-black text-base-content" x-text="(selectedPackage?.min_guests || 50) + ' Guests'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8 bg-base-200/50 flex gap-4 shrink-0">
                <button @click="showDetails = false" class="flex-1 py-4 bg-black text-white font-black text-[13px] uppercase tracking-wider rounded-full border border-base-content/10 shadow-sm">{{ __('Close') }}</button>
                <button 
                    @click="{{ $wire }}.toggleSelection(selectedPackage.id); showDetails = false"
                    class="flex-[2] py-4 bg-primary text-white font-black uppercase tracking-wider rounded-full shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform active:scale-95"
                >
                    {{ __('Add to Basket') }}
                </button>
            </div>
        </div>
    </div>
</template>
