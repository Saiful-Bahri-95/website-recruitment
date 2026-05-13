@props([
    'eyebrow' => null,
    'title',
    'icon' => null,
])

<div class="bg-white border border-cream-300">
    <div class="px-7 py-5 border-b border-cream-300 flex items-center gap-3">
        @if($icon)
            <div class="w-9 h-9 flex items-center justify-center bg-navy text-gold shrink-0">
                {!! $icon !!}
            </div>
        @endif

        <div>
            @if($eyebrow)
                <div class="text-[10px] tracking-wider-2 uppercase text-gold-600">
                    {{ $eyebrow }}
                </div>
            @endif
            <div class="font-display text-lg text-navy font-semibold">
                {{ $title }}
            </div>
        </div>
    </div>

    <div class="p-7">
        {{ $slot }}
    </div>
</div>
