<x-layouts::auth :title="__('Confirm password')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Confirm password')" :description="__('This is a secure area of the application. Please confirm your password before continuing.')" />

        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <div class="form-control w-full">
                <label class="label">
                    <span
                        class="label-text font-semibold uppercase tracking-wider text-base-content/50 text-xs">{{ __('Password') }}</span>
                </label>
                <input name="password" type="password" required autocomplete="current-password"
                    placeholder="{{ __('Password') }}"
                    class="input input-primary input-bordered w-full @error('password') input-error @enderror" />
                @error('password')
                    <label class="label">
                        <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Confirm') }}
            </button>
        </form>
    </div>
</x-layouts::auth>