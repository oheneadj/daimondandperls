<!DOCTYPE html>
<html lang="en" data-theme="light" class="scroll-smooth">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $pageTitle }} — {{ config('app.name', 'Diamonds & Pearls Catering') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>

<body class="bg-base-200 min-h-screen antialiased flex flex-col" style="font-family: 'Outfit', ui-sans-serif, system-ui, sans-serif;">

    {{-- Decorative blobs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-accent/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>
    </div>

    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4 py-16">

        {{-- Brand --}}
        <a href="/" class="flex items-center gap-3 mb-10 group">
            <div class="size-10 bg-primary rounded-full flex items-center justify-center text-white text-sm font-medium shadow-md transition-transform group-hover:scale-110">
                D&amp;P
            </div>
            <div class="flex flex-col leading-none">
                <span class="text-xl font-semibold text-base-content">Diamonds &amp; Pearls</span>
                <span class="text-[10px] text-base-content/60 uppercase tracking-[0.08em] font-bold">Catering Services</span>
            </div>
        </a>

        {{-- Card --}}
        <div class="bg-base-100 border border-base-content/10 rounded-2xl shadow-xl shadow-base-content/5 w-full max-w-md px-8 py-10 text-center">

            {{-- Error code badge --}}
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-primary/10 mb-6">
                <span class="text-4xl font-bold text-primary">{{ $code }}</span>
            </div>

            {{-- Heading --}}
            <h1 class="text-2xl font-bold text-base-content mb-2">{{ $heading }}</h1>

            {{-- Description --}}
            <p class="text-base-content/60 text-[15px] leading-relaxed mb-8">{{ $description }}</p>

            {{-- Action buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ $primaryUrl }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-primary text-white text-[14px] font-bold rounded-xl hover:bg-primary/90 transition-all shadow-sm">
                    {{ $primaryLabel }}
                </a>
                @if (!empty($secondaryLabel))
                    <a href="{{ $secondaryUrl }}"
                       class="inline-flex items-center justify-center px-6 py-3 bg-base-200 text-base-content/70 text-[14px] font-bold rounded-xl hover:bg-base-300 transition-all border border-base-content/10">
                        {{ $secondaryLabel }}
                    </a>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <p class="mt-8 text-[12px] text-base-content/40">
            Need help?
            <a href="/contact" class="underline hover:text-primary transition-colors">Contact us</a>
        </p>

    </div>

</body>

</html>
