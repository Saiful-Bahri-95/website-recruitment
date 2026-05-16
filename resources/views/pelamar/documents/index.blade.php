<x-app-layout>
    <x-slot name="title">Upload Dokumen - {{ config('app.name') }}</x-slot>

    <x-user-shell :activeStep="2">

        {{-- ============ PAGE HEADER ============ --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="h-px w-10 bg-gradient-to-r from-gold to-transparent"></div>
                    <span class="text-[10px] tracking-eyebrow font-semibold uppercase text-gold-600">
                        Tahap 2 dari 4
                    </span>
                </div>
                <h1 class="font-display text-4xl text-navy font-semibold leading-tight">
                    Unggah Dokumen Pendukung
                </h1>
                <p class="text-base text-cream-600 mt-3 max-w-2xl leading-relaxed">
                    Format file: <strong class="text-navy">PDF, JPG, atau PNG</strong>. Maksimal <strong class="text-navy">5MB</strong> per berkas. Pastikan dokumen terbaca dengan jelas.
                </p>
            </div>

            {{-- Progress Card --}}
            @php
                $requiredCount = count($requiredTypes);
                $uploadedRequiredCount = $documents->filter(fn($d) => in_array($d->type, $requiredTypes))->count();
                $totalUploaded = $documents->count();
                $progress = $requiredCount > 0 ? round(($uploadedRequiredCount / $requiredCount) * 100) : 0;
            @endphp

            <div class="bg-white rounded-2xl border border-cream-300 p-6 min-w-[280px] shadow-brand-md">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 font-semibold">Progres Wajib</div>
                    <div class="w-8 h-8 rounded-full bg-gradient-gold-soft flex items-center justify-center">
                        <svg class="w-4 h-4 text-gold-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75"/>
                        </svg>
                    </div>
                </div>

                <div class="flex items-baseline gap-2 mb-3">
                    <span class="font-display text-4xl text-navy font-bold">{{ $uploadedRequiredCount }}</span>
                    <span class="text-sm text-cream-500">/ {{ $requiredCount }} dokumen</span>
                </div>

                <div class="h-2 w-full bg-paper rounded-full overflow-hidden">
                    <div
                        class="h-full bg-gradient-to-r from-gold-400 to-gold-600 rounded-full transition-all duration-700 ease-out"
                        style="width: {{ $progress }}%"
                    ></div>
                </div>

                <div class="mt-3 text-xs text-cream-500">
                    {{ $progress }}% dokumen wajib selesai
                </div>
            </div>
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

        {{-- ============ ERROR MESSAGE ============ --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-300 rounded-2xl p-5 flex items-start gap-4 shadow-brand-sm">
                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-red-900 text-sm">Upload Gagal</div>
                    <ul class="text-xs text-red-700 mt-1 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ============ DOCUMENT CARDS GRID ============ --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 mb-12">

            @foreach($types as $typeKey => $typeLabel)
                @php
                    $isRequired = in_array($typeKey, $requiredTypes);
                    $document = $documents->get($typeKey);
                    $isUploaded = $document !== null;
                    $hint = $hints[$typeKey] ?? '';
                @endphp

                <div @class([
                    'relative bg-white rounded-2xl p-6 transition-all duration-300 overflow-hidden',
                    'border-2 border-gold shadow-gold-glow' => $isUploaded,
                    'border border-cream-300 shadow-brand-sm hover:shadow-brand-md hover:border-cream-400' => !$isUploaded,
                ])>

                    {{-- Uploaded Badge --}}
                    @if($isUploaded)
                        <div class="absolute top-0 right-0 px-3 py-1.5 text-[9px] tracking-wider-2 uppercase font-bold bg-gradient-to-br from-gold to-gold-600 text-navy rounded-bl-xl">
                            ✓ Terupload
                        </div>
                    @endif

                    {{-- Header --}}
                    <div class="flex items-start gap-3 mb-4">
                        <div @class([
                            'w-11 h-11 rounded-xl flex items-center justify-center shrink-0',
                            'bg-gradient-to-br from-gold/20 to-gold/10 text-gold-700' => $isUploaded,
                            'bg-paper text-cream-600' => !$isUploaded,
                        ])>
                            @if($isUploaded)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                {{-- Icon per document type --}}
                                @if(in_array($typeKey, ['pas_foto']))
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                    </svg>
                                @elseif(in_array($typeKey, ['ktp', 'npwp', 'kartu_kuning']))
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 010 1.954l-7.108 4.061A1.125 1.125 0 013 16.811V8.69zM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 010 1.954l-7.108 4.061a1.125 1.125 0 01-1.683-.977V8.69z"/>
                                    </svg>
                                @elseif(in_array($typeKey, ['ijazah', 'transkrip_nilai']))
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0015.482 0M9.75 21l4.5-4.5m-4.5 4.5h-3.75c-1.243 0-2.25-1.007-2.25-2.25V18c0-1.243 1.007-2.25 2.25-2.25H9.75M18 11.25l4.5 4.5"/>
                                    </svg>
                                @elseif(in_array($typeKey, ['skck', 'bpjs_kesehatan', 'vaksin']))
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296A3.745 3.745 0 018.932 4.593 3.745 3.745 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                                    </svg>
                                @elseif(in_array($typeKey, ['paklaring']))
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                    </svg>
                                @endif
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-sm font-semibold text-navy leading-tight">
                                    {{ $typeLabel }}
                                </h4>
                                @if(!$isRequired)
                                    <span class="text-[9px] tracking-wider-2 uppercase font-semibold px-2 py-0.5 bg-paper text-cream-600 rounded-full">
                                        Opsional
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs mt-1 leading-relaxed text-cream-600">
                                {{ $hint }}
                            </p>
                        </div>
                    </div>

                    {{-- Uploaded State: File info + actions --}}
                    @if($isUploaded)
                        <div class="pt-3 border-t border-cream-300">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-gold-600 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                                <span class="text-xs text-navy truncate flex-1 font-medium">
                                    {{ Str::limit($document->original_name, 28) }}
                                </span>
                                <span class="text-[10px] text-cream-500">
                                    {{ $document->getFormattedSize() }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                {{-- Replace button --}}
                                <button
                                    type="button"
                                    onclick="document.getElementById('upload-{{ $typeKey }}').click()"
                                    class="flex-1 text-xs tracking-wider uppercase font-semibold text-navy hover:text-gold-600 px-3 py-2 rounded-lg border border-cream-300 hover:border-gold transition-all"
                                >
                                    Ganti File
                                </button>

                                {{-- Delete button --}}
                                <form method="POST" action="{{ route('documents.destroy', $document) }}"
                                      onsubmit="return confirm('Hapus dokumen {{ $typeLabel }}?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all"
                                            title="Hapus dokumen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M5.79 5.79l1.05 13.88a2.25 2.25 0 002.244 2.078h6.832a2.25 2.25 0 002.244-2.077l1.05-13.88"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Empty State: Upload button --}}
                        <button
                            type="button"
                            onclick="document.getElementById('upload-{{ $typeKey }}').click()"
                            class="w-full border-2 border-dashed border-cream-300 hover:border-gold hover:bg-paper/50 py-4 text-xs tracking-wider uppercase font-semibold text-cream-600 hover:text-navy rounded-xl flex items-center justify-center gap-2 transition-all"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                            </svg>
                            Pilih Berkas
                        </button>
                    @endif

                    {{-- Hidden Upload Form (one per document type) --}}
                    <form
                        method="POST"
                        action="{{ route('documents.upload') }}"
                        enctype="multipart/form-data"
                        id="form-{{ $typeKey }}"
                        class="hidden"
                    >
                        @csrf
                        <input type="hidden" name="type" value="{{ $typeKey }}">
                        <input
                            type="file"
                            name="file"
                            id="upload-{{ $typeKey }}"
                            accept=".pdf,.jpg,.jpeg,.png"
                            onchange="this.form.submit()"
                        >
                    </form>
                </div>
            @endforeach

        </div>

        {{-- ============ INFO BOX ============ --}}
        <div class="bg-gradient-to-r from-white to-paper rounded-2xl border border-gold/40 p-7 mb-12 shadow-brand-md flex items-start gap-5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-gold to-gold-600 flex items-center justify-center shadow-gold-glow shrink-0">
                <svg class="w-6 h-6 text-cream" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 mb-1 font-semibold">Tips Upload</div>
                <h4 class="font-display text-lg text-navy font-semibold mb-2">
                    Pastikan Dokumen Berkualitas
                </h4>
                <ul class="text-sm text-cream-600 leading-relaxed space-y-1 list-disc list-inside marker:text-gold">
                    <li>Scan/foto dengan pencahayaan cukup, tidak buram</li>
                    <li>Ukuran maksimal <strong class="text-navy">5MB</strong> per file</li>
                    <li>Format yang didukung: <strong class="text-navy">PDF, JPG, PNG</strong></li>
                    <li>Pastikan semua informasi terbaca dengan jelas</li>
                </ul>
            </div>
        </div>

        {{-- ============ ACTION BUTTONS ============ --}}
        <div class="flex flex-col-reverse sm:flex-row sm:justify-between items-stretch sm:items-center gap-3 pt-4 border-t border-cream-300">
            <a href="{{ route('biodata.edit') }}"
               class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-sm tracking-wider uppercase font-medium text-navy border border-cream-300 rounded-xl hover:bg-white transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Biodata
            </a>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-sm tracking-wider uppercase font-semibold bg-gradient-navy text-cream rounded-xl shadow-navy-glow hover:shadow-brand-xl hover:-translate-y-0.5 transition-all group">
                Lanjut ke Pembayaran
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

    </x-user-shell>
</x-app-layout>
