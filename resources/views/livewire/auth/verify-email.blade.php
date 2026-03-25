<x-layouts::auth :title="__('Email verification')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Verify Email')" :description="__('Please verify your email address by clicking on the link we just emailed to you.')" />

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </span>
            </div>
        @endif

        <div class="flex flex-col gap-4 mt-2">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-ui.button type="submit" variant="primary" size="lg" class="w-full">
                    {{ __('Resend verification email') }}
                </x-ui.button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <x-ui.button type="submit" variant="ghost" size="sm" class="text-base-content/60 hover:text-primary" data-test="logout-button">
                    {{ __('Log out') }}
                </x-ui.button>
            </form>
        </div>
    </div>
</x-layouts::auth>