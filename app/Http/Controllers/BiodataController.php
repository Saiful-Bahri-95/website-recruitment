<?php

namespace App\Http\Controllers;

use App\Http\Requests\BiodataRequest;
use App\Models\Biodata;
use App\Models\Education;
use App\Models\EmergencyContact;
use App\Models\WorkExperience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BiodataController extends Controller
{
    /**
     * Display the biodata form (create or edit).
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load([
            'biodata',
            'educations',
            'workExperiences' => fn($q) => $q->orderBy('urutan'),
            'emergencyContacts',
        ]);

        return view('pelamar.biodata.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Save biodata data.
     */
    public function update(BiodataRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $request->validated();

        DB::transaction(function () use ($user, $data) {

            // === 1. UPDATE OR CREATE BIODATA ===
            Biodata::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_lengkap' => $data['nama_lengkap'],
                    'tempat_lahir' => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'tinggi_badan' => $data['tinggi_badan'] ?? null,
                    'berat_badan' => $data['berat_badan'] ?? null,
                    'alamat_ktp' => $data['alamat_ktp'],
                    'alamat_domisili' => $data['alamat_domisili'] ?? $data['alamat_ktp'],
                ]
            );

            // === 2. RESET & SAVE EDUCATIONS ===
            Education::where('user_id', $user->id)->delete();
            foreach ($data['educations'] as $edu) {
                if (!empty($edu['nama_sekolah'])) {
                    Education::create([
                        'user_id' => $user->id,
                        'nama_sekolah' => $edu['nama_sekolah'],
                        'jurusan' => $edu['jurusan'] ?? null,
                        'tahun_lulus' => $edu['tahun_lulus'],
                    ]);
                }
            }

            // === 3. RESET & SAVE WORK EXPERIENCES ===
            WorkExperience::where('user_id', $user->id)->delete();
            if (!empty($data['work_experiences'])) {
                foreach ($data['work_experiences'] as $index => $work) {
                    if (!empty($work['posisi']) && !empty($work['nama_perusahaan'])) {
                        WorkExperience::create([
                            'user_id' => $user->id,
                            'urutan' => $index + 1,
                            'posisi' => $work['posisi'],
                            'nama_perusahaan' => $work['nama_perusahaan'],
                        ]);
                    }
                }
            }

            // === 4. RESET & SAVE EMERGENCY CONTACTS ===
            EmergencyContact::where('user_id', $user->id)->delete();
            foreach ($data['emergency_contacts'] as $contact) {
                if (!empty($contact['nama']) && !empty($contact['nomor_hp'])) {
                    EmergencyContact::create([
                        'user_id' => $user->id,
                        'nama' => $contact['nama'],
                        'hubungan' => $contact['hubungan'],
                        'nomor_hp' => $contact['nomor_hp'],
                    ]);
                }
            }
        });

        return redirect()->route('dashboard')->with('status', 'biodata-saved');
    }
}
