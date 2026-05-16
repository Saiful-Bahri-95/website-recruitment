<form
    method="POST"
    action="{{ route('payment.upload') }}"
    enctype="multipart/form-data"
    class="space-y-5">
    @csrf

    {{-- Upload Area --}}
    <div x-data="{ fileName: '' }">
        <label for="proof"
            class="block cursor-pointer border-2 border-dashed border-cream-300 rounded-xl py-10 px-6 text-center hover:border-gold hover:bg-paper/30 transition-all bg-paper/20"
            :class="{ 'border-gold bg-gold/5': fileName }">

            <div class="w-14 h-14 rounded-2xl mx-auto mb-3 flex items-center justify-center bg-gradient-navy shadow-navy-glow">
                <svg x-show="!fileName" class="w-7 h-7 text-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15M9 12l3 3m0 0l3-3m-3 3V2.25"/>
                </svg>
                <svg x-show="fileName" x-cloak class="w-7 h-7 text-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>

            <div x-show="!fileName" class="text-sm font-medium text-navy mb-1">
                Klik untuk pilih file
            </div>
            <div x-show="fileName" x-cloak class="text-sm font-semibold text-navy mb-1 truncate" x-text="fileName"></div>

            <div class="text-xs text-cream-600">JPG, PNG, atau PDF · Maks. 3MB</div>
        </label>

        <input
            type="file"
            id="proof"
            name="proof"
            accept=".jpg,.jpeg,.png,.pdf"
            required
            class="hidden"
            @change="fileName = $event.target.files[0]?.name || ''"
        />
    </div>

    {{-- Bank Pengirim --}}
    <div>
        <label for="bank_pengirim" class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
            Bank Pengirim <span class="text-gold-600">*</span>
        </label>
        <input
            type="text"
            id="bank_pengirim"
            name="bank_pengirim"
            value="{{ old('bank_pengirim') }}"
            required
            placeholder="Contoh: Mandiri, BCA, BRI"
            class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
        />
    </div>

    {{-- Nama Pengirim --}}
    <div>
        <label for="nama_pengirim" class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
            Nama Pemilik Rekening Pengirim <span class="text-gold-600">*</span>
        </label>
        <input
            type="text"
            id="nama_pengirim"
            name="nama_pengirim"
            value="{{ old('nama_pengirim') }}"
            required
            placeholder="Nama lengkap sesuai rekening"
            class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
        />
    </div>

    {{-- Tanggal Transfer --}}
    <div>
        <label for="paid_at" class="block text-[10px] font-semibold uppercase tracking-wider-2 text-navy-600 mb-2">
            Tanggal & Jam Transfer <span class="text-gold-600">*</span>
        </label>
        <input
            type="datetime-local"
            id="paid_at"
            name="paid_at"
            value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}"
            required
            max="{{ now()->format('Y-m-d\TH:i') }}"
            class="w-full px-4 py-3 text-sm bg-white border border-cream-300 text-navy rounded-xl focus:outline-none focus:border-navy focus:ring-2 focus:ring-navy/10 transition-all"
        />
    </div>

    {{-- Submit Button --}}
    <button
        type="submit"
        class="w-full py-3.5 text-sm tracking-wider uppercase font-semibold bg-gradient-navy text-cream rounded-xl shadow-navy-glow hover:shadow-brand-xl hover:-translate-y-0.5 transition-all group flex items-center justify-center gap-2">
        Konfirmasi Pembayaran
        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
        </svg>
    </button>
</form>
