<?php

namespace App\Observers;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentObserver
{
    /**
     * Saat payment baru dibuat (pelamar upload pertama kali).
     * application.status: draft → submitted
     */
    public function created(Payment $payment): void
    {
        $application = $payment->user?->application;

        if (!$application) {
            Log::warning('PaymentObserver@created: application tidak ditemukan untuk user', [
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
            ]);
            return;
        }

        // Hanya maju dari draft. Jangan menyentuh status yang sudah lebih tinggi.
        if ($application->status === 'draft') {
            $application->update(['status' => 'submitted']);

            Log::info('PaymentObserver@created: application status submitted', [
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
            ]);
        }
    }

    /**
     * Saat payment.status berubah (admin verifikasi / tolak, atau pelamar re-upload).
     */
    public function updated(Payment $payment): void
    {
        // Hanya reaksi kalau kolom 'status' yang berubah.
        // Update kolom lain (mis. proof_path, paid_at) tidak memicu sinkronisasi.
        if (!$payment->wasChanged('status')) {
            return;
        }

        $application = $payment->user?->application;

        if (!$application) {
            Log::warning('PaymentObserver@updated: application tidak ditemukan', [
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
            ]);
            return;
        }

        // SAFETY NET: kalau application sudah 'verified' (berkas keseluruhan sudah
        // diverifikasi admin lewat UserResource), JANGAN sentuh. Verifikasi berkas
        // adalah keputusan akhir admin yang lebih tinggi dari status payment.
        if ($application->status === 'verified') {
            return;
        }

        $newAppStatus = match ($payment->status) {
            'verified' => 'paid',       // admin verifikasi pembayaran
            'rejected' => 'submitted',  // admin tolak bukti — kembali ke kondisi "menunggu"
            'pending'  => 'submitted',  // pelamar re-upload — anggap submitted ulang
            default    => null,
        };

        if ($newAppStatus && $application->status !== $newAppStatus) {
            $application->update(['status' => $newAppStatus]);

            Log::info('PaymentObserver@updated: application status synced', [
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
                'payment_status' => $payment->status,
                'application_status' => $newAppStatus,
            ]);
        }
    }
}
