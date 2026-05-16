<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $modelLabel = 'Pembayaran';
    protected static ?string $pluralModelLabel = 'Pembayaran';
    protected static ?string $navigationGroup = 'Manajemen Rekrutmen';
    protected static ?int $navigationSort = 2;

    /**
     * Badge jumlah payment 'pending' di sidebar (admin tahu antrian verifikasi).
     */
    public static function getNavigationBadge(): ?string
    {
        $pending = static::getModel()::where('status', 'pending')->count();
        return $pending > 0 ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelamar')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Placeholder::make('user_name')
                            ->label('Nama Pelamar')
                            ->content(fn($record) => $record?->user?->biodata?->nama_lengkap ?? $record?->user?->name ?? '-'),
                        Forms\Components\Placeholder::make('user_email')
                            ->label('Email')
                            ->content(fn($record) => $record?->user?->email ?? '-'),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Pembayaran')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Nominal')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn($state) => number_format((float) $state, 0, ',', '.')),
                        Forms\Components\TextInput::make('bank_pengirim')
                            ->label('Bank Pengirim')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('nama_pengirim')
                            ->label('Nama Pengirim')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Tanggal Transfer')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Forms\Components\Section::make('Bukti Pembayaran')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Placeholder::make('proof_link')
                            ->label('File Bukti')
                            ->content(function ($record) {
                                if (!$record?->proof_path) {
                                    return new \Illuminate\Support\HtmlString(
                                        '<span class="text-sm text-gray-500">Belum ada bukti.</span>'
                                    );
                                }

                                if (!Storage::disk('private')->exists($record->proof_path)) {
                                    return new \Illuminate\Support\HtmlString(
                                        '<span class="text-sm text-warning-600">⚠️ File tidak ditemukan di storage.</span>'
                                    );
                                }

                                $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                                    'payment.proof.view',
                                    now()->addMinutes(5),
                                    ['payment' => $record->id]
                                );
                                return new \Illuminate\Support\HtmlString(
                                    '<a href="' . $url . '" target="_blank" ' .
                                        'class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-primary-600 text-white rounded hover:bg-primary-700">' .
                                        '👁️ Lihat Bukti Transfer</a>'
                                );
                            }),
                    ]),

                Forms\Components\Section::make('Verifikasi Admin')
                    ->icon('heroicon-o-check-badge')
                    ->description('Ubah status untuk memverifikasi atau menolak pembayaran. Perubahan status akan otomatis mengupdate status aplikasi pelamar.')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending'  => 'Menunggu Verifikasi',
                                'verified' => 'Terverifikasi',
                                'rejected' => 'Ditolak',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\DateTimePicker::make('verified_at')
                            ->label('Tanggal Verifikasi')
                            ->helperText('Akan terisi otomatis saat status diubah ke "Terverifikasi".'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.biodata.nama_lengkap')
                    ->label('Nama Pelamar')
                    ->searchable()
                    ->placeholder('Belum diisi')
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR', divideBy: 1)
                    ->sortable(),

                Tables\Columns\TextColumn::make('bank_pengirim')
                    ->label('Bank')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending'  => 'warning',
                        'verified' => 'success',
                        'rejected' => 'danger',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending'  => 'Menunggu',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default    => $state,
                    }),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Tgl Transfer')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('verified_at')
                    ->label('Tgl Verifikasi')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diupload')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                // Quick action: Verify
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Payment $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Pembayaran?')
                    ->modalDescription(fn(Payment $record) =>
                    'Pembayaran dari ' . ($record->user?->biodata?->nama_lengkap ?? $record->user?->name) .
                        ' sebesar Rp ' . number_format((float) $record->amount, 0, ',', '.') . ' akan diverifikasi.')
                    ->action(function (Payment $record) {
                        $record->update([
                            'status' => 'verified',
                            'verified_at' => now(),
                        ]);
                        Notification::make()
                            ->title('Pembayaran berhasil diverifikasi')
                            ->success()
                            ->send();
                    }),

                // Quick action: Reject
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Payment $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pembayaran?')
                    ->modalDescription('Pelamar akan diminta upload ulang bukti pembayaran.')
                    ->action(function (Payment $record) {
                        $record->update([
                            'status' => 'rejected',
                            'verified_at' => null,
                        ]);
                        Notification::make()
                            ->title('Pembayaran ditolak')
                            ->warning()
                            ->send();
                    }),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum ada pembayaran')
            ->emptyStateDescription('Bukti pembayaran dari pelamar akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'view'  => Pages\ViewPayment::route('/{record}'),
            'edit'  => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
