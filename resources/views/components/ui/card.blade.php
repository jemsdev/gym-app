@props([
    'title' => null,
    'footer' => null,
])

<div {{ $attributes->merge(['class' => 'card admin-card']) }}>
    @if ($title !== null)
        <div class="card-header fw-semibold">
            {{ $title }}
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>

    @if ($footer !== null)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>

