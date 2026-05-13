@props([
    'eyebrow' => null,
    'title',
    'sub' => null,
    'center' => false,
])

<div class="mb-8 {{ $center ? 'text-center' : '' }}">
    @if($eyebrow)
        <div class="flex items-center gap-2 mb-2 {{ $center ? 'justify-center' : '' }}">
            <div class="h-px w-8 bg-gold"></div>
            <span class="text-[10px] font-medium uppercase tracking-eyebrow text-gold-600">
                {{ $eyebrow }}
            </span>
            @if($center)
                <div class="h-px w-8 bg-gold"></div>
            @endif
        </div>
    @endif

    <h2 class="font-display text-3xl md:text-4xl leading-tight text-navy font-semibold">
        {{ $title }}
    </h2>

    @if($sub)
        <p class="mt-2 text-sm text-cream-600 max-w-xl {{ $center ? 'mx-auto' : '' }} leading-relaxed">
            {{ $sub }}
        </p>
    @endif
</div>
