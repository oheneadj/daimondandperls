<div>
    <!-- Overlay Backdrop -->
    <div x-show="$wire.isOpen" 
         x-transition.opacity 
         @click="$wire.isOpen = false"
         class="fixed inset-0 z-[60] bg-dp-text-primary/10 backdrop-blur-sm"
         style="display: none;"></div>

    <!-- Slideover Panel -->
    <div x-show="$wire.isOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 z-[70] w-full max-w-sm bg-base-200 border-l border-base-content/10 shadow-xl flex flex-col"
         style="display: none;">
        
        <!-- Header -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-base-content/10 bg-base-100/50 backdrop-blur-md sticky top-0 z-10">
            <h2 class=" text-[22px] font-semibold text-base-content">{{ __('Notifications') }}</h2>
            <div class="flex items-center gap-2">
                @if(Auth::user()->unreadNotifications->isNotEmpty())
                    <button wire:click="markAllAsRead" class="text-[11px] font-bold uppercase tracking-[0.2em] text-primary hover:text-primary-hover transition-colors">
                        {{ __('Clear All') }}
                    </button>
                @endif
                <button @click="$wire.isOpen = false" class="p-2 rounded-full hover:bg-base-200-mid transition-colors text-base-content/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-5 space-y-4">
            @forelse($notifications as $notification)
                <div class="group relative rounded-xl border border-base-content/10 bg-base-100 overflow-hidden transition-all duration-300 hover:shadow-md {{ is_null($notification->read_at) ? 'ring-1 ring-dp-rose-border/30 bg-primary-soft/20' : '' }}">
                    @if(is_null($notification->read_at))
                        <div class="absolute top-0 left-0 bottom-0 w-1 bg-primary"></div>
                    @endif

                    <div class="p-4 pl-5">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-start justify-between gap-2">
                                <span class="text-[13px] font-semibold text-base-content leading-tight">
                                    {{ $notification->data['reference'] ?? __('Transactional Update') }}
                                </span>
                                <span class="text-[10px] font-bold text-base-content/60 uppercase tracking-[0.1em] shrink-0">
                                    {{ $notification->created_at->diffForHumans(short: true) }}
                                </span>
                            </div>
                            
                            <p class="text-[13px] text-base-content/60 leading-relaxed line-clamp-2">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-dp-pearl-mid">
                                <a href="{{ $notification->data['action_url'] ?? '#' }}" 
                                   wire:click="markAsRead('{{ $notification->id }}')"
                                   class="text-[11px] font-bold text-primary uppercase tracking-[0.1em] flex items-center gap-1 hover:translate-x-1 transition-transform">
                                    {{ __('View Details') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                                
                                @if(is_null($notification->read_at))
                                    <button wire:click="markAsRead('{{ $notification->id }}')" 
                                            class="text-[10px] font-bold text-base-content/60 uppercase tracking-[0.1em] hover:text-secondary transition-colors">
                                        {{ __('Acknowledge') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center space-y-4">
                    <div class="size-16 rounded-full bg-base-200-mid flex items-center justify-center text-dp-text-disabled/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 8-8-8" />
                        </svg>
                    </div>
                    <div>
                        <p class=" text-[18px] text-base-content font-semibold">{{ __('Serene silence') }}</p>
                        <p class="text-[13px] text-base-content/60 font-medium mt-1">{{ __('No pending notifications at this time.') }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if(count($notifications) >= 20)
            <div class="p-4 border-t border-base-content/10 bg-base-100/50 text-center">
                <a href="#" class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 hover:text-primary transition-all">
                    {{ __('View All Activity') }}
                </a>
            </div>
        @endif
    </div>
</div>
