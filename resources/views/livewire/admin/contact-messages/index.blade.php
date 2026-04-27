<div class="space-y-6">

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-[#9ABC05]/10 border border-[#9ABC05]/20 text-[#3a5c00] rounded-xl px-4 py-3 text-[13px] font-medium flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#9ABC05]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">{{ __('Contact Messages') }}</h1>
            <p class="text-[14px] text-base-content/50 mt-1">{{ __('Enquiries submitted through the website contact form') }}</p>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                @include('layouts.partials.icons.bell', ['class' => 'w-5 h-5 text-primary'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['total']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Total') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#D52518]/10 flex items-center justify-center">
                @include('layouts.partials.icons.exclamation-triangle-solid', ['class' => 'w-5 h-5 text-[#D52518]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['new']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('New') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#9ABC05]/10 flex items-center justify-center">
                @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-5 h-5 text-[#9ABC05]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['responded']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Responded') }}</p>
            </div>
        </div>
        <button wire:click="filterToday"
            @class([
                'text-left rounded-xl p-4 flex items-center gap-4 border transition-colors',
                'bg-[#F96015] border-[#F96015]' => $startDate === now()->toDateString() && $endDate === now()->toDateString(),
                'bg-white border-base-content/5 hover:border-[#F96015]/30' => !($startDate === now()->toDateString() && $endDate === now()->toDateString()),
            ])>
            <div @class([
                'w-10 h-10 rounded-xl flex items-center justify-center',
                'bg-white/20' => $startDate === now()->toDateString() && $endDate === now()->toDateString(),
                'bg-[#F96015]/10' => !($startDate === now()->toDateString() && $endDate === now()->toDateString()),
            ])>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $startDate === now()->toDateString() && $endDate === now()->toDateString() ? 'text-white' : 'text-[#F96015]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-[20px] font-bold {{ $startDate === now()->toDateString() && $endDate === now()->toDateString() ? 'text-white' : 'text-base-content' }}">{{ number_format($stats['today']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest {{ $startDate === now()->toDateString() && $endDate === now()->toDateString() ? 'text-white/70' : 'text-base-content/40' }}">{{ __('Today') }}</p>
            </div>
        </button>
    </div>

    {{-- Table --}}
    <x-ui.table search="search">
        <x-slot name="filters">
            <div class="flex flex-wrap items-center gap-3">
                {{-- Quick date filters --}}
                <div class="flex items-center gap-2">
                    <button wire:click="filterToday" @class([
                        'px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wide transition-colors',
                        'bg-[#F96015] text-white' => $startDate === now()->toDateString() && $endDate === now()->toDateString(),
                        'bg-base-200 text-base-content/60 hover:bg-base-300' => !($startDate === now()->toDateString() && $endDate === now()->toDateString()),
                    ])>Today</button>
                    <button wire:click="filterThisWeek" @class([
                        'px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wide transition-colors',
                        'bg-[#A31C4E] text-white' => $startDate === now()->startOfWeek()->toDateString() && $endDate === now()->endOfWeek()->toDateString(),
                        'bg-base-200 text-base-content/60 hover:bg-base-300' => !($startDate === now()->startOfWeek()->toDateString() && $endDate === now()->endOfWeek()->toDateString()),
                    ])>This Week</button>
                    <button wire:click="filterThisMonth" @class([
                        'px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wide transition-colors',
                        'bg-primary text-white' => $startDate === now()->startOfMonth()->toDateString() && $endDate === now()->endOfMonth()->toDateString(),
                        'bg-base-200 text-base-content/60 hover:bg-base-300' => !($startDate === now()->startOfMonth()->toDateString() && $endDate === now()->endOfMonth()->toDateString()),
                    ])>This Month</button>
                    @if($startDate || $endDate)
                        <button wire:click="clearDateFilter" class="text-[11px] text-base-content/40 hover:text-error font-bold transition-colors">✕ Clear</button>
                    @endif
                </div>

                <input type="date" wire:model.live="startDate"
                    class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 outline-none font-medium focus:ring-2 focus:ring-primary/30">
                <input type="date" wire:model.live="endDate"
                    class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 outline-none font-medium focus:ring-2 focus:ring-primary/30">

                <select wire:model.live="filterStatus" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30 outline-none font-medium">
                    <option value="">All Statuses</option>
                    <option value="new">New</option>
                    <option value="read">Read</option>
                    <option value="responded">Responded</option>
                </select>
            </div>
        </x-slot>

        <x-slot name="header">
            <th class="px-4 py-3 font-semibold text-left">Name</th>
            <th class="px-4 py-3 font-semibold text-left">Inquiry</th>
            <th class="px-4 py-3 font-semibold text-left">Contact</th>
            <th class="px-4 py-3 font-semibold text-left">Status</th>
            <th class="px-4 py-3 font-semibold text-left">Received</th>
            <th class="px-4 py-3 font-semibold text-right">Actions</th>
        </x-slot>

        @forelse($messages as $msg)
            <tr wire:key="cmsg-{{ $msg->id }}" class="hover:bg-base-200/40 transition-colors {{ $msg->status === 'new' ? 'font-semibold' : '' }}">
                <td class="px-4 py-3">
                    <p class="text-[13px] text-base-content">{{ $msg->name }}</p>
                </td>
                <td class="px-4 py-3">
                    <span class="text-[12px] px-2 py-0.5 rounded-full bg-base-200 text-base-content/70 font-medium">{{ $msg->inquiry_type }}</span>
                </td>
                <td class="px-4 py-3">
                    <div class="space-y-0.5">
                        @if($msg->email)
                            <p class="text-[12px] text-base-content/70">{{ $msg->email }}</p>
                        @endif
                        @if($msg->phone)
                            <p class="text-[12px] text-base-content/50 font-mono">{{ $msg->phone }}</p>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3">
                    @php
                        $badge = match($msg->status) {
                            'new'       => 'bg-[#D52518]/10 text-[#D52518]',
                            'read'      => 'bg-[#FFC926]/10 text-[#B08A00]',
                            'responded' => 'bg-[#9ABC05]/10 text-[#3a5c00]',
                            default     => 'bg-base-200 text-base-content/60',
                        };
                    @endphp
                    <span class="text-[11px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $badge }}">
                        {{ $msg->status }}
                    </span>
                </td>
                <td class="px-4 py-3 text-[12px] text-base-content/50">
                    <span title="{{ $msg->created_at->format('d M Y, H:i') }}">{{ $msg->created_at->diffForHumans() }}</span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button wire:click="openMessage({{ $msg->id }})"
                            class="px-3 py-1.5 text-[11px] font-bold rounded-lg bg-base-200 hover:bg-base-300 text-base-content/70 transition-colors">
                            View
                        </button>
                        <button wire:click="confirmDelete({{ $msg->id }})"
                            class="px-3 py-1.5 text-[11px] font-bold rounded-lg bg-[#D52518]/10 hover:bg-[#D52518]/20 text-[#D52518] transition-colors">
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-[13px] text-base-content/40">
                    No contact messages found.
                </td>
            </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $messages->links() }}
        </x-slot>
    </x-ui.table>

    {{-- Detail Modal --}}
    @if($viewing)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeMessage"></div>
            <div class="relative bg-base-100 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto z-10">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-6 border-b border-base-content/10">
                    <div>
                        <h2 class="text-[17px] font-semibold text-base-content">{{ $viewing->name }}</h2>
                        <p class="text-[12px] text-base-content/40 mt-0.5">
                            {{ $viewing->inquiry_type }} · {{ $viewing->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <button wire:click="closeMessage" class="size-8 rounded-full bg-base-200 hover:bg-base-300 flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Sender info --}}
                    <div class="grid grid-cols-2 gap-4">
                        @if($viewing->email)
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Email</p>
                                <p class="text-[13px] text-base-content font-mono">{{ $viewing->email }}</p>
                            </div>
                        @endif
                        @if($viewing->phone)
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Phone</p>
                                <p class="text-[13px] text-base-content font-mono">{{ $viewing->phone }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Status</p>
                            @php
                                $badge = match($viewing->status) {
                                    'new'       => 'bg-[#D52518]/10 text-[#D52518]',
                                    'read'      => 'bg-[#FFC926]/10 text-[#B08A00]',
                                    'responded' => 'bg-[#9ABC05]/10 text-[#3a5c00]',
                                    default     => 'bg-base-200 text-base-content/60',
                                };
                            @endphp
                            <span class="text-[11px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $badge }}">
                                {{ $viewing->status }}
                            </span>
                        </div>
                        @if($viewing->responded_at)
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Responded At</p>
                                <p class="text-[13px] text-base-content">{{ $viewing->responded_at->format('d M Y, H:i') }}</p>
                                @if($viewing->respondedBy)
                                    <p class="text-[11px] text-base-content/40">by {{ $viewing->respondedBy->name }}</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Message --}}
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-2">Message</p>
                        <div class="bg-base-200 rounded-xl px-4 py-3 text-[13px] text-base-content leading-relaxed whitespace-pre-wrap">{{ $viewing->message }}</div>
                    </div>

                    @if($viewing->response_notes)
                        <div class="bg-[#9ABC05]/5 border border-[#9ABC05]/20 rounded-xl px-4 py-3">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-[#3a5c00] mb-1">Response Notes</p>
                            <p class="text-[13px] text-base-content leading-relaxed whitespace-pre-wrap">{{ $viewing->response_notes }}</p>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex flex-wrap items-start gap-3 pt-2 border-t border-base-content/10">
                        @if($viewing->email)
                            <a href="mailto:{{ $viewing->email }}?subject=Re: {{ urlencode($viewing->inquiry_type) }} enquiry&body={{ urlencode('Hello ' . $viewing->name . ',' . "\n\n") }}"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-[13px] font-semibold hover:bg-primary/90 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Reply via Email
                            </a>
                        @endif

                        @if($viewing->status !== 'responded')
                            <div class="flex-1 min-w-[200px] space-y-2">
                                <textarea wire:model="responseNotes"
                                    rows="2"
                                    placeholder="Optional internal note (e.g. Called back, issue resolved)"
                                    class="w-full px-3 py-2 text-[13px] bg-base-200 border border-base-content/10 rounded-lg resize-none outline-none placeholder:text-base-content/40 focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
                                <button wire:click="markResponded"
                                    class="px-4 py-2 rounded-lg bg-[#9ABC05] text-white text-[13px] font-semibold hover:bg-[#7a9a00] transition-colors">
                                    Mark as Responded
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="cancelDelete"></div>
            <div class="relative bg-base-100 rounded-2xl shadow-2xl w-full max-w-sm z-10 p-6 space-y-4">
                <h3 class="text-[16px] font-semibold text-base-content">Delete Message?</h3>
                <p class="text-[13px] text-base-content/60">This contact message will be permanently removed. This cannot be undone.</p>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete"
                        class="px-4 py-2 rounded-lg bg-base-200 text-base-content text-[13px] font-semibold hover:bg-base-300 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="deleteMessage({{ $confirmingDeleteId }})"
                        class="px-4 py-2 rounded-lg bg-[#D52518] text-white text-[13px] font-semibold hover:bg-[#b01e14] transition-colors">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
