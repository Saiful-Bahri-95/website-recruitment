<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'payment_amount',
                'value' => '350000',
                'type' => 'integer',
                'label' => 'Nominal Pembayaran',
                'description' => 'Biaya administrasi yang harus dibayar pelamar (dalam Rupiah)',
            ],
            [
                'key' => 'payment_deadline_days',
                'value' => '7',
                'type' => 'integer',
                'label' => 'Deadline Pembayaran (hari)',
                'description' => 'Jumlah hari maksimum untuk melakukan pembayaran setelah registrasi',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
