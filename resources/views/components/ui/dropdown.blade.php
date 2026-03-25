@props(['position' => 'bottom-end'])

@php
    $placement = match($position) {
        'bottom-end' => '--placement:bottom-end',
        'bottom-start' => '--placement:bottom-start',
        'top-end' => '--placement:top-end',
        'top-start' => '--placement:top-start',
        'left-start' => '--placement:left-start',
        'left-end' => '--placement:left-end',
        'right-start' => '--placement:right-start',
        'right-end' => '--placement:right-end',
        default => '--placement:bottom-end'
    };
@endphp

<div class="dropdown relative inline-flex" style="{{ $placement }}">
    <div class="dropdown-toggle" aria-haspopup="menu" aria-expanded="false">
        {{ $trigger }}
    </div>
    <div class="dropdown-menu dropdown-open:opacity-100 hidden min-w-48 rounded-xl bg-base-100 p-1.5 shadow-xl border border-base-content/10 z-[100]" role="menu">
        {{ $slot }}
    </div>
</div>
