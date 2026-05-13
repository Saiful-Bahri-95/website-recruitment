<x-guest-layout>
    <div class="min-h-screen grid lg:grid-cols-5">

        {{-- ============ LEFT: BRAND PANEL ============ --}}
        <div class="lg:col-span-2 relative overflow-hidden p-10 lg:p-14 flex flex-col justify-between bg-navy">

            <x-ornament-circles class="-top-20 -right-20" :size="500" :opacity="0.07" />

            <div class="absolute bottom-0 right-0 opacity-[0.04]">
                <x-brand.logo-mark :size="400" />
            </div>

            {{-- TOP --}}
            <div class="relative z-10">
                <x-brand.wordmark light />
            </div>

            {{-- MIDDLE --}}
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <div class="h-px w-10 bg-gold"></div>
                    <span class="text-[10px] tracking-eyebrow font-medium uppercase text-gold-300">
                        Daftar Akun Baru
                    </span>
                </div>

                <h1 class="font-display text-4xl lg:text-5xl leading-[1.1] mb-5 text-cream font-semibold">
                    Langkah pertama
                    <span class="text-gold italic">menuju karier impian.</span>
                </h1>

                <p class="text-sm leading-relaxed max-w-md text-cream/70">
                    Buat akun Anda dalam 30 detik. Setelah itu, lengkapi biodata dan unggah dokumen pendukung untuk mulai proses lamaran.
                </p>

                {{-- Benefits list --}}
                <div class="mt-10 space-y-3 max-w-md">
                    @foreach([
                        'Akses ke 85+ perusahaan mitra HRD',
                        'Profil tersimpan permanen, isi sekali pakai berkali-kali',
                        'Verifikasi cepat 1×24 jam kerja',
                    ] as $benefit)
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-gold/20 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm text-cream/80">{{ $benefit }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- BOTTOM --}}
            <div class="relative z-10 text-[10px] tracking-wider text-cream/40">
                © {{ date('Y') }} Anugerah Global Recruitment · Surabaya, Indonesia
            </div>
        </div>

        {{-- ============ RIGHT: REGISTER FORM ============ --}}
        <div class="lg:col-span-3 flex items-center justify-center p-8 lg:p-16">
            <div class="w-full max-w-md">

                {{-- Header --}}
                <div class="mb-10">
                    <span class="text-[10px] tracking-eyebrow font-medium uppercase text-gold-600">
                        Mulai Perjalanan Karier
                    </span>
                    <h2 class="font-display text-3xl mt-2 text-navy font-semibold">
                        Buat akun pelamar
                    </h2>
                </div>

                {{-- Register Form --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-xs tracking-wider uppercase mb-2 font-medium text-navy">
                            Nama Lengkap
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-navy-600 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Sesuai KTP / dokumen resmi"
                                class="w-full pl-11 pr-4 py-3.5 text-sm bg-white border border-cream-300 text-navy rounded-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"
                            />
                        </div>
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs tracking-wider uppercase mb-2 font-medium text-navy">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-navy-600 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="username"
                                placeholder="email@contoh.com"
                                class="w-full pl-11 pr-4 py-3.5 text-sm bg-white border border-cream-300 text-navy rounded-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"
                            />
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-xs tracking-wider uppercase mb-2 font-medium text-navy">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-navy-600 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                class="w-full pl-11 pr-4 py-3.5 text-sm bg-white border border-cream-300 text-navy rounded-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"
                            />
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs tracking-wider uppercase mb-2 font-medium text-navy">
                            Konfirmasi Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-navy-600 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi kata sandi"
                                class="w-full pl-11 pr-4 py-3.5 text-sm bg-white border border-cream-300 text-navy rounded-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"
                            />
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Terms Notice --}}
                    <div class="text-xs text-cream-600 leading-relaxed">
                        Dengan mendaftar, Anda menyetujui
                        <a href="#" class="font-medium underline-offset-4 hover:underline text-gold-600">Syarat & Ketentuan</a>
                        serta
                        <a href="#" class="font-medium underline-offset-4 hover:underline text-gold-600">Kebijakan Privasi</a>
                        kami sesuai UU PDP No. 27 Tahun 2022.
                    </div>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        class="w-full py-3.5 text-sm tracking-wider uppercase font-medium transition-all hover:opacity-90 flex items-center justify-center gap-2 group bg-navy text-cream"
                    >
                        Daftar Sekarang
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </button>

                    {{-- Footer Link to Login --}}
                    <p class="text-center text-xs pt-2 text-navy-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-semibold underline-offset-4 hover:underline text-gold-600">
                            Masuk di sini
                        </a>
                    </p>
                </form>
            </div>
        </div>

    </div>
</x-guest-layout>
