<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentUploadRequest;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PaymentController extends Controller
{
    /**
     * Daftar rekening bank (placeholder).
     */
    private const BANK_ACCOUNTS = [
        [
            'bank' => 'Bank Mandiri',
            'short' => 'Mandiri',
            'account_number' => '142-00-1098765-4',
            'account_name' => 'PT Anggita Global Recruitment',
            'color' => '#003D79',
        ],
        [
            'bank' => 'Bank BCA',
            'short' => 'BCA',
            'account_number' => '0123-456-789',
            'account_name' => 'PT Anggita Global Recruitment',
            'color' => '#0060AF',
        ],
        [
            'bank' => 'Bank BRI',
            'short' => 'BRI',
            'account_number' => '0987-01-234567-50-3',
            'account_name' => 'PT Anggita Global Recruitment',
            'color' => '#00529C',
        ],
    ];

    /**
     * Tampilkan halaman pembayaran.
     */
    public function index()
    {
        $user = Auth::user();

        // ===== KUNCI AKSES: biodata + 10 dokumen wajib harus lengkap dulu =====
        $progress = $user->getProgressData();

        if (!$progress['steps']['biodata']) {
            return redirect()->route('biodata.edit')
                ->with('status', 'Lengkapi biodata terlebih dahulu sebelum melakukan pembayaran.');
        }

        if (!$progress['steps']['dokumen']) {
            return redirect()->route('documents.index')
                ->with('status', 'Lengkapi semua dokumen wajib terlebih dahulu sebelum melakukan pembayaran.');
        }

        $payment = $user->payment;

        $amount = Setting::get('payment_amount', 350000);
        $deadlineDays = Setting::get('payment_deadline_days', 7);

        return view('pelamar.payment.index', [
            'user' => $user,
            'payment' => $payment,
            'amount' => $amount,
            'deadlineDays' => $deadlineDays,
            'banks' => self::BANK_ACCOUNTS,
        ]);
    }

    /**
     * Upload bukti pembayaran.
     */
    public function store(PaymentUploadRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $file = $request->file('proof');
        $amount = Setting::get('payment_amount', 350000);

        // Delete existing payment proof jika sudah ada
        $existing = $user->payment;
        if ($existing && $existing->proof_path) {
            if (Storage::disk('private')->exists($existing->proof_path)) {
                Storage::disk('private')->delete($existing->proof_path);
            }
        }

        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = sprintf(
            'payment_%s_%s.%s',
            $user->id,
            Str::random(10),
            $extension
        );

        // Store file
        $path = $file->storeAs('payments', $filename, 'private');

        // Update or create payment record
        // Update or create payment record
        Payment::updateOrCreate(
            ['user_id' => $user->id],
            [
                'amount' => $amount,
                'bank_pengirim' => $request->input('bank_pengirim'),
                'nama_pengirim' => $request->input('nama_pengirim'),
                'proof_path' => $path,
                'status' => 'pending',
                'paid_at' => $request->input('paid_at'),
                'verified_at' => null,
            ]
        );

        // Audit log
        Log::info('Payment proof uploaded by pelamar', [
            // ... biarkan seperti semula ...
        ]);

        // Audit log
        Log::info('Payment proof uploaded by pelamar', [
            'user_id' => $user->id,
            'amount' => $amount,
            'bank' => $request->input('bank_pengirim'),
            'filename' => $filename,
            'ip' => $request->ip(),
        ]);

        return back()->with('status', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    /**
     * Tampilkan file bukti pembayaran (akses via signed URL, untuk admin).
     */
    public function viewProof(Payment $payment)
    {
        abort_unless($payment->proof_path, 404, 'Bukti pembayaran tidak ditemukan.');
        abort_unless(Storage::disk('private')->exists($payment->proof_path), 404, 'File tidak tersedia.');

        $mime = Storage::disk('private')->mimeType($payment->proof_path);
        $filename = basename($payment->proof_path);

        return response()->file(
            Storage::disk('private')->path($payment->proof_path),
            [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]
        );
    }
}
