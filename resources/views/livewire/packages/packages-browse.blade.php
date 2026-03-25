<div 
    class="bg-base-100 min-h-screen pb-24" 
    x-data="{ 
        showDetails: false, 
        selectedPackage: null,
        openDetails(pkg) {
            this.selectedPackage = pkg;
            this.showDetails = true;
        }
    }"
>
    <!-- Header Section -->
    <header class="bg-base-200 border-b border-base-content/10 py-12 lg:py-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-accent/10 rounded-full -ml-24 -mb-24 blur-3xl"></div>
        
        <div class="container mx-auto px-4 lg:px-8 relative">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                {{ __('Our culinary collections') }}
            </div>
            <h1 class="text-4xl lg:text-7xl font-bold text-base-content mb-6 leading-[1.1]">
                {{ __('Choose your') }} <span class="text-primary">{{ __('menu package') }}</span>
            </h1>
            <p class="text-base-content/60 text-[16px] lg:text-[18px] font-medium max-w-2xl leading-relaxed italic">
                {{ __('Select one or more packages for your event. Each package is crafted with Ghanaian tradition and modern elegance.') }}
            </p>
        </div>
    </header>

    <!-- Filter & Search Strip -->
    <div class="sticky top-[68px] z-40 bg-white/90 backdrop-blur-md border-b border-base-content/5 py-5 shadow-sm">
        <div class="container mx-auto px-4 lg:px-8 flex flex-col xl:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3 overflow-x-auto pb-2 xl:pb-0 scrollbar-hide w-full xl:w-auto">
                <button 
                    wire:click="$set('categoryId', null)"
                    class="inline-flex items-center px-6 py-2.5 text-[12px] font-bold uppercase tracking-wider rounded-full transition-all {{ is_null($categoryId) ? 'bg-[#121212] text-white shadow-lg' : 'bg-base-200 text-base-content/60 hover:bg-base-300' }}"
                >
                    {{ __('All Packages') }}
                </button>
                @foreach($categories as $category)
                    <button 
                        wire:click="$set('categoryId', {{ $category->id }})"
                        class="inline-flex items-center px-6 py-2.5 text-[12px] font-bold uppercase tracking-wider rounded-full transition-all {{ $categoryId === $category->id ? 'bg-[#121212] text-white shadow-lg' : 'bg-base-200 text-base-content/60 hover:bg-base-300' }}"
                    >
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <div class="relative w-full xl:w-[400px]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    wire:model.live.debounce.300ms="search"
                    type="text" 
                    placeholder="{{ __('Search for a specific dish or package...') }}" 
                    class="w-full pl-11 pr-4 py-3 bg-base-200 border-none focus:ring-2 focus:ring-primary/20 rounded-2xl transition-all text-[14px] font-bold placeholder:text-base-content/30"
                >
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="container mx-auto px-4 lg:px-8 py-12 lg:py-20">
        @if($packages->isEmpty())
            <div class="text-center py-32 bg-base-100 rounded-[40px] border-2 border-base-content/5 border-dashed flex flex-col items-center">
                <div class="w-24 h-24 bg-base-200 rounded-full flex items-center justify-center mb-8 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <h3 class="text-3xl font-bold text-base-content">{{ __('No packages found') }}</h3>
                <p class="text-base-content/50 mt-4 font-medium text-[16px] max-w-sm leading-relaxed">
                    {{ __('We couldn\'t find any packages matching those criteria. Try widening your search or clearing filters.') }}
                </p>
                <button 
                    wire:click="$set('categoryId', null); $set('search', '')" 
                    class="mt-10 px-8 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-transform"
                >
                    {{ __('Clear all filters') }}
                </button>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($packages as $package)
                    @php
                        $inCart = $cartItems->has($package->id);
                    @endphp
                    <div @click="openDetails({{ json_encode($package) }})">
                        <x-package-card 
                            :package="$package" 
                            :selected="$inCart" 
                        />
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Booking Bar (Dynamic) -->
    @if($cartCount > 0)
        <div 
            class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-2xl animate-in fade-in slide-in-from-bottom-5 duration-500"
        >
            <div class="bg-[#121212] text-white rounded-[24px] p-4 lg:p-5 flex items-center gap-5 shadow-2xl border border-white/10 backdrop-blur-md">
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-primary text-white flex items-center justify-center text-[18px] font-black shadow-lg">
                    {{ $cartCount }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-primary/80 mb-0.5">{{ __('Added to booking') }}</div>
                    <div class="text-[14px] lg:text-[16px] font-bold truncate">
                        {{ $cartItems->map(fn($i) => $i['package']->name)->implode(', ') }}
                    </div>
                </div>
                <button 
                    onclick="window.location.href='{{ route('checkout') }}'"
                    class="px-6 py-3 bg-white text-[#121212] font-black text-[13px] lg:text-[14px] uppercase tracking-wider rounded-xl hover:bg-primary hover:text-white transition-all shadow-md active:scale-95"
                >
                    {{ __('Proceed to book') }}
                </button>
            </div>
        </div>
    @endif

    <!-- Footer View All Button (Mockup) -->
    <div class="container mx-auto px-4 lg:px-8 mt-12 mb-20">
        <button class="w-full py-5 bg-base-200 hover:bg-base-300 rounded-3xl text-[14px] font-bold text-base-content/60 border border-base-content/5 transition-all text-center">
            {{ __('View full catering menu (PDF version available)') }} →
        </button>
    </div>

    <!-- Details Modal (Alpine.js) -->
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
                                <template x-for="item in (selectedPackage?.features || ['Chef Special Menu', 'Authentic Sides', 'Traditional Desserts', 'Service Staff'])">
                                    <span class="px-4 py-2 bg-base-200 text-base-content/80 text-[13px] font-bold rounded-xl border border-base-content/5" x-text="item"></span>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-base-content/5">
                            <div>
                                <div class="text-[11px] font-black uppercase tracking-[0.2em] text-primary mb-2">{{ __('Price') }}</div>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-base-content" x-text="'GH₵ ' + Number(selectedPackage?.price || 0).toLocaleString()"></span>
                                    <span class="text-[13px] font-bold text-base-content/40">{{ __('per head') }}</span>
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
                    <button @click="showDetails = false" class="flex-1 py-4 bg-white text-base-content font-black text-[13px] uppercase tracking-wider rounded-2xl border border-base-content/10 shadow-sm">{{ __('Close') }}</button>
                    <button 
                        @click="$wire.toggleSelection(selectedPackage.id); showDetails = false"
                        class="flex-[2] py-4 bg-primary text-white font-black text-[13px] uppercase tracking-wider rounded-2xl shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform active:scale-95"
                    >
                        {{ __('Add to my booking') }}
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
