<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-base-200 antialiased ">
    <div class="flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
        <div class="flex w-full {{ $maxWidth ?? 'max-w-[420px]' }} flex-col gap-12">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-4 group" wire:navigate>
                <div class="flex size-20 items-center justify-center rounded-2xl bg-primary-soft text-primary shadow-sm group-hover:shadow-md transition-all">
                    <x-app-logo-icon class="size-10 fill-current" />
                </div>
                <span class="sr-only">{{ config('app.name', 'Catering App') }}</span>
            </a>

            <div class="bg-base-100 rounded-2xl border border-dp-pearl-mid shadow-md overflow-hidden">
                <div class="p-8 lg:p-12">
                    {{ $slot }}
                </div>
            </div>
            
            <p class="text-center text-[10px] text-dp-text-disabled font-bold uppercase tracking-[0.2em] italic">
                &copy; {{ date('Y') }} Diamonds & Pearls Catering Services
            </p>
        </div>
    </div>
</body>

</html>