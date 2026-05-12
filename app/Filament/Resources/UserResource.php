<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Pelamar';

    protected static ?string $modelLabel = 'Pelamar';

    protected static ?string $pluralModelLabel = 'Pelamar';

    protected static ?string $navigationGroup = 'Manajemen Rekrutmen';

    protected static ?int $navigationSort = 1;

    /**
     * Filter agar hanya menampilkan user dengan role 'pelamar'
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'pelamar');
    }

    /**
     * Tampilkan jumlah pelamar di sidebar (badge angka)
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('role', 'pelamar')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ===== SECTION 1: AKUN PELAMAR =====
                Forms\Components\Section::make('Akun Pelamar')
                    ->description('Informasi login pelamar')
                    ->icon('heroicon-o-key')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Akun')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required(fn(string $context): bool => $context === 'create')
                            ->dehydrated(fn($state) => filled($state))
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->helperText('Min 8 karakter. Kosongkan saat edit jika tidak ingin ganti.')
                            ->minLength(8)
                            ->maxLength(255),
                        Forms\Components\Hidden::make('role')
                            ->default('pelamar'),
                    ])->columns(2),

                // ===== SECTION 2: BIODATA DIRI =====
                Forms\Components\Section::make('Biodata Diri')
                    ->description('Data pribadi sesuai KTP')
                    ->icon('heroicon-o-identification')
                    ->relationship('biodata')
                    ->schema([
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap (sesuai KTP)')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('alamat_ktp')
                            ->label('Alamat sesuai KTP')
                            ->required()
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('alamat_domisili')
                            ->label('Alamat Domisili')
                            ->helperText('Kosongkan jika sama dengan alamat KTP')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->maxDate(now()->subYears(17))
                            ->displayFormat('d M Y'),
                        Forms\Components\TextInput::make('tinggi_badan')
                            ->label('Tinggi Badan')
                            ->numeric()
                            ->suffix('cm')
                            ->minValue(120)
                            ->maxValue(250),
                        Forms\Components\TextInput::make('berat_badan')
                            ->label('Berat Badan')
                            ->numeric()
                            ->suffix('kg')
                            ->minValue(30)
                            ->maxValue(200),
                    ])->columns(2),

                // ===== SECTION 3: RIWAYAT PENDIDIKAN =====
                Forms\Components\Section::make('Riwayat Pendidikan')
                    ->description('Pendidikan formal yang pernah ditempuh')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Forms\Components\Repeater::make('educations')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('nama_sekolah')
                                    ->label('Nama Sekolah/Institusi')
                                    ->required(),
                                Forms\Components\TextInput::make('jurusan')
                                    ->label('Jurusan'),
                                Forms\Components\TextInput::make('tahun_lulus')
                                    ->label('Tahun Lulus')
                                    ->numeric()
                                    ->minValue(1980)
                                    ->maxValue((int) date('Y') + 1)
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('+ Tambah Pendidikan')
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['nama_sekolah'] ?? null),
                    ]),

                // ===== SECTION 4: PENGALAMAN KERJA =====
                Forms\Components\Section::make('Pengalaman Kerja')
                    ->description('Maksimal 3 pengalaman kerja terakhir')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        Forms\Components\Repeater::make('workExperiences')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('posisi')
                                    ->label('Posisi/Bagian')
                                    ->required(),
                                Forms\Components\TextInput::make('nama_perusahaan')
                                    ->label('Nama Perusahaan')
                                    ->required(),
                                Forms\Components\Hidden::make('urutan')
                                    ->default(1),
                            ])
                            ->columns(2)
                            ->maxItems(3)
                            ->defaultItems(0)
                            ->addActionLabel('+ Tambah Pengalaman')
                            ->collapsible()
                            ->itemLabel(
                                fn(array $state): ?string =>
                                isset($state['posisi'], $state['nama_perusahaan'])
                                    ? "{$state['posisi']} di {$state['nama_perusahaan']}"
                                    : null
                            ),
                    ]),

                // ===== SECTION 5: KONTAK DARURAT =====
                Forms\Components\Section::make('Kontak Darurat')
                    ->description('Maksimal 3 kontak darurat yang bisa dihubungi')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Forms\Components\Repeater::make('emergencyContacts')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama')
                                    ->required(),
                                Forms\Components\TextInput::make('hubungan')
                                    ->label('Hubungan')
                                    ->placeholder('Contoh: Orang Tua, Saudara')
                                    ->required(),
                                Forms\Components\TextInput::make('nomor_hp')
                                    ->label('Nomor HP')
                                    ->tel()
                                    ->placeholder('+62...')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->maxItems(3)
                            ->defaultItems(0)
                            ->addActionLabel('+ Tambah Kontak')
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['nama'] ?? null),
                    ]),

                // ===== SECTION 6: DOKUMEN PENDUKUNG (SECURE) =====
                Forms\Components\Section::make('Dokumen Pendukung')
                    ->description('Upload dokumen-dokumen yang diperlukan. Format: PDF, JPG, PNG. Maks 5MB per file. File disimpan di disk private dengan signed URL.')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Forms\Components\Repeater::make('documents')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Jenis Dokumen')
                                    ->options(\App\Models\Document::TYPES)
                                    ->required()
                                    ->searchable()
                                    ->columnSpan(1),

                                Forms\Components\FileUpload::make('file_path')
                                    ->label('File')
                                    ->directory(date('Y/m'))
                                    ->disk('documents')
                                    ->visibility('private')
                                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                                    ->maxSize(5120)
                                    ->required()
                                    ->preserveFilenames()
                                    ->storeFileNamesIn('original_name')
                                    ->openable(false)
                                    ->downloadable(false)
                                    ->columnSpan(2)
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state && method_exists($state, 'getSize')) {
                                            $set('file_size', $state->getSize());
                                            $set('mime_type', $state->getMimeType());
                                        }
                                    }),

                                Forms\Components\Hidden::make('file_size'),
                                Forms\Components\Hidden::make('mime_type'),

                                // Placeholder untuk tampilkan secure URL setelah upload
                                Forms\Components\Placeholder::make('secure_link')
                                    ->label('Akses File')
                                    ->columnSpanFull()
                                    ->content(function ($get, $record) {
                                        $documents = $record?->documents ?? collect();
                                        $documentId = $get('id');

                                        if (!$documentId) {
                                            return new \Illuminate\Support\HtmlString(
                                                '<span class="text-sm text-gray-500">💾 Simpan dulu untuk dapat link akses.</span>'
                                            );
                                        }

                                        $document = \App\Models\Document::find($documentId);
                                        if (!$document || !$document->fileExists()) {
                                            return new \Illuminate\Support\HtmlString(
                                                '<span class="text-sm text-warning-600">⚠️ File tidak ditemukan.</span>'
                                            );
                                        }

                                        return new \Illuminate\Support\HtmlString(
                                            '<div class="flex gap-2">' .
                                                '<a href="' . $document->getSecureViewUrl() . '" target="_blank" ' .
                                                'class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-primary-600 text-white rounded hover:bg-primary-700">' .
                                                '👁️ Lihat File</a>' .
                                                '<a href="' . $document->getSecureDownloadUrl() . '" ' .
                                                'class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-gray-600 text-white rounded hover:bg-gray-700">' .
                                                '⬇️ Download</a>' .
                                                '<span class="text-xs text-gray-500 self-center">⏱️ Link aktif 5 menit</span>' .
                                                '</div>'
                                        );
                                    }),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel('+ Tambah Dokumen')
                            ->collapsible()
                            ->itemLabel(
                                fn(array $state): ?string =>
                                isset($state['type'])
                                    ? (\App\Models\Document::TYPES[$state['type']] ?? $state['type'])
                                    : 'Dokumen Baru'
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->prefix('#')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('biodata.nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->placeholder('Belum diisi')
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email disalin ke clipboard')
                    ->icon('heroicon-m-envelope')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('application.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(?string $state): string => match ($state) {
                        'draft' => 'gray',
                        'submitted' => 'info',
                        'paid' => 'warning',
                        'verified' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'paid' => 'Paid',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                        default => '—',
                    })
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('documents_count')
                    ->label('Dokumen')
                    ->counts('documents')
                    ->badge()
                    ->color(fn($state) => match (true) {
                        $state == 0 => 'danger',
                        $state >= 1 && $state < 10 => 'warning',
                        $state >= 10 => 'success',
                        default => 'gray',
                    })
                    ->suffix(' / 10')
                    ->tooltip('Klik kolom Aksi → Dokumen untuk lihat detail'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Daftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Aplikasi')
                    ->relationship('application', 'status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'paid' => 'Paid',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('viewDocuments')
                    ->label('Dokumen')
                    ->icon('heroicon-o-folder-open')
                    ->color('info')
                    ->modalHeading(fn(User $record) => 'Dokumen: ' . ($record->biodata?->nama_lengkap ?? $record->name))
                    ->modalContent(fn(User $record) => view('filament.modals.document-list', [
                        'user' => $record,
                        'documents' => $record->documents()->orderBy('type')->get(),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->slideOver(),

                Tables\Actions\Action::make('generatePdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->tooltip('Generate PDF gabungan biodata + dokumen')
                    ->requiresConfirmation()
                    ->modalHeading('Generate PDF Profile?')
                    ->modalDescription(
                        fn(User $record) =>
                        'PDF akan berisi biodata lengkap, riwayat pendidikan, pengalaman kerja, dan daftar ' .
                            $record->documents->count() . ' dokumen pelamar. ' .
                            'PDF akan tersimpan selama 24 jam.'
                    )
                    ->modalSubmitActionLabel('Ya, Generate')
                    ->action(function (User $record) {
                        try {
                            $pdfService = app(\App\Services\PdfGeneratorService::class);
                            $pdfGeneration = $pdfService->generateForUser($record);

                            \Filament\Notifications\Notification::make()
                                ->title('PDF Berhasil Di-generate')
                                ->body("File: {$pdfGeneration->file_name} ({$pdfGeneration->getReadableSize()})")
                                ->success()
                                ->duration(8000)
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('view')
                                        ->label('Lihat PDF')
                                        ->url($pdfGeneration->getSecureViewUrl(), shouldOpenInNewTab: true)
                                        ->button(),
                                    \Filament\Notifications\Actions\Action::make('download')
                                        ->label('Download')
                                        ->url($pdfGeneration->getSecureDownloadUrl())
                                        ->color('gray'),
                                ])
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal Generate PDF')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('pdfHistory')
                    ->label('Riwayat PDF')
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->modalHeading(fn(User $record) => 'Riwayat PDF: ' . ($record->biodata?->nama_lengkap ?? $record->name))
                    ->modalContent(fn(User $record) => view('filament.modals.pdf-history', [
                        'user' => $record,
                        'pdfs' => \App\Models\PdfGeneration::where('user_id', $record->id)
                            ->where('expires_at', '>', now())
                            ->orderBy('created_at', 'desc')
                            ->get(),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->slideOver(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum ada pelamar')
            ->emptyStateDescription('Pelamar yang mendaftar akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-users');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
