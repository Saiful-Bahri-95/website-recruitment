<x-guest-layout>
    <div class="min-h-screen grid lg:grid-cols-5">

        {{-- ============ LEFT: BRAND PANEL ============ --}}
        <div class="lg:col-span-2 relative overflow-hidden p-10 lg:p-14 flex flex-col justify-between bg-navy">

            {{-- Decorative ornaments --}}
            <x-ornament-circles class="-top-20 -right-20" :size="500" :opacity="0.07" />

            {{-- Logo Mark Background Decorative --}}
            <div class="absolute bottom-0 right-0 opacity-[0.04]">
                <x-brand.logo-mark :size="400" />
            </div>

            {{-- TOP: Brand wordmark --}}
            <div class="relative z-10">
                <x-brand.wordmark light />
            </div>

            {{-- MIDDLE: Hero text + stats --}}
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <div class="h-px w-10 bg-gold"></div>
                    <span class="text-[10px] tracking-eyebrow font-medium uppercase text-gold-300">
                        Portal Pelamar
                    </span>
                </div>

                <h1 class="font-display text-4xl lg:text-5xl leading-[1.1] mb-5 text-cream font-semibold">
                    Karier yang berarti
                    <span class="text-gold italic">dimulai dari sini.</span>
                </h1>

                <p class="text-sm leading-relaxed max-w-md text-cream/70">
                    Daftarkan profil Anda sekali, dan biarkan kami menghubungkan kompetensi Anda dengan perusahaan-perusahaan yang tepat di seluruh nusantara.
                </p>

                {{-- Stats --}}
                <div class="mt-10 grid grid-cols-3 gap-4 max-w-md">
                    <div class="border-l border-gold/40 pl-3">
                        <div class="font-display text-2xl text-gold font-semibold">1.2K+</div>
                        <div class="text-[10px] tracking-wider uppercase text-cream/55">Kandidat</div>
                    </div>
                    <div class="border-l border-gold/40 pl-3">
                        <div class="font-display text-2xl text-gold font-semibold">85+</div>
                        <div class="text-[10px] tracking-wider uppercase text-cream/55">Mitra HRD</div>
                    </div>
                    <div class="border-l border-gold/40 pl-3">
                        <div class="font-display text-2xl text-gold font-semibold">94%</div>
                        <div class="text-[10px] tracking-wider uppercase text-cream/55">Tingkat Salur</div>
                    </div>
                </div>
            </div>

            {{-- BOTTOM: Copyright --}}
            <div class="relative z-10 text-[10px] tracking-wider text-cream/40">
                © {{ date('Y') }} ANGGITA Global Recruitment · Bekasi, Indonesia
            </div>
        </div>

        {{-- ============ RIGHT: LOGIN FORM ============ --}}
        <div class="lg:col-span-3 flex items-center justify-center p-8 lg:p-16">
            <div class="w-full max-w-md">

                {{-- Header --}}
                <div class="mb-10">
                    <span class="text-[10px] tracking-eyebrow font-medium uppercase text-gold-600">
                        Selamat datang kembali
                    </span>
                    <h2 class="font-display text-3xl mt-2 text-navy font-semibold">
                        Masuk ke akun Anda
                    </h2>
                </div>

                {{-- Session Status (kalau habis register, redirect ke login) --}}
                @if (session('status'))
                    <div class="mb-6 p-4 bg-gold/10 border border-gold/30 text-sm text-navy">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

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
                                autofocus
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
                        <div class="flex justify-between items-end mb-2">
                            <label for="password" class="text-xs tracking-wider uppercase font-medium text-navy">
                                Kata Sandi
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs underline-offset-4 hover:underline text-gold-600">
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>
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
                                autocomplete="current-password"
                                placeholder="••••••••••"
                                class="w-full pl-11 pr-4 py-3.5 text-sm bg-white border border-cream-300 text-navy rounded-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"
                            />
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 border border-navy text-navy focus:ring-1 focus:ring-navy rounded-sm"
                        />
                        <span class="text-xs text-navy-600">Tetap masuk di perangkat ini</span>
                    </label>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        class="w-full py-3.5 text-sm tracking-wider uppercase font-medium transition-all hover:opacity-90 flex items-center justify-center gap-2 group bg-navy text-cream"
                    >
                        Masuk ke Portal
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </button>

                    {{-- Footer Link to Register --}}
                    <p class="text-center text-xs pt-2 text-navy-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold underline-offset-4 hover:underline text-gold-600">
                            Daftar di sini
                        </a>
                    </p>
                </form>
            </div>
        </div>

    </div>
</x-guest-layout>
