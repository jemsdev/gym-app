<x-guest-layout>
    <h1 class="h5 fw-semibold mb-2">Forgot Password</h1>
    <div class="mb-3 text-muted">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>{{ __('Email Password Reset Link') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>
