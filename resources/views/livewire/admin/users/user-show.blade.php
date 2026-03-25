<div class="space-y-10 pb-10">
    <div class="flex items-center justify-between mb-8">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-base-content/60">
            <a href="{{ route('admin.users.index') }}" wire:navigate class="hover:text-[#F96015] transition-colors">{{ __('Users') }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-base-content">{{ $user->name }}</span>
        </div>
    </div>

    <!-- Page Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-4">
                
                <div>
                    <h1 class="text-[28px] font-semibold text-base-content flex items-center gap-3">
                        {{ $user->name }}
                        @if($user->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-success/10 text-dp-success border border-success/20">
                                <span class="size-1.5 rounded-full bg-success animate-pulse"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-base-content/5 text-base-content/40 border border-base-content/10">
                                Inactive
                            </span>
                        @endif
                    </h1>
                    <p class="text-[14px] text-base-content/50 mt-1 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('Member since') }} <span class="font-medium text-base-content">{{ $user->created_at->format('M d, Y') }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <x-ui.button variant="black" class="border-0" size="sm" href="{{ route('admin.users.index') }}" wire:navigate>
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </x-slot:icon>
                {{ __('Back to Users') }}
            </x-ui.button>

            <x-ui.button variant="primary" size="sm" href="{{ route('admin.users.edit', $user->uuid) }}">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </x-slot:icon>
                {{ __('Edit User') }}
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Main Content Area (Left 2/3) -->
        <div class="xl:col-span-2 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Contact Details Card -->
                <x-ui.card>
                    <div class="flex items-center gap-2.5 mb-6">
                        <div class="w-8 h-8 rounded-full bg-[#F96015]/10 text-[#F96015] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Contact Details') }}</h2>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Full Name') }}</p>
                            <p class="text-[20px] font-semibold text-base-content">{{ $user->name }}</p>
                        </div>
                        <div class="grid grid-cols-1 gap-3">
                            <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Contact Info') }}</p>
                            <div class="flex flex-col gap-2.5">
                                <a href="mailto:{{ $user->email }}" class="flex items-center gap-3 text-[13px] text-base-content border border-base-content/10 p-3 rounded-xl hover:bg-[#F96015]/5 hover:border-[#F96015]/20 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $user->email }}
                                </a>
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}" class="flex items-center gap-3 text-[13px] text-base-content border border-base-content/10 p-3 rounded-xl hover:bg-[#F96015]/5 hover:border-[#F96015]/20 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $user->phone }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Account Details Card -->
                <x-ui.card>
                    <div class="flex items-center gap-2.5 mb-6">
                        <div class="w-8 h-8 rounded-full bg-[#FFC926]/15 text-[#FFC926] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Account Details') }}</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center gap-5">
                            <div class="flex flex-col">
                                <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Account Type') }}</p>
                                <p class="text-[15px] font-bold text-base-content">
                                    {{ str($user->type?->value ?? 'N/A')->replace('_', ' ')->title() }}
                                </p>
                            </div>
                            <div class="h-8 w-px border-l border-base-content/5"></div>
                            <div class="flex flex-col">
                                <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Status') }}</p>
                                <p class="text-[15px] font-bold {{ $user->is_active ? 'text-success' : 'text-base-content/40' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Email Verification') }}</p>
                            @if($user->email_verified_at)
                                <div class="inline-flex items-center gap-1.5 text-[13px] font-bold text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Verified {{ $user->email_verified_at->format('M d, Y') }}
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 text-[13px] font-bold text-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Not verified
                                </div>
                            @endif
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Notification Preference') }}</p>
                            <x-badge type="ghost" class="mt-1">{{ str($user->notification_preference?->value ?? 'email')->replace('_', ' ')->title() }}</x-badge>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <!-- Recent Activity -->
            <x-ui.card padding="none">
                <div class="p-8 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-[#9ABC05]/10 text-[#9ABC05] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Recent Activity') }}</h2>
                    </div>
                </div>
                <div class="px-8 pb-8 text-center py-10">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-base-content/5 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9.75m-3-7.875V21a2.625 2.625 0 002.625 2.625h8.25A2.625 2.625 0 0020.25 21V8.625M3.75 21h.008v.008H3.75V21z" />
                            </svg>
                        </div>
                        <p class="text-[13px] font-medium text-base-content/40">{{ __('No recent activity logged for this user.') }}</p>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Sidebar Action Panel (Right 1/3) -->
        <div class="space-y-8">

            <!-- Roles Card -->
            <x-ui.card class="relative overflow-hidden border-0 shadow-xl shadow-base-content/5 ring-1 ring-base-content/10">
                <!-- Decorative background elements -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-secondary/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#1c1c1c] to-[#0a0a0a] flex items-center justify-center shadow-lg shadow-black/20 ring-1 ring-white/10 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-[12px] font-black uppercase tracking-[0.25em] text-base-content">{{ __('Assigned Roles') }}</h2>
                        <p class="text-[10px] text-base-content/50 uppercase tracking-widest mt-0.5">{{ __('Permissions & access') }}</p>
                    </div>
                </div>

                <div class="relative flex flex-wrap gap-2">
                    @forelse($user->roles as $role)
                        <span class="px-3 py-1.5 bg-primary/10 text-primary border border-primary/20 rounded-full text-[10px] font-black uppercase tracking-widest">
                            {{ $role->name }}
                        </span>
                    @empty
                        <div class="w-full text-center py-4">
                            <p class="text-[13px] font-medium text-base-content/40">{{ __('No roles assigned.') }}</p>
                        </div>
                    @endforelse
                </div>
            </x-ui.card>

            <!-- Account Timeline Card -->
            <div class="relative rounded-xl p-[1px] shadow-sm">
                <div class="p-6 rounded-[calc(1.5rem-1px)] bg-base-100 h-full w-full relative overflow-hidden backdrop-blur-xl">
                    <!-- Decorative Icon -->
                    <div class="absolute -right-6 -bottom-6 opacity-[0.02] text-base-content pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 mb-5">{{ __('Account Timeline') }}</h3>

                    <div class="space-y-5 relative z-10">
                        <div class="flex flex-col gap-1.5 border-l-2 border-[#F96015]/30 pl-3">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest">{{ __('Created') }}</span>
                            </div>
                            <span class="text-[13px] font-bold text-base-content">{{ $user->created_at->format('M d, Y') }} <span class="text-[11px] text-base-content/40 font-normal ml-1">({{ $user->created_at->diffForHumans() }})</span></span>
                        </div>

                        <div class="flex flex-col gap-1.5 border-l-2 border-[#FFC926]/30 pl-3">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest">{{ __('Last Login') }}</span>
                            </div>
                            <span class="text-[13px] font-bold text-base-content">{{ $user->last_login_at?->format('M d, H:i') ?? 'Never' }}</span>
                        </div>

                        <div class="flex flex-col gap-1.5 border-l-2 border-[#9ABC05]/30 pl-3">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest">{{ __('Last Updated') }}</span>
                            </div>
                            <span class="text-[13px] font-bold text-base-content">{{ $user->updated_at->format('M d, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <x-ui.card>
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 mb-4">{{ __('Quick Actions') }}</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="mailto:{{ $user->email }}" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-base-content/5 hover:bg-base-content/10 text-base-content text-[13px] font-bold transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ __('Email') }}
                    </a>
                    @if($user->phone)
                        <a href="tel:{{ $user->phone }}" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-base-content/5 hover:bg-base-content/10 text-base-content text-[13px] font-bold transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ __('Call') }}
                        </a>
                    @endif
                </div>
            </x-ui.card>

        </div>
    </div>
</div>
