@props([
    'status',
])

@php
    $badge = match($status) {
        'PAID' => 'success',
        'PENDING' => 'warning',
        'CANCELED' => 'secondary',
        'EXPIRED' => 'danger',
        default => 'light',
    };
@endphp

<span class="badge text-bg-{{ $badge }}">{{ $status }}</span>

