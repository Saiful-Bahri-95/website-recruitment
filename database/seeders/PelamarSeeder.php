<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Biodata;
use App\Models\Education;
use App\Models\WorkExperience;
use App\Models\EmergencyContact;
use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PelamarSeeder extends Seeder
{
    public function run(): void
    {
        $namaDepan = ['Andi', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gita', 'Hadi', 'Indra', 'Joko'];
        $namaBelakang = ['Pratama', 'Wijaya', 'Saputra', 'Lestari', 'Anggraini', 'Setiawan', 'Hidayat'];
        $kotaList = ['Surabaya', 'Jakarta', 'Bandung', 'Yogyakarta', 'Malang', 'Semarang'];
        $kampusList = ['Universitas Airlangga', 'Universitas Indonesia', 'ITB', 'UGM', 'ITS'];
        $jurusanList = ['Manajemen', 'Akuntansi', 'Teknik Informatika', 'Hukum', 'Psikologi'];
        $statusList = ['draft', 'submitted', 'paid', 'verified', 'rejected'];

        for ($i = 1; $i <= 20; $i++) {
            $namaLengkap = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
            $emailSlug = strtolower(str_replace(' ', '.', $namaLengkap)) . $i;

            // Buat User
            $user = User::create([
                'name' => $namaLengkap,
                'email' => $emailSlug . '@email.com',
                'password' => Hash::make('password'),
                'role' => 'pelamar',
            ]);

            // Buat Biodata
            Biodata::create([
                'user_id' => $user->id,
                'nama_lengkap' => $namaLengkap,
                'alamat_ktp' => 'Jl. Sudirman No. ' . rand(1, 200) . ', ' . $kotaList[array_rand($kotaList)],
                'alamat_domisili' => 'Jl. Diponegoro No. ' . rand(1, 200) . ', ' . $kotaList[array_rand($kotaList)],
                'tempat_lahir' => $kotaList[array_rand($kotaList)],
                'tanggal_lahir' => now()->subYears(rand(22, 35))->subDays(rand(1, 365)),
                'tinggi_badan' => rand(155, 180),
                'berat_badan' => rand(50, 80),
            ]);

            // Buat Pendidikan
            Education::create([
                'user_id' => $user->id,
                'nama_sekolah' => $kampusList[array_rand($kampusList)],
                'jurusan' => $jurusanList[array_rand($jurusanList)],
                'tahun_lulus' => rand(2018, 2024),
            ]);

            // Buat 1-3 Pengalaman Kerja
            $jumlahPengalaman = rand(1, 3);
            for ($j = 1; $j <= $jumlahPengalaman; $j++) {
                WorkExperience::create([
                    'user_id' => $user->id,
                    'posisi' => ['Staff Marketing', 'Sales Officer', 'Admin', 'Customer Service'][rand(0, 3)],
                    'nama_perusahaan' => 'PT ' . ['Maju Bersama', 'Sejahtera Abadi', 'Mitra Sukses'][rand(0, 2)],
                    'urutan' => $j,
                ]);
            }

            // Buat 2 Kontak Darurat
            EmergencyContact::create([
                'user_id' => $user->id,
                'nama' => 'Bapak ' . $namaDepan[array_rand($namaDepan)],
                'hubungan' => 'Orang Tua',
                'nomor_hp' => '+628' . rand(1, 9) . rand(10000000, 99999999),
            ]);

            EmergencyContact::create([
                'user_id' => $user->id,
                'nama' => 'Ibu ' . $namaDepan[array_rand($namaDepan)],
                'hubungan' => 'Orang Tua',
                'nomor_hp' => '+628' . rand(1, 9) . rand(10000000, 99999999),
            ]);

            // Buat 8-12 dokumen dummy random
            $allTypes = array_keys(\App\Models\Document::TYPES);
            $jumlahDokumen = rand(8, 12);
            $selectedTypes = array_slice(
                array_values(array_unique($allTypes)),
                0,
                $jumlahDokumen
            );

            foreach ($selectedTypes as $type) {
                \App\Models\Document::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'file_path' => 'documents/dummy.pdf',
                    'original_name' => $type . '_dummy.pdf',
                    'uploaded_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Buat Application dengan status random
            Application::create([
                'user_id' => $user->id,
                'status' => $statusList[array_rand($statusList)],
                'submitted_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        $this->command->info('20 pelamar dummy berhasil dibuat!');
    }
}
