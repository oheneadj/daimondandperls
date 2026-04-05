<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-base-200 antialiased">
    <div class="flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
        <div class="flex w-full {{ $maxWidth ?? 'max-w-[460px]' }} flex-col gap-8">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-3 group" wire:navigate>
                <div class="flex size-16 items-center justify-center rounded-xl bg-base-100 text-primary shadow-dp-lg border border-base-content/10 group-hover:scale-105 transition-all duration-300">
                    <x-app-logo-icon class="size-8 fill-current" />
                </div>
                <span class="sr-only">{{ config('app.name', 'Catering App') }}</span>
            </a>

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