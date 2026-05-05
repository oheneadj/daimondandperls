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
                                    <button wire:click="delete({{ $review->id }})"
                                        wire:confirm="Delete this review?"
                                        class="text-[11px] font-semibold text-error hover:text-error/70 transition-colors">
                                        Delete
                                    </button>
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
</div>
