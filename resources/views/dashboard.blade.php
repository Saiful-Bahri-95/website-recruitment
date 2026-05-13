<x-app-layout>
    <x-slot name="title">Dashboard - {{ config('app.name') }}</x-slot>

    <x-user-shell>

        {{-- Top decorative gradient --}}
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-navy via-gold to-navy"></div>

        {{-- ============ WELCOME HEADER ============ --}}
        <div class="mb-12">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">

                {{-- Left: Welcome text --}}
                <div class="animate-slide-up">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="h-px w-10 bg-gradient-to-r from-gold to-transparent"></div>
                        <span class="text-[10px] tracking-eyebrow font-semibold uppercase text-gold-600">
                            Dashboard Pelamar
                        </span>
                    </div>
                    <h1 class="font-display text-4xl md:text-5xl text-navy font-semibold leading-[1.1] tracking-tight">
                        Selamat datang,
                        <span class="text-gold italic">{{ explode(' ', auth()->user()->name)[0] }}.</span>
                    </h1>
                    <p class="text-base text-cream-600 mt-4 max-w-2xl leading-relaxed">
                        Lengkapi tahapan di bawah untuk menyelesaikan aplikasi Anda. Tim verifikasi akan meninjau profil dalam <strong class="text-navy">1×24 jam kerja</strong> setelah pembayaran terkonfirmasi.
                    </p>
                </div>

                {{-- Right: Progress Card --}}
                @php
                    $stepsCompleted = 0; // Placeholder
                    $totalSteps = 4;
                    $progress = round(($stepsCompleted / $totalSteps) * 100);
                @endphp

                <div class="bg-white rounded-2xl border border-cream-300 p-6 min-w-[280px] shadow-brand-md">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 font-semibold">Progress Aplikasi</div>
                        <div class="w-8 h-8 rounded-full bg-gradient-gold-soft flex items-center justify-center">
                            <svg class="w-4 h-4 text-gold-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-baseline gap-2 mb-3">
                        <span class="font-display text-4xl text-navy font-bold">{{ $stepsCompleted }}</span>
                        <span class="text-sm text-cream-500">/ {{ $totalSteps }} tahap selesai</span>
                    </div>

                    {{-- Progress bar dengan gradient --}}
                    <div class="h-2 w-full bg-paper rounded-full overflow-hidden">
                        <div
                            class="h-full bg-gradient-to-r from-gold-400 to-gold-600 rounded-full transition-all duration-700 ease-out"
                            style="width: {{ $progress }}%"
                        ></div>
                    </div>

                    <div class="mt-3 text-xs text-cream-500">
                        {{ $progress }}% selesai
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ 4 STEP CARDS - MODERN GRID ============ --}}
        <div class="grid md:grid-cols-2 gap-6 mb-12">

            {{-- STEP 1: BIODATA --}}
            <a href="{{ route('biodata.edit') }}" class="group block">
                <div class="relative bg-white rounded-2xl border border-cream-300 p-7 transition-all duration-300
                            hover:border-gold hover:shadow-gold-glow hover:-translate-y-1
                            overflow-hidden">

                    {{-- Decorative gold accent corner --}}
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-gold/10 to-transparent rounded-bl-full pointer-events-none"></div>

                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-14 h-14 rounded-xl bg-gradient-navy flex items-center justify-center shadow-navy-glow">
                                <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                            </div>
                            <span class="text-[9px] tracking-wider-2 uppercase font-semibold px-3 py-1.5 bg-paper text-cream-600 rounded-full">
                                Wajib
                            </span>
                        </div>

                        <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 mb-1 font-semibold">Tahap 1</div>
                        <h3 class="font-display text-2xl text-navy font-semibold mb-2 leading-tight">
                            Lengkapi Biodata Diri
                        </h3>
                        <p class="text-sm text-cream-600 leading-relaxed mb-6">
                            Isi data pribadi, riwayat pendidikan, pengalaman kerja, dan kontak darurat sesuai dokumen resmi.
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t border-cream-300/50">
                            <span class="text-xs tracking-wider uppercase font-semibold text-navy group-hover:text-gold-600 transition-colors">
                                Mulai Isi Biodata
                            </span>
                            <div class="w-8 h-8 rounded-full bg-paper group-hover:bg-gold flex items-center justify-center transition-all">
                                <svg class="w-4 h-4 text-navy group-hover:text-navy transition-all group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            {{-- STEP 2: DOKUMEN --}}
            <a href="#dokumen" class="group block">
                <div class="relative bg-white rounded-2xl border border-cream-300 p-7 transition-all duration-300
                            hover:border-gold hover:shadow-gold-glow hover:-translate-y-1
                            overflow-hidden">

                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-gold/10 to-transparent rounded-bl-full pointer-events-none"></div>

                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-14 h-14 rounded-xl bg-gradient-navy flex items-center justify-center shadow-navy-glow">
                                <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            </div>
                            <span class="text-[9px] tracking-wider-2 uppercase font-semibold px-3 py-1.5 bg-paper text-cream-600 rounded-full">
                                12 Dokumen
                            </span>
                        </div>

                        <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 mb-1 font-semibold">Tahap 2</div>
                        <h3 class="font-display text-2xl text-navy font-semibold mb-2 leading-tight">
                            Unggah Dokumen Pendukung
                        </h3>
                        <p class="text-sm text-cream-600 leading-relaxed mb-6">
                            Upload pas foto, KTP, ijazah, SKCK, dan dokumen pendukung lainnya. Format PDF/JPG/PNG, maks 5MB.
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t border-cream-300/50">
                            <span class="text-xs tracking-wider uppercase font-semibold text-navy group-hover:text-gold-600 transition-colors">
                                Upload Dokumen
                            </span>
                            <div class="w-8 h-8 rounded-full bg-paper group-hover:bg-gold flex items-center justify-center transition-all">
                                <svg class="w-4 h-4 text-navy transition-all group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            {{-- STEP 3: PEMBAYARAN --}}
            <a href="#pembayaran" class="group block">
                <div class="relative bg-white rounded-2xl border border-cream-300 p-7 transition-all duration-300
                            hover:border-gold hover:shadow-gold-glow hover:-translate-y-1
                            overflow-hidden">

                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-gold/10 to-transparent rounded-bl-full pointer-events-none"></div>

                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-14 h-14 rounded-xl bg-gradient-navy flex items-center justify-center shadow-navy-glow">
                                <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                                </svg>
                            </div>
                            <span class="text-[9px] tracking-wider-2 uppercase font-semibold px-3 py-1.5 bg-gradient-gold-soft text-gold-700 rounded-full">
                                Rp 350.000
                            </span>
                        </div>

                        <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 mb-1 font-semibold">Tahap 3</div>
                        <h3 class="font-display text-2xl text-navy font-semibold mb-2 leading-tight">
                            Pembayaran Administrasi
                        </h3>
                        <p class="text-sm text-cream-600 leading-relaxed mb-6">
                            Transfer biaya pemrosesan berkas ke rekening yang tersedia, lalu unggah bukti pembayaran.
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t border-cream-300/50">
                            <span class="text-xs tracking-wider uppercase font-semibold text-navy group-hover:text-gold-600 transition-colors">
                                Lihat Info Pembayaran
                            </span>
                            <div class="w-8 h-8 rounded-full bg-paper group-hover:bg-gold flex items-center justify-center transition-all">
                                <svg class="w-4 h-4 text-navy transition-all group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            {{-- STEP 4: VERIFIKASI --}}
            <div class="group block">
                <div class="relative bg-white rounded-2xl border border-cream-300 p-7 transition-all duration-300
                            hover:border-gold hover:shadow-gold-glow hover:-translate-y-1
                            overflow-hidden">

                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-gold/10 to-transparent rounded-bl-full pointer-events-none"></div>

                    {{-- Shimmer effect overlay --}}
                    <div class="absolute inset-0 animate-shimmer opacity-30 pointer-events-none"></div>

                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-cream-400 to-cream-500 flex items-center justify-center">
                                <svg class="w-6 h-6 text-navy/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                                </svg>
                            </div>
                            <span class="text-[9px] tracking-wider-2 uppercase font-semibold px-3 py-1.5 bg-paper text-cream-600 rounded-full">
                                1×24 Jam
                            </span>
                        </div>

                        <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 mb-1 font-semibold">Tahap 4</div>
                        <h3 class="font-display text-2xl text-navy/70 font-semibold mb-2 leading-tight">
                            Verifikasi Admin
                        </h3>
                        <p class="text-sm text-cream-600 leading-relaxed mb-6">
                            Tim verifikasi akan meninjau kelengkapan biodata, dokumen, dan pembayaran Anda. Notifikasi via email.
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t border-cream-300/50">
                            <span class="text-xs tracking-wider uppercase font-semibold text-cream-500">
                                Otomatis Setelah Submit
                            </span>
                            <div class="w-8 h-8 rounded-full bg-paper flex items-center justify-center">
                                <svg class="w-4 h-4 text-cream-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ INFO BOX - PREMIUM ALERT ============ --}}
        <div class="relative bg-gradient-to-r from-white to-paper rounded-2xl border border-gold/40 p-7 mb-12 overflow-hidden shadow-brand-md">

            {{-- Decorative gold gradient blob --}}
            <div class="absolute -left-20 -top-20 w-60 h-60 bg-gradient-to-br from-gold/20 to-transparent rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative flex items-start gap-5">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gold to-gold-600 flex items-center justify-center shadow-gold-glow shrink-0">
                    <svg class="w-7 h-7 text-cream" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 mb-1 font-semibold">Informasi Penting</div>
                    <h4 class="font-display text-xl text-navy font-semibold mb-2">
                        Mulai dari Tahap 1 — Biodata Diri
                    </h4>
                    <p class="text-sm text-cream-600 leading-relaxed">
                        Pastikan semua data yang Anda isi <strong class="text-navy">sesuai dengan dokumen resmi</strong> (KTP, ijazah, dll). Data yang tidak akurat dapat memperlambat proses verifikasi atau menyebabkan aplikasi ditolak.
                    </p>
                </div>
            </div>
        </div>

        {{-- ============ QUICK STATS GRID ============ --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            @php
                $stats = [
                    ['label' => 'Total Pelamar', 'value' => '247', 'icon' => 'users', 'color' => 'navy'],
                    ['label' => 'Mitra HRD', 'value' => '85+', 'icon' => 'building', 'color' => 'gold'],
                    ['label' => 'Tingkat Salur', 'value' => '94%', 'icon' => 'trend', 'color' => 'navy'],
                    ['label' => 'Verifikasi Cepat', 'value' => '24h', 'icon' => 'clock', 'color' => 'gold'],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="bg-white rounded-xl border border-cream-300 p-5 shadow-brand-sm hover:shadow-brand-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <div @class([
                            'w-9 h-9 rounded-lg flex items-center justify-center',
                            'bg-navy/5 text-navy' => $stat['color'] === 'navy',
                            'bg-gold/10 text-gold-600' => $stat['color'] === 'gold',
                        ])>
                            @switch($stat['icon'])
                                @case('users')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    @break
                                @case('building')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    @break
                                @case('trend')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    @break
                                @case('clock')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    <div class="font-display text-2xl text-navy font-bold leading-none">{{ $stat['value'] }}</div>
                    <div class="text-xs text-cream-600 mt-1 tracking-wide">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- ============ DECORATIVE FOOTER ============ --}}
        <div class="text-center pt-8">
            <div class="inline-flex items-center gap-3">
                <div class="h-px w-12 bg-gradient-to-r from-transparent to-gold/40"></div>
                <x-brand.logo-mark :size="24" />
                <div class="h-px w-12 bg-gradient-to-l from-transparent to-gold/40"></div>
            </div>
            <p class="text-xs tracking-wider uppercase text-cream-500 mt-4 font-medium">
                PT Anugerah Global Recruitment · Surabaya, Indonesia
            </p>
        </div>

    </x-user-shell>
</x-app-layout>
