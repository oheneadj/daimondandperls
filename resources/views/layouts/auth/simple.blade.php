<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-[#F4F4F6] antialiased relative overflow-x-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute -top-[10%] -right-[10%] w-[40%] h-[40%] bg-primary/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-[10%] -left-[10%] w-[30%] h-[30%] bg-accent/10 rounded-full blur-[100px] pointer-events-none"></div>
    
    <div class="relative z-10 flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
        <div class="flex w-full {{ $maxWidth ?? 'max-w-[550px]' }} flex-col gap-10">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-4 group" wire:navigate>
                <div class="flex size-24 items-center justify-center rounded-[28px] bg-white text-primary shadow-xl shadow-primary/10 border border-primary/5 group-hover:scale-105 group-hover:shadow-2xl transition-all duration-500">
                    <x-app-logo-icon class="size-12 fill-current" />
                </div>
                <span class="sr-only">{{ config('app.name', 'Catering App') }}</span>
            </a>

            <div class="bg-white rounded-3xl border border-base-content/5 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.08)] overflow-hidden backdrop-blur-sm">
                <div class="p-8 sm:p-12 lg:p-14">
                    {{ $slot }}
                </div>
            </div>
            
            <p class="text-center text-[10px] text-base-content/30 font-bold uppercase tracking-[0.25em] italic">
                &copy; {{ date('Y') }} Diamonds & Pearls Catering Services
            </p>
        </div>
    </div>
</body>

</html>