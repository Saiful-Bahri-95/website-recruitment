@props([
    'opacity' => 0.07,
    'size' => 500,
    'class' => '',
])

<svg
    class="absolute pointer-events-none {{ $class }}"
    width="{{ $size }}"
    height="{{ $size }}"
    viewBox="0 0 200 200"
    style="opacity: {{ $opacity }};"
    xmlns="http://www.w3.org/2000/svg"
    aria-hidden="true">
    <circle cx="100" cy="100" r="80" stroke="#C9A961" stroke-width="0.3" fill="none" />
    <circle cx="100" cy="100" r="60" stroke="#C9A961" stroke-width="0.3" fill="none" />
    <circle cx="100" cy="100" r="40" stroke="#C9A961" stroke-width="0.3" fill="none" />
</svg>
