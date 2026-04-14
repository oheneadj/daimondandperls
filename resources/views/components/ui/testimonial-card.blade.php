@props([
    'quote',
    'name',
    'event',
    'initials' => null,
])

@php
    $initials = $initials ?? mb_strtoupper(mb_substr($name, 0, 1));
@endphp

<div class="bg-base-100 rounded-2xl p-6 sm:p-7 flex flex-col shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5 relative overflow-hidden">
    {{-- Subtle top accent line --}}
    <div class="absolute top-0 left-6 right-6 h-[2px] bg-gradient-to-r from-transparent via-primary/30 to-transparent rounded-full"></div>

    {{-- Stars --}}
    <div class="flex items-center gap-0.5 mb-4">
        @for($i = 0; $i < 5; $i++)
            <svg class="size-4 text-accent" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        @endfor
    </div>

    {{-- Quote --}}
    <blockquote class="flex-1 text-[14px] text-base-content/70 font-medium leading-relaxed mb-6">
        "{{ $quote }}"
    </blockquote>

    {{-- Author --}}
    <div class="flex items-center gap-3 pt-4 border-t border-base-content/8">
        <div class="size-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-[14px] shrink-0">
            {{ $initials }}
        </div>
        <div>
            <p class="text-[14px] font-bold text-base-content leading-tight">{{ $name }}</p>
            <p class="text-[11px] font-bold text-primary uppercase tracking-widest mt-0.5">{{ $event }}</p>
        </div>
    </div>
</div>
