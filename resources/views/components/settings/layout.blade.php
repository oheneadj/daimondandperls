@props([
    'title' => null,
    'description' => null,
])

<div class="flex flex-col gap-8">
    <div class="flex flex-col gap-2">
        @if ($title)
            <h1 class=" text-4xl font-semibold tracking-tight text-base-content">{{ $title }}</h1>
        @endif
        @if ($description)
            <p class=" text-[14px] text-base-content/60 font-medium italic opacity-60">{{ $description }}</p>
        @endif
    </div>

    <!-- Settings Navigation Tabs -->
    <div class="flex items-center gap-1 bg-base-200-mid/50 p-1.5 rounded-2xl border border-base-content/10/40 shadow-sm overflow-x-auto whitespace-nowrap scrollbar-hidden">
        @if(request()->routeIs('profile.edit', 'user-password.edit', 'appearance.edit', 'two-factor.show'))
            <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-2.5 px-6 py-3 rounded-xl  text-[13px] font-bold tracking-wide transition-all {{ request()->routeIs('profile.edit') ? 'bg-base-100 text-primary shadow-sm' : 'text-base-content/60 hover:text-base-content' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                {{ __('Profile') }}
            </a>

            <a href="{{ route('user-password.edit') }}" wire:navigate class="flex items-center gap-2.5 px-6 py-3 rounded-xl  text-[13px] font-bold tracking-wide transition-all {{ request()->routeIs('user-password.edit') ? 'bg-base-100 text-primary shadow-sm' : 'text-base-content/60 hover:text-base-content' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                {{ __('Password') }}
            </a>
            <a href="{{ route('appearance.edit') }}" wire:navigate class="flex items-center gap-2.5 px-6 py-3 rounded-xl  text-[13px] font-bold tracking-wide transition-all {{ request()->routeIs('appearance.edit') ? 'bg-base-100 text-primary shadow-sm' : 'text-base-content/60 hover:text-base-content' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-3.3a2 2 0 01-1.4-.6l-2-2a2 2 0 01-.6-1.4V3" /></svg>
                {{ __('Appearance') }}
            </a>
        @else
            <a href="{{ route('settings.general') }}" wire:navigate class="flex items-center gap-2.5 px-6 py-3 rounded-xl  text-[13px] font-bold tracking-wide transition-all {{ request()->routeIs('settings.general') ? 'bg-base-100 text-primary shadow-sm' : 'text-base-content/60 hover:text-base-content' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                {{ __('General') }}
            </a>
            <a href="{{ route('settings.notifications') }}" wire:navigate class="flex items-center gap-2.5 px-6 py-3 rounded-xl  text-[13px] font-bold tracking-wide transition-all {{ request()->routeIs('settings.notifications') ? 'bg-base-100 text-primary shadow-sm' : 'text-base-content/60 hover:text-base-content' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                {{ __('Notifications') }}
            </a>
            <a href="{{ route('settings.payment') }}" wire:navigate class="flex items-center gap-2.5 px-6 py-3 rounded-xl  text-[13px] font-bold tracking-wide transition-all {{ request()->routeIs('settings.payment') ? 'bg-base-100 text-primary shadow-sm' : 'text-base-content/60 hover:text-base-content' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                {{ __('Payment') }}
            </a>
        @endif
    </div>

    <!-- Content Area -->
    <div class="flex flex-col gap-10">
        {{ $slot }}
    </div>
</div>
