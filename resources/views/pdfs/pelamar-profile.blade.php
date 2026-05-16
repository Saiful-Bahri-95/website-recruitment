<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profil Pelamar - {{ $user->biodata->nama_lengkap ?? $user->name }}</title>
    <style>
        @page {
            margin: 0;
            size: A4;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* === COVER PAGE === */
        .cover-page {
            page-break-after: always;
            padding: 60px 50px;
            position: relative;
            min-height: 100vh;
        }

        .cover-page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
        }

        .header-brand {
            text-align: center;
            margin-bottom: 80px;
        }

        .header-brand .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
        }

        .header-brand .tagline {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
            letter-spacing: 2px;
        }

        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #1e3a8a 50%, transparent 100%);
            margin: 30px 0;
        }

        .cover-title {
            text-align: center;
            margin: 60px 0;
        }

        .cover-title .doc-type {
            font-size: 14px;
            color: #666;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .cover-title .applicant-name {
            font-size: 32px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 15px 0;
        }

        .cover-info {
            background: #f8fafc;
            border-left: 4px solid #1e3a8a;
            padding: 25px 30px;
            margin: 40px 60px;
        }

        .cover-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .cover-info td {
            padding: 8px 0;
            font-size: 12px;
        }

        .cover-info td:first-child {
            color: #666;
            width: 40%;
        }

        .cover-info td:last-child {
            color: #1e3a8a;
            font-weight: 600;
        }

        .cover-footer {
            position: absolute;
            bottom: 40px;
            left: 50px;
            right: 50px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }

        /* === CONTENT PAGES === */
        .content-page {
            padding: 30px 50px 50px 50px;
            page-break-after: always;
        }

        .content-page:last-child {
            page-break-after: auto;
        }

        .page-header {
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .page-header .page-title {
            color: #1e3a8a;
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .page-header .applicant-ref {
            color: #999;
            font-size: 10px;
            float: right;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e3a8a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11px;
            vertical-align: top;
        }

        .info-table td.label {
            color: #666;
            width: 30%;
        }

        .info-table td.value {
            color: #1f2937;
            font-weight: 500;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .data-table th {
            background: #1e3a8a;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }

        .empty-state {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>

{{-- ============ COVER PAGE ============ --}}
<div class="cover-page">
    <div class="header-brand">
        <div class="company-name">ANGGITA GLOBAL</div>
        <div class="tagline">RECRUITMENT</div>
    </div>

    <div class="divider"></div>

    <div class="cover-title">
        <div class="doc-type">Profil Pelamar</div>
        <div class="applicant-name">{{ $user->biodata->nama_lengkap ?? $user->name }}</div>
        <div style="color: #666; font-size: 11px;">Calon Karyawan #{{ $user->id }}</div>
    </div>

    <div class="cover-info">
        <table>
            <tr>
                <td>Email</td>
                <td>{{ $user->email }}</td>
            </tr>
            @if($user->biodata)
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>{{ $user->biodata->tempat_lahir }}, {{ $user->biodata->tanggal_lahir?->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Domisili</td>
                <td>{{ $user->biodata->alamat_domisili ?? $user->biodata->alamat_ktp }}</td>
            </tr>
            @endif
            <tr>
                <td>Dokumen Terupload</td>
                <td>{{ $user->documents->count() }} dokumen</td>
            </tr>
            <tr>
                <td>Tanggal Generate</td>
                <td>{{ now()->format('d F Y, H:i') }} WIB</td>
            </tr>
        </table>
    </div>

    <div class="cover-footer">
        <strong>PT ANGGITA GLOBAL RECRUITMENT</strong><br>
        Dokumen ini berisi data pribadi yang dilindungi oleh UU PDP No. 27 Tahun 2022.
    </div>
</div>

{{-- ============ HALAMAN BIODATA DETAIL ============ --}}
<div class="content-page">
    <div class="page-header">
        <div class="page-title">Biodata Diri</div>
        <div class="applicant-ref">#{{ $user->id }} · {{ $user->biodata->nama_lengkap ?? $user->name }}</div>
    </div>

    @if($user->biodata)
    <div class="section">
        <div class="section-title">Identitas Pribadi</div>
        <table class="info-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="value">{{ $user->biodata->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td class="value">{{ $user->biodata->tempat_lahir }}, {{ $user->biodata->tanggal_lahir?->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tinggi / Berat Badan</td>
                <td class="value">{{ $user->biodata->tinggi_badan ?? '-' }} cm / {{ $user->biodata->berat_badan ?? '-' }} kg</td>
            </tr>
            <tr>
                <td class="label">Alamat KTP</td>
                <td class="value">{{ $user->biodata->alamat_ktp }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Domisili</td>
                <td class="value">{{ $user->biodata->alamat_domisili ?? '-' }}</td>
            </tr>
        </table>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Riwayat Pendidikan</div>
        @if($user->educations->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Sekolah / Institusi</th>
                    <th>Jurusan</th>
                    <th>Tahun Lulus</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->educations as $edu)
                <tr>
                    <td>{{ $edu->nama_sekolah }}</td>
                    <td>{{ $edu->jurusan ?? '-' }}</td>
                    <td>{{ $edu->tahun_lulus }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">Belum ada riwayat pendidikan.</div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Pengalaman Kerja</div>
        @if($user->workExperiences->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Posisi</th>
                    <th>Perusahaan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->workExperiences as $exp)
                <tr>
                    <td>{{ $exp->urutan }}</td>
                    <td>{{ $exp->posisi }}</td>
                    <td>{{ $exp->nama_perusahaan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">Belum ada pengalaman kerja.</div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Kontak Darurat</div>
        @if($user->emergencyContacts->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Hubungan</th>
                    <th>Nomor HP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->emergencyContacts as $contact)
                <tr>
                    <td>{{ $contact->nama }}</td>
                    <td>{{ $contact->hubungan }}</td>
                    <td>{{ $contact->nomor_hp }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">Belum ada kontak darurat.</div>
        @endif
    </div>
</div>

</body>
</html>
