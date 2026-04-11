@props([
    'title' => null,
    'description' => null,
])

<div class="space-y-6">
    {{-- Page Header --}}
    <div>
        @if ($title)
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">{{ $title }}</h1>
        @endif
        @if ($description)
            <p class="text-[14px] text-base-content/50 mt-1">{{ $description }}</p>
        @endif
    </div>

    {{-- Tab Navigation --}}
    <div class="flex items-center gap-1 overflow-x-auto whitespace-nowrap scrollbar-hidden bg-white border border-base-content/5 rounded-xl p-1 shadow-sm w-fit">
        @if(request()->routeIs('profile.edit', 'user-password.edit', 'two-factor.show'))
            <a href="{{ route('profile.edit') }}" wire:navigate
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-bold tracking-wide transition-all
                    {{ request()->routeIs('profile.edit') ? 'bg-primary text-white shadow-sm' : 'text-base-content/50 hover:text-base-content hover:bg-base-200' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                {{ __('Profile') }}
            </a>
            <a href="{{ route('user-password.edit') }}" wire:navigate
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-bold tracking-wide transition-all
                    {{ request()->routeIs('user-password.edit') ? 'bg-primary text-white shadow-sm' : 'text-base-content/50 hover:text-base-content hover:bg-base-200' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                {{ __('Password') }}
            </a>
            @if(request()->routeIs('two-factor.show') || class_exists(\Laravel\Fortify\Features::class) && \Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <a href="{{ route('two-factor.show') }}" wire:navigate
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-bold tracking-wide transition-all
                        {{ request()->routeIs('two-factor.show') ? 'bg-primary text-white shadow-sm' : 'text-base-content/50 hover:text-base-content hover:bg-base-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 21a11.955 11.955 0 01-9.618-7.016m19.236 0A11.955 11.955 0 0112 2.984a11.955 11.955 0 019.618 7.016z" /></svg>
                    {{ __('2FA') }}
                </a>
            @endif
        @else
            <a href="{{ route('settings.general') }}" wire:navigate
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-bold tracking-wide transition-all
                    {{ request()->routeIs('settings.general') ? 'bg-primary text-white shadow-sm' : 'text-base-content/50 hover:text-base-content hover:bg-base-200' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                {{ __('General') }}
            </a>
            <a href="{{ route('settings.notifications') }}" wire:navigate
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-bold tracking-wide transition-all
                    {{ request()->routeIs('settings.notifications') ? 'bg-primary text-white shadow-sm' : 'text-base-content/50 hover:text-base-content hover:bg-base-200' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                {{ __('Notifications') }}
            </a>
            <a href="{{ route('settings.payment') }}" wire:navigate
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-bold tracking-wide transition-all
                    {{ request()->routeIs('settings.payment') ? 'bg-primary text-white shadow-sm' : 'text-base-content/50 hover:text-base-content hover:bg-base-200' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                {{ __('Payment') }}
            </a>
        @endif
    </div>

    {{-- Content --}}
    <div>
        {{ $slot }}
    </div>
</div>
