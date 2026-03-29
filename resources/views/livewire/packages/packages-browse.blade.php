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
            <div class="flex items-center gap-2 overflow-x-auto pb-2 xl:pb-0 scrollbar-hide w-full xl:w-auto">
                <button 
                    wire:click="$set('categoryId', null)"
                    @class([
                        'inline-flex items-center px-5 py-2 text-[12px] font-bold rounded-full transition-all border',
                        'bg-[#121212] text-white border-[#121212] shadow-md' => is_null($categoryId),
                        'bg-transparent text-base-content/50 border-base-content/10 hover:bg-base-200' => !is_null($categoryId),
                    ])
                >
                    {{ __('All Packages') }}
                </button>
                @foreach($categories as $category)
                    <button 
                        wire:click="$set('categoryId', {{ $category->id }})"
                        @class([
                            'inline-flex items-center px-5 py-2 text-[12px] font-bold rounded-full transition-all border whitespace-nowrap',
                            'bg-[#121212] text-white border-[#121212] shadow-md' => $categoryId === $category->id,
                            'bg-transparent text-base-content/50 border-base-content/10 hover:bg-base-200' => $categoryId !== $category->id,
                        ])
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
            class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-xl animate-in fade-in slide-in-from-bottom-5 border border-accent rounded-3xl duration-500">
            <div class="booking-bar-status">
                <div class="booking-count-badge">
                    {{ $cartCount }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-white/40 mb-0.5">{{ __('Added to booking') }}</div>
                    <div class="text-[14px] font-bold truncate text-white">
                        {{ $cartItems->map(fn($i) => $i['package']->name)->implode(', ') }}
                    </div>
                </div>
                <button 
                    onclick="window.location.href='{{ route('checkout') }}'"
                    class="px-5 py-2.5 bg-white text-[#121212] font-black text-[12px] uppercase tracking-wider rounded-xl hover:bg-primary hover:text-white transition-all shadow-md active:scale-95"
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
    <!-- Package Details Modal Component -->
    <x-package-details-modal />
</div>
