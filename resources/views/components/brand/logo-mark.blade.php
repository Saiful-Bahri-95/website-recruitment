@props([
    'size' => 40,
])

<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 100 100" class="shrink-0" xmlns="http://www.w3.org/2000/svg">
    {{-- Letter A (navy) --}}
    <path d="M30 78 L48 22 L52 22 L70 78 L62 78 L57 62 L43 62 L38 78 Z M45 55 L55 55 L50 38 Z"
          fill="#0A2540"
          stroke="#0A2540"
          stroke-width="0.5" />

    {{-- Curved S (gold) --}}
    <path d="M40 50 Q55 35 75 42 Q72 50 65 52 Q62 60 70 65 Q60 72 50 65 Q45 60 50 55 Q42 56 40 50 Z"
          fill="#C9A961"
          opacity="0.95" />
</svg>
