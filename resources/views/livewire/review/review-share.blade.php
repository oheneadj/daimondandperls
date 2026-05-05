<div class="bg-base-200 min-h-screen py-10 lg:py-20">
    <div class="container mx-auto px-4 max-w-lg">

        {{-- Referral badge --}}
        <div class="text-center mb-8">
            <div class="size-14 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
            <h1 class="text-[22px] font-bold text-base-content">
                {{ $review->friend_name ? $review->friend_name . ', a' : 'A' }} friend thinks you'd love this
            </h1>
            <p class="text-[14px] text-base-content/50 mt-1">
                {{ $review->author_name }} shared their experience with you.
            </p>
        </div>

        {{-- Review card --}}
        <div class="bg-base-100 border border-base-content/10 rounded-2xl p-6 shadow-sm mb-5">

            {{-- Stars --}}
            <div class="flex items-center gap-1 mb-4">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= ($review->stars ?? 0) ? 'text-yellow-400' : 'text-base-content/20' }} text-2xl">★</span>
                @endfor
            </div>

            @if($review->message)
                <blockquote class="text-[15px] text-base-content/80 italic leading-relaxed mb-4">
                    "{{ $review->message }}"
                </blockquote>
            @endif

            <p class="text-[12px] text-base-content/40 font-semibold">
                — {{ Str::substr($review->author_name ?? 'A verified customer', 0, strrpos($review->author_name ?? '', ' ') ?: strlen($review->author_name ?? '')) }}{{ $review->author_name && str_contains($review->author_name, ' ') ? ' ' . strtoupper(substr(strrchr($review->author_name, ' '), 1, 1)) . '.' : '' }}, verified DPC customer
            </p>
        </div>

        {{-- CTA --}}
        <a href="{{ $orderUrl }}" class="btn btn-primary w-full text-[15px] font-bold py-4">
            🍽 Order Now — Your first order awaits
        </a>

        <p class="text-center text-[11px] text-base-content/40 mt-3">
            Browse our full menu at DPC Catering
        </p>

    </div>
</div>
