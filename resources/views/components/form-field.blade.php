@props([
    'label',
    'name',
    'type' => 'text',
    'placeholder' => '',
    'value' => null,
    'icon' => null,
    'required' => false,
    'full' => false,
    'help' => null,
])

<div class="{{ $full ? 'col-span-2' : '' }}">
    <label for="{{ $name }}" class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-gold-600">*</span>
        @endif
    </label>

    <div class="relative">
        @if($icon)
            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-cream-500 pointer-events-none">
                {!! $icon !!}
            </div>
        @endif

        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full ' . ($icon ? 'pl-10' : 'pl-4') . ' pr-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all'
            ]) }}
        />
    </div>

    @if($help)
        <p class="mt-1.5 text-xs text-cream-600">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>
