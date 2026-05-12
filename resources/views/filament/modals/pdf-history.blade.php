<div class="space-y-3">
    @if($pdfs->isEmpty())
        <div class="text-center py-12 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm">Belum ada PDF yang di-generate.</p>
            <p class="text-xs mt-1">Klik tombol "PDF" untuk generate yang pertama.</p>
        </div>
    @else
        <div class="text-sm text-gray-600 mb-4">
            Total: <strong>{{ $pdfs->count() }}</strong> PDF aktif.
            PDF otomatis terhapus setelah <strong>24 jam</strong>.
        </div>

        @foreach($pdfs as $pdf)
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-sm text-gray-900 dark:text-gray-100 truncate">
                                {{ $pdf->file_name }}
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $pdf->getReadableSize() }} ·
                                {{ $pdf->document_count }} dokumen ·
                                Generated {{ $pdf->created_at->diffForHumans() }}
                            </div>
                            <div class="text-xs text-gray-500">
                                Oleh: {{ $pdf->generatedBy->name }} ·
                                Expired: {{ $pdf->expires_at->format('d M Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ $pdf->getSecureViewUrl() }}"
                       target="_blank"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-primary-600 rounded hover:bg-primary-700 transition">
                        👁️ Lihat PDF
                    </a>
                    <a href="{{ $pdf->getSecureDownloadUrl() }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-gray-600 rounded hover:bg-gray-700 transition">
                        ⬇️ Download
                    </a>
                </div>
            </div>
        @endforeach
    @endif
</div>
