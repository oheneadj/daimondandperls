<div>
    <!-- Floating Cart Button -->
    @if($cartCount > 0 && !$isOpen)
    <div class="fixed bottom-6 right-6 sm:bottom-10 sm:right-10 z-50 animate-fade-in group">
        <button wire:click="toggleSidebar" class="flex items-center justify-center size-16 bg-primary text-dp-white rounded-full shadow-dp-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative shadow-xl border border-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            <span class="absolute -top-1 -right-1 flex items-center justify-center size-6 bg-accent text-dp-white text-[11px] font-black rounded-full ring-4 ring-accent shadow-inner">{{ $cartCount }}</span>
            
            <div class="absolute right-full mr-4 bg-base-100 px-4 py-2 rounded-lg shadow-md border border-base-content/10 opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 transition-all pointer-events-none">
                <p class="whitespace-nowrap text-[12px] font-bold text-base-content uppercase tracking-widest">{{ __('View Selection') }}</p>
            </div>
        </button>
    </div>
    @endif

    <!-- Overlay -->
    @if($isOpen)
    <div wire:click="closeSidebar" class="fixed inset-0 bg-dp-text-primary/10 backdrop-blur-[2px] z-[60] transition-opacity duration-500"></div>
    @endif

    <!-- Sidebar / Slide-over -->
    <div @class([
        'fixed inset-y-0 right-0 z-[70] w-full max-w-[420px] bg-base-100 shadow-xl border-l border-white transform transition-transform duration-500 ease-out flex flex-col',
        'translate-x-0' => $isOpen,
        'translate-x-full' => !$isOpen,
    ])>
        
        <!-- Header -->
        <div class="px-6 py-6 sm:px-8 sm:py-8 border-b border-base-content/10 flex items-center justify-between bg-base-200/30">
            <div class="flex items-center gap-4">
                <div class="size-10 rounded-xl bg-primary-soft flex items-center justify-center text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <h2 class=" text-xl sm:text-2xl font-semibold text-base-content tracking-tight">
                    {{ __('Your Selection') }}
                </h2>
            </div>
            <button wire:click="closeSidebar" class="p-2 text-dp-text-disabled hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto px-6 py-8 sm:px-8 sm:py-10 space-y-10">
            @if($cartCount === 0)
                <div class="h-full flex flex-col items-center justify-center text-center space-y-8 py-12">
                    <div class="size-24 rounded-full bg-base-200-mid/50 flex items-center justify-center text-dp-text-disabled/30 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class=" text-xl font-semibold text-base-content">{{ __('Your cart is empty') }}</h3>
                        <p class="text-base-content/60 text-[14px] max-w-[240px] italic">{{ __('Refine your catering selection by browsing our curated packages.') }}</p>
                    </div>
                    <x-ui.button wire:click="closeSidebar" variant="primary" size="lg" class="px-8 shadow-md">
                        {{ __('Catalogue') }}
                    </x-ui.button>
                </div>
            @else
                @foreach($cartItems as $item)
                    <div class="flex gap-4 sm:gap-6 animate-fade-in group pb-4 border-b border-base-content/10">
                        <!-- Image -->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-base-200-mid overflow-hidden shrink-0 shadow-sm border border-base-content/10 transition-transform group-hover:scale-105">
                            @if($item['package']->image_path)
                                <img src="{{ Storage::url($item['package']->image_path) }}" alt="{{ $item['package']->name }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('images/' . ($item['package']->id % 2 == 0 ? 'catering8.jpg' : 'port-012-copyright-890x664.jpg')) }}" alt="{{ $item['package']->name }}" class="w-full h-full object-cover opacity-80">
                            @endif
                        </div>
                        
                        <!-- Details -->
                        <div class="flex-1 flex flex-col justify-between py-1">
                            <div class="space-y-1">
                                <div class="flex justify-between items-start gap-4">
                                    <h3 class=" text-base sm:text-[17px] font-semibold text-base-content line-clamp-1 leading-tight group-hover:text-primary transition-colors">{{ $item['package']->name }}</h3>
                                    <button wire:click="removeItem({{ $item['package']->id }})" class="text-dp-text-disabled hover:text-error transition-colors shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                                <p class="text-primary  font-semibold text-[15px]">GHS {{ number_format($item['package']->price, 2) }}</p>
                            </div>

                            <!-- Quantity controls -->
                            <div class="flex items-center justify-between gap-4 mt-auto">
                                <div class="flex items-center bg-base-200 rounded-xl border border-base-content/10 p-1 overflow-hidden">
                                    <button wire:click="decrementQuantity({{ $item['package']->id }})" class="w-8 h-8 flex items-center justify-center text-base-content/60 hover:bg-base-100 hover:text-primary rounded-lg transition-all font-bold text-lg">−</button>
                                    <div class="w-10 text-center text-[13px] font-black text-base-content">{{ $item['quantity'] }}</div>
                                    <button wire:click="incrementQuantity({{ $item['package']->id }})" class="w-8 h-8 flex items-center justify-center text-base-content/60 hover:bg-base-100 hover:text-primary rounded-lg transition-all font-bold text-lg">+</button>
                                </div>
                                <span class="text-[11px] font-bold text-dp-text-disabled uppercase tracking-widest">{{ __('Sub') }}: <span class="text-base-content">GHS {{ number_format($item['subtotal'], 2) }}</span></span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Footer -->
        @if($cartCount > 0)
        <div class="p-6 sm:p-8 bg-base-100 border-t border-base-content/10 space-y-8 shadow-xl relative">
            <div class="flex justify-between items-end">
                <div class="space-y-1">
                   <span class="text-[10px] font-bold text-dp-text-disabled uppercase tracking-[0.2em]">{{ __('Menu Total') }}</span>
                   <p class="text-[10px] text-secondary font-bold uppercase tracking-widest">{{ __('Food & Setup Included') }}</p>
                </div>
                <span class=" text-3xl sm:text-4xl font-bold text-primary tracking-tight"><span class="text-[18px] mr-1">GHS</span>{{ number_format($cartTotal, 2) }}</span>
            </div>
            <div class="space-y-4">
                <x-ui.button :href="route('checkout')" class="w-full h-16 text-[15px] shadow-md !rounded-full" variant="primary" wire:navigate>
                    {{ __('Begin Secure Checkout') }}
                </x-ui.button>
                <p class="text-center text-[10px] text-dp-text-disabled font-bold uppercase tracking-[0.2em]">
                    {{ __('Events & Logistics captured next') }}
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
