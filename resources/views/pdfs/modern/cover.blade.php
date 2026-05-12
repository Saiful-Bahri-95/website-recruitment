<div class="page cover-page">
    <div class="company-badge">
        PT ANGGITA GLOBAL RECRUITMENT
    </div>

    <div class="cover-title">
        <p>PROFIL PELAMAR</p>

        <h1>
            {{ $user->biodata->nama_lengkap ?? $user->name }}
        </h1>

        <p>
            Generated {{ now()->format('d F Y H:i') }}
        </p>
    </div>

    <div class="profile-card">
        <table width="100%">
            <tr>
                <td width="50%">
                    <div class="label">Email</div>
                    <div class="value">{{ $user->email }}</div>
                </td>

                <td width="50%">
                    <div class="label">Dokumen</div>
                    <div class="value">
                        {{ $user->documents->count() }} Dokumen
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div>CONFIDENTIAL DOCUMENT</div>
        <div>PT AGR</div>
    </div>
</div>
