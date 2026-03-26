@props(['package', 'selected' => false])

@php
    $bgClass = match($package->category?->slug) {
        'rice' => 'bg-cat-rice',
        'banku' => 'bg-cat-banku',
        'grills' => 'bg-cat-grills',
        'soups' => 'bg-cat-soups',
        default => 'bg-base-200',
    };
    
    $emoji = match($package->category?->slug) {
        'rice' => '🍚',
        'banku' => '🍲',
        'grills' => '🔥',
        'soups' => '🥬',
        default => '🥘',
    };

    $containerClasses = [
        'package-card bg-white border overflow-hidden transition-all duration-500 relative flex flex-col',
        'rounded-[24px]',
        $selected ? 'border-primary ring-2 ring-primary/10' : 'border-base-content/10 shadow-sm hover:shadow-2xl hover:-translate-y-1',
    ];
    
    // We detect Livewire context so we can use interactive buttons for the browse page
    // and standard redirect links for the static welcome page, while keeping identical styling.
    $isLivewireContext = isset($this); 
@endphp

<div 
    @if($isLivewireContext) wire:key="package-{{ $package->id }}" @endif
    @class($containerClasses)
>
    <!-- Card Image Area -->
    <div class="relative flex items-center justify-center overflow-hidden border border-base-content/5 h-48 {{ $bgClass }}">
        @if($package->image_path)
            <img src="{{ Storage::url($package->image_path) }}" alt="{{ $package->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
        @elseif(isset($package->image_url))
            <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
        @else
            <span class="text-5xl translate-y-1 drop-shadow-sm">{{ $emoji }}</span>
        @endif

        @if($package->is_popular)
            <div class="absolute badge-popular text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm top-3 left-3">
                {{ __('Most Popular') }}
            </div>
        @endif

        <div @class([
            'absolute top-3 right-3 w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center shadow-md transition-all duration-300',
            'opacity-100 scale-100' => $selected,
            'opacity-0 scale-50' => !$selected,
        ])>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
    </div>

    <!-- Card Body -->
    <div class="flex flex-col flex-1 p-5">
        <h3 class="font-bold text-base-content leading-tight text-[16px] mb-1">
            {{ $package->name }}
        </h3>
        
        <div class="flex items-baseline gap-1.5 mb-4">
            <span class="font-black text-primary tracking-tight text-[18px]">
                GH₵{{ number_format((float) $package->price, 0) }}
            </span>
        </div>

        <div class="flex-1 mb-6">
            <p class="text-[12px] text-base-content/60 leading-relaxed line-clamp-2">
                {{ $package->description }}
            </p>
            
            @php
                $features = is_array($package->features) ? $package->features : (json_decode($package->features ?? '[]', true) ?: []);
                if (empty($features)) {
                    $features = ['Full catering service', 'Chafing dishes included', 'Professional serving staff'];
                }
            @endphp
            <ul class="space-y-3 pt-4 mt-2 border-t border-base-content/5">
                @foreach(array_slice($features, 0, 3) as $feature)
                    <li class="flex items-start gap-3">
                        <div class="size-4 bg-success/10 text-success rounded-full flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="size-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-[12px] font-medium leading-tight text-base-content/80">{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="flex gap-2">
            @if($isLivewireContext)
                <button 
                    @click.stop="openDetails({{ json_encode($package) }})"
                    class="flex-1 py-2.5 text-[12px] font-bold text-base-content/70 bg-base-200 hover:bg-base-300 rounded-xl transition-all border border-base-content/5"
                >
                    {{ __('Details') }}
                </button>
                <button 
                    wire:click.stop="toggleSelection({{ $package->id }})"
                    @class([
                        'flex-1 py-2.5 text-[12px] font-extrabold transition-all rounded-xl border',
                        'bg-primary text-white border-primary shadow-sm' => $selected,
                        'text-primary border-primary/20 hover:bg-primary/5 bg-transparent' => !$selected,
                    ])
                >
                    {{ $selected ? __('Added') : __('Add to booking') }}
                </button>
            @else
                <a 
                    href="{{ route('packages.browse') }}"
                    class="flex-1 py-2.5 text-[12px] font-bold text-center text-base-content/80 bg-base-200 hover:bg-base-300 rounded-xl transition-all border border-base-content/5 block"
                >
                    {{ __('Details') }}
                </a>
                <a 
                    href="{{ route('packages.browse') }}"
                    class="flex-1 py-2.5 text-[12px] font-extrabold text-center transition-all rounded-xl border text-primary border-primary/20 hover:bg-primary/5 bg-transparent block"
                >
                    {{ __('Add to booking') }}
                </a>
            @endif
        </div>
    </div>
</div>
