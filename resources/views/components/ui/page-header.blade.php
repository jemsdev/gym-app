@props([
    'title',
    'subtitle' => null,
])

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <div class="h5 mb-0 fw-semibold">{{ $title }}</div>
        @if ($subtitle)
            <div class="text-muted small">{{ $subtitle }}</div>
        @endif
    </div>
    <div class="d-flex gap-2">
        {{ $actions ?? '' }}
    </div>
</div>

