<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">{{ __('System Logs') }}</h1>
            <p class="text-[14px] text-base-content/50 mt-1">{{ __('Payment errors, SMS, activity, and notification logs') }}</p>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:border-[#D52518]/30 transition-colors"
            wire:click="$set('activeTab', 'errors')">
            <div class="w-10 h-10 rounded-xl bg-[#D52518]/10 flex items-center justify-center">
                @include('layouts.partials.icons.exclamation-triangle-solid', ['class' => 'w-5 h-5 text-[#D52518]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['errors_unresolved']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Open Errors') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:border-primary/30 transition-colors"
            wire:click="$set('activeTab', 'sms')">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                @include('layouts.partials.icons.bell', ['class' => 'w-5 h-5 text-primary'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['sms_total']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('SMS Sent') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:border-[#9ABC05]/30 transition-colors"
            wire:click="$set('activeTab', 'activity')">
            <div class="w-10 h-10 rounded-xl bg-[#9ABC05]/10 flex items-center justify-center">
                @include('layouts.partials.icons.clipboard-document-list', ['class' => 'w-5 h-5 text-[#5A7A00]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['activity_today']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Activity Today') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:border-[#F96015]/30 transition-colors"
            wire:click="$set('activeTab', 'notifications')">
            <div class="w-10 h-10 rounded-xl bg-[#F96015]/10 flex items-center justify-center">
                @include('layouts.partials.icons.information-circle-solid', ['class' => 'w-5 h-5 text-[#F96015]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['notifications_failed']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Notif. Failures') }}</p>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 bg-base-200 p-1 rounded-xl w-full sm:w-auto sm:inline-flex">
        @foreach([
            ['key' => 'errors', 'label' => 'Payment Errors'],
            ['key' => 'sms', 'label' => 'SMS Logs'],
            ['key' => 'activity', 'label' => 'Activity'],
            ['key' => 'notifications', 'label' => 'Notifications'],
        ] as $tab)
            <button wire:click="$set('activeTab', '{{ $tab['key'] }}')"
                @class([
                    'flex-1 sm:flex-none px-4 py-2 rounded-lg text-[13px] font-semibold transition-all whitespace-nowrap',
                    'bg-white text-base-content shadow-sm' => $activeTab === $tab['key'],
                    'text-base-content/50 hover:text-base-content' => $activeTab !== $tab['key'],
                ])>
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Payment Errors Tab --}}
    @if($activeTab === 'errors')
        <x-ui.table search="search">
            <x-slot name="filters">
                <div class="flex flex-wrap items-center gap-3">
                    <select wire:model.live="filterLevel"
                        class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30 outline-none transition-all font-medium">
                        <option value="">All Levels</option>
                        <option value="error">Error</option>
                        <option value="warning">Warning</option>
                        <option value="info">Info</option>
                    </select>
                    <select wire:model.live="filterSource"
                        class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30 outline-none transition-all font-medium">
                        <option value="">All Sources</option>
                        <option value="payment">Payment</option>
                        <option value="webhook">Webhook</option>
                    </select>
                    <select wire:model.live="filterResolved"
                        class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30 outline-none transition-all font-medium">
                        <option value="">All Statuses</option>
                        <option value="0">Unresolved</option>
                        <option value="1">Resolved</option>
                    </select>
                </div>
            </x-slot>

            <x-ui.table.th>Time</x-ui.table.th>
            <x-ui.table.th>Level</x-ui.table.th>
            <x-ui.table.th>Booking</x-ui.table.th>
            <x-ui.table.th>Context</x-ui.table.th>
            <x-ui.table.th>Message</x-ui.table.th>
            <x-ui.table.th>Network / Payer</x-ui.table.th>
            <x-ui.table.th align="center">Status</x-ui.table.th>
            <x-ui.table.th align="right">Actions</x-ui.table.th>

            @forelse($logs as $log)
                <x-ui.table.row wire:key="log-{{ $log->id }}">
                    <x-ui.table.td>
                        <span class="text-[13px] text-base-content font-medium whitespace-nowrap">{{ $log->created_at->format('d M Y') }}</span>
                        <span class="block text-[11px] text-base-content/40">{{ $log->created_at->format('H:i:s') }}</span>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        @php $levelClass = match($log->level) { 'error' => 'bg-[#D52518]/10 text-[#D52518]', 'warning' => 'bg-[#FFC926]/10 text-[#B08A00]', default => 'bg-base-200 text-base-content/60' }; @endphp
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $levelClass }}">{{ $log->level }}</span>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        @if($log->booking_reference)
                            <a href="{{ route('admin.bookings.show', $log->booking_reference) }}" wire:navigate
                                class="text-[13px] font-mono font-semibold text-primary hover:underline">{{ $log->booking_reference }}</a>
                        @else
                            <span class="text-base-content/30 text-[13px]">—</span>
                        @endif
                    </x-ui.table.td>
                    <x-ui.table.td>
                        <span class="text-[12px] font-mono text-base-content/50 bg-base-200 px-2 py-0.5 rounded">
                            {{ $log->source }}{{ $log->context ? '/' . $log->context : '' }}
                        </span>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        <p class="text-[13px] text-base-content line-clamp-2 max-w-xs">{{ $log->message }}</p>
                        @if($log->error_code)
                            <span class="text-[11px] font-mono text-base-content/40">{{ $log->error_code }}</span>
                        @endif
                    </x-ui.table.td>
                    <x-ui.table.td>
                        @if($log->network || $log->payer_number)
                            <span class="text-[13px] text-base-content">
                                {{ match($log->network) { '13' => 'MTN', '6' => 'Telecel', '7' => 'AirtelTigo', default => $log->network ?? '—' } }}
                            </span>
                            @if($log->payer_number)
                                <span class="block text-[11px] text-base-content/40 font-mono">{{ $log->payer_number }}</span>
                            @endif
                        @else
                            <span class="text-base-content/30 text-[13px]">—</span>
                        @endif
                    </x-ui.table.td>
                    <x-ui.table.td align="center">
                        @if($log->resolved)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#9ABC05]/10 text-[#5A7A00]">Resolved</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#D52518]/10 text-[#D52518]">Open</span>
                        @endif
                    </x-ui.table.td>
                    <x-ui.table.td align="right">
                        <button wire:click="viewLog({{ $log->id }})"
                            class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-primary hover:text-primary/80 transition-colors">
                            @include('layouts.partials.icons.eye', ['class' => 'w-4 h-4'])
                            View
                        </button>
                    </x-ui.table.td>
                </x-ui.table.row>
            @empty
                <x-ui.table.row>
                    <x-ui.table.td colspan="8">
                        <div class="py-12 text-center">
                            <div class="w-12 h-12 rounded-full bg-base-200 flex items-center justify-center mx-auto mb-3">
                                @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-6 h-6 text-base-content/30'])
                            </div>
                            <p class="text-[14px] font-semibold text-base-content/40">No errors found</p>
                            <p class="text-[12px] text-base-content/30 mt-1">All clear — no matching error logs.</p>
                        </div>
                    </x-ui.table.td>
                </x-ui.table.row>
            @endforelse

            <x-slot name="pagination">
                {{ $logs->links() }}
            </x-slot>
        </x-ui.table>
    @endif

    {{-- SMS Logs Tab --}}
    @if($activeTab === 'sms')
        <x-ui.table search="search">
            <x-ui.table.th>Time</x-ui.table.th>
            <x-ui.table.th>Recipient</x-ui.table.th>
            <x-ui.table.th>Message</x-ui.table.th>
            <x-ui.table.th>Message ID</x-ui.table.th>
            <x-ui.table.th align="center">Status</x-ui.table.th>

            @forelse($smsLogs as $log)
                <x-ui.table.row wire:key="sms-{{ $log->id }}">
                    <x-ui.table.td>
                        <span class="text-[13px] text-base-content font-medium whitespace-nowrap">{{ $log->created_at->format('d M Y') }}</span>
                        <span class="block text-[11px] text-base-content/40">{{ $log->created_at->format('H:i:s') }}</span>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        <span class="text-[13px] font-mono text-base-content">{{ $log->to }}</span>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        <p class="text-[13px] text-base-content line-clamp-2 max-w-sm">{{ $log->message }}</p>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        @if($log->message_id)
                            <span class="text-[12px] font-mono text-base-content/50">{{ $log->message_id }}</span>
                        @else
                            <span class="text-base-content/30 text-[13px]">—</span>
                        @endif
                    </x-ui.table.td>
                    <x-ui.table.td align="center">
                        @php $statusClass = $log->status === 'sent' ? 'bg-[#9ABC05]/10 text-[#5A7A00]' : 'bg-[#D52518]/10 text-[#D52518]'; @endphp
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusClass }}">
                            {{ $log->status }}
                        </span>
                    </x-ui.table.td>
                </x-ui.table.row>
            @empty
                <x-ui.table.row>
                    <x-ui.table.td colspan="5">
                        <div class="py-12 text-center">
                            <p class="text-[14px] font-semibold text-base-content/40">No SMS logs found</p>
                        </div>
                    </x-ui.table.td>
                </x-ui.table.row>
            @endforelse

            <x-slot name="pagination">
                {{ $smsLogs->links() }}
            </x-slot>
        </x-ui.table>
    @endif

    {{-- Activity Logs Tab --}}
    @if($activeTab === 'activity')
        <x-ui.table search="search">
            <x-ui.table.th>Time</x-ui.table.th>
            <x-ui.table.th>User</x-ui.table.th>
            <x-ui.table.th>Action</x-ui.table.th>
            <x-ui.table.th>Subject</x-ui.table.th>
            <x-ui.table.th>IP Address</x-ui.table.th>

            @forelse($activityLogs as $log)
                <x-ui.table.row wire:key="activity-{{ $log->id }}">
                    <x-ui.table.td>
                        <span class="text-[13px] text-base-content font-medium whitespace-nowrap">{{ $log->created_at->format('d M Y') }}</span>
                        <span class="block text-[11px] text-base-content/40">{{ $log->created_at->format('H:i:s') }}</span>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        @if($log->user)
                            <span class="text-[13px] font-medium text-base-content">{{ $log->user->name }}</span>
                            <span class="block text-[11px] text-base-content/40">{{ $log->user->email }}</span>
                        @else
                            <span class="text-base-content/30 text-[13px]">System</span>
                        @endif
                    </x-ui.table.td>
                    <x-ui.table.td>
                        <span class="text-[13px] text-base-content font-medium">{{ $log->action }}</span>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        @if($log->subject_type)
                            <span class="text-[12px] font-mono text-base-content/50 bg-base-200 px-2 py-0.5 rounded">
                                {{ class_basename($log->subject_type) }}
                                @if($log->subject_id) #{{ $log->subject_id }} @endif
                            </span>
                        @else
                            <span class="text-base-content/30 text-[13px]">—</span>
                        @endif
                    </x-ui.table.td>
                    <x-ui.table.td>
                        <span class="text-[12px] font-mono text-base-content/50">{{ $log->ip_address ?? '—' }}</span>
                    </x-ui.table.td>
                </x-ui.table.row>
            @empty
                <x-ui.table.row>
                    <x-ui.table.td colspan="5">
                        <div class="py-12 text-center">
                            <p class="text-[14px] font-semibold text-base-content/40">No activity logs found</p>
                        </div>
                    </x-ui.table.td>
                </x-ui.table.row>
            @endforelse

            <x-slot name="pagination">
                {{ $activityLogs->links() }}
            </x-slot>
        </x-ui.table>
    @endif

    {{-- Notification Logs Tab --}}
    @if($activeTab === 'notifications')
        <x-ui.table search="search">
            <x-ui.table.th>Time</x-ui.table.th>
            <x-ui.table.th>Booking</x-ui.table.th>
            <x-ui.table.th>Channel</x-ui.table.th>
            <x-ui.table.th>Recipient</x-ui.table.th>
            <x-ui.table.th>Template</x-ui.table.th>
            <x-ui.table.th align="center">Status</x-ui.table.th>
            <x-ui.table.th>Error</x-ui.table.th>

            @forelse($notificationLogs as $log)
                <x-ui.table.row wire:key="notif-{{ $log->id }}">
                    <x-ui.table.td>
                        <span class="text-[13px] text-base-content font-medium whitespace-nowrap">{{ $log->created_at->format('d M Y') }}</span>
                        <span class="block text-[11px] text-base-content/40">{{ $log->created_at->format('H:i:s') }}</span>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        @if($log->booking)
                            <a href="{{ route('admin.bookings.show', $log->booking->reference) }}" wire:navigate
                                class="text-[13px] font-mono font-semibold text-primary hover:underline">
                                {{ $log->booking->reference }}
                            </a>
                        @else
                            <span class="text-base-content/30 text-[13px]">—</span>
                        @endif
                    </x-ui.table.td>
                    <x-ui.table.td>
                        <span class="text-[12px] font-mono text-base-content/50 bg-base-200 px-2 py-0.5 rounded uppercase">
                            {{ $log->channel?->value ?? '—' }}
                        </span>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        <span class="text-[13px] font-mono text-base-content">{{ $log->recipient }}</span>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        <span class="text-[13px] text-base-content">{{ $log->template ?? '—' }}</span>
                    </x-ui.table.td>
                    <x-ui.table.td align="center">
                        @php $notifStatusClass = $log->status?->value === 'sent' ? 'bg-[#9ABC05]/10 text-[#5A7A00]' : ($log->status?->value === 'failed' ? 'bg-[#D52518]/10 text-[#D52518]' : 'bg-[#FFC926]/10 text-[#B08A00]'); @endphp
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $notifStatusClass }}">
                            {{ $log->status?->value ?? '—' }}
                        </span>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        @if($log->error_message)
                            <p class="text-[12px] text-[#D52518] line-clamp-2 max-w-xs">{{ $log->error_message }}</p>
                        @else
                            <span class="text-base-content/30 text-[13px]">—</span>
                        @endif
                    </x-ui.table.td>
                </x-ui.table.row>
            @empty
                <x-ui.table.row>
                    <x-ui.table.td colspan="7">
                        <div class="py-12 text-center">
                            <p class="text-[14px] font-semibold text-base-content/40">No notification logs found</p>
                        </div>
                    </x-ui.table.td>
                </x-ui.table.row>
            @endforelse

            <x-slot name="pagination">
                {{ $notificationLogs->links() }}
            </x-slot>
        </x-ui.table>
    @endif

    {{-- Error Detail Modal --}}
    @if($viewing)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
            x-data x-init="$nextTick(() => $el.scrollIntoView({ behavior: 'smooth' }))">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeLog"></div>
            <div class="relative bg-base-100 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto z-10">

                <div class="flex items-center justify-between p-6 border-b border-base-content/10">
                    <div>
                        <h2 class="text-[17px] font-semibold text-base-content">Error Detail</h2>
                        <p class="text-[12px] text-base-content/40 mt-0.5">ID #{{ $viewing->id }} · {{ $viewing->created_at->diffForHumans() }}</p>
                    </div>
                    <button wire:click="closeLog" class="size-8 rounded-full bg-base-200 hover:bg-base-300 flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Level</p>
                            @php $lc = match($viewing->level) { 'error' => 'bg-[#D52518]/10 text-[#D52518]', 'warning' => 'bg-[#FFC926]/10 text-[#B08A00]', default => 'bg-base-200 text-base-content/60' }; @endphp
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $lc }}">{{ $viewing->level }}</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Source / Context</p>
                            <span class="text-[12px] font-mono text-base-content bg-base-200 px-2 py-0.5 rounded">
                                {{ $viewing->source }}{{ $viewing->context ? '/' . $viewing->context : '' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Booking</p>
                            @if($viewing->booking_reference)
                                <a href="{{ route('admin.bookings.show', $viewing->booking_reference) }}" wire:navigate
                                    class="text-[13px] font-mono font-semibold text-primary hover:underline">
                                    {{ $viewing->booking_reference }}
                                </a>
                            @else
                                <span class="text-base-content/30 text-[13px]">—</span>
                            @endif
                        </div>
                        @if($viewing->error_code)
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Error Code</p>
                                <span class="text-[12px] font-mono text-base-content">{{ $viewing->error_code }}</span>
                            </div>
                        @endif
                        @if($viewing->network || $viewing->payer_number)
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Network</p>
                                <span class="text-[13px] text-base-content">
                                    {{ match($viewing->network) { '13' => 'MTN MoMo', '6' => 'Telecel Cash', '7' => 'AirtelTigo Money', default => $viewing->network ?? '—' } }}
                                </span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Payer Number</p>
                                <span class="text-[13px] font-mono text-base-content">{{ $viewing->payer_number ?? '—' }}</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-2">Message</p>
                        <p class="text-[14px] text-base-content bg-base-200/50 rounded-lg p-3">{{ $viewing->message }}</p>
                    </div>

                    @if($viewing->payload)
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-2">Raw Payload</p>
                            <pre class="text-[11px] font-mono text-base-content/70 bg-base-200/50 rounded-lg p-3 overflow-x-auto whitespace-pre-wrap break-all">{{ json_encode($viewing->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @endif

                    <div class="border-t border-base-content/10 pt-5">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-3">Resolution</p>

                        @if($viewing->resolved)
                            <div class="bg-[#9ABC05]/5 border border-[#9ABC05]/20 rounded-lg p-4 space-y-2 mb-4">
                                <div class="flex items-center gap-2">
                                    @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-4 h-4 text-[#5A7A00]'])
                                    <span class="text-[13px] font-semibold text-[#5A7A00]">Resolved</span>
                                    @if($viewing->resolvedBy)
                                        <span class="text-[12px] text-base-content/40">by {{ $viewing->resolvedBy->name }}</span>
                                    @endif
                                    @if($viewing->resolved_at)
                                        <span class="text-[12px] text-base-content/40">· {{ $viewing->resolved_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                                @if($viewing->resolution_note)
                                    <p class="text-[13px] text-base-content/70 pl-6">{{ $viewing->resolution_note }}</p>
                                @endif
                            </div>
                        @endif

                        <div class="space-y-3">
                            <textarea
                                wire:model="resolutionNote"
                                rows="3"
                                placeholder="Add a resolution note (optional)..."
                                class="w-full px-3 py-2.5 text-[13px] bg-base-100 border border-base-content/10 rounded-lg outline-none focus:border-primary focus:ring-3 focus:ring-primary/20 transition-all resize-none placeholder:text-base-content/30"
                            ></textarea>
                            <div class="flex gap-3">
                                @if(!$viewing->resolved)
                                    <button wire:click="markResolved({{ $viewing->id }})"
                                        class="flex-1 px-4 py-2.5 bg-[#9ABC05] text-white rounded-lg font-bold text-[13px] hover:brightness-105 transition-all flex items-center justify-center gap-2">
                                        @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-4 h-4'])
                                        Mark as Resolved
                                    </button>
                                @else
                                    <button wire:click="markUnresolved({{ $viewing->id }})"
                                        class="flex-1 px-4 py-2.5 bg-base-200 text-base-content rounded-lg font-bold text-[13px] hover:bg-base-300 transition-all">
                                        Reopen
                                    </button>
                                    <button wire:click="markResolved({{ $viewing->id }})"
                                        class="flex-1 px-4 py-2.5 bg-primary text-white rounded-lg font-bold text-[13px] hover:brightness-105 transition-all">
                                        Update Note
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
