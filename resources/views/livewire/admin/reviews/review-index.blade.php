<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">Reviews</h1>
            <p class="text-[14px] text-base-content/50 mt-1">Customer ratings and feedback</p>
        </div>

        {{-- Filters --}}
        <div class="flex items-center gap-3">
            <select wire:model.live="filterStars" class="select select-bordered select-sm text-[13px]">
                <option value="">All Stars</option>
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">{{ $i }} ★</option>
                @endfor
            </select>
            <select wire:model.live="filterApproved" class="select select-bordered select-sm text-[13px]">
                <option value="">All Reviews</option>
                <option value="1">Approved</option>
                <option value="0">Pending</option>
            </select>
        </div>
    </div>

    <div class="bg-base-100 border border-base-content/5 rounded-2xl shadow-sm overflow-hidden">
        @if($reviews->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="size-14 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                    @include('layouts.partials.icons.star', ['class' => 'size-7 text-primary'])
                </div>
                <p class="text-[15px] font-semibold text-base-content">No reviews yet</p>
                <p class="text-[13px] text-base-content/50 mt-1">Reviews appear once customers complete their orders.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-base-content/40 border-b border-base-content/5">
                            <th class="py-4 px-6 text-left font-bold">Date</th>
                            <th class="py-4 px-6 text-left font-bold">Booking</th>
                            <th class="py-4 px-6 text-left font-bold">Customer</th>
                            <th class="py-4 px-6 text-left font-bold">Stars</th>
                            <th class="py-4 px-6 text-left font-bold">Message</th>
                            <th class="py-4 px-6 text-left font-bold">Approved</th>
                            <th class="py-4 px-6 text-left font-bold">Friend SMS</th>
                            <th class="py-4 px-6 text-left font-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base-content/5">
                        @foreach($reviews as $review)
                            <tr wire:key="review-{{ $review->id }}" class="hover:bg-base-200/40 transition-colors">
                                <td class="py-3.5 px-6 text-[12px] text-base-content/60 whitespace-nowrap">
                                    {{ $review->submitted_at->format('d M Y') }}
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="text-[12px] font-mono font-semibold text-base-content">
                                        {{ $review->booking->reference }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6">
                                    <p class="text-[13px] font-semibold text-base-content">{{ $review->author_name }}</p>
                                    <p class="text-[11px] text-base-content/40">{{ $review->reviewer_phone }}</p>
                                </td>
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= ($review->stars ?? 0) ? 'text-yellow-400' : 'text-base-content/20' }} text-base">★</span>
                                        @endfor
                                    </div>
                                </td>
                                <td class="py-3.5 px-6 max-w-[200px]">
                                    <p class="text-[12px] text-base-content/70 truncate">
                                        {{ $review->message ? Str::limit($review->message, 80) : '—' }}
                                    </p>
                                </td>
                                <td class="py-3.5 px-6">
                                    <button wire:click="approve({{ $review->id }})"
                                        @class([
                                            'badge badge-sm font-semibold',
                                            'badge-success' => $review->is_approved,
                                            'badge-ghost' => !$review->is_approved,
                                        ])>
                                        {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                    </button>
                                </td>
                                <td class="py-3.5 px-6 text-[13px]">
                                    {{ $review->friend_sms_sent_at ? '✓' : '—' }}
                                </td>
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="viewReview({{ $review->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-[12px] font-bold hover:bg-primary/20 transition-colors">
                                            @include('layouts.partials.icons.eye', ['class' => 'w-3.5 h-3.5'])
                                            View
                                        </button>
                                        <button wire:click="delete({{ $review->id }})"
                                            wire:confirm="Delete this review?"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-error/10 text-error hover:bg-error/20 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($reviews->hasPages())
                <div class="px-6 py-4 border-t border-base-content/5">
                    {{ $reviews->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- View Review Modal --}}
    <x-ui.modal wire:model="showViewModal" title="Review Details" maxWidth="lg">
        @if($selectedReview)
            <div class="space-y-5">
                {{-- Customer & Meta --}}
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[15px] font-bold text-base-content">{{ $selectedReview->author_name }}</p>
                        <p class="text-[12px] text-base-content/50 mt-0.5">{{ $selectedReview->reviewer_phone }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="flex items-center gap-0.5 justify-end">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= ($selectedReview->stars ?? 0) ? 'text-yellow-400' : 'text-base-content/20' }} text-lg leading-none">★</span>
                            @endfor
                        </div>
                        <p class="text-[11px] text-base-content/40 mt-1">{{ $selectedReview->submitted_at->format('d M Y, g:ia') }}</p>
                    </div>
                </div>

                {{-- Status badges --}}
                <div class="flex items-center gap-2 flex-wrap">
                    <span @class([
                        'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide',
                        'bg-success/10 text-success' => $selectedReview->is_approved,
                        'bg-base-200 text-base-content/50' => !$selectedReview->is_approved,
                    ])>
                        <span class="w-1.5 h-1.5 rounded-full {{ $selectedReview->is_approved ? 'bg-success' : 'bg-base-content/30' }}"></span>
                        {{ $selectedReview->is_approved ? 'Approved' : 'Pending Approval' }}
                    </span>
                    @if($selectedReview->points_awarded)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide bg-[#FFC926]/10 text-[#b38a00]">
                            +{{ $selectedReview->points_awarded }} pts awarded
                        </span>
                    @endif
                </div>

                {{-- Booking reference --}}
                <div class="bg-base-200/60 rounded-xl px-4 py-3 flex items-center justify-between gap-4">
                    <span class="text-[11px] font-bold uppercase tracking-widest text-base-content/40">Booking</span>
                    <span class="font-mono text-[13px] font-semibold text-base-content">{{ $selectedReview->booking->reference }}</span>
                </div>

                {{-- Review message --}}
                @if($selectedReview->message)
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/40 mb-2">Message</p>
                        <div class="bg-base-200/60 rounded-xl px-4 py-3">
                            <p class="text-[14px] text-base-content leading-relaxed">{{ $selectedReview->message }}</p>
                        </div>
                    </div>
                @else
                    <div class="bg-base-200/40 rounded-xl px-4 py-3 text-center">
                        <p class="text-[13px] text-base-content/40 italic">No message left.</p>
                    </div>
                @endif

                {{-- Friend referral --}}
                @if($selectedReview->friend_name || $selectedReview->friend_phone)
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/40 mb-2">Friend Referral</p>
                        <div class="bg-base-200/60 rounded-xl px-4 py-3 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-[13px] font-semibold text-base-content">{{ $selectedReview->friend_name ?? '—' }}</p>
                                <p class="text-[12px] text-base-content/50">{{ $selectedReview->friend_phone ?? '—' }}</p>
                            </div>
                            @if($selectedReview->friend_sms_sent_at)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-success/10 text-success">
                                    SMS sent {{ $selectedReview->friend_sms_sent_at->format('d M') }}
                                </span>
                            @else
                                <span class="text-[11px] text-base-content/30 font-medium">SMS not sent</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <x-slot:footer>
                <x-ui.button variant="ghost" wire:click="$set('showViewModal', false)">Close</x-ui.button>
                <x-ui.button
                    variant="primary"
                    wire:click="approve({{ $selectedReview->id }})"
                    class="{{ $selectedReview->is_approved ? 'opacity-60' : '' }}"
                >
                    {{ $selectedReview->is_approved ? 'Revoke Approval' : 'Approve Review' }}
                </x-ui.button>
            </x-slot:footer>
        @endif
    </x-ui.modal>
</div>
