@props(['package', 'selected' => false, 'activeWindow' => null, 'windowStatus' => null])

@php
    $firstCategorySlug = $package->categories->first()?->slug ?? null;

    $bgClass = match($firstCategorySlug) {
        'rice' => 'bg-cat-rice',
        'banku' => 'bg-cat-banku',
        'grills' => 'bg-cat-grills',
        'soups' => 'bg-cat-soups',
        default => 'bg-base-200',
    };

    $emoji = match($firstCategorySlug) {
        'rice' => '🍚',
        'banku' => '🍲',
        'grills' => '🔥',
        'soups' => '🥬',
        default => '🥘',
    };

    $containerClasses = [
        'package-card bg-white border overflow-hidden transition-all duration-500 relative flex flex-col',
        'rounded-2xl',
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
    <div class="relative flex items-center justify-center overflow-hidden border border-base-content/5 aspect-square {{ $bgClass }}">
        @if($package->image_path)
            <img src="{{ Storage::url($package->image_path) }}" alt="{{ $package->name }} catering package — Diamonds &amp; Pearls Catering Accra" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" decoding="async">
        @elseif(isset($package->image_url))
            <img src="{{ $package->image_url }}" alt="{{ $package->name }} catering package — Diamonds &amp; Pearls Catering Accra" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" decoding="async">
        @else
            <span class="text-5xl translate-y-1 drop-shadow-sm">{{ $emoji }}</span>
        @endif

        @if($package->is_popular)
            <div class="absolute badge-popular text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm top-3 left-3">
                {{ __('Most Popular') }}
            </div>
        @endif

        @if($activeWindow)
            @php
                $ws = app(\App\Services\BookingWindowService::class)->getStatus($activeWindow);
            @endphp
            @if($ws['open'])
                @php
                    $diffSeconds = $ws['cutoff']->diffInSeconds(now());
                    $initH = floor($diffSeconds / 3600);
                    $initM = floor(($diffSeconds % 3600) / 60);
                    $initS = $diffSeconds % 60;
                    $initLabel = $initH > 0 ? "{$initH}h {$initM}m" : "{$initM}m {$initS}s";
                @endphp
                <div
                    class="absolute bottom-0 left-0 right-0 bg-[#121212]/90 backdrop-blur-sm text-white px-3 py-2 flex items-center justify-between gap-2"
                    x-data="{
                        deadline: {{ $ws['cutoff']->timestamp * 1000 }},
                        label: '{{ $initLabel }}',
                        tick() {
                            const secs = Math.floor((this.deadline - Date.now()) / 1000);
                            if (secs <= 0) { this.label = 'Window closed'; return; }
                            const h = Math.floor(secs / 3600);
                            const m = Math.floor((secs % 3600) / 60);
                            const s = secs % 60;
                            this.label = h > 0 ? `${h}h ${m}m` : `${m}m ${s}s`;
                        }
                    }"
                    x-init="tick(); setInterval(() => tick(), 1000)"
                >
                    <span class="text-[11px] font-bold text-white/60 uppercase tracking-widest leading-tight">Book by {{ $ws['cutoffLabel'] }}, {{ substr($ws['cutoff']->format('H:i'), 0, 5) }}</span>
                    <span class="flex items-center gap-1.5 text-[12px] font-black text-white shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse shrink-0"></span>
                        <span x-text="label"></span>
                    </span>
                </div>
            @else
                <div class="absolute bottom-0 left-0 right-0 bg-error text-white px-3 py-2 flex items-center justify-between gap-2">
                    <span class="text-[11px] font-bold text-white/70 uppercase tracking-widest leading-tight">Next delivery</span>
                    <span class="text-[12px] font-black text-white shrink-0">{{ $ws['scheduledDelivery']->format('D, M j') }}</span>
                </div>
            @endif
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

        <div class="flex items-stretch mt-auto">
            @if($isLivewireContext)
                <button
                    wire:click.stop="toggleSelection({{ $package->id }})"
                    @class([
                        'flex-1 py-3 px-4 text-[12px] sm:text-[13px] font-black uppercase tracking-widest transition-all rounded-xl border flex items-center justify-center gap-2 leading-none whitespace-nowrap',
                        'bg-base-content text-base-100 border-base-content shadow-md shadow-base-content/20 scale-[0.98]' => $selected,
                        'bg-primary text-white border-primary hover:bg-primary/90 hover:border-primary/90 hover:shadow-sm' => !$selected,
                    ])
                >
                    @if($selected)
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ __('Added') }}</span>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span>{{ __('Add to Basket') }}</span>
                    @endif
                </button>
            @else
                <a
                    href="{{ route('packages.browse') }}"
                    class="flex-1 py-3 px-4 text-[12px] sm:text-[13px] font-black uppercase tracking-widest text-center transition-all bg-primary text-white hover:bg-primary/90 rounded-xl border border-primary hover:border-primary/90 hover:shadow-sm flex items-center justify-center gap-2 shadow-sm leading-none whitespace-nowrap"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span>{{ __('Add to Basket') }}</span>
                </a>
            @endif
        </div>
    </div>
</div>
