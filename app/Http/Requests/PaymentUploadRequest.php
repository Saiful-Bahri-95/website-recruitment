<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PaymentUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'bank_pengirim' => ['required', 'string', 'max:100'],
            'nama_pengirim' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'date', 'before_or_equal:now'],
            'proof' => [
                'required',
                'file',
                'max:3072', // 3 MB
                'mimes:jpg,jpeg,png,pdf',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'bank_pengirim.required' => 'Nama bank pengirim wajib diisi.',
            'nama_pengirim.required' => 'Nama pemilik rekening pengirim wajib diisi.',

            'paid_at.required' => 'Tanggal & jam transfer wajib diisi.',
            'paid_at.date' => 'Format tanggal tidak valid.',
            'paid_at.before_or_equal' => 'Tanggal transfer tidak boleh di masa depan.',

            'proof.required' => 'Silakan upload bukti pembayaran.',
            'proof.file' => 'File tidak valid.',
            'proof.max' => 'Ukuran file maksimal 3 MB.',
            'proof.mimes' => 'Format file harus JPG, PNG, atau PDF.',
        ];
    }
}
