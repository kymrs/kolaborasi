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
                                            <option value="Laki-laki" <?= ($master->jenis_kelamin == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                                            <option value="Perempuan" <?= ($master->jenis_kelamin == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
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
                                        <input type="text" class="form-control datepicker" name="tgl_lahir" id="tgl_lahir" value="<?= $master->tgl_lahir ?>" placeholder="Pilih Tanggal Lahir" style="cursor: pointer">
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
                                            <option value="Tidak Sekolah" <?= ($master->pendidikan == 'Tidak Sekolah') ? 'selected' : '' ?>>Tidak/Belum Sekolah</option>
                                            <option value="Belum Tamat SD" <?= ($master->pendidikan == 'Belum Tamat SD') ? 'selected' : '' ?>>Belum Tamat SD/Sederajat</option>
                                            <option value="SD" <?= ($master->pendidikan == 'SD') ? 'selected' : '' ?>>SD/Sederajat</option>
                                            <option value="SMP" <?= ($master->pendidikan == 'SMP') ? 'selected' : '' ?>>SMP/Sederajat</option>
                                            <option value="SMA" <?= ($master->pendidikan == 'SMA') ? 'selected' : '' ?>>SMA/SMK/MA/Sederajat</option>
                                            <option value="Diploma I (D1)" <?= ($master->pendidikan == 'Diploma I (D1)') ? 'selected' : '' ?>>Diploma I (D1)</option>
                                            <option value="Diploma II (D2)" <?= ($master->pendidikan == 'Diploma II (D2)') ? 'selected' : '' ?>>Diploma II (D2)</option>
                                            <option value="Diploma III (D3)" <?= ($master->pendidikan == 'Diploma III (D3)') ? 'selected' : '' ?>>Diploma III (D3)</option>
                                            <option value="Sarjana (S1)" <?= ($master->pendidikan == 'Sarjana (S1)') ? 'selected' : '' ?>>Sarjana (S1)/Diploma IV (D4)</option>
                                            <option value="Magister (S2)" <?= ($master->pendidikan == 'Magister (S2)') ? 'selected' : '' ?>>Magister (S2)</option>
                                            <option value="Doktor (S3)" <?= ($master->pendidikan == 'Doktor (S3)') ? 'selected' : '' ?>>Doktor (S3)</option>
                                            <option value="Lainnya" <?= ($master->pendidikan == 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
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
                                            <option value="Belum Nikah" <?= ($master->status_pernikahan == 'Belum Nikah') ? 'selected' : '' ?>>Belum Menikah</option>
                                            <option value="Menikah" <?= ($master->status_pernikahan == 'Menikah') ? 'selected' : '' ?>>Menikah</option>
                                            <option value="Cerai Hidup" <?= ($master->status_pernikahan == 'Cerai Hidup') ? 'selected' : '' ?>>Cerai Hidup</option>
                                            <option value="Cerai Mati" <?= ($master->status_pernikahan == 'Cerai Mati') ? 'selected' : '' ?>>Cerai Mati</option>
                                            <option value="Duda" <?= ($master->status_pernikahan == 'Duda') ? 'selected' : '' ?>>Duda</option>
                                            <option value="Janda" <?= ($master->status_pernikahan == 'Janda') ? 'selected' : '' ?>>Janda</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="ktk">KTK</label>
                                    <div class="col-sm-7">
                                        <select id="ktk" name="ktk" class="form-control">
                                            <option value="">Pilih KTK</option>
                                            <option value="TK/0" <?= ($master->ktk == 'TK/0') ? 'selected' : '' ?>>TK/0 - Tidak Kawin, 0 tanggungan</option>
                                            <option value="TK/1" <?= ($master->ktk == 'TK/1') ? 'selected' : '' ?>>TK/1 - Tidak Kawin, 1 tanggungan</option>
                                            <option value="TK/2" <?= ($master->ktk == 'TK/2') ? 'selected' : '' ?>>TK/2 - Tidak Kawin, 2 tanggungan</option>
                                            <option value="TK/3" <?= ($master->ktk == 'TK/3') ? 'selected' : '' ?>>TK/3 - Tidak Kawin, 3 tanggungan</option>
                                            <option value="K/0" <?= ($master->ktk == 'K/0') ? 'selected' : '' ?>>K/0 - Kawin, 0 tanggungan</option>
                                            <option value="K/1" <?= ($master->ktk == 'K/1') ? 'selected' : '' ?>>K/1 - Kawin, 1 tanggungan</option>
                                            <option value="K/2" <?= ($master->ktk == 'K/2') ? 'selected' : '' ?>>K/2 - Kawin, 2 tanggungan</option>
                                            <option value="K/3" <?= ($master->ktk == 'K/3') ? 'selected' : '' ?>>K/3 - Kawin, 3 tanggungan</option>
                                            <option value="K/I/0" <?= ($master->ktk == 'K/I/0') ? 'selected' : '' ?>>K/I/0 - Kawin, istri bekerja, 0 tanggungan</option>
                                            <option value="K/I/1" <?= ($master->ktk == 'K/I/1') ? 'selected' : '' ?>>K/I/1 - Kawin, istri bekerja, 1 tanggungan</option>
                                            <option value="K/I/2" <?= ($master->ktk == 'K/I/2') ? 'selected' : '' ?>>K/I/2 - Kawin, istri bekerja, 2 tanggungan</option>
                                            <option value="K/I/3" <?= ($master->ktk == 'K/I/3') ? 'selected' : '' ?>>K/I/3 - Kawin, istri bekerja, 3 tanggungan</option>
                                        </select>
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
                                            <option value="Tidak Tahu" <?= ($master->gol_darah == 'Tidak Tahu') ? 'selected' : '' ?>>Tidak Tahu</option>
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
                                            <option value="Kantor Pusat" <?= ($master->lokasi_kerja == 'Kantor Pusat') ? 'selected' : '' ?>>Kantor Pusat</option>
                                            <option value="Cabang" <?= ($master->lokasi_kerja == 'Cabang') ? 'selected' : '' ?>>Cabang</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="wilayah_kerja" class="col-sm-5 col-form-label">Wilayah Kerja</label>
                                    <div class="col-sm-7">
                                        <select id="wilayah_kerja" name="wilayah_kerja" class="form-control">
                                            <option value="" hidden>Pilih Wilayah Kerja</option>
                                            <option value="Nasional" <?= ($master->wilayah_kerja == 'Nasional') ? 'selected' : '' ?>>Nasional</option>
                                            <option value="Bogor" <?= ($master->wilayah_kerja == 'Bogor') ? 'selected' : '' ?>>Bogor (Jabar 1)</option>
                                            <option value="Bandung" <?= ($master->wilayah_kerja == 'Bandung') ? 'selected' : '' ?>>Bandung (Jabar 2)</option>
                                            <option value="Gresik" <?= ($master->wilayah_kerja == 'Gresik') ? 'selected' : '' ?>>Gresik</option>
                                            <option value="Yogyakarta" <?= ($master->wilayah_kerja == 'Yogyakarta') ? 'selected' : '' ?>>Yogyakarta</option>
                                            <option value="Palembang" <?= ($master->wilayah_kerja == 'Palembang') ? 'selected' : '' ?>>Palembang</option>
                                            <option value="Pekanbaru" <?= ($master->wilayah_kerja == 'Pekanbaru') ? 'selected' : '' ?>>Pekanbaru</option>
                                            <option value="Lampung" <?= ($master->wilayah_kerja == 'Lampung') ? 'selected' : '' ?>>Lampung</option>
                                            <option value="Serang" <?= ($master->wilayah_kerja == 'Serang') ? 'selected' : '' ?>>Serang</option>
                                            <option value="Lubuklinggau" <?= ($master->wilayah_kerja == 'Lubuklinggau') ? 'selected' : '' ?>>Lubuklinggau</option>
                                            <option value="Kediri" <?= ($master->wilayah_kerja == 'Kediri') ? 'selected' : '' ?>>Kediri</option>
                                            <option value="Bali" <?= ($master->wilayah_kerja == 'Bali') ? 'selected' : '' ?>>Bali</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="wilayah_kerja" class="col-sm-5 col-form-label">Foto</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="file" name="foto" id="foto">
                                        <span class="kwitansi-label">Max Size : 3MB</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label for="posisi" class="col-sm-5 col-form-label">Unit Bisnis</label>
                                    <div class="col-sm-7">
                                        <select id="unit_bisnis" name="unit_bisnis" class="form-control">
                                            <option value="" hidden>Pilih Unit Bisnis</option>
                                            <option value="KPS" <?= $master->unit_bisnis == 'KPS' ? 'selected' : '' ?>>Kolaborasi Para Sahabat</option>
                                            <option value="sebelaswarna" <?= $master->unit_bisnis == 'sebelaswarna' ? 'selected' : '' ?>>sebelaswarna</option>
                                            <option value="pengenumroh" <?= $master->unit_bisnis == 'pengenumroh' ? 'selected' : '' ?>>pengenumroh</option>
                                            <option value="qubagift" <?= $master->unit_bisnis == 'qubagift' ? 'selected' : '' ?>>qubagift</option>
                                            <option value="by.moment" <?= $master->unit_bisnis == 'by.moment' ? 'selected' : '' ?>>by.moment</option>
                                            <option value="sobatwisata" <?= $master->unit_bisnis == 'sobatwisata' ? 'selected' : '' ?>>sobatwisata</option>
                                            <option value="mobileautocare" <?= $master->unit_bisnis == 'mobileautocare' ? 'selected' : '' ?>>mobileautocare</option>
                                            <option value="carstensz" <?= $master->unit_bisnis == 'carstensz' ? 'selected' : '' ?>>carstensz</option>
                                            <option value="sahabat multi logistik" <?= $master->unit_bisnis == 'sahabat multi logistik' ? 'selected' : '' ?>>Sahabat Multi Logistik</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="posisi" class="col-sm-5 col-form-label">Posisi</label>
                                    <div class="col-sm-7">
                                        <select name="posisi" id="posisi" class="form-control" required>
                                            <option value="" hidden>Pilih Posisi</option>
                                            <option value="Admin HRD" <?= ($master->posisi == 'Admin HRD') ? 'selected' : '' ?>>Admin HRD</option>
                                            <option value="Staff Keuangan" <?= ($master->posisi == 'Staff Keuangan') ? 'selected' : '' ?>>Staff Keuangan</option>
                                            <option value="Marketing Digital" <?= ($master->posisi == 'Marketing Digital') ? 'selected' : '' ?>>Marketing Digital</option>
                                            <option value="Teknisi Lapangan" <?= ($master->posisi == 'Teknisi Lapangan') ? 'selected' : '' ?>>Teknisi Lapangan</option>
                                            <option value="Customer Service" <?= ($master->posisi == 'Customer Service') ? 'selected' : '' ?>>Customer Service</option>
                                            <option value="Content Writer" <?= ($master->posisi == 'Content Writer') ? 'selected' : '' ?>>Content Writer</option>
                                            <option value="Operator Produksi" <?= ($master->posisi == 'Operator Produksi') ? 'selected' : '' ?>>Operator Produksi</option>
                                            <option value="Driver" <?= ($master->posisi == 'Driver') ? 'selected' : '' ?>>Driver</option>
                                            <option value="Office Boy" <?= ($master->posisi == 'Office Boy') ? 'selected' : '' ?>>Office Boy</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="jabatan" class="col-sm-5 col-form-label">Jabatan</label>
                                    <div class="col-sm-7">
                                        <select id="jabatan" name="jabatan" class="form-control">
                                            <option value="" hidden>Pilih Jabatan</option>
                                            <option value="Magang" <?= ($master->jabatan == 'Magang') ? 'selected' : '' ?>>Magang / Intern</option>
                                            <option value="Staff" <?= ($master->jabatan == 'Staff') ? 'selected' : '' ?>>Staff</option>
                                            <option value="Supervisor" <?= ($master->jabatan == 'Supervisor') ? 'selected' : '' ?>>Supervisor</option>
                                            <option value="Asisten Manager" <?= ($master->jabatan == 'Asisten Manager') ? 'selected' : '' ?>>Asisten Manager</option>
                                            <option value="Manager" <?= ($master->jabatan == 'Manager') ? 'selected' : '' ?>>Manager</option>
                                            <option value="Senior Manager" <?= ($master->jabatan == 'Senior Manager') ? 'selected' : '' ?>>Senior Manager</option>
                                            <option value="Kepala Divisi" <?= ($master->jabatan == 'Kepala Divisi') ? 'selected' : '' ?>>Kepala Divisi</option>
                                            <option value="Kepala Bagian" <?= ($master->jabatan == 'Kepala Bagian') ? 'selected' : '' ?>>Kepala Bagian</option>
                                            <option value="Kepala Cabang" <?= ($master->jabatan == 'Kepala Cabang') ? 'selected' : '' ?>>Kepala Cabang</option>
                                            <option value="Kepala Unit" <?= ($master->jabatan == 'Kepala Unit') ? 'selected' : '' ?>>Kepala Unit</option>
                                            <option value="Direktur" <?= ($master->jabatan == 'Direktur') ? 'selected' : '' ?>>Direktur</option>
                                            <option value="Wakil Direktur" <?= ($master->jabatan == 'Wakil Direktur') ? 'selected' : '' ?>>Wakil Direktur</option>
                                            <option value="Direktur Utama" <?= ($master->jabatan == 'Direktur Utama') ? 'selected' : '' ?>>Direktur Utama</option>
                                            <option value="Presiden Direktur" <?= ($master->jabatan == 'Presiden Direktur') ? 'selected' : '' ?>>Presiden Direktur</option>
                                            <option value="Komisaris" <?= ($master->jabatan == 'Komisaris') ? 'selected' : '' ?>>Komisaris</option>
                                            <option value="Komisaris Utama" <?= ($master->jabatan == 'Komisaris Utama') ? 'selected' : '' ?>>Komisaris Utama</option>
                                            <option value="Staf Administrasi" <?= ($master->jabatan == 'Staf Administrasi') ? 'selected' : '' ?>>Staf Administrasi</option>
                                            <option value="Staf Keuangan" <?= ($master->jabatan == 'Staf Keuangan') ? 'selected' : '' ?>>Staf Keuangan</option>
                                            <option value="Staf Pemasaran" <?= ($master->jabatan == 'Staf Pemasaran') ? 'selected' : '' ?>>Staf Pemasaran</option>
                                            <option value="Staf HRD" <?= ($master->jabatan == 'Staf HRD') ? 'selected' : '' ?>>Staf HRD</option>
                                            <option value="Supervisor Produksi" <?= ($master->jabatan == 'Supervisor Produksi') ? 'selected' : '' ?>>Supervisor Produksi</option>
                                            <option value="Teknisi" <?= ($master->jabatan == 'Teknisi') ? 'selected' : '' ?>>Teknisi</option>
                                            <option value="Operator" <?= ($master->jabatan == 'Operator') ? 'selected' : '' ?>>Operator</option>
                                            <option value="Kepala Proyek" <?= ($master->jabatan == 'Kepala Proyek') ? 'selected' : '' ?>>Kepala Proyek</option>
                                            <option value="Direktur Keuangan" <?= ($master->jabatan == 'Direktur Keuangan') ? 'selected' : '' ?>>Direktur Keuangan</option>
                                            <option value="Direktur Operasional" <?= ($master->jabatan == 'Direktur Operasional') ? 'selected' : '' ?>>Direktur Operasional</option>
                                            <option value="Asisten Direktur" <?= ($master->jabatan == 'Asisten Direktur') ? 'selected' : '' ?>>Asisten Direktur</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="department" class="col-sm-5 col-form-label">Department</label>
                                    <div class="col-sm-7">
                                        <select name="department" id="department" class="form-control">
                                            <option value="">Pilih Departement</option>
                                            <option value="HRD" <?= ($master->department == 'HRD') ? 'selected' : '' ?>>HRD</option>
                                            <option value="Finance" <?= ($master->department == 'Finance') ? 'selected' : '' ?>>Finance</option>
                                            <option value="Customer Service" <?= ($master->department == 'Customer Service') ? 'selected' : '' ?>>Customer Service</option>
                                            <option value="IT" <?= ($master->department == 'IT') ? 'selected' : '' ?>>IT</option>
                                            <option value="Marketing" <?= ($master->department == 'Marketing') ? 'selected' : '' ?>>Marketing</option>
                                            <option value="Produksi" <?= ($master->department == 'Produksi') ? 'selected' : '' ?>>Produksi</option>
                                            <option value="Operasional" <?= ($master->department == 'Operasional') ? 'selected' : '' ?>>Operasional</option>
                                            <option value="Logistik" <?= ($master->department == 'Logistik') ? 'selected' : '' ?>>Logistik</option>
                                            <option value="Purchasing" <?= ($master->department == 'Purchasing') ? 'selected' : '' ?>>Purchasing</option>
                                            <option value="R&D" <?= ($master->department == 'R&D') ? 'selected' : '' ?>>R&D</option>
                                            <option value="QA/QC" <?= ($master->department == 'QA/QC') ? 'selected' : '' ?>>QA/QC</option>
                                        </select>
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
                                    <label for="asal_karyawan" class="col-sm-5 col-form-label">Asal Karyawan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="asal_karyawan" id="asal_karyawan">
                                            <option value="" hidden>Pilih Asal Karyawan</option>
                                            <option value="MCS" <?= ($master->asal_karyawan == 'MCS') ? 'selected' : '' ?>>MCS</option>
                                            <option value="KPS" <?= ($master->asal_karyawan == 'KPS') ? 'selected' : '' ?>>KPS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_masuk" class="col-sm-5 col-form-label">Tanggal Masuk</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="tgl_masuk" id="tgl_masuk" value="<?= $master->tgl_masuk ?>" placeholder="Pilih Tanggal Masuk" style="cursor: pointer">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_rekrut" class="col-sm-5 col-form-label">Tanggal Rekrut</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="tgl_rekrut" id="tgl_rekrut" value="<?= $master->tgl_rekrut ?>" placeholder="Pilih Tanggal Rekrut" style="cursor: pointer">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_permanen" class="col-sm-5 col-form-label">Tanggal Permanen</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="tgl_permanen" id="tgl_permanen" value="<?= $master->tgl_permanen ?>" placeholder="Pilih Tanggal Permanen" style="cursor: pointer">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_akhir_kontrak" class="col-sm-5 col-form-label">Tanggal Akhir Kontrak</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="tgl_akhir_kontrak" id="tgl_akhir_kontrak" value="<?= $master->tgl_akhir_kontrak ?>" placeholder="Pilih Tanggal Akhir Kontrak" style="cursor: pointer">
                                    </div>
                                </div>
                                <div class="form-group row" id="tgl_phk_container" style="<?= ($master->status_kerja == 'nonaktif') ? '' : 'display: none;' ?>">
                                    <label for="tgl_phk" class="col-sm-5 col-form-label">Tanggal PHK</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="tgl_phk" id="tgl_phk" value="<?= $master->tgl_phk ?>" placeholder="Pilih Tanggal PHK" style="cursor: pointer">
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
                                        <input type="text" class="form-control only-number" name="total_bulan" id="total_bulan" value="<?= $master->total_bulan ?>">
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
                                                    <option value="Tidak Sekolah" <?= ($data->pendidikan == 'Tidak Sekolah') ? 'selected' : '' ?>>Tidak/Belum Sekolah</option>
                                                    <option value="Belum Tamat SD" <?= ($data->pendidikan == 'Belum Tamat SD') ? 'selected' : '' ?>>Belum Tamat SD/Sederajat</option>
                                                    <option value="SD" <?= ($data->pendidikan == 'SD') ? 'selected' : '' ?>>SD/Sederajat</option>
                                                    <option value="SMP" <?= ($data->pendidikan == 'SMP') ? 'selected' : '' ?>>SMP/Sederajat</option>
                                                    <option value="SMA" <?= ($data->pendidikan == 'SMA') ? 'selected' : '' ?>>SMA/SMK/MA/Sederajat</option>
                                                    <option value="Diploma I (D1)" <?= ($data->pendidikan == 'Diploma I (D1)') ? 'selected' : '' ?>>Diploma I (D1)</option>
                                                    <option value="Diploma II (D2)" <?= ($data->pendidikan == 'Diploma II (D2)') ? 'selected' : '' ?>>Diploma II (D2)</option>
                                                    <option value="Diploma III (D3)" <?= ($data->pendidikan == 'Diploma III (D3)') ? 'selected' : '' ?>>Diploma III (D3)</option>
                                                    <option value="Sarjana (S1)" <?= ($data->pendidikan == 'Sarjana (S1)') ? 'selected' : '' ?>>Sarjana (S1)/Diploma IV (D4)</option>
                                                    <option value="Magister (S2)" <?= ($data->pendidikan == 'Magister (S2)') ? 'selected' : '' ?>>Magister (S2)</option>
                                                    <option value="Doktor (S3)" <?= ($data->pendidikan == 'Doktor (S3)') ? 'selected' : '' ?>>Doktor (S3)</option>
                                                    <option value="Lainnya" <?= ($data->pendidikan == 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
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

    $('#status_kerja').on('change', function() {
        if ($(this).val() == 'nonaktif') {
            $('#tgl_phk_container').show();
        } else {
            $('#tgl_phk_container').hide();
        }
    });

    $('#foto').on('change', function() {
        const file = this.files[0];

        if (file) {
            const maxSize = 3 * 1024 * 1024; // 3 MB dalam byte

            if (file.size > maxSize) {
                swal.fire({
                    icon: 'warning',
                    text: "Ukuran file tidak boleh melebihi dari 3MB!",
                    confirmButtonColor: "#0e131d"
                });
                $(this).val(''); // reset input file
            }
        }
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

    $("#form").validate({
        rules: {
            status_kerja: {
                required: true,
            },
            npk: {
                required: true,
            },
            nama_lengkap: {
                required: true,
            },
            jenis_kelamin: {
                required: true,
            },
            tempat_lahir: {
                required: true,
            },
            tgl_lahir: {
                required: true,
            },
            umur: {
                required: true,
            },
            pendidikan: {
                required: true,
            },
            no_ktp: {
                required: true,
            },
            status_pernikahan: {
                required: true,
            },
            ktk: {
                required: true,
            },
            alamat_ktp: {
                required: true,
            },
            domisili: {
                required: true,
            },
            telp_klrga_serumah: {
                required: true,
            },
            telp_klrga_tdk_serumah: {
                required: true,
            },
            gol_darah: {
                required: true,
            },
            no_hp: {
                required: true,
            },
            lokasi_kerja: {
                required: true,
            },
            wilayah_kerja: {
                required: true,
            },
            posisi: {
                required: true,
            },
            jabatan: {
                required: true,
            },
            department: {
                required: true,
            },
            grade: {
                required: true,
            },
            status_karyawan: {
                required: true,
            },
            tgl_masuk: {
                required: true,
            },
            tgl_rekrut: {
                required: true,
            },
            tgl_permanen: {
                required: true,
            },
            tgl_phk: {
                required: true,
            },
            tgl_akhir_kontrak: {
                required: true,
            },
            masa_kerja: {
                required: true,
            },
            total_bulan: {
                required: true,
            },
            no_rek: {
                required: true,
            },
            nama_pemilik_rek: {
                required: true,
            },
            nama_bank: {
                required: true,
            },
            asal_karyawan: {
                required: true,
            },
            keahlian: {
                required: true,
            },
            pelatihan_internal: {
                required: true,
            },
            pelatihan_eksternal: {
                required: true,
            },
        },
        messages: {

        },
        errorPlacement: function(error, element) {
            if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) {
            $(element).addClass('is-invalid'); // Tambahkan kelas untuk menandai input tidak valid
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid'); // Hapus kelas jika input valid
        },
        focusInvalid: false, // Disable auto-focus on the first invalid field
    });
</script>