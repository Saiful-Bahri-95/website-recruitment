<div class="space-y-3">
    @if($documents->isEmpty())
        <div class="text-center py-8 text-gray-500">
            <p class="text-sm">Belum ada dokumen yang diupload.</p>
        </div>
    @else
        <div class="text-sm text-gray-600 mb-4">
            Total: <strong>{{ $documents->count() }}</strong> dokumen.
            Link akses aktif selama <strong>5 menit</strong> per klik.
        </div>

        @foreach($documents as $document)
            <div class="flex items-center justify-between gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="flex-shrink-0 w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                        @if(str_contains($document->mime_type ?? '', 'pdf'))
                            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-sm text-gray-900 dark:text-gray-100">
                            {{ $document->getTypeLabel() }}
                        </div>
                        <div class="text-xs text-gray-500 truncate">
                            {{ $document->original_name }}
                            @if($document->file_size)
                                · {{ $document->getReadableSize() }}
                            @endif
                            · {{ $document->uploaded_at?->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <a href="{{ $document->getSecureViewUrl() }}"
                       target="_blank"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-primary-600 rounded hover:bg-primary-700 transition">
                        👁️ Lihat
                    </a>
                    <a href="{{ $document->getSecureDownloadUrl() }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-gray-600 rounded hover:bg-gray-700 transition">
                        ⬇️ Download
                    </a>
                </div>
            </div>
        @endforeach
    @endif

    <div class="mt-4 p-3 bg-warning-50 dark:bg-warning-950 border border-warning-200 dark:border-warning-800 rounded-lg">
        <div class="flex items-start gap-2">
            <svg class="w-5 h-5 text-warning-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495z" clip-rule="evenodd"/>
            </svg>
            <div class="text-xs text-warning-800 dark:text-warning-200">
                <strong>Security Notice:</strong> Setiap akses dokumen dicatat di audit log dengan IP & timestamp.
                Link bersifat temporary dan tidak bisa dishare ke pihak lain (akan invalid).
            </div>
        </div>
    </div>
</div>
