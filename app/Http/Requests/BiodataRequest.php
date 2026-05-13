<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BiodataRequest extends FormRequest
{
    /**
     * Hanya user authenticated yang boleh akses (otomatis dicek di middleware).
     */
    public function authorize(): bool
    {
        return \Illuminate\Support\Facades\Auth::check();
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            // === IDENTITAS PRIBADI ===
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'tinggi_badan' => ['nullable', 'integer', 'min:100', 'max:250'],
            'berat_badan' => ['nullable', 'integer', 'min:30', 'max:200'],
            'alamat_ktp' => ['required', 'string', 'max:500'],
            'alamat_domisili' => ['nullable', 'string', 'max:500'],

            // === RIWAYAT PENDIDIKAN (array of objects) ===
            'educations' => ['required', 'array', 'min:1', 'max:5'],
            'educations.*.nama_sekolah' => ['required', 'string', 'max:255'],
            'educations.*.jurusan' => ['nullable', 'string', 'max:100'],
            'educations.*.tahun_lulus' => ['required', 'integer', 'min:1970', 'max:' . date('Y')],

            // === PENGALAMAN KERJA (optional, 0-5) ===
            'work_experiences' => ['nullable', 'array', 'max:5'],
            'work_experiences.*.posisi' => ['nullable', 'string', 'max:100'],
            'work_experiences.*.nama_perusahaan' => ['nullable', 'string', 'max:255'],

            // === KONTAK DARURAT (1-3) ===
            'emergency_contacts' => ['required', 'array', 'min:1', 'max:3'],
            'emergency_contacts.*.nama' => ['required', 'string', 'max:100'],
            'emergency_contacts.*.hubungan' => ['required', 'string', 'max:50'],
            'emergency_contacts.*.nomor_hp' => ['required', 'string', 'max:20', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/'],
        ];
    }

    /**
     * Custom error messages (Indonesian).
     */
    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => 'Kolom :attribute harus berupa teks.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'before' => 'Kolom :attribute harus sebelum hari ini.',
            'integer' => 'Kolom :attribute harus berupa angka.',
            'min' => 'Kolom :attribute minimal :min.',
            'max' => 'Kolom :attribute maksimal :max.',
            'array' => 'Kolom :attribute harus berupa daftar.',

            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tinggi_badan.min' => 'Tinggi badan minimal 100 cm.',
            'tinggi_badan.max' => 'Tinggi badan maksimal 250 cm.',
            'berat_badan.min' => 'Berat badan minimal 30 kg.',
            'berat_badan.max' => 'Berat badan maksimal 200 kg.',

            'educations.required' => 'Riwayat pendidikan wajib diisi minimal 1.',
            'educations.min' => 'Riwayat pendidikan wajib diisi minimal 1.',
            'educations.max' => 'Riwayat pendidikan maksimal 5.',
            'educations.*.nama_sekolah.required' => 'Nama sekolah/institusi wajib diisi.',
            'educations.*.tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'educations.*.tahun_lulus.min' => 'Tahun lulus minimal 1970.',
            'educations.*.tahun_lulus.max' => 'Tahun lulus tidak boleh lebih dari ' . date('Y') . '.',

            'work_experiences.max' => 'Pengalaman kerja maksimal 5.',

            'emergency_contacts.required' => 'Kontak darurat wajib diisi minimal 1.',
            'emergency_contacts.min' => 'Kontak darurat wajib diisi minimal 1.',
            'emergency_contacts.max' => 'Kontak darurat maksimal 3.',
            'emergency_contacts.*.nama.required' => 'Nama kontak darurat wajib diisi.',
            'emergency_contacts.*.hubungan.required' => 'Hubungan dengan kontak wajib diisi.',
            'emergency_contacts.*.nomor_hp.required' => 'Nomor HP kontak darurat wajib diisi.',
            'emergency_contacts.*.nomor_hp.regex' => 'Format nomor HP tidak valid (contoh: 08123456789).',
        ];
    }

    /**
     * Friendly attribute names.
     */
    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'nama lengkap',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'tinggi_badan' => 'tinggi badan',
            'berat_badan' => 'berat badan',
            'alamat_ktp' => 'alamat KTP',
            'alamat_domisili' => 'alamat domisili',
        ];
    }
}
