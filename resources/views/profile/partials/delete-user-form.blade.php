<section>
    <header>
        <h2 class="h6 fw-semibold text-danger">
            {{ __('Delete Account') }}
        </h2>

        <p class="text-muted mb-0">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="mt-3" onsubmit="return confirm('Hapus akun ini secara permanen?')">
        @csrf
        @method('delete')

        <div class="alert alert-warning py-2">
            {{ __('Please enter your password to confirm you would like to permanently delete your account.') }}
        </div>

        <div class="mb-2">
            <x-input-label for="password" value="{{ __('Password') }}" />
            <x-text-input id="password" name="password" type="password" placeholder="{{ __('Password') }}" required />
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
        </div>

        <x-danger-button>{{ __('Delete Account') }}</x-danger-button>
    </form>
</section>
