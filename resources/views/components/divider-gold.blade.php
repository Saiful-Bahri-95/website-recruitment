@props([
    'width' => 'full',  {{-- 'full', 'short' (w-8), atau 'medium' (w-32) --}}
])

@php
    $widthClass = match($width) {
        'short' => 'w-8',
        'medium' => 'w-32',
        default => 'w-full',
    };
@endphp

<div class="h-px {{ $widthClass }} bg-gradient-to-r from-transparent via-gold to-transparent my-8"></div>
