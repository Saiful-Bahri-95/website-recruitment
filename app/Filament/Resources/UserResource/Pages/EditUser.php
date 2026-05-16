<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Jika ada nested data 'application' dari relationship section
        if (isset($data['application'])) {
            $app = $data['application'];

            // Auto-set tanggal verifikasi saat status jadi verified
            if (($app['status'] ?? null) === 'verified' && empty($app['verified_at'])) {
                $data['application']['verified_at'] = now();
            }

            // Kosongkan verified_at kalau status mundur dari verified
            if (($app['status'] ?? null) !== 'verified') {
                $data['application']['verified_at'] = null;
            }
        }

        return $data;
    }
}
