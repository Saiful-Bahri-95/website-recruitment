<x-app-layout>
    <x-slot name="title">Pembayaran - {{ config('app.name') }}</x-slot>

    <x-user-shell :activeStep="3">

        {{-- ============ PAGE HEADER ============ --}}
        <div class="mb-10">
            <div class="flex items-center gap-2 mb-3">
                <div class="h-px w-10 bg-gradient-to-r from-gold to-transparent"></div>
                <span class="text-[10px] tracking-eyebrow font-semibold uppercase text-gold-600">
                    Tahap 3 dari 4
                </span>
            </div>
            <h1 class="font-display text-4xl text-navy font-semibold leading-tight">
                Selesaikan Pembayaran
            </h1>
            <p class="text-base text-cream-600 mt-3 max-w-2xl leading-relaxed">
                Biaya administrasi dan pemrosesan berkas. Setelah transfer, mohon unggah bukti pembayaran di bawah.
            </p>
        </div>

        {{-- ============ STATUS MESSAGE ============ --}}
        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-300 rounded-2xl p-5 flex items-start gap-4 shadow-brand-sm animate-slide-up">
                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-green-900 text-sm">Berhasil</div>
                    <div class="text-xs text-green-700 mt-0.5">{{ session('status') }}</div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-300 rounded-2xl p-5 shadow-brand-sm">
                <div class="font-semibold text-red-900 text-sm mb-2">Upload Gagal</div>
                <ul class="text-xs text-red-700 space-y-0.5 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ============ MAIN GRID ============ --}}
        <div class="grid lg:grid-cols-5 gap-6">

            {{-- ============ LEFT: BANK INFO ============ --}}
            <div class="lg:col-span-3 space-y-5">

                {{-- Invoice Card (Navy Premium) --}}
                <div class="relative overflow-hidden rounded-2xl bg-gradient-navy p-7 lg:p-9 shadow-navy-glow">

                    <div class="relative">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="h-px w-8 bg-gold"></div>
                            <span class="text-[10px] tracking-eyebrow uppercase font-semibold text-gold-300">
                                Nominal Pembayaran
                            </span>
                            <div class="h-px w-8 bg-gold"></div>
                        </div>

                        <div class="flex items-baseline gap-2">
                            <span class="text-sm text-gold-300">Rp</span>
                            <span class="font-display text-5xl lg:text-6xl text-cream font-bold tracking-tight" style="font-size: 3.75rem">
                                {{ number_format($amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="text-xs tracking-wider text-cream/60" style="color: rgba(250, 247, 240, 0.6);">
                            No. Invoice:
                            <span class="text-gold-300 font-medium">INV/{{ now()->format('Y/m') }}/{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-t border-gold/25" style=" margin-top: 0.75rem; padding-top: 0.75rem;">
                            <div>
                                <div class="text-[10px] tracking-wider-2 uppercase mb-1 text-cream/55">Nama Pelamar</div>
                                <div class="text-sm text-cream font-medium">{{ $user->name }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] tracking-wider-2 uppercase mb-1 text-cream/55">Batas Pembayaran</div>
                                <div class="text-sm text-cream font-medium">{{ now()->addDays($deadlineDays)->format('d M Y') }} - 23:59</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bank List --}}
                <div class="bg-white rounded-2xl border border-cream-300 shadow-brand overflow-hidden">
                    <div class="px-7 py-5 border-b border-cream-300 bg-gradient-to-r from-white to-paper/30">
                        <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 font-semibold">Transfer ke Rekening</div>
                        <div class="font-display text-lg text-navy font-semibold">Pilih salah satu metode</div>
                    </div>

                    <div class="divide-y divide-paper">
                        @foreach($banks as $bank)
                            <div class="px-7 py-5 flex items-center gap-5 hover:bg-paper/30 transition-colors group">
                                {{-- Bank Logo Circle --}}
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-xs shrink-0"
                                     style="background-color: {{ $bank['color'] }};">
                                    {{ $bank['short'] }}
                                </div>

                                {{-- Bank Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-navy">{{ $bank['bank'] }}</div>
                                    <div class="font-display text-xl text-navy tracking-wide font-semibold mt-0.5"
                                         x-data
                                         x-ref="acc{{ $loop->index }}">
                                        {{ $bank['account_number'] }}
                                    </div>
                                    <div class="text-[11px] text-cream-600 mt-0.5">
                                        a.n. {{ $bank['account_name'] }}
                                    </div>
                                </div>

                                {{-- Copy Button --}}
                                <button
                                    type="button"
                                    onclick="copyAccountNumber('{{ str_replace(['-', ' '], '', $bank['account_number']) }}', this)"
                                    class="p-2.5 hover:bg-gold/10 rounded-xl transition-all group/btn"
                                    title="Salin nomor rekening">
                                    <svg class="w-4 h-4 text-gold-600 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Warning Alert --}}
                <div class="flex items-start gap-3 p-5 rounded-2xl border border-gold/40 bg-gradient-gold-soft">
                    <svg class="w-5 h-5 text-gold-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.008v.008H12v-.008z"/>
                    </svg>
                    <div class="text-xs leading-relaxed text-navy">
                        <strong>Penting:</strong> Mohon transfer dengan jumlah <strong class="text-gold-700">tepat Rp {{ number_format($amount, 0, ',', '.') }}</strong> (jangan dibulatkan). Hindari metode QRIS atau e-wallet untuk mempercepat verifikasi.
                    </div>
                </div>
            </div>

            {{-- ============ RIGHT: UPLOAD PROOF FORM ============ --}}
            <div class="lg:col-span-2">

                @if($payment && $payment->status === 'verified')
                    {{-- VERIFIED STATE --}}
                    <div class="sticky top-24 bg-white rounded-2xl border-2 border-green-400 shadow-brand-lg overflow-hidden">
                        <div class="p-7 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <div class="text-[10px] tracking-wider-2 uppercase text-green-700 mb-1 font-semibold">Status Pembayaran</div>
                            <h3 class="font-display text-2xl text-navy font-semibold mb-2">Terverifikasi</h3>
                            <p class="text-sm text-cream-600">Pembayaran Anda telah dikonfirmasi oleh admin pada {{ $payment->verified_at?->format('d M Y, H:i') }} WIB.</p>
                        </div>
                    </div>

                @elseif($payment && $payment->status === 'pending')
                    {{-- PENDING STATE --}}
                    <div class="sticky top-24 bg-white rounded-2xl border-2 border-gold shadow-gold-glow overflow-hidden">
                        <div class="p-7 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4">
                                <svg class="w-8 h-8 text-gold-600 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 mb-1 font-semibold">Status Pembayaran</div>
                            <h3 class="font-display text-2xl text-navy font-semibold mb-2">Menunggu Verifikasi</h3>
                            <p class="text-sm text-cream-600 mb-5">Bukti pembayaran Anda sedang ditinjau oleh admin (1×24 jam kerja).</p>

                            <div class="space-y-2 text-xs text-left bg-paper/50 rounded-xl p-4">
                                <div class="flex justify-between">
                                    <span class="text-cream-600">Bank:</span>
                                    <span class="font-semibold text-navy">{{ $payment->bank_pengirim }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-cream-600">Pengirim:</span>
                                    <span class="font-semibold text-navy">{{ $payment->nama_pengirim }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-cream-600">Tanggal:</span>
                                    <span class="font-semibold text-navy">{{ $payment->paid_at?->format('d M Y, H:i') }} WIB</span>
                                </div>
                            </div>

                            <button
                                type="button"
                                onclick="document.getElementById('upload-form').classList.toggle('hidden')"
                                class="mt-5 text-xs tracking-wider uppercase font-semibold text-gold-700 hover:text-gold-900">
                                Ganti Bukti Transfer
                            </button>
                        </div>

                        {{-- Re-upload form (hidden by default) --}}
                        <div id="upload-form" class="hidden border-t border-cream-300 p-6">
                            @include('pelamar.payment._upload-form')
                        </div>
                    </div>

                @else
                    {{-- EMPTY STATE: Show form --}}
                    <div class="sticky top-24 bg-white rounded-2xl border border-cream-300 shadow-brand overflow-hidden">
                        <div class="px-6 py-5 border-b border-cream-300 bg-gradient-to-r from-white to-paper/30">
                            <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 font-semibold">Langkah Akhir</div>
                            <div class="font-display text-lg text-navy font-semibold">Unggah Bukti Pembayaran</div>
                        </div>

                        <div class="p-6">
                            @include('pelamar.payment._upload-form')
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ============ ACTION BUTTONS ============ --}}
        <div class="flex flex-col-reverse sm:flex-row sm:justify-between items-stretch sm:items-center gap-3 pt-10 mt-10 border-t border-cream-300">
            <a href="{{ route('documents.index') }}"
               class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-sm tracking-wider uppercase font-medium text-navy border border-cream-300 rounded-xl hover:bg-white transition-all" style="margin-top: 0.75rem;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Dokumen
            </a>

            <a href="{{ route('status') }}"
               class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-sm tracking-wider uppercase font-semibold bg-gradient-navy text-cream rounded-xl shadow-navy-glow hover:shadow-brand-xl hover:-translate-y-0.5 transition-all group" style="margin-top: 0.75rem;">
                Lihat Status Aplikasi
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

    </x-user-shell>

    @push('scripts')
        <script>
            function copyAccountNumber(number, button) {
                navigator.clipboard.writeText(number).then(() => {
                    const originalHTML = button.innerHTML;
                    button.innerHTML = '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';

                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                    }, 1500);
                });
            }
        </script>
    @endpush
</x-app-layout>
