<div class="dropdown dropdown-end">
    <label tabindex="0" class="btn btn-ghost btn-sm h-10 px-2 flex items-center gap-2">
        <div class="avatar placeholder">
            <div class="bg-primary text-primary-content rounded-md w-8 h-8">
                <span class="text-xs uppercase font-bold">{{ auth()->user()->initials() }}</span>
            </div>
        </div>
        <div class="hidden sm:flex flex-col items-start gap-0.5 text-left leading-tight">
            <span class="text-xs font-bold">{{ auth()->user()->name }}</span>
            <span class="text-[10px] text-base-content/60">{{ auth()->user()->email }}</span>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 opacity-50" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </label>
    <ul tabindex="0"
        class="dropdown-content z-[1] menu p-2 shadow-2xl bg-base-100 border border-base-content/5 rounded-xl w-64 mt-4">
        <li class="menu-title px-4 py-3 text-[10px] font-black uppercase tracking-[0.2em] text-base-content/30 italic">
            Account Management</li>
        <li>
            <a href="{{ route('profile.edit') }}" wire:navigate
                class="gap-3 py-3 rounded-lg active:!bg-primary/10 active:!text-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <div class="flex flex-col">
                    <span class="font-bold text-sm">{{ __('My Profile') }}</span>
                    <span class="text-[10px] opacity-60 italic">{{ __('Personal info & metrics') }}</span>
                </div>
            </a>
        </li>
        <div class="divider my-1 opacity-5"></div>
        <li>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 py-3 rounded-lg text-error hover:!bg-error/10 transition-all font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    {{ __('Sign out now') }}
                </button>
            </form>
        </li>
    </ul>
</div>