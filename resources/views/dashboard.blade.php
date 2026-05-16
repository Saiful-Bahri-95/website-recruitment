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
                    $progressData = auth()->user()->getProgressData();
                    $stepsCompleted = $progressData['completed'];
                    $totalSteps = $progressData['total'];
                    $progress = $progressData['percentage'];
                    $appStatus = $progressData['status'];

                    // ===== Variasi card STEP 4 berdasarkan status aplikasi =====
                    $step4 = match($appStatus) {
                        'paid' => [
                            'variant'      => 'active',
                            'iconBg'       => 'bg-gradient-navy shadow-navy-glow',
                            'iconColor'    => 'text-gold',
                            'titleColor'   => 'text-navy',
                            'badgeText'    => 'Sedang Ditinjau',
                            'badgeBg'      => 'bg-gradient-gold-soft text-gold-700',
                            'actionLabel'  => 'Tinjau Status Lamaran',
                            'actionColor'  => 'text-navy group-hover:text-gold-600',
                            'arrowBg'      => 'bg-paper group-hover:bg-gold',
                            'arrowColor'   => 'text-navy',
                            'shimmer'      => false,
                        ],
                        'verified' => [
                            'variant'      => 'success',
                            'iconBg'       => 'bg-gradient-to-br from-green-500 to-green-600 shadow-brand-md',
                            'iconColor'    => 'text-white',
                            'titleColor'   => 'text-navy',
                            'badgeText'    => 'Lulus Verifikasi',
                            'badgeBg'      => 'bg-green-50 text-green-700 border border-green-200',
                            'actionLabel'  => 'Lihat Hasil Verifikasi',
                            'actionColor'  => 'text-green-700 group-hover:text-green-900',
                            'arrowBg'      => 'bg-green-50 group-hover:bg-green-500',
                            'arrowColor'   => 'text-green-700 group-hover:text-white',
                            'shimmer'      => false,
                        ],
                        'rejected' => [
                            'variant'      => 'alert',
                            'iconBg'       => 'bg-gradient-to-br from-red-500 to-red-600 shadow-brand-md',
                            'iconColor'    => 'text-white',
                            'titleColor'   => 'text-navy',
                            'badgeText'    => 'Perlu Perhatian',
                            'badgeBg'      => 'bg-red-50 text-red-700 border border-red-200',
                            'actionLabel'  => 'Lihat Catatan Admin',
                            'actionColor'  => 'text-red-700 group-hover:text-red-900',
                            'arrowBg'      => 'bg-red-50 group-hover:bg-red-500',
                            'arrowColor'   => 'text-red-700 group-hover:text-white',
                            'shimmer'      => false,
                        ],
                        default => [ // draft, submitted
                            'variant'      => 'idle',
                            'iconBg'       => 'bg-gradient-to-br from-cream-400 to-cream-500',
                            'iconColor'    => 'text-navy/40',
                            'titleColor'   => 'text-navy/70',
                            'badgeText'    => '1×24 Jam',
                            'badgeBg'      => 'bg-paper text-cream-600',
                            'actionLabel'  => 'Menunggu Tahapan Sebelumnya',
                            'actionColor'  => 'text-cream-500',
                            'arrowBg'      => 'bg-paper',
                            'arrowColor'   => 'text-cream-500',
                            'shimmer'      => true,
                        ],
                    };
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

        {{-- STEP 4: VERIFIKASI --}}
        <a href="{{ route('status') }}" class="group block">
            <div class="relative bg-white rounded-2xl border border-cream-300 p-7 transition-all duration-300
                        hover:border-gold hover:shadow-gold-glow hover:-translate-y-1
                        overflow-hidden" style="margin-bottom: 1.75rem";>

                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-gold/10 to-transparent rounded-bl-full pointer-events-none"></div>

                {{-- Shimmer hanya saat idle --}}
                @if($step4['shimmer'])
                    <div class="absolute inset-0 animate-shimmer opacity-30 pointer-events-none"></div>
                @endif

                <div class="relative">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center {{ $step4['iconBg'] }}">
                            @switch($step4['variant'])
                                @case('success')
                                    {{-- Checkmark untuk verified --}}
                                    <svg class="w-6 h-6 {{ $step4['iconColor'] }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                    </svg>
                                    @break
                                @case('alert')
                                    {{-- Warning untuk rejected --}}
                                    <svg class="w-6 h-6 {{ $step4['iconColor'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @break
                                @default
                                    {{-- Shield untuk idle & active --}}
                                    <svg class="w-6 h-6 {{ $step4['iconColor'] }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                                    </svg>
                            @endswitch
                        </div>
                        <span class="text-[9px] tracking-wider-2 uppercase font-semibold px-3 py-1.5 rounded-full {{ $step4['badgeBg'] }}">
                            {{ $step4['badgeText'] }}
                        </span>
                    </div>

                    <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 mb-1 font-semibold">Tahap 4</div>
                    <h3 class="font-display text-2xl font-semibold mb-2 leading-tight {{ $step4['titleColor'] }}">
                        Verifikasi Admin
                    </h3>
                    <p class="text-sm text-cream-600 leading-relaxed mb-6">
                        @switch($step4['variant'])
                            @case('active')
                                Berkas Anda sedang ditinjau oleh tim verifikasi. Estimasi 1×24 jam kerja.
                                @break
                            @case('success')
                                Selamat! Berkas Anda telah lolos verifikasi. Tim kami akan menghubungi untuk tahap selanjutnya.
                                @break
                            @case('alert')
                                Ada hal yang perlu diperhatikan. Buka halaman status untuk membaca catatan dari tim verifikasi.
                                @break
                            @default
                                Tim verifikasi akan meninjau kelengkapan biodata, dokumen, dan pembayaran Anda. Notifikasi via email.
                        @endswitch
                    </p>

                    <div class="flex items-center justify-between pt-4 border-t border-cream-300/50">
                        <span class="text-xs tracking-wider uppercase font-semibold transition-colors {{ $step4['actionColor'] }}">
                            {{ $step4['actionLabel'] }}
                        </span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all {{ $step4['arrowBg'] }}">
                            @if($step4['variant'] === 'idle')
                                {{-- Idle: jam, bukan panah --}}
                                <svg class="w-4 h-4 {{ $step4['arrowColor'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                {{-- Aktif/Success/Alert: panah --}}
                                <svg class="w-4 h-4 transition-all group-hover:translate-x-0.5 {{ $step4['arrowColor'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>

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
                PT Anggita Global Recruitment · Bekasi, Indonesia
            </p>
        </div>

    </x-user-shell>
</x-app-layout>
