<x-app-layout>
    <x-user-shell :activeStep="4">

        <div class="max-w-3xl mx-auto px-6 lg:px-10 py-10">

            {{-- ============ HEADER ============ --}}
            <div class="text-center mb-10">
                <div class="text-[11px] tracking-[0.2em] uppercase text-gold-600 font-semibold mb-2">
                    Status Lamaran
                </div>
                <h1 class="font-display text-3xl md:text-4xl text-navy font-bold leading-tight">
                    Progres Verifikasi Anda
                </h1>
                <p class="text-sm text-cream-600 mt-3 max-w-md mx-auto">
                    Pantau perkembangan lamaran Anda di PT Anggita Global Recruitment di sini.
                </p>
            </div>

            {{-- ============ STATUS BADGE ============ --}}
            @php
                $badgeStyles = [
                    'gray'    => 'bg-cream-100 text-cream-700 border-cream-300',
                    'amber'   => 'bg-amber-50 text-amber-700 border-amber-200',
                    'blue'    => 'bg-blue-50 text-blue-700 border-blue-200',
                    'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'red'     => 'bg-red-50 text-red-700 border-red-200',
                ];
                $badgeClass = $badgeStyles[$statusColor] ?? $badgeStyles['gray'];
            @endphp

            <div class="flex justify-center mb-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border {{ $badgeClass }}">
                    <span class="h-2 w-2 rounded-full bg-current"></span>
                    <span class="text-sm font-medium">{{ $statusLabel }}</span>
                </div>
            </div>

            {{-- ============ PANEL CATATAN ADMIN (REVISI / DITOLAK) ============ --}}
            @if($adminNotes && in_array($status, ['rejected']))
                <div class="mb-10 rounded-lg border border-red-200 bg-red-50 p-6">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-display text-lg text-navy font-semibold mb-1">
                                Catatan dari Tim Rekrutmen
                            </h3>
                            <p class="text-sm text-red-800 leading-relaxed whitespace-pre-line">{{ $adminNotes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ============ TIMELINE 4 STEP ============ --}}
            <div class="bg-white rounded-lg border border-cream-300 p-8">
                <div class="space-y-0">
                    @foreach($steps as $i => $step)
                        @php
                            $isLast = $i === count($steps) - 1;
                            $stateStyles = match($step['state']) {
                                'done'     => ['circle' => 'bg-navy text-gold border-navy', 'line' => 'bg-navy', 'title' => 'text-navy', 'desc' => 'text-cream-600'],
                                'active'   => ['circle' => 'bg-gold text-navy border-gold', 'line' => 'bg-cream-300', 'title' => 'text-navy', 'desc' => 'text-cream-600'],
                                'upcoming' => ['circle' => 'bg-cream-100 text-cream-400 border-cream-300', 'line' => 'bg-cream-300', 'title' => 'text-cream-400', 'desc' => 'text-cream-400'],
                            };
                        @endphp

                        <div class="flex gap-5">
                            {{-- Indikator: lingkaran + garis --}}
                            <div class="flex flex-col items-center">
                                <div class="relative shrink-0 w-11 h-11 rounded-full border-2 flex items-center justify-center {{ $stateStyles['circle'] }}">
                                    @if($step['state'] === 'done')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <span class="font-display text-sm font-bold">{{ $i + 1 }}</span>
                                    @endif

                                    {{-- Pulse animation untuk step aktif --}}
                                    @if($step['state'] === 'active')
                                        <span class="absolute inset-0 rounded-full bg-gold animate-ping opacity-40"></span>
                                    @endif
                                </div>

                                {{-- Garis penghubung ke step berikutnya --}}
                                @if(!$isLast)
                                    <div class="w-0.5 flex-1 min-h-[3rem] my-1 {{ $stateStyles['line'] }}"></div>
                                @endif
                            </div>

                            {{-- Konten step --}}
                            <div class="flex-1 pb-8 {{ $isLast ? 'pb-0' : '' }}">
                                <div class="flex items-center gap-3">
                                    <h3 class="font-display text-lg font-semibold {{ $stateStyles['title'] }}">
                                        {{ $step['title'] }}
                                    </h3>
                                    @if($step['state'] === 'active')
                                        <span class="text-[10px] tracking-wider uppercase text-gold-600 font-semibold px-2 py-0.5 bg-gold/10 rounded">
                                            Sedang Berjalan
                                        </span>
                                    @endif
                                    @if($step['state'] === 'done')
                                        <span class="text-[10px] tracking-wider uppercase text-navy/60 font-semibold">
                                            Selesai
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm mt-1 {{ $stateStyles['desc'] }}">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ============ PESAN KONDISIONAL ============ --}}
            @if($isVerified)
                <div class="mt-8 text-center">
                    <p class="text-sm text-emerald-700 font-medium">
                        Selamat! Lamaran Anda telah terverifikasi. Tim kami akan menghubungi Anda untuk tahap selanjutnya.
                    </p>
                </div>
            @elseif($isRejected)
                <div class="mt-8 text-center">
                    <p class="text-sm text-cream-600">
                        Silakan periksa catatan dari tim rekrutmen di atas. Hubungi kami jika ada pertanyaan.
                    </p>
                </div>
            @endif

            {{-- ============ DECORATIVE FOOTER ============ --}}
            <div class="text-center pt-10">
                <div class="inline-flex items-center gap-3">
                    <div class="h-px w-12 bg-gradient-to-r from-transparent to-gold/40"></div>
                    <x-brand.logo-mark :size="24" />
                    <div class="h-px w-12 bg-gradient-to-l from-transparent to-gold/40"></div>
                </div>
            </div>

        </div>

    </x-user-shell>
</x-app-layout>
