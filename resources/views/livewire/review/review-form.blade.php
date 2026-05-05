<div class="bg-base-200 min-h-screen py-10 lg:py-20">
    <div class="container mx-auto px-4 max-w-lg">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="size-14 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-primary" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1 class="text-[24px] font-bold text-base-content">Rate Your Experience</h1>
            <p class="text-[14px] text-base-content/50 mt-1">
                Tell us how we did for order #{{ $review->booking->reference }}
            </p>
        </div>

        @if(!$submitted)
            {{-- Review form --}}
            <div class="bg-base-100 border border-base-content/10 rounded-2xl p-6 shadow-sm">

                {{-- Star picker --}}
                <div class="mb-6">
                    <label class="block text-[13px] font-semibold text-base-content mb-3">Your Rating</label>
                    <div class="flex items-center gap-2" x-data="{ hovered: 0, selected: $wire.entangle('stars') }">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button"
                                @mouseenter="hovered = {{ $i }}"
                                @mouseleave="hovered = 0"
                                @click="selected = {{ $i }}"
                                :class="(hovered || selected) >= {{ $i }} ? 'text-yellow-400' : 'text-base-content/20'"
                                class="text-4xl transition-colors focus:outline-none">★</button>
                        @endfor
                    </div>
                    @error('stars')
                        <p class="text-xs text-error mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Name --}}
                <div class="mb-4">
                    <label class="block text-[13px] font-semibold text-base-content mb-1.5">Your Name</label>
                    <input type="text" wire:model="authorName"
                        class="w-full input input-bordered text-[14px]"
                        placeholder="Alice Johnson" />
                    @error('authorName')
                        <p class="text-xs text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-4">
                    <label class="block text-[13px] font-semibold text-base-content mb-1.5">Phone (optional)</label>
                    <input type="tel" wire:model="reviewerPhone"
                        class="w-full input input-bordered text-[14px]"
                        placeholder="0201234567" />
                    @error('reviewerPhone')
                        <p class="text-xs text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Message --}}
                <div class="mb-6">
                    <label class="block text-[13px] font-semibold text-base-content mb-1.5">Message (optional)</label>
                    <textarea wire:model="message" rows="4"
                        class="w-full textarea textarea-bordered text-[14px] resize-none"
                        placeholder="Tell us what you loved..."></textarea>
                    @error('message')
                        <p class="text-xs text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="button" wire:click="submit" wire:loading.attr="disabled"
                    class="w-full btn btn-primary text-[14px] font-semibold">
                    <span wire:loading.remove wire:target="submit">Submit Review</span>
                    <span wire:loading wire:target="submit">Submitting...</span>
                </button>
            </div>

        @else
            {{-- Success card --}}
            <div class="bg-base-100 border border-success/20 rounded-2xl p-6 shadow-sm text-center mb-5">
                <div class="size-12 rounded-full bg-success/10 flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-[16px] font-bold text-base-content">Thank you for your review!</p>
                @if($review->points_awarded > 0)
                    <p class="text-[13px] text-base-content/60 mt-1">
                        You've earned <span class="font-bold text-primary">{{ number_format($review->points_awarded) }} loyalty points</span>.
                    </p>
                @endif

                {{-- Stars display --}}
                <div class="flex justify-center gap-1 mt-3">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $review->stars ? 'text-yellow-400' : 'text-base-content/20' }} text-2xl">★</span>
                    @endfor
                </div>

                @if($review->message)
                    <p class="text-[13px] text-base-content/60 italic mt-3">"{{ $review->message }}"</p>
                @endif
            </div>

            {{-- Friend nomination --}}
            @if(!$friendNominated)
                <div class="bg-base-100 border border-base-content/10 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="size-9 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[14px] font-bold text-base-content">Know someone who'd love our meals?</p>
                            <p class="text-[12px] text-base-content/50">Share the experience — they'll get a personal invite from us.</p>
                        </div>
                    </div>

                    @if($friendError)
                        <div class="bg-error/8 border border-error/20 rounded-xl px-4 py-2.5 mb-4">
                            <p class="text-[12px] text-error">{{ $friendError }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="block text-[12px] font-semibold text-base-content mb-1.5">Friend's Name</label>
                        <input type="text" wire:model="friendName"
                            class="w-full input input-bordered input-sm text-[13px]"
                            placeholder="John Mensah" />
                        @error('friendName')
                            <p class="text-xs text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-[12px] font-semibold text-base-content mb-1.5">Friend's Phone</label>
                        <input type="tel" wire:model="friendPhone"
                            class="w-full input input-bordered input-sm text-[13px]"
                            placeholder="0201234567" />
                        @error('friendPhone')
                            <p class="text-xs text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="button" wire:click="nominateFriend" wire:loading.attr="disabled"
                        class="w-full btn btn-outline btn-primary btn-sm text-[13px] font-semibold">
                        <span wire:loading.remove wire:target="nominateFriend">Send them a message</span>
                        <span wire:loading wire:target="nominateFriend">Sending...</span>
                    </button>
                </div>
            @else
                <div class="bg-base-100 border border-success/20 rounded-2xl p-5 shadow-sm text-center">
                    <p class="text-[14px] font-bold text-success">Friend notified!</p>
                    <p class="text-[12px] text-base-content/50 mt-1">We sent them a personal message from you.</p>
                </div>
            @endif
        @endif

    </div>
</div>
