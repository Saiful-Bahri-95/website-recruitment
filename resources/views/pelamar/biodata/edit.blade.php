<x-app-layout>
    <x-slot name="title">Biodata - {{ config('app.name') }}</x-slot>

    <x-user-shell :activeStep="1">

        <form method="POST" action="{{ route('biodata.update') }}"
              x-data="biodataForm()"
              @submit="isSubmitting = true"
              class="space-y-8">
            @csrf
            @method('PUT')

            {{-- ============ PAGE HEADER ============ --}}
            <div class="mb-2">
                <div class="flex items-center gap-2 mb-3">
                    <div class="h-px w-10 bg-gradient-to-r from-gold to-transparent"></div>
                    <span class="text-[10px] tracking-eyebrow font-semibold uppercase text-gold-600">
                        Tahap 1 dari 4
                    </span>
                </div>
                <h1 class="font-display text-4xl text-navy font-semibold leading-tight">
                    Lengkapi Biodata Diri
                </h1>
                <p class="text-base text-cream-600 mt-3 max-w-2xl leading-relaxed">
                    Pastikan seluruh informasi sesuai dengan <strong class="text-navy">dokumen resmi</strong> (KTP, ijazah). Data ini akan disampaikan kepada perusahaan tujuan.
                </p>
            </div>

            {{-- Error Summary --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-300 rounded-2xl p-6 shadow-brand-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-red-900 mb-2">Ada {{ $errors->count() }} kesalahan pada form:</h3>
                            <ul class="text-sm text-red-800 space-y-1 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ============ SECTION A: IDENTITAS PRIBADI ============ --}}
            <div class="bg-white rounded-2xl border border-cream-300 shadow-brand overflow-hidden">
                {{-- Header --}}
                <div class="px-7 py-5 border-b border-cream-300 flex items-center gap-4 bg-gradient-to-r from-white to-paper/30">
                    <div class="w-12 h-12 rounded-xl bg-gradient-navy flex items-center justify-center shadow-navy-glow shrink-0">
                        <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 font-semibold">Bagian A</div>
                        <div class="font-display text-xl text-navy font-semibold">Identitas Pribadi</div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-7">
                    <div class="grid md:grid-cols-2 gap-5">

                        {{-- Nama Lengkap (full width) --}}
                        <div class="md:col-span-2">
                            <label for="nama_lengkap" class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                Nama Lengkap (sesuai KTP) <span class="text-gold-600">*</span>
                            </label>
                            <input
                                type="text"
                                id="nama_lengkap"
                                name="nama_lengkap"
                                value="{{ old('nama_lengkap', $user->biodata?->nama_lengkap) }}"
                                required
                                placeholder="Contoh: Andi Pratama Wijaya"
                                class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                            />
                            @error('nama_lengkap')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tempat Lahir --}}
                        <div>
                            <label for="tempat_lahir" class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                Tempat Lahir <span class="text-gold-600">*</span>
                            </label>
                            <input
                                type="text"
                                id="tempat_lahir"
                                name="tempat_lahir"
                                value="{{ old('tempat_lahir', $user->biodata?->tempat_lahir) }}"
                                required
                                placeholder="Contoh: Surabaya"
                                class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                            />
                            @error('tempat_lahir')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label for="tanggal_lahir" class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                Tanggal Lahir <span class="text-gold-600">*</span>
                            </label>
                            <input
                                type="date"
                                id="tanggal_lahir"
                                name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $user->biodata?->tanggal_lahir?->format('Y-m-d')) }}"
                                required
                                max="{{ now()->format('Y-m-d') }}"
                                class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                            />
                            @error('tanggal_lahir')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tinggi Badan --}}
                        <div>
                            <label for="tinggi_badan" class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                Tinggi Badan (cm)
                            </label>
                            <input
                                type="number"
                                id="tinggi_badan"
                                name="tinggi_badan"
                                value="{{ old('tinggi_badan', $user->biodata?->tinggi_badan) }}"
                                min="100"
                                max="250"
                                placeholder="Contoh: 172"
                                class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                            />
                            @error('tinggi_badan')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Berat Badan --}}
                        <div>
                            <label for="berat_badan" class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                Berat Badan (kg)
                            </label>
                            <input
                                type="number"
                                id="berat_badan"
                                name="berat_badan"
                                value="{{ old('berat_badan', $user->biodata?->berat_badan) }}"
                                min="30"
                                max="200"
                                placeholder="Contoh: 68"
                                class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                            />
                            @error('berat_badan')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alamat KTP --}}
                        <div class="md:col-span-2">
                            <label for="alamat_ktp" class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                Alamat Sesuai KTP <span class="text-gold-600">*</span>
                            </label>
                            <textarea
                                id="alamat_ktp"
                                name="alamat_ktp"
                                rows="2"
                                required
                                placeholder="Contoh: Jl. Diponegoro No. 145, Kel. Tegalsari, Kec. Tegalsari, Surabaya 60262"
                                class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all resize-none"
                            >{{ old('alamat_ktp', $user->biodata?->alamat_ktp) }}</textarea>
                            @error('alamat_ktp')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alamat Domisili --}}
                        <div class="md:col-span-2">
                            <label for="alamat_domisili" class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                Alamat Domisili (jika berbeda dari KTP)
                            </label>
                            <textarea
                                id="alamat_domisili"
                                name="alamat_domisili"
                                rows="2"
                                placeholder="Kosongkan jika sama dengan alamat KTP"
                                class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all resize-none"
                            >{{ old('alamat_domisili', $user->biodata?->alamat_domisili) }}</textarea>
                            @error('alamat_domisili')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ SECTION B: RIWAYAT PENDIDIKAN (DYNAMIC) ============ --}}
            <div class="bg-white rounded-2xl border border-cream-300 shadow-brand overflow-hidden">
                {{-- Header --}}
                <div class="px-7 py-5 border-b border-cream-300 flex items-center justify-between gap-4 bg-gradient-to-r from-white to-paper/30">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-navy flex items-center justify-center shadow-navy-glow shrink-0">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0015.482 0M9.75 21l4.5-4.5m-4.5 4.5h-3.75c-1.243 0-2.25-1.007-2.25-2.25V18c0-1.243 1.007-2.25 2.25-2.25H9.75M18 11.25l4.5 4.5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 font-semibold">Bagian B</div>
                            <div class="font-display text-xl text-navy font-semibold">Riwayat Pendidikan</div>
                        </div>
                    </div>

                    {{-- Add button --}}
                    <button type="button"
                            @click="addEducation()"
                            x-show="educations.length < 5"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs tracking-wider uppercase font-semibold text-navy hover:bg-navy hover:text-cream rounded-lg border border-cream-300 hover:border-navy transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Tambah
                    </button>
                </div>

                {{-- Body: Dynamic Rows --}}
                <div class="p-7 space-y-4">
                    <template x-for="(edu, index) in educations" :key="index">
                        <div class="relative pb-5 border-b border-paper last:border-0 last:pb-0">

                            {{-- Row Header --}}
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg bg-paper text-navy font-display font-semibold text-xs flex items-center justify-center">
                                        <span x-text="index + 1"></span>
                                    </div>
                                    <span class="text-xs tracking-wider uppercase font-semibold text-navy-600">
                                        Pendidikan ke-<span x-text="index + 1"></span>
                                    </span>
                                </div>

                                <button type="button"
                                        @click="removeEducation(index)"
                                        x-show="educations.length > 1"
                                        class="text-xs text-red-500 hover:text-red-700 inline-flex items-center gap-1 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                    Hapus
                                </button>
                            </div>

                            {{-- Form fields --}}
                            <div class="grid md:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                        Nama Sekolah / Institusi <span class="text-gold-600">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        :name="`educations[${index}][nama_sekolah]`"
                                        x-model="edu.nama_sekolah"
                                        required
                                        placeholder="Contoh: Universitas Airlangga"
                                        class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                                    />
                                </div>

                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                        Jurusan
                                    </label>
                                    <input
                                        type="text"
                                        :name="`educations[${index}][jurusan]`"
                                        x-model="edu.jurusan"
                                        placeholder="Contoh: Manajemen"
                                        class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                                    />
                                </div>

                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                        Tahun Lulus <span class="text-gold-600">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        :name="`educations[${index}][tahun_lulus]`"
                                        x-model="edu.tahun_lulus"
                                        required
                                        min="1970"
                                        max="{{ date('Y') }}"
                                        placeholder="2021"
                                        class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                                    />
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ============ SECTION C: PENGALAMAN KERJA (DYNAMIC) ============ --}}
            <div class="bg-white rounded-2xl border border-cream-300 shadow-brand overflow-hidden">
                {{-- Header --}}
                <div class="px-7 py-5 border-b border-cream-300 flex items-center justify-between gap-4 bg-gradient-to-r from-white to-paper/30">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-navy flex items-center justify-center shadow-navy-glow shrink-0">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 font-semibold">Bagian C · Opsional</div>
                            <div class="font-display text-xl text-navy font-semibold">Pengalaman Kerja</div>
                        </div>
                    </div>

                    <button type="button"
                            @click="addWork()"
                            x-show="workExperiences.length < 5"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs tracking-wider uppercase font-semibold text-navy hover:bg-navy hover:text-cream rounded-lg border border-cream-300 hover:border-navy transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Tambah
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-7">
                    <div x-show="workExperiences.length === 0" class="text-center py-8 text-cream-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-cream-400" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25"/>
                        </svg>
                        <p class="text-sm">Belum ada pengalaman kerja.</p>
                        <p class="text-xs mt-1">Klik "Tambah" jika Anda memiliki pengalaman kerja.</p>
                    </div>

                    <div class="space-y-5">
                        <template x-for="(work, index) in workExperiences" :key="index">
                            <div class="relative pb-5 border-b border-paper last:border-0 last:pb-0">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-lg bg-paper text-navy font-display font-semibold text-xs flex items-center justify-center">
                                            <span x-text="index + 1"></span>
                                        </div>
                                        <span class="text-xs tracking-wider uppercase font-semibold text-navy-600">
                                            Pengalaman ke-<span x-text="index + 1"></span>
                                        </span>
                                    </div>

                                    <button type="button"
                                            @click="removeWork(index)"
                                            class="text-xs text-red-500 hover:text-red-700 inline-flex items-center gap-1 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M5.79 5.79l1.05 13.88a2.25 2.25 0 002.244 2.078h6.832a2.25 2.25 0 002.244-2.077l1.05-13.88"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>

                                <div class="grid md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                            Posisi / Bagian
                                        </label>
                                        <input
                                            type="text"
                                            :name="`work_experiences[${index}][posisi]`"
                                            x-model="work.posisi"
                                            placeholder="Contoh: Marketing Executive"
                                            class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                            Nama Perusahaan
                                        </label>
                                        <input
                                            type="text"
                                            :name="`work_experiences[${index}][nama_perusahaan]`"
                                            x-model="work.nama_perusahaan"
                                            placeholder="Contoh: PT Maju Bersama"
                                            class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                                        />
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ============ SECTION D: KONTAK DARURAT (DYNAMIC) ============ --}}
            <div class="bg-white rounded-2xl border border-cream-300 shadow-brand overflow-hidden">
                {{-- Header --}}
                <div class="px-7 py-5 border-b border-cream-300 flex items-center justify-between gap-4 bg-gradient-to-r from-white to-paper/30">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-navy flex items-center justify-center shadow-navy-glow shrink-0">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[10px] tracking-wider-2 uppercase text-gold-600 font-semibold">Bagian D</div>
                            <div class="font-display text-xl text-navy font-semibold">Kontak Darurat</div>
                        </div>
                    </div>

                    <button type="button"
                            @click="addContact()"
                            x-show="emergencyContacts.length < 3"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs tracking-wider uppercase font-semibold text-navy hover:bg-navy hover:text-cream rounded-lg border border-cream-300 hover:border-navy transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Tambah
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-7 space-y-5">
                    <template x-for="(contact, index) in emergencyContacts" :key="index">
                        <div class="relative pb-5 border-b border-paper last:border-0 last:pb-0">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg bg-paper text-navy font-display font-semibold text-xs flex items-center justify-center">
                                        <span x-text="index + 1"></span>
                                    </div>
                                    <span class="text-xs tracking-wider uppercase font-semibold text-navy-600">
                                        Kontak ke-<span x-text="index + 1"></span>
                                    </span>
                                </div>

                                <button type="button"
                                        @click="removeContact(index)"
                                        x-show="emergencyContacts.length > 1"
                                        class="text-xs text-red-500 hover:text-red-700 inline-flex items-center gap-1 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166"/>
                                    </svg>
                                    Hapus
                                </button>
                            </div>

                            <div class="grid md:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                        Nama Lengkap <span class="text-gold-600">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        :name="`emergency_contacts[${index}][nama]`"
                                        x-model="contact.nama"
                                        required
                                        placeholder="Nama kontak darurat"
                                        class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                                    />
                                </div>

                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                        Hubungan <span class="text-gold-600">*</span>
                                    </label>
                                    <select
                                        :name="`emergency_contacts[${index}][hubungan]`"
                                        x-model="contact.hubungan"
                                        required
                                        class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all">
                                        <option value="">Pilih hubungan...</option>
                                        <option value="Orang Tua">Orang Tua</option>
                                        <option value="Saudara Kandung">Saudara Kandung</option>
                                        <option value="Pasangan">Pasangan / Istri / Suami</option>
                                        <option value="Anak">Anak</option>
                                        <option value="Kerabat">Kerabat</option>
                                        <option value="Teman">Teman</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
                                        Nomor HP <span class="text-gold-600">*</span>
                                    </label>
                                    <input
                                        type="tel"
                                        :name="`emergency_contacts[${index}][nomor_hp]`"
                                        x-model="contact.nomor_hp"
                                        required
                                        placeholder="08123456789"
                                        class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
                                    />
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ============ ACTION BUTTONS ============ --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-between items-stretch sm:items-center gap-3 pt-4">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-sm tracking-wider uppercase font-medium text-navy border border-cream-300 rounded-xl hover:bg-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Kembali ke Dashboard
                </a>

                <button type="submit"
                        :disabled="isSubmitting"
                        class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-sm tracking-wider uppercase font-semibold bg-gradient-navy text-cream rounded-xl shadow-navy-glow hover:shadow-brand-xl hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:translate-y-0 group">
                    <span x-show="!isSubmitting">Simpan Biodata</span>
                    <span x-show="isSubmitting" x-cloak>Menyimpan...</span>
                    <svg x-show="!isSubmitting" class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    <svg x-show="isSubmitting" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"></circle>
                        <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </div>

        </form>

    </x-user-shell>

    {{-- Alpine.js Component Logic --}}
    @php
        // Prepare initial data untuk Alpine.js
        $initialEducations = old('educations',
            $user->educations->count() > 0
                ? $user->educations->map(fn($e) => [
                    'nama_sekolah' => $e->nama_sekolah,
                    'jurusan' => $e->jurusan,
                    'tahun_lulus' => $e->tahun_lulus,
                ])->toArray()
                : [['nama_sekolah' => '', 'jurusan' => '', 'tahun_lulus' => '']]
        );

        $initialWorks = old('work_experiences',
            $user->workExperiences->count() > 0
                ? $user->workExperiences->map(fn($w) => [
                    'posisi' => $w->posisi,
                    'nama_perusahaan' => $w->nama_perusahaan,
                ])->toArray()
                : []
        );

        $initialContacts = old('emergency_contacts',
            $user->emergencyContacts->count() > 0
                ? $user->emergencyContacts->map(fn($c) => [
                    'nama' => $c->nama,
                    'hubungan' => $c->hubungan,
                    'nomor_hp' => $c->nomor_hp,
                ])->toArray()
                : [['nama' => '', 'hubungan' => '', 'nomor_hp' => '']]
        );
    @endphp

    @push('scripts')
        <script>
            function biodataForm() {
                return {
                    isSubmitting: false,

                    educations: @json($initialEducations),
                    workExperiences: @json($initialWorks),
                    emergencyContacts: @json($initialContacts),

                    // === Education Methods ===
                    addEducation() {
                        if (this.educations.length < 5) {
                            this.educations.push({ nama_sekolah: '', jurusan: '', tahun_lulus: '' });
                        }
                    },
                    removeEducation(index) {
                        if (this.educations.length > 1) {
                            this.educations.splice(index, 1);
                        }
                    },

                    // === Work Experience Methods ===
                    addWork() {
                        if (this.workExperiences.length < 5) {
                            this.workExperiences.push({ posisi: '', nama_perusahaan: '' });
                        }
                    },
                    removeWork(index) {
                        this.workExperiences.splice(index, 1);
                    },

                    // === Emergency Contact Methods ===
                    addContact() {
                        if (this.emergencyContacts.length < 3) {
                            this.emergencyContacts.push({ nama: '', hubungan: '', nomor_hp: '' });
                        }
                    },
                    removeContact(index) {
                        if (this.emergencyContacts.length > 1) {
                            this.emergencyContacts.splice(index, 1);
                        }
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
