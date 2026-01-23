@props([
    'colspan' => 1,
    'text' => 'Tidak ada data.',
])

<tr>
    <td colspan="{{ $colspan }}" class="text-center text-muted py-4">
        {{ $text }}
    </td>
</tr>

