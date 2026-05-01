<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-base-200 antialiased">
    <div class="flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
        <div class="flex w-full {{ $maxWidth ?? 'max-w-[460px]' }} flex-col gap-8">
            <div class="flex flex-col items-center gap-4">
                <a href="{{ route('home') }}" class="group" wire:navigate>
                    <x-app-logo class="text-primary group-hover:opacity-80 transition-opacity duration-200" />
                </a>
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
                   class="inline-flex items-center gap-1.5 text-[12px] text-base-content/40 hover:text-base-content/70 font-medium transition-colors duration-200"
                   wire:navigate>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Go back
                </a>
            </div>

            <div class="bg-base-100 rounded-lg border border-base-content/10 shadow-dp-lg overflow-hidden">
                <div class="p-6 sm:p-10">
                    {{ $slot }}
                </div>
            </div>
            
            <p class="text-center text-[10px] text-base-content/30 font-medium uppercase tracking-[0.15em]">
                &copy; {{ date('Y') }} Diamonds & Pearls Catering Services
            </p>
        </div>
    </div>
</body>

</html>