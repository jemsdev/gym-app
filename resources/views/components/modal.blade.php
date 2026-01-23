@props([
    'name',
    'show' => false,
])

{{-- Bootstrap-friendly modal wrapper (no Tailwind / no Alpine). --}}
@if ($show)
    <div class="modal fade show" tabindex="-1" style="display:block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
@else
    {{-- Modal hidden --}}
@endif
