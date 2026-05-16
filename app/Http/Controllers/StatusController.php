<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Defensif: application bisa null kalau belum dibuat
        $application = $user->application;

        // ===== Hitung progress tiap step =====
        // Step 1: Biodata — dianggap selesai kalау relasi biodata sudah ada
        $biodataDone = $user->biodata()->exists();

        // Step 2: Dokumen — selesai kalau sudah upload minimal 10 dokumen wajib
        $documentsCount = $user->documents()->count();
        $documentsDone = $documentsCount >= 10;

        // Step 3: Pembayaran — selesai kalau status application minimal 'paid'
        $status = $application?->status ?? 'draft';
        $paymentDone = in_array($status, ['paid', 'verified']);

        // Step 4: Verifikasi — selesai kalau status 'verified'
        $verificationDone = $status === 'verified';

        // ===== Susun daftar step untuk timeline =====
        $steps = [
            [
                'key'   => 'biodata',
                'title' => 'Pengisian Biodata',
                'desc'  => $biodataDone
                    ? 'Data diri telah dilengkapi'
                    : 'Lengkapi data diri sesuai KTP',
                'done'  => $biodataDone,
            ],
            [
                'key'   => 'dokumen',
                'title' => 'Upload Dokumen',
                'desc'  => $documentsDone
                    ? "{$documentsCount} dokumen diupload (10 wajib)"
                    : "Unggah dokumen persyaratan ({$documentsCount}/10 wajib)",
                'done'  => $documentsDone,
            ],
            [
                'key'   => 'pembayaran',
                'title' => 'Pembayaran',
                'desc'  => match (true) {
                    $verificationDone               => 'Pembayaran telah diverifikasi',
                    $paymentDone                    => 'Pembayaran telah diverifikasi',
                    $status === 'submitted'         => 'Bukti pembayaran sedang ditinjau',
                    $status === 'rejected'          => 'Bukti pembayaran ditolak, mohon upload ulang',
                    default                         => 'Upload bukti pembayaran biaya administrasi',
                },
                'done'  => $paymentDone,
            ],
            [
                'key'   => 'verifikasi',
                'title' => 'Verifikasi Admin',
                'desc'  => match (true) {
                    $verificationDone       => 'Berkas telah diverifikasi oleh tim rekrutmen',
                    $status === 'rejected'  => 'Berkas memerlukan revisi, lihat catatan dari admin',
                    $paymentDone            => 'Berkas sedang ditinjau oleh tim rekrutmen',
                    default                 => 'Menunggu kelengkapan tahapan sebelumnya',
                },
                'done'  => $verificationDone,
            ],
        ];

        // ===== Tentukan step yang sedang aktif =====
        // Step aktif = step pertama yang belum 'done'
        $activeIndex = null;
        foreach ($steps as $i => $step) {
            if (!$step['done']) {
                $activeIndex = $i;
                break;
            }
        }
        // Kalau semua done, tidak ada step aktif (proses selesai)

        // ===== Tandai tiap step: done / active / upcoming =====
        foreach ($steps as $i => &$step) {
            if ($step['done']) {
                $step['state'] = 'done';
            } elseif ($i === $activeIndex) {
                $step['state'] = 'active';
            } else {
                $step['state'] = 'upcoming';
            }
        }
        unset($step);

        // ===== Info untuk header status =====
        $isRejected = $status === 'rejected';
        $isVerified = $status === 'verified';
        $adminNotes = $application?->admin_notes;

        // Label & warna status keseluruhan
        [$statusLabel, $statusColor] = match ($status) {
            'draft'     => ['Belum Lengkap', 'gray'],
            'submitted' => ['Menunggu Pembayaran', 'amber'],
            'paid'      => ['Menunggu Verifikasi', 'blue'],
            'verified'  => ['Terverifikasi', 'emerald'],
            'rejected'  => ['Data Perlu Diperbaiki', 'red'],
            default     => ['Belum Lengkap', 'gray'],
        };

        return view('status.index', compact(
            'user',
            'application',
            'steps',
            'status',
            'statusLabel',
            'statusColor',
            'isRejected',
            'isVerified',
            'adminNotes',
            'documentsCount',
        ));
    }
}
