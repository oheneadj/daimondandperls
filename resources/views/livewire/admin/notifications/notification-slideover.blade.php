<div>
    {{-- Backdrop --}}
    <div
        x-show="$wire.isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$wire.isOpen = false"
        class="fixed inset-0 z-[60] bg-black/30 backdrop-blur-sm"
        style="display: none;"
    ></div>

    {{-- Slideover Panel --}}
    <div
        x-show="$wire.isOpen"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 z-[70] w-full max-w-[400px] bg-white border-l border-base-content/5 shadow-2xl flex flex-col"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="flex-shrink-0 flex items-center justify-between px-5 py-4 border-b border-base-content/5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-[15px] font-bold text-base-content leading-tight">{{ __('Notifications') }}</h2>
                    @if($unreadCount > 0)
                        <p class="text-[11px] text-base-content/40 font-medium leading-tight">{{ $unreadCount }} {{ __('unread') }}</p>
                    @else
                        <p class="text-[11px] text-base-content/40 font-medium leading-tight">{{ __('All caught up') }}</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-1">
                @if($unreadCount > 0)
                    <button
                        wire:click="markAllAsRead"
                        class="text-[11px] font-bold text-primary hover:text-primary/70 px-2 py-1 rounded-md hover:bg-primary/5 transition-all uppercase tracking-wider"
                    >
                        {{ __('Mark all read') }}
                    </button>
                @endif
                <button
                    @click="$wire.isOpen = false"
                    class="p-1.5 rounded-lg text-base-content/40 hover:text-base-content hover:bg-base-200 transition-all"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Notification List --}}
        <div class="flex-1 overflow-y-auto divide-y divide-base-content/5">
            @forelse($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $type = $notification->data['type'] ?? 'default';
                    $iconConfig = match($type) {
                        'booking_received'  => ['bg' => 'bg-[#F96015]/10', 'text' => 'text-[#F96015]', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        'booking_confirmed' => ['bg' => 'bg-[#9ABC05]/10', 'text' => 'text-[#9ABC05]', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'booking_completed' => ['bg' => 'bg-[#18542A]/10', 'text' => 'text-[#18542A]', 'icon' => 'M5 13l4 4L19 7'],
                        'quote_ready'       => ['bg' => 'bg-[#FFC926]/10', 'text' => 'text-[#b8910a]', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        default             => ['bg' => 'bg-primary/10',   'text' => 'text-primary',   'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    };
                @endphp

                <div
                    wire:key="notif-{{ $notification->id }}"
                    class="relative flex gap-3.5 px-5 py-4 transition-colors {{ $isUnread ? 'bg-primary/[0.03]' : 'bg-white hover:bg-base-200/40' }}"
                >
                    {{-- Unread left bar --}}
                    @if($isUnread)
                        <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-primary rounded-r-full"></div>
                    @endif

                    {{-- Icon --}}
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="w-9 h-9 rounded-xl {{ $iconConfig['bg'] }} flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ $iconConfig['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconConfig['icon'] }}" />
                            </svg>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] {{ $isUnread ? 'font-semibold text-base-content' : 'font-medium text-base-content/70' }} leading-snug">
                            {{ $notification->data['message'] ?? __('Notification') }}
                        </p>

                        @if(!empty($notification->data['reference']))
                            <p class="text-[11px] font-bold text-base-content/40 mt-0.5 uppercase tracking-wider">
                                {{ $notification->data['reference'] }}
                                @if(!empty($notification->data['amount']))
                                    · GH₵{{ number_format((float) $notification->data['amount'], 2) }}
                                @endif
                            </p>
                        @endif

                        <div class="flex items-center justify-between mt-2.5 gap-2">
                            <span class="text-[11px] text-base-content/30 font-medium">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>

                            <div class="flex items-center gap-2">
                                @if(!empty($notification->data['action_url']))
                                    <a
                                        href="{{ $notification->data['action_url'] }}"
                                        wire:click="markAsRead('{{ $notification->id }}')"
                                        class="text-[11px] font-bold text-primary hover:text-primary/70 transition-colors flex items-center gap-1"
                                    >
                                        {{ __('View') }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @endif

                                @if($isUnread)
                                    <button
                                        wire:click="markAsRead('{{ $notification->id }}')"
                                        class="p-1 rounded-md text-base-content/30 hover:text-[#9ABC05] hover:bg-[#9ABC05]/10 transition-all"
                                        title="{{ __('Mark as read') }}"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-24 px-8 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-base-200 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <p class="text-[15px] font-bold text-base-content">{{ __("You're all caught up") }}</p>
                    <p class="text-[13px] text-base-content/40 mt-1">{{ __('No notifications at this time.') }}</p>
                </div>
            @endforelse
        </div>

        {{-- Load more footer --}}
        @if($notifications->count() >= 20)
            <div class="flex-shrink-0 border-t border-base-content/5 px-5 py-3 bg-base-200/30">
                <button
                    wire:click="loadMore"
                    class="w-full text-[12px] font-bold text-base-content/50 hover:text-primary uppercase tracking-wider transition-colors py-1"
                >
                    {{ __('Load more') }}
                </button>
            </div>
        @endif
    </div>
</div>
