<div class="page">

    <div class="grid">

        {{-- SIDEBAR --}}
        <div class="col-left">

            <div class="sidebar-card">

                <div class="avatar"></div>

                <div class="info-item">
                    <div class="label">Nama Lengkap</div>
                    <div class="value">
                        {{ $user->biodata->nama_lengkap ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="label">Email</div>
                    <div class="value">
                        {{ $user->email }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="label">Tempat Lahir</div>
                    <div class="value">
                        {{ $user->biodata->tempat_lahir ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="label">Tanggal Lahir</div>
                    <div class="value">
                        {{ $user->biodata->tanggal_lahir?->format('d F Y') ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="label">Tinggi Badan</div>
                    <div class="value">
                        {{ $user->biodata->tinggi_badan ?? '-' }} cm
                    </div>
                </div>

                <div class="info-item">
                    <div class="label">Berat Badan</div>
                    <div class="value">
                        {{ $user->biodata->berat_badan ?? '-' }} kg
                    </div>
                </div>

            </div>

            <div class="sidebar-card">

                <div class="section-title">
                    Kontak
                </div>

                <div class="info-item">
                    <div class="label">Alamat KTP</div>
                    <div class="value">
                        {{ $user->biodata->alamat_ktp ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="label">Domisili</div>
                    <div class="value">
                        {{ $user->biodata->alamat_domisili ?? '-' }}
                    </div>
                </div>

            </div>

        </div>

        {{-- CONTENT --}}
        <div class="col-right">

            {{-- PENDIDIKAN --}}
            <div class="section">

                <div class="section-title">
                    Riwayat Pendidikan
                </div>

                @forelse($user->educations as $edu)

                    <div class="timeline-item">
                        <div class="value">
                            {{ $edu->nama_sekolah }}
                        </div>

                        <div class="label">
                            {{ $edu->jurusan ?? '-' }}
                            •
                            {{ $edu->tahun_lulus }}
                        </div>
                    </div>

                @empty

                    <div class="label">
                        Belum ada riwayat pendidikan
                    </div>

                @endforelse

            </div>

            {{-- PENGALAMAN --}}
            <div class="section">

                <div class="section-title">
                    Pengalaman Kerja
                </div>

                <div class="timeline">

                    @forelse($user->workExperiences as $exp)

                        <div class="timeline-item">

                            <div class="value">
                                {{ $exp->posisi }}
                            </div>

                            <div class="label">
                                {{ $exp->nama_perusahaan }}
                            </div>

                        </div>

                    @empty

                        <div class="label">
                            Belum ada pengalaman kerja
                        </div>

                    @endforelse

                </div>

            </div>

            {{-- KONTAK DARURAT --}}
            <div class="section">

                <div class="section-title">
                    Kontak Darurat
                </div>

                @forelse($user->emergencyContacts as $contact)

                    <div class="sidebar-card" style="margin-bottom: 15px;">

                        <div class="info-item">
                            <div class="label">Nama</div>
                            <div class="value">
                                {{ $contact->nama }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="label">Hubungan</div>
                            <div class="value">
                                {{ $contact->hubungan }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="label">Nomor HP</div>
                            <div class="value">
                                {{ $contact->nomor_hp }}
                            </div>
                        </div>

                    </div>

                @empty

                    <div class="label">
                        Belum ada kontak darurat
                    </div>

                @endforelse

            </div>

        </div>

    </div>

    <div class="footer">
        <div>
            PT Anugerah Global Recruitment
        </div>

        <div>
            Generated {{ now()->format('d M Y H:i') }}
        </div>
    </div>

</div>
