@props(['align' => 'right', 'width' => '48', 'contentClasses' => ''])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'right':
    default:
        $alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
        break;
}

switch ($width) {
    case '48':
        $width = 'w-48';
        break;
}
@endphp

{{-- Simplified (Bootstrap-friendly). Prefer using Bootstrap dropdowns directly in views. --}}
<div class="dropdown d-inline-block">
    <div>
        {{ $trigger }}
    </div>
    <div class="dropdown-menu dropdown-menu-end show position-static">
        {{ $content }}
    </div>
</div>
