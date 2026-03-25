@props(['package', 'selected' => false])

@php
    $categoryColors = [
        'rice' => 'bg-[#EAF3DE]',
        'banku' => 'bg-[#FAEEDA]',
        'grills' => 'bg-[#FAECE7]',
        'soups' => 'bg-[#E1F5EE]',
    ];
    $bgColor = $categoryColors[$package->category?->slug] ?? 'bg-base-200-mid';
    
    // Fallback emoji if no image
    $emoji = match($package->category?->slug) {
        'rice' => '🍚',
        'banku' => '🍲',
        'grills' => '🔥',
        'soups' => '🥬',
        default => '🥘',
    };
@endphp

<div 
    wire:key="package-{{ $package->id }}"
    class="card bg-base-100 border transition-all duration-300 {{ $selected ? 'border-primary ring-2 ring-primary/20' : 'border-base-content/10 hover:border-primary/50' }} rounded-[20px] overflow-hidden group cursor-pointer"
>
    <!-- Card Image/Icon Area -->
    <div class="relative h-40 {{ $bgColor }} flex items-center justify-center overflow-hidden">
        @if($package->image_path)
            <img src="{{ Storage::url($package->image_path) }}" alt="{{ $package->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 bg-black/5"></div>
        @else
            <span class="text-5xl transition-transform duration-500 group-hover:scale-110">{{ $emoji }}</span>
        @endif

        @if($package->is_popular)
            <div class="absolute top-3 left-3 bg-[#EAF3DE] text-[#27500A] text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm">
                {{ __('Most Popular') }}
            </div>
        @endif

        @if($selected)
            <div class="absolute top-3 right-3 w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center shadow-md animate-in zoom-in duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>

    <!-- Card Body -->
    <div class="p-5 flex flex-col flex-1">
        <h3 class="text-[17px] font-bold text-base-content mb-1.5 group-hover:text-primary transition-colors">
            {{ $package->name }}
        </h3>
        
        <p class="text-[13px] text-base-content/60 leading-relaxed line-clamp-2 mb-4 italic font-medium">
            {{ $package->description ?? __('A delicious selection of culinary delights prepared with love.') }}
        </p>

        <div class="flex items-center justify-between mt-auto pt-4 border-t border-base-content/5">
            <div>
                <div class="text-[18px] font-bold text-primary">GH₵{{ number_format($package->price, 0) }}</div>
                <div class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">{{ __('per head') }}</div>
            </div>
            
            <div class="flex items-center gap-1.5 text-[11px] font-bold text-base-content/60 bg-base-200 px-2 py-1 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Min {{ $package->min_guests ?? 50 }}
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-5">
            <button 
                @click.stop="openDetails({{ json_encode($package) }})"
                class="py-2.5 text-[12px] font-bold text-base-content/60 bg-base-200 hover:bg-base-300 rounded-xl transition-all border border-transparent"
            >
                {{ __('Details') }}
            </button>
            <button 
                wire:click.stop="toggleSelection({{ $package->id }})"
                class="py-2.5 text-[12px] font-bold transition-all rounded-xl {{ $selected ? 'bg-primary text-white shadow-md' : 'text-primary border border-primary/20 hover:bg-primary/5 bg-transparent' }}"
            >
                {{ $selected ? __('Added') : __('Add') }}
            </button>
        </div>
    </div>
</div>
