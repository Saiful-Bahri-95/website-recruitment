@props([
    'light' => false,
    'size' => 36,
])

<div class="flex items-center gap-3">
    <x-brand.logo-mark :size="$size" />

    <div class="leading-tight">
        <div class="font-display tracking-[0.18em] text-[15px] font-semibold {{ $light ? 'text-cream' : 'text-navy' }}">
            ANUGERAH
        </div>
        <div class="tracking-[0.32em] text-[9px] font-medium text-gold">
            GLOBAL · RECRUITMENT
        </div>
    </div>
</div>
