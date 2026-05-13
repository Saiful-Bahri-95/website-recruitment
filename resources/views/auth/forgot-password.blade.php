<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-8 relative overflow-hidden">

        {{-- Decorative ornaments --}}
        <x-ornament-circles class="-top-32 -left-32" :size="600" :opacity="0.05" />
        <x-ornament-circles class="-bottom-32 -right-32" :size="500" :opacity="0.04" />

        <div class="w-full max-w-md relative z-10">

            {{-- Logo --}}
            <div class="flex justify-center mb-8">
                <x-brand.wordmark />
            </div>

            <div class="bg-white border border-cream-300 p-10">

                {{-- Header --}}
                <div class="mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gold/10 mb-4">
                        <svg class="w-7 h-7 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>

                    <span class="text-[10px] tracking-eyebrow font-medium uppercase text-gold-600">
                        Lupa Kata Sandi
                    </span>
                    <h2 class="font-display text-2xl mt-2 text-navy font-semibold">
                        Reset Akses Anda
                    </h2>
                    <p class="text-sm text-cream-600 mt-3 leading-relaxed">
                        Masukkan email Anda, dan kami akan kirim link untuk reset kata sandi.
                    </p>
                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="mb-6 p-4 bg-gold/10 border border-gold/30 text-sm text-navy">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

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
                                placeholder="email@contoh.com"
                                class="w-full pl-11 pr-4 py-3.5 text-sm bg-white border border-cream-300 text-navy rounded-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"
                            />
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3.5 text-sm tracking-wider uppercase font-medium transition-all hover:opacity-90 bg-navy text-cream"
                    >
                        Kirim Link Reset
                    </button>

                    <p class="text-center text-xs pt-2 text-navy-600">
                        Ingat kata sandi Anda?
                        <a href="{{ route('login') }}" class="font-semibold underline-offset-4 hover:underline text-gold-600">
                            Kembali ke login
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
