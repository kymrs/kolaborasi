<style>
    /* Styling box input Select2 */
    .select2-container .select2-selection--single {
        height: 38px;
        /* Sama seperti form-control Bootstrap */
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        transition: 300ms;
    }

    /* Saat select2 dalam keadaan fokus */
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--single:hover {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Styling dropdown Select2 */
    .select2-container--default .select2-results__option {
        padding: 10px;
        font-size: 1rem;
    }

    /* Hover pada dropdown */
    .select2-container--default .select2-results__option--highlighted {
        background-color: #007bff;
        color: white;
    }

    /* Styling placeholder */
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }

    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        font-size: 2em;
    }

    .rating input {
        display: none;
    }

    .rating label {
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s;
        position: relative;
        right: 107px;
        bottom: 5px;
    }

    .rating input:checked~label,
    .rating label:hover,
    .rating label:hover~label {
        color: #f5b301;
    }

    /* Tambahkan di <style> atau file CSS Anda */
    .tag {
        display: inline-block;
        background: #1a2035;
        color: #fff;
        border-radius: 15px;
        padding: 3px 12px 3px 10px;
        margin: 2px 2px 2px 0;
        font-size: 0.95em;
        position: relative;
    }

    .tag .remove-tag {
        margin-left: 8px;
        cursor: pointer;
        color: #fff;
        font-weight: bold;
    }

    .tag .remove-tag:hover {
        color: red;
    }
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>
    <form id="form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header text-right">
                        <a style="background-color: rgb(36, 44, 73);" class="btn btn-secondary btn-sm" onclick="window.history.back()">
                            <i class="fas fa-chevron-left"></i>&nbsp;Back
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="status_kerja">Status Kerja</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="status_kerja" id="status_kerja">
                                            <option value="" hidden>Pilih Status Kerja</option>
                                            <option value="aktif" <?= ($master->status_kerja == 'aktif') ? 'selected' : '' ?>>Aktif</option>
                                            <option value="nonaktif" <?= ($master->status_kerja == 'nonaktif') ? 'selected' : '' ?>>Non Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="npk">NPK</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="npk" id="npk" value="<?= $master->npk ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="nama_lengkap">Nama Lengkap</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" value="<?= $master->nama_lengkap ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="jenis_kelamin">Jenis Kelamin</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                            <option value="" hidden>Pilih Jenis Kelamin</option>
                                            <option value="laki-laki" <?= ($master->jenis_kelamin == 'laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                                            <option value="perempuan" <?= ($master->jenis_kelamin == 'perempuan') ? 'selected' : '' ?>>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tempat_lahir">Tempat Lahir</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="<?= $master->tempat_lahir ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tgl_lahir">Tanggal Lahir</label>
                                    <div class="col-sm-7">
                                        <input type="date" class="form-control datepicker" name="tgl_lahir" id="tgl_lahir" value="<?= $master->tgl_lahir ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="umur">Umur</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="umur" id="umur" value="<?= $master->umur ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="pendidikan">Pendidikan</label>
                                    <div class="col-sm-7">
                                        <select id="pendidikan" name="pendidikan" class="form-control">
                                            <option value="" hidden>Pilih Pendidikan Terakhir</option>
                                            <option value="tidak_sekolah" <?= ($master->pendidikan == 'tidak_sekolah') ? 'selected' : '' ?>>Tidak/Belum Sekolah</option>
                                            <option value="belum_sd" <?= ($master->pendidikan == 'belum_sd') ? 'selected' : '' ?>>Belum Tamat SD/Sederajat</option>
                                            <option value="sd" <?= ($master->pendidikan == 'sd') ? 'selected' : '' ?>>SD/Sederajat</option>
                                            <option value="smp" <?= ($master->pendidikan == 'smp') ? 'selected' : '' ?>>SMP/Sederajat</option>
                                            <option value="sma" <?= ($master->pendidikan == 'sma') ? 'selected' : '' ?>>SMA/SMK/MA/Sederajat</option>
                                            <option value="diploma1" <?= ($master->pendidikan == 'diploma1') ? 'selected' : '' ?>>Diploma I (D1)</option>
                                            <option value="diploma2" <?= ($master->pendidikan == 'diploma2') ? 'selected' : '' ?>>Diploma II (D2)</option>
                                            <option value="diploma3" <?= ($master->pendidikan == 'diploma3') ? 'selected' : '' ?>>Diploma III (D3)</option>
                                            <option value="sarjana" <?= ($master->pendidikan == 'sarjana') ? 'selected' : '' ?>>Sarjana (S1)/Diploma IV (D4)</option>
                                            <option value="magister" <?= ($master->pendidikan == 'magister') ? 'selected' : '' ?>>Magister (S2)</option>
                                            <option value="doktor" <?= ($master->pendidikan == 'doktor') ? 'selected' : '' ?>>Doktor (S3)</option>
                                            <option value="lainnya" <?= ($master->pendidikan == 'lainnya') ? 'selected' : '' ?>>Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="no_ktp">No KTP</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="no_ktp" id="no_ktp" value="<?= $master->no_ktp ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="status_pernikahan">Status Pernikahan</label>
                                    <div class="col-sm-7">
                                        <select id="status_pernikahan" name="status_pernikahan" class="form-control">
                                            <option value="" hidden>Pilih Status Pernikahan</option>
                                            <option value="belum menikah" <?= ($master->status_pernikahan == 'belum menikah') ? 'selected' : '' ?>>Belum Menikah</option>
                                            <option value="menikah" <?= ($master->status_pernikahan == 'menikah') ? 'selected' : '' ?>>Menikah</option>
                                            <option value="cerai_hidup" <?= ($master->status_pernikahan == 'cerai_hidup') ? 'selected' : '' ?>>Cerai Hidup</option>
                                            <option value="cerai_mati" <?= ($master->status_pernikahan == 'cerai_mati') ? 'selected' : '' ?>>Cerai Mati</option>
                                            <option value="duda" <?= ($master->status_pernikahan == 'duda') ? 'selected' : '' ?>>Duda</option>
                                            <option value="janda" <?= ($master->status_pernikahan == 'janda') ? 'selected' : '' ?>>Janda</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="ktk">KTK</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="ktk" id="ktk" value="<?= $master->ktk ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="alamat_ktp">Alamat KTP</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" name="alamat_ktp" id="alamat_ktp" rows="3"><?= $master->alamat_ktp ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="domisili">Domisili</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="domisili" id="domisili" value="<?= $master->domisili ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="telp_klrga_serumah">Telp Keluarga Serumah</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="telp_klrga_serumah" id="telp_klrga_serumah" value="<?= $master->telp_klrga_serumah ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="telp_klrga_tdk_serumah">Telp Keluarga Tidak Serumah</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="telp_klrga_tdk_serumah" id="telp_klrga_tdk_serumah" value="<?= $master->telp_klrga_tdk_serumah ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="gol_darah">Gol Darah</label>
                                    <div class="col-sm-7">
                                        <select id="gol_darah" name="gol_darah" class="form-control">
                                            <option value="" hidden>Pilih Golongan Darah</option>
                                            <option value="A" <?= ($master->gol_darah == 'A') ? 'selected' : '' ?>>A</option>
                                            <option value="B" <?= ($master->gol_darah == 'B') ? 'selected' : '' ?>>B</option>
                                            <option value="AB" <?= ($master->gol_darah == 'AB') ? 'selected' : '' ?>>AB</option>
                                            <option value="O" <?= ($master->gol_darah == 'O') ? 'selected' : '' ?>>O</option>
                                            <option value="tidak_tahu" <?= ($master->gol_darah == 'tidak_tahu') ? 'selected' : '' ?>>Tidak Tahu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="no_hp">No HP</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="no_hp" id="no_hp" value="<?= $master->no_hp ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lokasi_kerja" class="col-sm-5 col-form-label">Lokasi Kerja</label>
                                    <div class="col-sm-7">
                                        <select id="lokasi_kerja" name="lokasi_kerja" class="form-control">
                                            <option value="" hidden>Pilih Lokasi Kerja</option>
                                            <option value="kps" <?= ($master->lokasi_kerja == 'kps') ? 'selected' : '' ?>>Kolaborasi Para Sahabat</option>
                                            <option value="sebelaswarna" <?= ($master->lokasi_kerja == 'sebelaswarna') ? 'selected' : '' ?>>sebelaswarna</option>
                                            <option value="pengenumroh" <?= ($master->lokasi_kerja == 'pengenumroh') ? 'selected' : '' ?>>pengenumroh</option>
                                            <option value="qubagift" <?= ($master->lokasi_kerja == 'qubagift') ? 'selected' : '' ?>>qubagift</option>
                                            <option value="by.moment" <?= ($master->lokasi_kerja == 'by.moment') ? 'selected' : '' ?>>by.moment</option>
                                            <option value="sobatwisata" <?= ($master->lokasi_kerja == 'sobatwisata') ? 'selected' : '' ?>>sobatwisata</option>
                                            <option value="mobileautocare" <?= ($master->lokasi_kerja == 'mobileautocare') ? 'selected' : '' ?>>mobileautocare</option>
                                            <option value="carstensz" <?= ($master->lokasi_kerja == 'carstensz') ? 'selected' : '' ?>>carstensz</option>
                                            <option value="sahabat_multi_logistik" <?= ($master->lokasi_kerja == 'sahabat_multi_logistik') ? 'selected' : '' ?>>Sahabat Multi Logistik</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <label for="wilayah_kerja" class="col-sm-5 col-form-label">Wilayah Kerja</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="wilayah_kerja" id="wilayah_kerja" value="<?= $master->wilayah_kerja ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label for="posisi" class="col-sm-5 col-form-label">Posisi</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="posisi" id="posisi" value="<?= $master->posisi ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="jabatan" class="col-sm-5 col-form-label">Jabatan</label>
                                    <div class="col-sm-7">
                                        <select id="jabatan" name="jabatan" class="form-control">
                                            <option value="" hidden>Pilih Jabatan</option>
                                            <option value="magang" <?= ($master->jabatan == 'magang') ? 'selected' : '' ?>>Magang / Intern</option>
                                            <option value="staff" <?= ($master->jabatan == 'staff') ? 'selected' : '' ?>>Staff</option>
                                            <option value="supervisor" <?= ($master->jabatan == 'supervisor') ? 'selected' : '' ?>>Supervisor</option>
                                            <option value="asisten_manager" <?= ($master->jabatan == 'asisten_manager') ? 'selected' : '' ?>>Asisten Manager</option>
                                            <option value="manager" <?= ($master->jabatan == 'manager') ? 'selected' : '' ?>>Manager</option>
                                            <option value="senior_manager" <?= ($master->jabatan == 'senior_manager') ? 'selected' : '' ?>>Senior Manager</option>
                                            <option value="kepala_divisi" <?= ($master->jabatan == 'kepala_divisi') ? 'selected' : '' ?>>Kepala Divisi</option>
                                            <option value="kepala_bagian" <?= ($master->jabatan == 'kepala_bagian') ? 'selected' : '' ?>>Kepala Bagian</option>
                                            <option value="kepala_cabang" <?= ($master->jabatan == 'kepala_cabang') ? 'selected' : '' ?>>Kepala Cabang</option>
                                            <option value="kepala_unit" <?= ($master->jabatan == 'kepala_unit') ? 'selected' : '' ?>>Kepala Unit</option>
                                            <option value="direktur" <?= ($master->jabatan == 'direktur') ? 'selected' : '' ?>>Direktur</option>
                                            <option value="wakil_direktur" <?= ($master->jabatan == 'wakil_direktur') ? 'selected' : '' ?>>Wakil Direktur</option>
                                            <option value="direktur_utama" <?= ($master->jabatan == 'direktur_utama') ? 'selected' : '' ?>>Direktur Utama</option>
                                            <option value="presiden_direktur" <?= ($master->jabatan == 'presiden_direktur') ? 'selected' : '' ?>>Presiden Direktur</option>
                                            <option value="komisaris" <?= ($master->jabatan == 'komisaris') ? 'selected' : '' ?>>Komisaris</option>
                                            <option value="komisaris_utama" <?= ($master->jabatan == 'komisaris_utama') ? 'selected' : '' ?>>Komisaris Utama</option>
                                            <option value="staf_administrasi" <?= ($master->jabatan == 'staf_administrasi') ? 'selected' : '' ?>>Staf Administrasi</option>
                                            <option value="staf_keuangan" <?= ($master->jabatan == 'staf_keuangan') ? 'selected' : '' ?>>Staf Keuangan</option>
                                            <option value="staf_pemasaran" <?= ($master->jabatan == 'staf_pemasaran') ? 'selected' : '' ?>>Staf Pemasaran</option>
                                            <option value="staf_hrd" <?= ($master->jabatan == 'staf_hrd') ? 'selected' : '' ?>>Staf HRD</option>
                                            <option value="supervisor_produksi" <?= ($master->jabatan == 'supervisor_produksi') ? 'selected' : '' ?>>Supervisor Produksi</option>
                                            <option value="teknisi" <?= ($master->jabatan == 'teknisi') ? 'selected' : '' ?>>Teknisi</option>
                                            <option value="operator" <?= ($master->jabatan == 'operator') ? 'selected' : '' ?>>Operator</option>
                                            <option value="kepala_proyek" <?= ($master->jabatan == 'kepala_proyek') ? 'selected' : '' ?>>Kepala Proyek</option>
                                            <option value="direktur_keuangan" <?= ($master->jabatan == 'direktur_keuangan') ? 'selected' : '' ?>>Direktur Keuangan</option>
                                            <option value="direktur_operasional" <?= ($master->jabatan == 'direktur_operasional') ? 'selected' : '' ?>>Direktur Operasional</option>
                                            <option value="asisten_direktur" <?= ($master->jabatan == 'asisten_direktur') ? 'selected' : '' ?>>Asisten Direktur</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="department" class="col-sm-5 col-form-label">Department</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="department" id="department" value="<?= $master->department ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="grade" class="col-sm-5 col-form-label">Grade</label>
                                    <div class="col-sm-7">
                                        <select id="grade" name="grade" class="form-control">
                                            <option value="" hidden>Pilih Grade</option>
                                            <!-- Level 1 -->
                                            <option value="1A" <?= ($master->grade == '1A') ? 'selected' : '' ?>>1A</option>
                                            <option value="1B" <?= ($master->grade == '1B') ? 'selected' : '' ?>>1B</option>
                                            <option value="1C" <?= ($master->grade == '1C') ? 'selected' : '' ?>>1C</option>
                                            <option value="1D" <?= ($master->grade == '1D') ? 'selected' : '' ?>>1D</option>
                                            <option value="1E" <?= ($master->grade == '1E') ? 'selected' : '' ?>>1E</option>
                                            <option value="1F" <?= ($master->grade == '1F') ? 'selected' : '' ?>>1F</option>
                                            <!-- Level 2 -->
                                            <option value="2A" <?= ($master->grade == '2A') ? 'selected' : '' ?>>2A</option>
                                            <option value="2B" <?= ($master->grade == '2B') ? 'selected' : '' ?>>2B</option>
                                            <option value="2C" <?= ($master->grade == '2C') ? 'selected' : '' ?>>2C</option>
                                            <option value="2D" <?= ($master->grade == '2D') ? 'selected' : '' ?>>2D</option>
                                            <option value="2E" <?= ($master->grade == '2E') ? 'selected' : '' ?>>2E</option>
                                            <option value="2F" <?= ($master->grade == '2F') ? 'selected' : '' ?>>2F</option>
                                            <!-- Level 3 -->
                                            <option value="3A" <?= ($master->grade == '3A') ? 'selected' : '' ?>>3A</option>
                                            <option value="3B" <?= ($master->grade == '3B') ? 'selected' : '' ?>>3B</option>
                                            <option value="3C" <?= ($master->grade == '3C') ? 'selected' : '' ?>>3C</option>
                                            <option value="3D" <?= ($master->grade == '3D') ? 'selected' : '' ?>>3D</option>
                                            <option value="3E" <?= ($master->grade == '3E') ? 'selected' : '' ?>>3E</option>
                                            <option value="3F" <?= ($master->grade == '3F') ? 'selected' : '' ?>>3F</option>
                                            <!-- Level 4 -->
                                            <option value="4A" <?= ($master->grade == '4A') ? 'selected' : '' ?>>4A</option>
                                            <option value="4B" <?= ($master->grade == '4B') ? 'selected' : '' ?>>4B</option>
                                            <option value="4C" <?= ($master->grade == '4C') ? 'selected' : '' ?>>4C</option>
                                            <option value="4D" <?= ($master->grade == '4D') ? 'selected' : '' ?>>4D</option>
                                            <option value="4E" <?= ($master->grade == '4E') ? 'selected' : '' ?>>4E</option>
                                            <option value="4F" <?= ($master->grade == '4F') ? 'selected' : '' ?>>4F</option>
                                            <!-- Level 5 -->
                                            <option value="5A" <?= ($master->grade == '5A') ? 'selected' : '' ?>>5A</option>
                                            <option value="5B" <?= ($master->grade == '5B') ? 'selected' : '' ?>>5B</option>
                                            <option value="5C" <?= ($master->grade == '5C') ? 'selected' : '' ?>>5C</option>
                                            <option value="5D" <?= ($master->grade == '5D') ? 'selected' : '' ?>>5D</option>
                                            <option value="5E" <?= ($master->grade == '5E') ? 'selected' : '' ?>>5E</option>
                                            <option value="5F" <?= ($master->grade == '5F') ? 'selected' : '' ?>>5F</option>
                                            <!-- Level 6 -->
                                            <option value="6A" <?= ($master->grade == '6A') ? 'selected' : '' ?>>6A</option>
                                            <option value="6B" <?= ($master->grade == '6B') ? 'selected' : '' ?>>6B</option>
                                            <option value="6C" <?= ($master->grade == '6C') ? 'selected' : '' ?>>6C</option>
                                            <option value="6D" <?= ($master->grade == '6D') ? 'selected' : '' ?>>6D</option>
                                            <option value="6E" <?= ($master->grade == '6E') ? 'selected' : '' ?>>6E</option>
                                            <option value="6F" <?= ($master->grade == '6F') ? 'selected' : '' ?>>6F</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="status_karyawan" class="col-sm-5 col-form-label">Status Karyawan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="status_karyawan" id="status_karyawan">
                                            <option value="" hidden>Pilih Status Karyawan</option>
                                            <option value="kontrak" <?= ($master->status_karyawan == 'kontrak') ? 'selected' : '' ?>>Kontrak</option>
                                            <option value="permanen" <?= ($master->status_karyawan == 'permanen') ? 'selected' : '' ?>>Permanen</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_masuk" class="col-sm-5 col-form-label">Tanggal Masuk</label>
                                    <div class="col-sm-7">
                                        <input type="date" class="form-control datepicker" name="tgl_masuk" id="tgl_masuk" value="<?= $master->tgl_masuk ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_rekrut" class="col-sm-5 col-form-label">Tanggal Rekrut</label>
                                    <div class="col-sm-7">
                                        <input type="date" class="form-control datepicker" name="tgl_rekrut" id="tgl_rekrut" value="<?= $master->tgl_rekrut ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_permanen" class="col-sm-5 col-form-label">Tanggal Permanen</label>
                                    <div class="col-sm-7">
                                        <input type="date" class="form-control datepicker" name="tgl_permanen" id="tgl_permanen" value="<?= $master->tgl_permanen ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_akhir_kontrak" class="col-sm-5 col-form-label">Tanggal Akhir Kontrak</label>
                                    <div class="col-sm-7">
                                        <input type="date" class="form-control datepicker" name="tgl_akhir_kontrak" id="tgl_akhir_kontrak" value="<?= $master->tgl_akhir_kontrak ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_phk" class="col-sm-5 col-form-label">Tanggal PHK</label>
                                    <div class="col-sm-7">
                                        <input type="date" class="form-control datepicker" name="tgl_phk" id="tgl_phk" value="<?= $master->tgl_phk ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="masa_kerja" class="col-sm-5 col-form-label">Masa Kerja</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="masa_kerja" id="masa_kerja" value="<?= $master->masa_kerja ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="total_bulan" class="col-sm-5 col-form-label">Total Bulan</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control only-number" name="total_bulan" id="total_bulan" value="<?= $master->total_bulan ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="no_rek" class="col-sm-5 col-form-label">No Rekening</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="no_rek" id="no_rek" value="<?= $master->no_rek ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_pemilik_rek" class="col-sm-5 col-form-label">Nama Pemilik Rekening</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="nama_pemilik_rek" id="nama_pemilik_rek" value="<?= $master->nama_pemilik_rek ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_bank" class="col-sm-5 col-form-label">Nama Bank</label>
                                    <div class="col-sm-7">
                                        <select id="nama_bank" name="nama_bank" class="form-control">
                                            <option value="" hidden>Pilih Nama Bank</option>
                                            <option value="BCA" <?= ($master->nama_bank == 'BCA') ? 'selected' : '' ?>>Bank Central Asia (BCA)</option>
                                            <option value="BRI" <?= ($master->nama_bank == 'BRI') ? 'selected' : '' ?>>Bank Rakyat Indonesia (BRI)</option>
                                            <option value="BNI" <?= ($master->nama_bank == 'BNI') ? 'selected' : '' ?>>Bank Negara Indonesia (BNI)</option>
                                            <option value="Mandiri" <?= ($master->nama_bank == 'Mandiri') ? 'selected' : '' ?>>Bank Mandiri</option>
                                            <option value="BTN" <?= ($master->nama_bank == 'BTN') ? 'selected' : '' ?>>Bank Tabungan Negara (BTN)</option>
                                            <option value="CIMB Niaga" <?= ($master->nama_bank == 'CIMB Niaga') ? 'selected' : '' ?>>CIMB Niaga</option>
                                            <option value="Danamon" <?= ($master->nama_bank == 'Danamon') ? 'selected' : '' ?>>Bank Danamon</option>
                                            <option value="Permata" <?= ($master->nama_bank == 'Permata') ? 'selected' : '' ?>>Bank Permata</option>
                                            <option value="Panin" <?= ($master->nama_bank == 'Panin') ? 'selected' : '' ?>>Bank Panin</option>
                                            <option value="OCBC NISP" <?= ($master->nama_bank == 'OCBC NISP') ? 'selected' : '' ?>>Bank OCBC NISP</option>
                                            <option value="Maybank" <?= ($master->nama_bank == 'Maybank') ? 'selected' : '' ?>>Maybank Indonesia</option>
                                            <option value="Mega" <?= ($master->nama_bank == 'Mega') ? 'selected' : '' ?>>Bank Mega</option>
                                            <option value="BTPN" <?= ($master->nama_bank == 'BTPN') ? 'selected' : '' ?>>Bank BTPN</option>
                                            <option value="Sinarmas" <?= ($master->nama_bank == 'Sinarmas') ? 'selected' : '' ?>>Bank Sinarmas</option>
                                            <option value="BSI" <?= ($master->nama_bank == 'BSI') ? 'selected' : '' ?>>Bank Syariah Indonesia (BSI)</option>
                                            <option value="Muamalat" <?= ($master->nama_bank == 'Muamalat') ? 'selected' : '' ?>>Bank Muamalat</option>
                                            <option value="Lainnya" <?= ($master->nama_bank == 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="asal_karyawan" class="col-sm-5 col-form-label">Asal Karyawan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="asal_karyawan" id="asal_karyawan" value="<?= $master->asal_karyawan ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="keahlian" class="col-sm-5 col-form-label">Keahlian</label>
                                    <div class="col-sm-7">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" id="keahlian_input" placeholder="Tambah keahlian">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="add_keahlian"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div id="keahlian_tags" class="mb-2"></div>
                                        <input type="hidden" name="keahlian" id="keahlian" value="<?= $master->keahlian ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pelatihan_internal" class="col-sm-5 col-form-label">Pelatihan Internal</label>
                                    <div class="col-sm-7">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" id="pelatihan_internal_input" placeholder="Tambah pelatihan internal">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="add_pelatihan_internal"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div id="pelatihan_internal_tags" class="mb-2"></div>
                                        <input type="hidden" name="pelatihan_internal" id="pelatihan_internal" value="<?= $master->pelatihan_internal ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pelatihan_eksternal" class="col-sm-5 col-form-label">Pelatihan Eksternal</label>
                                    <div class="col-sm-7">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" id="pelatihan_eksternal_input" placeholder="Tambah pelatihan eksternal">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="add_pelatihan_eksternal"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div id="pelatihan_eksternal_tags" class="mb-2"></div>
                                        <input type="hidden" name="pelatihan_eksternal" id="pelatihan_eksternal" value="<?= $master->pelatihan_eksternal ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($kontrak)) : ?>
            <!-- Data Kontrak PKWT -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Data Kontrak PKWT</h1>
            </div>

            <?php $i = 1 ?>
            <?php foreach ($kontrak as $data) : ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header text-right">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 text-gray-800">Kontrak Tahap <?= $i++ ?> </h5>
                                    <?php if ($aksi == 'edit') : ?>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete-kontrak" data-id="<?= $data->id ?>" title="Hapus Kontrak">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- First Set of Fields -->
                                        <!-- Hidden Id -->
                                        <input type="text" class="form-control" name="id_kontrak[]" id="id_kontrak" value="<?= $data->id ?>" hidden>
                                        <div class="form-group row mb-0">
                                            <label for="start" class="col-sm-5 col-form-label">Tanggal Awal</label>
                                            <div class="col-sm-7">
                                                <input type="date" class="form-control datepicker" name="start[]" id="start" value="<?= $data->start ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Second Set of Fields -->
                                        <div class="form-group row mb-0">
                                            <label for="end" class="col-sm-5 col-form-label">Tanggal Akhir</label>
                                            <div class="col-sm-7">
                                                <input type="date" class="form-control datepicker" name="end[]" id="end" value="<?= $data->end ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>

        <?php if (!empty($keluarga)) : ?>
            <!-- Data Keluarga -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Data Keluarga Karyawan</h1>
            </div>
            <?php $i = 1 ?>
            <?php foreach ($keluarga as $data) : ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header text-right">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 text-gray-800">Anggota <?= $i++ ?></h5>
                                    <?php if ($aksi == 'edit') : ?>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete-keluarga" data-id="<?= $data->id ?>" title="Hapus Anggota">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- First Set of Fields -->
                                        <!-- hidden id -->
                                        <input type="hidden" class="form-control" name="id_keluarga[]" value="<?= $data->id ?>">
                                        <div class="form-group row">
                                            <label class="col-sm-5" for="nama_anggota">Nama Anggota</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="nama_anggota[]" value="<?= $data->nama_anggota ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5" for="status_wp">Status Wajib Pajak</label>
                                            <div class="col-sm-7">
                                                <select id="status_wp" name="status_wp[]" class="form-control">
                                                    <option value="" hidden>Pilih Status WP</option>
                                                    <option value="TK/0" <?= ($data->status_wp == 'TK/0') ? 'selected' : '' ?>>TK/0 - Tidak Kawin, tanpa tanggungan</option>
                                                    <option value="TK/1" <?= ($data->status_wp == 'TK/1') ? 'selected' : '' ?>>TK/1 - Tidak Kawin, dengan 1 tanggungan</option>
                                                    <option value="TK/2" <?= ($data->status_wp == 'TK/2') ? 'selected' : '' ?>>TK/2 - Tidak Kawin, dengan 2 tanggungan</option>
                                                    <option value="TK/3" <?= ($data->status_wp == 'TK/3') ? 'selected' : '' ?>>TK/3 - Tidak Kawin, dengan 3 tanggungan</option>
                                                    <option value="K/0" <?= ($data->status_wp == 'K/0') ? 'selected' : '' ?>>K/0 - Kawin, tanpa tanggungan</option>
                                                    <option value="K/1" <?= ($data->status_wp == 'K/1') ? 'selected' : '' ?>>K/1 - Kawin, dengan 1 tanggungan</option>
                                                    <option value="K/2" <?= ($data->status_wp == 'K/2') ? 'selected' : '' ?>>K/2 - Kawin, dengan 2 tanggungan</option>
                                                    <option value="K/3" <?= ($data->status_wp == 'K/3') ? 'selected' : '' ?>>K/3 - Kawin, dengan 3 tanggungan</option>
                                                    <option value="HB/0" <?= ($data->status_wp == 'HB/0') ? 'selected' : '' ?>>HB/0 - Hidup Berpisah, tanpa tanggungan</option>
                                                    <option value="HB/1" <?= ($data->status_wp == 'HB/1') ? 'selected' : '' ?>>HB/1 - Hidup Berpisah, dengan 1 tanggungan</option>
                                                    <option value="HB/2" <?= ($data->status_wp == 'HB/2') ? 'selected' : '' ?>>HB/2 - Hidup Berpisah, dengan 2 tanggungan</option>
                                                    <option value="HB/3" <?= ($data->status_wp == 'HB/3') ? 'selected' : '' ?>>HB/3 - Hidup Berpisah, dengan 3 tanggungan</option>
                                                    <option value="PH/0" <?= ($data->status_wp == 'PH/0') ? 'selected' : '' ?>>PH/0 - Pisah Harta, tanpa tanggungan</option>
                                                    <option value="PH/1" <?= ($data->status_wp == 'PH/1') ? 'selected' : '' ?>>PH/1 - Pisah Harta, dengan 1 tanggungan</option>
                                                    <option value="PH/2" <?= ($data->status_wp == 'PH/2') ? 'selected' : '' ?>>PH/2 - Pisah Harta, dengan 2 tanggungan</option>
                                                    <option value="PH/3" <?= ($data->status_wp == 'PH/3') ? 'selected' : '' ?>>PH/3 - Pisah Harta, dengan 3 tanggungan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5" for="jenis_kelamin">Jenis Kelamin</label>
                                            <div class="col-sm-7">
                                                <select class="form-control" name="jenis_kelamin_kel[]">
                                                    <option value="" hidden>Pilih Jenis Kelamin</option>
                                                    <option value="laki-laki" <?= ($data->jenis_kelamin == 'laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                                                    <option value="perempuan" <?= ($data->jenis_kelamin == 'perempuan') ? 'selected' : '' ?>>Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5" for="tgl_lahir">Tanggal Lahir</label>
                                            <div class="col-sm-7">
                                                <input type="date" class="form-control datepicker" id="tgl_lahir_kel" name="tgl_lahir_kel[]" value="<?= $data->tgl_lahir ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <label class="col-sm-5" for="umur">Umur</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="umur_kel" name="umur_kel[]" value="<?= $data->umur ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Second Set of Fields -->
                                        <div class="form-group row">
                                            <label class="col-sm-5" for="pendidikan_kel">Pendidikan</label>
                                            <div class="col-sm-7">
                                                <select id="pendidikan_kel" name="pendidikan_kel[]" class="form-control">
                                                    <option value="" hidden>Pilih Pendidikan Terakhir</option>
                                                    <option value="tidak_sekolah" <?= ($data->pendidikan == 'tidak_sekolah') ? 'selected' : '' ?>>Tidak/Belum Sekolah</option>
                                                    <option value="belum_sd" <?= ($data->pendidikan == 'belum_sd') ? 'selected' : '' ?>>Belum Tamat SD/Sederajat</option>
                                                    <option value="sd" <?= ($data->pendidikan == 'sd') ? 'selected' : '' ?>>SD/Sederajat</option>
                                                    <option value="smp" <?= ($data->pendidikan == 'smp') ? 'selected' : '' ?>>SMP/Sederajat</option>
                                                    <option value="sma" <?= ($data->pendidikan == 'sma') ? 'selected' : '' ?>>SMA/SMK/MA/Sederajat</option>
                                                    <option value="diploma1" <?= ($data->pendidikan == 'diploma1') ? 'selected' : '' ?>>Diploma I (D1)</option>
                                                    <option value="diploma2" <?= ($data->pendidikan == 'diploma2') ? 'selected' : '' ?>>Diploma II (D2)</option>
                                                    <option value="diploma3" <?= ($data->pendidikan == 'diploma3') ? 'selected' : '' ?>>Diploma III (D3)</option>
                                                    <option value="sarjana" <?= ($data->pendidikan == 'sarjana') ? 'selected' : '' ?>>Sarjana (S1)/Diploma IV (D4)</option>
                                                    <option value="magister" <?= ($data->pendidikan == 'magister') ? 'selected' : '' ?>>Magister (S2)</option>
                                                    <option value="doktor" <?= ($data->pendidikan == 'doktor') ? 'selected' : '' ?>>Doktor (S3)</option>
                                                    <option value="lainnya" <?= ($data->pendidikan == 'lainnya') ? 'selected' : '' ?>>Lainnya</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5" for="keanggotaan">Keanggotaan</label>
                                            <div class="col-sm-7">
                                                <select class="form-control" name="keanggotaan[]" id="keanggotaan">
                                                    <option value="" hidden>Pilih Keanggotaan</option>
                                                    <option value="pasangan" <?= ($data->keanggotaan == 'pasangan') ? 'selected' : '' ?>>Pasangan</option>
                                                    <option value="anak" <?= ($data->keanggotaan == 'anak') ? 'selected' : '' ?>>Anak</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5" for="lokasi_kerja">Lokasi Kerja</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="lokasi_kerja_kel[]" value="<?= $data->lokasi_kerja ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <label class="col-sm-5" for="wilayah_kerja">Wilayah Kerja</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="wilayah_kerja_kel[]" value="<?= $data->wilayah_kerja ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif ?>
        <!-- Hidden inputs -->
        <input type="hidden" name="id_master" value="<?= $id_master ?>">
        <?php if (!empty($aksi)) { ?>
            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
        <?php } ?>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-sm aksi mt-1 mb-4">Update</button>
    </form>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    let aksi = $('#aksi').val();

    $(document).on('input', '.only-number', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $(document).ready(function() {
        $('#nama_bank').select2({
            placeholder: "Pilih Nama Bank",
            allowClear: true
        });
        $('#jabatan').select2({
            placeholder: "Pilih Jabatan",
            allowClear: true
        });
        $('#grade').select2({
            placeholder: "Pilih Grade",
            allowClear: true
        });
    });

    $(function() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd', // atau format lain sesuai kebutuhan
            changeMonth: true,
            yearRange: "1900:2099", // bisa disesuaikan
            changeYear: true,
            autoclose: true
        });
    });

    if (aksi == "read") {
        $('#form input, #form select, #form textarea, .form-control').prop('disabled', true).css('cursor', 'not-allowed');
        $('.aksi').hide();
    }

    $(document).on('change', '#tgl_lahir', function() {
        var tgl = $(this).val();
        if (tgl) {
            var birthYear = new Date(tgl).getFullYear();
            var nowYear = new Date().getFullYear();
            var umur = nowYear - birthYear;
            if (umur < 0) umur = 0;
            $('#umur').val(umur);
        } else {
            $('#umur').val('');
        }
    });

    $(document).on('change', '#tgl_lahir_kel', function() {
        var tgl = $(this).val();
        if (tgl) {
            var birthYear = new Date(tgl).getFullYear();
            var nowYear = new Date().getFullYear();
            var umur = nowYear - birthYear;
            if (umur < 0) umur = 0;
            $('#umur_kel').val(umur);
        } else {
            $('#umur_kel').val('');
        }
    });

    function handleTagInput(inputId, addBtnId, tagsDivId, hiddenInputId, readonly = false) {
        let tags = [];
        let initial = $('#' + hiddenInputId).val();
        if (initial) {
            tags = initial.split(',').map(e => e.trim()).filter(e => e);
            renderTags();
        }

        function renderTags() {
            let html = '';
            tags.forEach((tag, idx) => {
                html += `<span class="tag">${tag}`;
                if (!readonly) {
                    html += `<span class="remove-tag" data-idx="${idx}">&times;</span>`;
                }
                html += `</span>`;
            });
            $('#' + tagsDivId).html(html);
            $('#' + hiddenInputId).val(tags.join(', '));
        }

        if (!readonly) {
            $('#' + addBtnId).off('click').on('click', function() {
                let val = $('#' + inputId).val().trim();
                if (val && !tags.includes(val)) {
                    tags.push(val);
                    renderTags();
                    $('#' + inputId).val('');
                }
            });

            $('#' + inputId).off('keypress').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#' + addBtnId).click();
                }
            });

            $('#' + tagsDivId).off('click', '.remove-tag').on('click', '.remove-tag', function() {
                let idx = $(this).data('idx');
                tags.splice(idx, 1);
                renderTags();
            });
        } else {
            // Disable input dan tombol plus
            $('#' + inputId).prop('disabled', true);
            $('#' + addBtnId).prop('disabled', true);
        }
    }

    // Inisialisasi
    $(document).ready(function() {
        let aksi = $('#aksi').val();
        let readonly = aksi === 'read';
        handleTagInput('keahlian_input', 'add_keahlian', 'keahlian_tags', 'keahlian', readonly);
        handleTagInput('pelatihan_internal_input', 'add_pelatihan_internal', 'pelatihan_internal_tags', 'pelatihan_internal', readonly);
        handleTagInput('pelatihan_eksternal_input', 'add_pelatihan_eksternal', 'pelatihan_eksternal_tags', 'pelatihan_eksternal', readonly);
    });

    $("#form").submit(function(e) {
        e.preventDefault();
        var $form = $(this);
        if (!$form.valid()) return false;

        // Gunakan FormData untuk menangani file upload
        var formData = new FormData($form[0]); // Ambil semua data form termasuk file

        // Tampilkan loading Swal sebelum request dikirim
        Swal.fire({
            title: 'Memproses...',
            html: 'Mohon tunggu, data sedang disimpan',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "<?php echo site_url('kps_karyawan/update') ?>",
            type: "POST",
            data: formData,
            contentType: false, // Agar tidak mengubah tipe konten FormData
            processData: false, // Agar data FormData tidak diproses menjadi string
            dataType: "JSON",
            success: function(data) {
                Swal.close(); // Tutup loading swal setelah request selesai

                if (data.status) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Your data has been saved',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.href = "<?= base_url('kps_karyawan') ?>";
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close(); // Tutup loading swal jika terjadi error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error adding / updating data!',
                });
            }
        });
    });

    $(document).on('click', '.btn-delete-kontrak', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Kontrak?',
            text: "Data kontrak ini akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= site_url('kps_karyawan/delete_kontrak') ?>",
                    type: "POST",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Data kontrak berhasil dihapus.',
                                showConfirmButton: false,
                                timer: 1200
                            }).then(() => location.reload());
                        } else {
                            Swal.fire('Gagal!', res.message || 'Gagal menghapus data.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan server.', 'error');
                    }
                });
            }
        });
    });

    $(document).on('click', '.btn-delete-keluarga', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Anggota Keluarga?',
            text: "Data anggota keluarga ini akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= site_url('kps_karyawan/delete_keluarga') ?>",
                    type: "POST",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Data anggota keluarga berhasil dihapus.',
                                showConfirmButton: false,
                                timer: 1200
                            }).then(() => location.reload());
                        } else {
                            Swal.fire('Gagal!', res.message || 'Gagal menghapus data.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan server.', 'error');
                    }
                });
            }
        });
    });
</script>