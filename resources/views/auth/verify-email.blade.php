<x-guest-layout>
    <h1 class="h5 fw-semibold mb-2">Verify Email</h1>
    <div class="mb-3 text-muted">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>{{ __('Resend Verification Email') }}</x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-outline-secondary">Logout</button>
        </form>
    </div>
</x-guest-layout>
