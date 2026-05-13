<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Components - Anggi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cream min-h-screen p-10">

    <div class="max-w-4xl mx-auto space-y-12">

        {{-- Header --}}
        <div class="text-center">
            <x-brand.wordmark class="justify-center" />
            <p class="text-cream-600 text-sm mt-3">Component Showcase · Testing Page</p>
        </div>

        <x-divider-gold />

        {{-- Section: Brand Components --}}
        <div class="bg-white border border-cream-300 p-10">
            <x-section-heading
                eyebrow="Section 1"
                title="Brand Components"
                sub="Logo mark dengan dan tanpa wordmark, dalam berbagai ukuran." />

            <div class="flex items-center gap-8 flex-wrap">
                <x-brand.logo-mark :size="40" />
                <x-brand.logo-mark :size="60" />
                <x-brand.logo-mark :size="80" />
                <x-brand.wordmark />
                <div class="bg-navy p-4">
                    <x-brand.wordmark light />
                </div>
            </div>
        </div>

        {{-- Section: Form Components --}}
        <x-form-card eyebrow="Section 2" title="Form Components Test">
            <x-slot:icon>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                </svg>
            </x-slot:icon>

            <div class="grid md:grid-cols-2 gap-5">
                <x-form-field
                    label="Nama Lengkap"
                    name="nama_test"
                    placeholder="Contoh: Andi Pratama"
                    required
                    full />

                <x-form-field
                    label="Email"
                    name="email_test"
                    type="email"
                    placeholder="email@contoh.com" />

                <x-form-field
                    label="Tanggal Lahir"
                    name="tanggal_test"
                    type="date" />

                <x-form-field
                    label="Helper Test"
                    name="helper_test"
                    placeholder="Field dengan help text"
                    help="Ini adalah helper text di bawah field"
                    full />
            </div>
        </x-form-card>

        {{-- Section: Color Palette --}}
        <div class="bg-white border border-cream-300 p-10">
            <x-section-heading
                eyebrow="Section 3"
                title="Color Palette"
                sub="Brand colors: Navy + Gold + Cream." />

            <div class="grid grid-cols-4 gap-4">
                <div class="aspect-square bg-navy rounded-sm flex items-end p-3">
                    <span class="text-cream text-xs">Navy</span>
                </div>
                <div class="aspect-square bg-gold rounded-sm flex items-end p-3">
                    <span class="text-navy text-xs">Gold</span>
                </div>
                <div class="aspect-square bg-cream border border-cream-300 rounded-sm flex items-end p-3">
                    <span class="text-navy text-xs">Cream</span>
                </div>
                <div class="aspect-square bg-paper border border-cream-300 rounded-sm flex items-end p-3">
                    <span class="text-navy text-xs">Paper</span>
                </div>
            </div>
        </div>

        {{-- Section: Buttons --}}
        <div class="bg-white border border-cream-300 p-10">
            <x-section-heading
                eyebrow="Section 4"
                title="Button Styles" />

            <div class="flex gap-3 flex-wrap">
                <button class="btn-primary">
                    Primary Button
                </button>
                <button class="btn-outline">
                    Outline Button
                </button>
            </div>
        </div>

        {{-- Section: Ornament Demo --}}
        <div class="relative overflow-hidden bg-navy p-16 min-h-[300px]">
            <x-ornament-circles class="-top-10 -right-10" :size="400" :opacity="0.08" />
            <x-ornament-circles class="bottom-0 left-0" :size="300" :opacity="0.05" />

            <div class="relative z-10">
                <x-brand.wordmark light />
                <h2 class="font-display text-4xl text-cream mt-8 leading-tight max-w-lg">
                    Karier yang berarti
                    <span class="text-gold italic">dimulai dari sini.</span>
                </h2>
                <p class="text-cream/70 text-sm mt-4 max-w-md leading-relaxed">
                    Daftarkan profil Anda sekali, dan biarkan kami menghubungkan kompetensi Anda dengan perusahaan yang tepat.
                </p>
            </div>
        </div>

    </div>

</body>
</html>
