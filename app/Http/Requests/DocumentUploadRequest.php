<?php

namespace App\Http\Requests;

use App\Models\Document;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DocumentUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $typeRules = ['required', 'string'];

        if (defined(Document::class . '::DOCUMENT_TYPES')) {
            $typeRules[] = Rule::in(array_keys(constant(Document::class . '::DOCUMENT_TYPES')));
        }

        return [
            'type' => $typeRules,
            'file' => [
                'required',
                'file',
                'max:5120', // 5 MB
                'mimes:pdf,jpg,jpeg,png',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Jenis dokumen wajib dipilih.',
            'type.in' => 'Jenis dokumen tidak valid.',

            'file.required' => 'Silakan pilih file untuk diupload.',
            'file.file' => 'File tidak valid.',
            'file.max' => 'Ukuran file maksimal 5 MB.',
            'file.mimes' => 'Format file harus PDF, JPG, atau PNG.',
        ];
    }
}
