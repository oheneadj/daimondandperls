<x-guest-layout :title="$package->name">
    <div class="container mx-auto px-4 py-16 max-w-5xl">
        <div class="mb-10">
            <a href="{{ route('home') }}" class="group flex items-center gap-2 text-primary hover:text-primary-dark transition-colors font-bold text-[13px] tracking-wide">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Catalogue') }}
            </a>
        </div>

        <x-ui.card class="p-0 overflow-hidden shadow-md border-dp-pearl-mid">
            <div class="grid md:grid-cols-2">
                <!-- Image Section -->
                <div class="relative h-[400px] md:h-auto bg-base-200-mid">
                    @if($package->image_path)
                        <img src="{{ Storage::url($package->image_path) }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/' . ($package->id % 2 == 0 ? 'catering8.jpg' : 'port-012-copyright-890x664.jpg')) }}" alt="{{ $package->name }}" class="w-full h-full object-cover opacity-90">
                    @endif
                    
                    @if($package->category)
                    <div class="absolute top-8 left-8">
                        <x-ui.badge variant="white" class="backdrop-blur-md bg-white/90 border-0 shadow-sm px-5 py-2 text-[10px] font-bold tracking-[0.2em] uppercase">
                            {{ $package->category->name }}
                        </x-ui.badge>
                    </div>
                    @endif
                </div>

                <!-- Details Section -->
                <div class="p-6 sm:p-10 md:p-14 lg:p-16 flex flex-col justify-center relative bg-base-100">
                    <div class="mb-2">
                        <span class="text-secondary font-bold uppercase tracking-[0.3em] text-[10px]">{{ __('Catering Selection') }}</span>
                        <h1 class=" text-3xl sm:text-4xl lg:text-5xl font-semibold text-base-content mb-6 tracking-tight mt-2 leading-tight">
                            {{ $package->name }}
                        </h1>
                    </div>
                    
                    <div class="flex items-center gap-4 mb-10 pb-10 border-b border-dp-pearl-mid">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] mb-1">Estimated Inflow</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-primary  text-2xl sm:text-3xl font-bold">GHS {{ number_format($package->price, 2) }}</span>
                                @if($package->serving_size)
                                    <span class="text-base-content/60 text-[13px] font-medium italic">| For {{ $package->serving_size }}</span>
                                @else
                                    <span class="text-base-content/60 text-[13px] font-medium italic">| Bespoke Serving</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class=" text-[16px] leading-[1.8] text-dp-text-body font-medium mb-12 italic">
                        {{ $package->description ?? 'Our culinary experts have meticulously crafted this selection to satisfy even the most discerning tastes. Contact us for custom adjustments.' }}
                    </div>

                    <div class="bg-secondary-soft/30 rounded-2xl p-6 sm:p-8 mb-10 border border-dp-green-border/20">
                        <h3 class=" text-[18px] font-semibold text-base-content mb-4 flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-dp-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            The Experience
                        </h3>
                        <ul class="text-base-content/60 space-y-4 text-[13px] font-medium ml-1">
                            <li class="flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-secondary-border"></span> Premium sourced ingredients & spices</li>
                            <li class="flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-secondary-border"></span> Artistic setup & thematic presentation</li>
                            <li class="flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-secondary-border"></span> Dedicated delivery to your chosen venue</li>
                        </ul>
                    </div>

                    <div class="mt-auto space-y-6">
                        <livewire:booking.add-to-cart-button :package="$package" />
                        <p class="text-center text-[10px] text-dp-text-disabled font-bold uppercase tracking-[0.2em] italic">
                            {{ __('Confirmed via secure digital settlement') }}
                        </p>
                    </div>
                </div>
            </div>
        </x-ui.card>
    </div>
</x-guest-layout>
