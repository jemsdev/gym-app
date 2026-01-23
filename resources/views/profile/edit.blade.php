<x-app-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="h5 mb-0 fw-semibold">Profile</div>
            <div class="text-muted small">Kelola data akun kamu.</div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-danger">
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
