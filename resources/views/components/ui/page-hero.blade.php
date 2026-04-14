@props([
    'badge'    => null,
    'title',
    'subtitle' => null,
    'centered' => true,
])

<section class="relative bg-primary py-20 lg:py-28 overflow-hidden">
    {{-- Crosshatch texture --}}
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]" aria-hidden="true"></div>

    {{-- Glow blobs --}}
    <div class="absolute top-0 right-0 size-[500px] bg-white/6 blur-[100px] rounded-full -translate-y-1/3 translate-x-1/4" aria-hidden="true"></div>
    <div class="absolute bottom-0 left-0 size-[350px] bg-black/12 blur-[80px] rounded-full translate-y-1/2 -translate-x-1/4" aria-hidden="true"></div>
    <div class="absolute top-1/2 left-1/2 size-[300px] bg-white/3 blur-[80px] rounded-full -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></div>

    {{-- Floating decorative food icons --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        {{-- Spoon & Fork top-left --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-8 left-[8%] size-16 text-white/5 rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        {{-- Chef hat top-right --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-6 right-[12%] size-20 text-white/5 -rotate-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
        </svg>
        {{-- Star / sparkle bottom-left --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute bottom-10 left-[15%] size-10 text-accent/20 rotate-45" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        {{-- Plate / dish bottom-right --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute bottom-6 right-[8%] size-24 text-white/4 rotate-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        {{-- Small sparkle mid-left --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-1/2 left-[5%] size-6 text-accent/25 -rotate-12" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        {{-- Small sparkle mid-right --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-1/3 right-[5%] size-8 text-white/8" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
    </div>

    <div @class(['container mx-auto px-4 lg:px-8 relative z-10', 'text-center' => $centered])>
        @if($badge)
            <div class="inline-flex items-center gap-2 bg-white/15 text-white text-[11px] font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6">
                <span class="size-2 rounded-full bg-accent animate-pulse"></span>
                {{ $badge }}
            </div>
        @endif

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-white tracking-tight leading-tight mb-5">
            {!! $title !!}
        </h1>

        @if($subtitle)
            <p class="text-[16px] sm:text-lg text-white/65 font-medium {{ $centered ? 'max-w-2xl mx-auto' : 'max-w-2xl' }} leading-relaxed">
                {{ $subtitle }}
            </p>
        @endif

        {{ $slot }}
    </div>
</section>
