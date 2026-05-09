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
                    ->color('gray')
                    ->suffix(' file'),
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
                Tables\Actions\Action::make('generatePdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->tooltip('Generate PDF gabungan biodata + dokumen')
                    ->action(function (User $record) {
                        \Filament\Notifications\Notification::make()
                            ->title('Generate PDF')
                            ->body("Fitur generate PDF untuk {$record->biodata?->nama_lengkap} akan diimplementasi di tahap berikutnya.")
                            ->info()
                            ->send();
                    }),
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
