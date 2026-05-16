<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Saat status diubah ke verified, isi verified_at otomatis kalau kosong
        if ($data['status'] === 'verified' && empty($data['verified_at'])) {
            $data['verified_at'] = now();
        }

        // Saat status mundur dari verified (jadi pending/rejected), kosongkan verified_at
        if ($data['status'] !== 'verified') {
            $data['verified_at'] = null;
        }

        return $data;
    }
}
