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

    /* ...existing code... */
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

    /* ...existing code... */

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
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a style="background-color: rgb(36, 44, 73);" class="btn btn-secondary btn-sm" onclick="window.history.back()">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="status_kerja">Status Kerja</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="status_kerja" id="status_kerja">
                                            <option value="" hidden>Pilih Status Kerja</option>
                                            <option value="Aktif" selected>Aktif</option>
                                            <option value="Non Aktif">Non Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="npk">NPK</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="npk" id="npk" placeholder="NPK">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="nama_lengkap">Nama Lengkap</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="nama_lengkap" id="nama_lengkap">
                                            <option value="" hidden>Pilih User</option>
                                            <?php foreach ($user as $data) : ?>
                                                <option value="<?= $data['fullname'] ?><?= $data['id_user'] ?>"><?= $data['fullname'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="jenis_kelamin">Jenis Kelamin</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                            <option value="" hidden>Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tempat_lahir">Tempat Lahir</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tgl_lahir">Tanggal Lahir</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker-tgl-lahir" name="tgl_lahir" id="tgl_lahir" autocomplete="off" placeholder="Pilih Tanggal Lahir" style="cursor: pointer">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="umur">Umur</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="umur" id="umur" readonly placeholder="Umur">
                                    </div> 
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5" for="pendidikan">Pendidikan</label>
                                    <div class="col-sm-7">
                                        <select id="pendidikan" name="pendidikan" class="form-control">
                                            <option value="" hidden>Pilih Pendidikan Terakhir</option>
                                            <option value="Tidak Sekolah">Tidak/Belum Sekolah</option>
                                            <option value="Belum Tamat SD">Belum Tamat SD/Sederajat</option>
                                            <option value="SD">SD/Sederajat</option>
                                            <option value="SMP">SMP/Sederajat</option>
                                            <option value="SMA">SMA/SMK/MA/Sederajat</option>
                                            <option value="Diploma I (D1)">Diploma I (D1)</option>
                                            <option value="Diploma II (D2)">Diploma II (D2)</option>
                                            <option value="Diploma III (D3)">Diploma III (D3)</option>
                                            <option value="Sarjana (S1)">Sarjana (S1)/Diploma IV (D4)</option>
                                            <option value="Magister (S2)">Magister (S2)</option>
                                            <option value="Doktor (S3)">Doktor (S3)</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="no_ktp">No KTP</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="no_ktp" id="no_ktp" placeholder="No KTP">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="status_pernikahan">Status Pernikahan</label>
                                    <div class="col-sm-7">
                                        <select id="status_pernikahan" name="status_pernikahan" class="form-control">
                                            <option value="" hidden>Pilih Status Pernikahan</option>
                                            <option value="Belum Nikah">Belum Menikah</option>
                                            <option value="Menikah">Menikah</option>
                                            <option value="Cerai Hidup">Cerai Hidup</option>
                                            <option value="Cerai Mati">Cerai Mati</option>
                                            <option value="Duda">Duda</option>
                                            <option value="Janda">Janda</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="ktk">KTK</label>
                                    <div class="col-sm-7">
                                        <select id="ktk" name="ktk" class="form-control">
                                            <option value="">Pilih KTK</option>
                                            <option value="TK/0">TK/0 - Tidak Kawin, 0 tanggungan</option>
                                            <option value="TK/1">TK/1 - Tidak Kawin, 1 tanggungan</option>
                                            <option value="TK/2">TK/2 - Tidak Kawin, 2 tanggungan</option>
                                            <option value="TK/3">TK/3 - Tidak Kawin, 3 tanggungan</option>
                                            <option value="K/0">K/0 - Kawin, 0 tanggungan</option>
                                            <option value="K/1">K/1 - Kawin, 1 tanggungan</option>
                                            <option value="K/2">K/2 - Kawin, 2 tanggungan</option>
                                            <option value="K/3">K/3 - Kawin, 3 tanggungan</option>
                                            <option value="K/I/0">K/I/0 - Kawin, istri bekerja, 0 tanggungan</option>
                                            <option value="K/I/1">K/I/1 - Kawin, istri bekerja, 1 tanggungan</option>
                                            <option value="K/I/2">K/I/2 - Kawin, istri bekerja, 2 tanggungan</option>
                                            <option value="K/I/3">K/I/3 - Kawin, istri bekerja, 3 tanggungan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="alamat_ktp">Alamat KTP</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" name="alamat_ktp" id="alamat_ktp" rows="3" placeholder="Alamat KTP"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="domisili">Domisili</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="domisili" id="domisili" placeholder="Domisili">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="telp_klrga_serumah">Telp Keluarga Serumah</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="telp_klrga_serumah" id="telp_klrga_serumah" placeholder="Telp Keluarga Serumah">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="telp_klrga_tdk_serumah">Telp Keluarga Tidak Serumah</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="telp_klrga_tdk_serumah" id="telp_klrga_tdk_serumah" placeholder="Telp Keluarga Tidak Serumah">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="gol_darah">Gol Darah</label>
                                    <div class="col-sm-7">
                                        <select id="gol_darah" name="gol_darah" class="form-control">
                                            <option value="" hidden>Pilih Golongan Darah</option>
                                            <option value="-">-</option>
                                            <option value="A">A</option>
                                            <option value="A+">A+</option>
                                            <option value="B">B</option>
                                            <option value="AB">AB</option>
                                            <option value="O">O</option>
                                            <option value="Tidak Tahu">Tidak Tahu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="no_hp">No HP</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="no_hp" id="no_hp" placeholder="No HP">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lokasi_kerja" class="col-sm-5 col-form-label">Lokasi Kerja</label>
                                    <div class="col-sm-7">
                                        <select id="lokasi_kerja" name="lokasi_kerja" class="form-control">
                                            <option value="" hidden>Pilih Lokasi Kerja</option>
                                            <option value="HEAD OFFICE">Head Office</option>
                                            <option value="Kantor Pusat">Kantor Pusat</option>
                                            <option value="Cabang">Cabang</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="wilayah_kerja" class="col-sm-5 col-form-label">Wilayah Kerja</label>
                                    <div class="col-sm-7">
                                        <select id="wilayah_kerja" name="wilayah_kerja" class="form-control">
                                            <option value="HEAD OFFICE">Head Office</option>
                                            <option value="" hidden>Pilih Wilayah Kerja</option>
                                            <option value="Nasional">Nasional</option>
                                            <option value="Bogor (Jabar 1)">Bogor (Jabar 1)</option>
                                            <option value="Bandung (Jabar 2)">Bandung (Jabar 2)</option>
                                            <option value="Gresik">Gresik</option>
                                            <option value="Yogyakarta">Yogyakarta</option>
                                            <option value="Palembang">Palembang</option>
                                            <option value="Pekanbaru">Pekanbaru</option>
                                            <option value="Lampung">Lampung</option>
                                            <option value="Serang">Serang</option>
                                            <option value="Lubuklinggau">Lubuklinggau</option>
                                            <option value="Kediri">Kediri</option>
                                            <option value="Bali">Bali</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Pas Foto</label>
                                    <div class="col-sm-7">
                                        <div id="foto_preview_container" style="margin-bottom:8px; display:none;">
                                            <img id="foto_preview" src="" alt="Foto Karyawan" style="max-width:120px;max-height:120px;border-radius:8px;border:1px solid #ddd;">
                                        </div>
                                        <input class="form-control" type="file" name="foto" id="foto">
                                        <span class="kwitansi-label">Max Size : 3MB</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Kartu Keluarga</label>
                                    <div class="col-sm-7">
                                        <div id="kk_preview_container" style="margin-bottom:8px; display:none;">
                                            <img id="kk_preview" src="" alt="KK Karyawan" style="max-width:120px;max-height:120px;border-radius:8px;border:1px solid #ddd;">
                                        </div>
                                        <input class="form-control" type="file" name="kk" id="kk">
                                        <span class="kwitansi-label">Max Size : 3MB</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">KTP</label>
                                    <div class="col-sm-7">
                                        <div id="ktp_preview_container" style="margin-bottom:8px; display:none;">
                                            <img id="ktp_preview" src="" alt="KTP Karyawan" style="max-width:120px;max-height:120px;border-radius:8px;border:1px solid #ddd;">
                                        </div>
                                        <input class="form-control" type="file" name="ktp" id="ktp">
                                        <span class="kwitansi-label">Max Size : 3MB</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">NPWP</label>
                                    <div class="col-sm-7">
                                        <div id="npwp_preview_container" style="margin-bottom:8px; display:none;">
                                            <img id="npwp_preview" src="" alt="NPWP Karyawan" style="max-width:120px;max-height:120px;border-radius:8px;border:1px solid #ddd;">
                                        </div>
                                        <input class="form-control" type="file" name="npwp" id="npwp">
                                        <span class="kwitansi-label">Max Size : 3MB</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Ijazah Terakhir</label>
                                    <div class="col-sm-7">
                                        <div id="ijazah_preview_container" style="margin-bottom:8px; display:none;">
                                            <img id="ijazah_preview" src="" alt="Ijazah Karyawan" style="max-width:120px;max-height:120px;border-radius:8px;border:1px solid #ddd;">
                                        </div>
                                        <input class="form-control" type="file" name="ijazah" id="ijazah">
                                        <span class="kwitansi-label">Max Size : 3MB</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="unit_bisnis" class="col-sm-5 col-form-label">Unit Bisnis</label>
                                    <div class="col-sm-7">
                                        <select id="unit_bisnis" name="unit_bisnis" class="form-control">
                                            <option value="" hidden>Pilih Unit Bisnis</option>
                                            <option value="-">-</option>
                                            <option value="KPS">Kolaborasi Para Sahabat</option>
                                            <option value="sebelaswarna">sebelaswarna</option>
                                            <option value="pengenumroh">pengenumroh</option>
                                            <option value="qubagift">qubagift</option>  
                                            <option value="by.moment">by.moment</option>
                                            <option value="sobatwisata">sobatwisata</option>
                                            <option value="mobileautocare">mobileautocare</option>
                                            <option value="carstensz">carstensz</option>
                                            <option value="sahabat multi logistik">Sahabat Multi Logistik</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="posisi" class="col-sm-5 col-form-label">Posisi</label>
                                    <div class="col-sm-7">
                                        <select name="posisi" id="posisi" class="form-control" required>
                                            <option value="" hidden>Pilih Posisi</option>
                                            <option value="Director">Director</option>
                                            <option value="Finance Manager">Finance Manager</option>
                                            <option value="HCGA Manager">HCGA Manager</option>
                                            <option value="Customer Service">Customer Service</option>
                                            <option value="IT Support">IT Support</option>
                                            <option value="Staff Human Capital">Staff Human Capital</option>
                                            <option value="Recruitment & Training Development Officer">Recruitment & Training Development Officer</option>
                                            <option value="Admin HRD">Admin HRD</option>
                                            <option value="Staff Keuangan">Staff Keuangan</option>
                                            <option value="Marketing Digital">Marketing Digital</option>
                                            <option value="Teknisi Lapangan">Teknisi Lapangan</option>
                                            <option value="Content Writer">Content Writer</option>
                                            <option value="Operator Produksi">Operator Produksi</option>
                                            <option value="Driver">Driver</option>
                                            <option value="Office Boy">Office Boy</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="jabatan" class="col-sm-5 col-form-label">Jabatan</label>
                                    <div class="col-sm-7">
                                        <select id="jabatan" name="jabatan" class="form-control">
                                            <option value="" hidden>Pilih Jabatan</option>
                                            <option value="Magang">Magang / Intern</option>
                                            <option value="Staff">Staff</option>
                                            <option value="Junior Administrator">Junior Administrator</option>
                                            <option value="Administrator">Administrator</option>
                                            <option value="Supervisor">Supervisor</option>
                                            <option value="Asisten Manager">Asisten Manager</option>
                                            <option value="Manager">Manager</option>
                                            <option value="Senior Manager">Senior Manager</option>
                                            <option value="Kepala Divisi">Kepala Divisi</option>
                                            <option value="Kepala Bagian">Kepala Bagian</option>
                                            <option value="Kepala Cabang">Kepala Cabang</option>
                                            <option value="Kepala Unit">Kepala Unit</option>
                                            <option value="Director">Director</option>
                                            <option value="Wakil Direktur">Wakil Direktur</option>
                                            <option value="Direktur Utama">Direktur Utama</option>
                                            <option value="Presiden Direktur">Presiden Direktur</option>
                                            <option value="Komisaris">Komisaris</option>
                                            <option value="Komisaris Utama">Komisaris Utama</option>
                                            <option value="Staf Administrasi">Staf Administrasi</option>
                                            <option value="Staf Keuangan">Staf Keuangan</option>
                                            <option value="Staf Pemasaran">Staf Pemasaran</option>
                                            <option value="Staf HRD">Staf HRD</option>
                                            <option value="Supervisor Produksi">Supervisor Produksi</option>
                                            <option value="Teknisi">Teknisi</option>
                                            <option value="Operator">Operator</option>
                                            <option value="Kepala Proyek">Kepala Proyek</option>
                                            <option value="Direktur Keuangan">Direktur Keuangan</option>
                                            <option value="Direktur Operasional">Direktur Operasional</option>
                                            <option value="Asisten Direktur">Asisten Direktur</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="department" class="col-sm-5 col-form-label">Department</label>
                                    <div class="col-sm-7">
                                        <select name="department" id="department" class="form-control">
                                            <option value="" hidden>Pilih Departement</option>
                                            <option value="Human Capital">Human Capital</option>
                                            <option value="Finance and Accounting">Finance and Accounting</option>
                                            <option value="Business Support">Business Support</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Customer Service">Customer Service</option>
                                            <option value="Information Technology">Information Technology</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Produksi">Produksi</option>
                                            <option value="Operational">Operational</option>
                                            <option value="Logistik">Logistik</option>
                                            <option value="Purchasing">Purchasing</option>
                                            <option value="R&D">R&D</option>
                                            <option value="QA/QC">QA/QC</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="grade" class="col-sm-5 col-form-label">Grade</label>
                                    <div class="col-sm-7">
                                        <select id="grade" name="grade" class="form-control">
                                            <option value="" hidden>Pilih Grade</option>
                                            <!-- Level 1 -->
                                            <option value="1A">1A</option>
                                            <option value="1B">1B</option>
                                            <option value="1C">1C</option>
                                            <option value="1D">1D</option>
                                            <option value="1E">1E</option>
                                            <option value="1F">1F</option>
                                            <!-- Level 2 -->
                                            <option value="2A">2A</option>
                                            <option value="2B">2B</option>
                                            <option value="2C">2C</option>
                                            <option value="2D">2D</option>
                                            <option value="2E">2E</option>
                                            <option value="2F">2F</option>
                                            <!-- Level 3 -->
                                            <option value="3A">3A</option>
                                            <option value="3B">3B</option>
                                            <option value="3C">3C</option>
                                            <option value="3D">3D</option>
                                            <option value="3E">3E</option>
                                            <option value="3F">3F</option>
                                            <!-- Level 4 -->
                                            <option value="4A">4A</option>
                                            <option value="4B">4B</option>
                                            <option value="4C">4C</option>
                                            <option value="4D">4D</option>
                                            <option value="4E">4E</option>
                                            <option value="4F">4F</option>
                                            <!-- Level 5 -->
                                            <option value="5A">5A</option>
                                            <option value="5B">5B</option>
                                            <option value="5C">5C</option>
                                            <option value="5D">5D</option>
                                            <option value="5E">5E</option>
                                            <option value="5F">5F</option>
                                            <!-- Level 6 -->
                                            <option value="6A">6A</option>
                                            <option value="6B">6B</option>
                                            <option value="6C">6C</option>
                                            <option value="6D">6D</option>
                                            <option value="6E">6E</option>
                                            <option value="6F">6F</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="status_karyawan" class="col-sm-5 col-form-label">Status Karyawan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="status_karyawan" id="status_karyawan">
                                            <option value="" selected hidden>Pilih Status Karyawan</option>
                                            <option value="Kontrak">Kontrak</option>
                                            <option value="Permanen">Permanen</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="asal_karyawan" class="col-sm-5 col-form-label">Asal Karyawan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="asal_karyawan" id="asal_karyawan">
                                            <option value="" hidden>Pilih Asal Karyawan</option>
                                            <option value="MCS">MCS</option>
                                            <option value="KPS">KPS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="total_bulan" class="col-sm-5 col-form-label">Total Bulan</label>
                                    <div class="col-sm-7">
                                        <!-- Interactive month stepper -->
                                        <div class="d-flex align-items-center">
                                            <div class="input-group" style="width: 150px;">
                                                <div class="input-group-prepend" style="z-index: 0;">
                                                    <button type="button" class="btn btn-outline-primary btn-step" data-step="-1" aria-label="kurangi">−</button>
                                                </div>
                                                <!-- default sekarang 0 dan boleh 0 -->
                                                <input type="number" class="form-control text-center" name="total_bulan" id="total_bulan" min="0" max="120" value="0" aria-label="total bulan">
                                                <div class="input-group-append" style="z-index: 0;">
                                                    <button type="button" class="btn btn-outline-primary btn-step" data-step="1" aria-label="tambah">+</button>
                                                </div>
                                            </div>

                                            <div class="ml-3" id="total_bulan_label" style="min-width:120px;color:#495057;font-weight:600;">0 bulan</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_masuk" class="col-sm-5 col-form-label">Tanggal Masuk</label>
                                    <div class="col-sm-7">
                                        <!-- disabled awal, akan di-enable jika total_bulan > 0 -->
                                        <input type="text" class="form-control datepicker" name="tgl_masuk" id="tgl_masuk" placeholder="Pilih Tanggal Masuk" style="cursor: pointer" autocomplete="off" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_akhir_kontrak" class="col-sm-5 col-form-label">Tanggal Akhir Kontrak</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="tgl_akhir_kontrak" id="tgl_akhir_kontrak" readonly placeholder="Tanggal Akhir Kontrak" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_rekrut" class="col-sm-5 col-form-label">Tanggal Rekrut</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="tgl_rekrut" id="tgl_rekrut" placeholder="Pilih Tanggal Rekrut" style="cursor: pointer" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row" id="tgl_permanen_container">
                                    <label for="tgl_permanen" class="col-sm-5 col-form-label">Tanggal Permanen</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="tgl_permanen" id="tgl_permanen" placeholder="Pilih Tanggal Permanen" style="cursor: pointer" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row" id="tgl_phk_container" style="display: none;">
                                    <label for="tgl_phk" class="col-sm-5 col-form-label">Tanggal PHK</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="tgl_phk" id="tgl_phk">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="masa_kerja" class="col-sm-5 col-form-label">Masa Kerja</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control only-number" name="masa_kerja" id="masa_kerja" readonly placeholder="Masa Kerja">
                                    </div>
                                    <div class="col-sm-3">
                                        <p style="padding-top: 6px;">Tahun</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="no_rek" class="col-sm-5 col-form-label">No Rekening</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control only-number" name="no_rek" id="no_rek" placeholder="No Rekening">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_pemilik_rek" class="col-sm-5 col-form-label">Nama Pemilik Rekening</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="nama_pemilik_rek" id="nama_pemilik_rek" placeholder="Nama Pemilik Rekening">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_bank" class="col-sm-5 col-form-label">Nama Bank</label>
                                    <div class="col-sm-7">
                                        <select id="nama_bank" name="nama_bank" class="form-control">
                                            <option value="" hidden>Pilih Nama Bank</option>
                                            <option value="BCA">Bank Central Asia (BCA)</option>
                                            <option value="BRI">Bank Rakyat Indonesia (BRI)</option>
                                            <option value="BNI">Bank Negara Indonesia (BNI)</option>
                                            <option value="Mandiri">Bank Mandiri</option>
                                            <option value="BTN">Bank Tabungan Negara (BTN)</option>
                                            <option value="CIMB Niaga">CIMB Niaga</option>
                                            <option value="Danamon">Bank Danamon</option>
                                            <option value="Permata">Bank Permata</option>
                                            <option value="Panin">Bank Panin</option>
                                            <option value="OCBC NISP">Bank OCBC NISP</option>
                                            <option value="Maybank">Maybank Indonesia</option>
                                            <option value="Mega">Bank Mega</option>
                                            <option value="BTPN">Bank BTPN</option>
                                            <option value="Sinarmas">Bank Sinarmas</option>
                                            <option value="BSI">Bank Syariah Indonesia (BSI)</option>
                                            <option value="Muamalat">Bank Muamalat</option>
                                            <option value="Lainnya">Lainnya</option>
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
                                        <input type="hidden" name="keahlian" id="keahlian">
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
                                        <input type="hidden" name="pelatihan_internal" id="pelatihan_internal">
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
                                        <input type="hidden" name="pelatihan_eksternal" id="pelatihan_eksternal" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="width: 100%; height: 0.1px; background-color: rgba(0,0,0,0.3); margin-bottom: 12px"></div>
                            <div style="margin-bottom: 16px;">
                                <h5 class="text-primary text-center" style="margin-bottom: -30px;"><i class="fas fa-users"></i> Data Keluarga</h5>
                                <button type="button" class="btn btn-primary btn-sm" id="addKeluargaBtn" style="margin-bottom: -8px;"><i class="fas fa-plus"></i> Add</button>
                            </div>
                        <div style="width: 100%; height: 0.1px; background-color: rgba(0,0,0,0.3); margin-bottom: 24px"></div>
                        <div id="keluargaContainer">
                            <!-- Generate Inputan Keluarga -->
                        </div>
                        <!-- Hidden inputs -->
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="">
                        <?php } ?>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-sm aksi mt-3"></button>
                    </form>

                    <!-- Modal -->
                    <div id="imageModal" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <img id="modalImage" src="" alt="Gambar" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
(function(){
    var idMonthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    function isoToVisible(iso) {
        if (!iso) return '';
        var d = new Date(iso);
        if (isNaN(d.getTime())) return '';
        var day = ('0' + d.getDate()).slice(-2);
        var month = idMonthNames[d.getMonth()];
        var year = d.getFullYear();
        return day + ' ' + month + ' ' + year;
    }

    // Hitung Tanggal Akhir berdasarkan tgl_masuk_iso + total_bulan
    function computeTglAkhirKontrak() {
        var startIso = $('#tgl_masuk_iso').val();
        var months = parseInt($('#total_bulan').val(), 10);

        if (!startIso || isNaN(months) || months <= 0) {
            $('#tgl_akhir_kontrak_iso').val('');
            $('#tgl_akhir_kontrak').val('');
            return;
        }

        var parts = startIso.split('-');
        if (parts.length < 3) return;

        var year = parseInt(parts[0], 10);
        var monthIndex = parseInt(parts[1], 10) - 1;
        var day = parseInt(parts[2], 10);

        var dt = new Date(year, monthIndex, day);
        // tambah bulan lalu kurangi 1 hari -> hasil inklusif
        dt.setMonth(dt.getMonth() + months);
        dt.setDate(dt.getDate() - 1);

        var y = dt.getFullYear();
        var m = ('0' + (dt.getMonth() + 1)).slice(-2);
        var d = ('0' + dt.getDate()).slice(-2);
        var isoEnd = y + '-' + m + '-' + d;

        $('#tgl_akhir_kontrak_iso').val(isoEnd);
        $('#tgl_akhir_kontrak').val(isoToVisible(isoEnd));
    }

    // Jika perlu menghitung total_bulan dari dua tanggal, gunakan fungsi ini (tidak dipanggil otomatis)
    function calcTotalBulanInclusive(startIso, endIso) {
        if (!startIso || !endIso) return 0;
        var s = new Date(startIso);
        var e = new Date(endIso);
        if (isNaN(s.getTime()) || isNaN(e.getTime())) return 0;
        var years = e.getFullYear() - s.getFullYear();
        var months = years * 12 + (e.getMonth() - s.getMonth());
        // inklusif jika hari akhir >= hari awal
        if (e.getDate() >= s.getDate()) months += 1;
        if (months < 0) months = 0;
        return months;
    }

    // Datepicker: set hidden ISO and ONLY compute tgl akhir (jangan ubah total_bulan!)
    $('#tgl_masuk').datepicker({
        dateFormat: 'dd MM yy',
        changeMonth: true,
        changeYear: true,
        monthNames: idMonthNames,
        yearRange: "1900:2099",
        onSelect: function(dateText) {
            var d = $(this).datepicker('getDate');
            if (d) {
                var iso = $.datepicker.formatDate('yy-mm-dd', d);
                $('#tgl_masuk_iso').val(iso);
            }
            // Hanya hitung tgl akhir dari total_bulan; JANGAN ubah #total_bulan di sini
            computeTglAkhirKontrak();
        }
    });

    // event: saat total_bulan berubah -> enable tgl_masuk and recompute tgl akhir
    $(document).on('input change', '#total_bulan', function() {
        var val = parseInt($(this).val(), 10);
        if (isNaN(val) || val <= 0) {
            $('#tgl_masuk').prop('disabled', true);
            $('#tgl_masuk_iso').val('');
            $('#tgl_akhir_kontrak_iso').val('');
            $('#tgl_akhir_kontrak').val('');
            return;
        }
        $('#tgl_masuk').prop('disabled', false);
        // recompute only tgl akhir
        computeTglAkhirKontrak();
    });

    // Prevent accidental recalculation of total_bulan on click/focus of tgl_masuk
    // (no handler that recalculates total_bulan should be present)
    // If you have legacy code that recalculates total_bulan from dates, remove or comment it.

    // init on load
    $(document).ready(function(){
        // restore visible from ISO if edit
        var isoStart = $('#tgl_masuk_iso').val();
        if (isoStart) $('#tgl_masuk').val(isoToVisible(isoStart));
        var isoEnd = $('#tgl_akhir_kontrak_iso').val();
        if (isoEnd) $('#tgl_akhir_kontrak').val(isoToVisible(isoEnd));

        // set disabled state based on initial total_bulan
        var initial = parseInt($('#total_bulan').val(), 10);
        if (isNaN(initial) || initial <= 0) $('#tgl_masuk').prop('disabled', true);
        else $('#tgl_masuk').prop('disabled', false);
    });

    // expose calc function if needed (optional)
    window.calcTotalBulanInclusive = calcTotalBulanInclusive;
    window.computeTglAkhirKontrak = computeTglAkhirKontrak;
})();
</script>
<script>
    // fix final: normalisasi datepicker #tgl_masuk dan satu compute konsisten
    (function(){
        // pastikan idMonthNames & isoToVisible tersedia (ada di atas)
        // hapus inisialisasi ganda datepicker jika ada
        try { $('#tgl_masuk').datepicker('destroy'); } catch(e){}

        $('#tgl_masuk').datepicker({
            dateFormat: 'dd MM yy',
            altField: '#tgl_masuk_iso',
            altFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            monthNames: idMonthNames,
            yearRange: "1900:2099",
            onSelect: function(dateText) {
                // pastikan iso ter-set
                var d = $(this).datepicker('getDate');
                if (d) {
                    $('#tgl_masuk_iso').val($.datepicker.formatDate('yy-mm-dd', d));
                }
                computeTglAkhirKontrak();
            }
        });

        // pastikan hanya satu handler untuk total_bulan
        $(document).off('input change', '#total_bulan').on('input change', '#total_bulan', function(){
            var val = parseInt($(this).val(), 10);
            if (isNaN(val) || val <= 0) {
                $('#tgl_masuk').prop('disabled', true);
                $('#tgl_masuk_iso').val('');
                $('#tgl_akhir_kontrak_iso').val('');
                $('#tgl_akhir_kontrak').val('');
                return;
            }
            $('#tgl_masuk').prop('disabled', false);
            computeTglAkhirKontrak();
        });

        // juga pastikan perubahan ISO langsung menghitung ulang
        $(document).off('change', '#tgl_masuk_iso').on('change', '#tgl_masuk_iso', computeTglAkhirKontrak);

        // konsisten compute function (override jika ada yang lain)
        window.computeTglAkhirKontrak = function() {
            var startIso = $('#tgl_masuk_iso').val();
            var months = parseInt($('#total_bulan').val(), 10);

            if (!startIso || isNaN(months) || months <= 0) {
                $('#tgl_akhir_kontrak_iso').val('');
                $('#tgl_akhir_kontrak').val('');
                return;
            }

            var parts = startIso.split('-');
            if (parts.length < 3) {
                $('#tgl_akhir_kontrak_iso').val('');
                $('#tgl_akhir_kontrak').val('');
                return;
            }

            var year = parseInt(parts[0], 10);
            var monthIndex = parseInt(parts[1], 10) - 1;
            var day = parseInt(parts[2], 10);

            var dt = new Date(year, monthIndex, day);
            // tambah months dan kurangi 1 hari -> inklusif
            dt.setMonth(dt.getMonth() + months);
            dt.setDate(dt.getDate() - 1);

            var y = dt.getFullYear();
            var m = ('0' + (dt.getMonth() + 1)).slice(-2);
            var d = ('0' + dt.getDate()).slice(-2);
            var isoEnd = y + '-' + m + '-' + d;

            $('#tgl_akhir_kontrak_iso').val(isoEnd);
            $('#tgl_akhir_kontrak').val(isoToVisible(isoEnd));
        };

        // init compute saat load (jika ada nilai)
        $(function(){ computeTglAkhirKontrak(); });
    })();
</script>
<script>
    
    $(document).ready(function() {
        $(document).on('input', '.only-number', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

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

        // Sembunyikan tanggal permanen di awal
        $('#tgl_permanen_container').hide();

        // Tampilkan/hide tanggal permanen sesuai status karyawan
        $('#status_karyawan').on('change', function() {
            if ($(this).val() === 'permanen') {
                $('#tgl_permanen_container').show();
            } else {
                $('#tgl_permanen_container').hide();
                $('#tgl_permanen').val('');
            }
        });

        // Jika edit, cek status_karyawan dan tampilkan jika perlu
        if ($('#status_karyawan').val() === 'permanen') {
            $('#tgl_permanen_container').show();
        }

        var $wilayah = $('#wilayah_kerja');
        // Simpan semua option cabang (selain Nasional)
        var cabangOptions = '';
        $wilayah.find('option').each(function() {
            if ($(this).val() !== 'Nasional' && $(this).val() !== '') {
                cabangOptions += this.outerHTML;
            }
        });

        $('#lokasi_kerja').on('change', function() {
            var val = $(this).val();
            if (val === 'Kantor Pusat') {
                // Hanya tampilkan Nasional saja
                $wilayah.html('<option value="Nasional">Nasional</option>');
                $wilayah.val('Nasional').trigger('change');
            } else if (val === 'Cabang') {
                // Hilangkan Nasional, tampilkan hanya cabang
                $wilayah.html('<option value="" hidden>Pilih Wilayah Kerja</option>' + cabangOptions);
                $wilayah.val('').trigger('change');
            }
        });

        function hitungMasaKerja() {
            // prioritas: gunakan total_bulan jika diisi (sumber kebenaran)
            var totalBulanInput = parseInt($('#total_bulan').val(), 10);
            if (!isNaN(totalBulanInput) && totalBulanInput > 0) {
                var masaKerja = Math.floor(totalBulanInput / 12);
                $('#masa_kerja').val(masaKerja);
                return;
            }

            // fallback: jika total_bulan kosong, hitung dari tanggal (tapi JANGAN tulis ke #total_bulan)
            var startIso = $('#tgl_masuk_iso').val() || '';
            var endIsoVisible = $('#tgl_akhir_kontrak').val() || $('#tgl_akhir_kontrak_iso').val() || '';
            var endIso = $('#tgl_akhir_kontrak_iso').val() || endIsoVisible;

            // gunakan fungsi calcTotalBulanInclusive yang sudah ada
            var totalBulan = calcTotalBulanInclusive(startIso, endIso);
            var masaKerjaFall = Math.floor(totalBulan / 12);
            $('#masa_kerja').val(isNaN(masaKerjaFall) ? '' : masaKerjaFall);
        }

        // update bindings: hitung masa kerja saat total_bulan berubah,
        // atau saat tgl_akhir/tgl_masuk berubah (tidak akan menimpa total_bulan)
        $(document).off('input change', '#total_bulan').on('input change', '#total_bulan', function() {
            // enable/disable tgl_masuk handled elsewhere
            hitungMasaKerja();
            computeTglAkhirKontrak(); // maintain end date sync
        });

        // jika user ubah tanggal (visible atau ISO) recompute masa kerja fallback
        $(document).off('change', '#tgl_masuk_iso, #tgl_akhir_kontrak_iso, #tgl_masuk, #tgl_akhir_kontrak')
            .on('change', '#tgl_masuk_iso, #tgl_akhir_kontrak_iso, #tgl_masuk, #tgl_akhir_kontrak', function() {
                hitungMasaKerja();
            });
    });

    function requestGenerateNpk(yearTwoDigits) {
        $.ajax({
            url: '<?= site_url("kps_karyawan/generate_npk") ?>',
            type: 'POST',
            dataType: 'json',
            data: { year: yearTwoDigits },
            success: function(res) {
                if (res && res.status) {
                    $('#npk').val(res.npk).prop('readonly', true);
                }
            },
            error: function() {
                    console.warn('Gagal generate NPK');
            }
        });
    }

    function getYearTwoDigitsFromDateInput(dateStr) {
        if (!dateStr) return ('' + new Date().getFullYear()).slice(-2);
        var d = new Date(dateStr);
        if (isNaN(d.getTime())) return ('' + new Date().getFullYear()).slice(-2);
        return ('' + d.getFullYear()).slice(-2);
    }

    // only run on add page (id == 0)
    var isAdd = ( $('#id').val() == '0' || $('#id').val() === '' );
    if (isAdd) {
        // generate on load using tgl_masuk if present or current year
        var initialYear = getYearTwoDigitsFromDateInput($('#tgl_masuk').val());
        requestGenerateNpk(initialYear);

        // regenerate when tgl_masuk changes
        $('#tgl_masuk').on('change', function() {
            var y = getYearTwoDigitsFromDateInput($(this).val());
            requestGenerateNpk(y);
        });
    }

    var $input = $('#total_bulan');
    var $label = $('#total_bulan_label');
    var MIN = parseInt($input.attr('min')) || 0;
    var MAX = parseInt($input.attr('max')) || 120;

    function setTotalBulan(val, trigger) {
        val = parseInt(val);
        if (isNaN(val)) val = MIN;
        if (val < MIN) val = MIN;
        if (val > MAX) val = MAX;

        $input.val(val);
        $label.text(val + ' ' + (val > 1 ? 'bulan' : 'bulan'));

        // enable/disable tgl_masuk sesuai total_bulan
        if (val > 0) {
            $('#tgl_masuk').prop('disabled', false);
        } else {
            $('#tgl_masuk').prop('disabled', true).val('');
            $('#tgl_akhir_kontrak').val('');
        }

        if (trigger) $input.trigger('change');
    }

    // stepper buttons
        $(document).on('click', '.btn-step', function(e) {
            e.preventDefault();
            var step = parseInt($(this).data('step')) || 0;
            var current = parseInt($input.val()) || MIN;
            setTotalBulan(current + step, true);
        });

        // input number manual
        $(document).on('input', '#total_bulan', function() {
            var v = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(v);
            setTotalBulan(v, true);
        });

        // when leaving the input, normalize and trigger compute
        $(document).on('change', '#total_bulan', function() {
            setTotalBulan($(this).val(), true);
        });

        // range slider (jika ada)
        $(document).on('input change', '#total_bulan_range', function() {
            if ($(this).length) setTotalBulan($(this).val(), true);
        });

        // initialize from existing value (if any)
        $(document).ready(function() {
            var initial = parseInt($input.val());
            if (isNaN(initial)) initial = MIN;
            setTotalBulan(initial, true);
        });

        // computeTglAkhirKontrak sudah ada, pastikan dipanggil saat total_bulan berubah
        $(document).on('change keyup', '#tgl_masuk, #total_bulan', function() {
            if (!$('#tgl_masuk').prop('disabled')) {
                if (typeof computeTglAkhirKontrak === 'function') computeTglAkhirKontrak();
            }
        });

    function computeTglAkhirKontrak() {
            var start = $('#tgl_masuk').val();
            var months = parseInt($('#total_bulan').val(), 10);

            if (!start || isNaN(months) || months <= 0) {
                $('#tgl_akhir_kontrak').val('');
                return;
            }

            // parse yyyy-mm-dd
            var parts = start.split('-');
            if (parts.length < 3) {
                $('#tgl_akhir_kontrak').val('');
                return;
            }

            var year = parseInt(parts[0], 10);
            var monthIndex = parseInt(parts[1], 10) - 1; // JS month 0-based
            var day = parseInt(parts[2], 10);

            var dt = new Date(year, monthIndex, day);

            // tambahkan months
            dt.setMonth(dt.getMonth() + months);

            // kurangi 1 hari untuk mendapatkan tanggal akhir inklusif
            dt.setDate(dt.getDate() - 1);

            // format yyyy-mm-dd
            var y = dt.getFullYear();
            var m = ('0' + (dt.getMonth() + 1)).slice(-2);
            var d = ('0' + dt.getDate()).slice(-2);

            $('#tgl_akhir_kontrak').val(y + '-' + m + '-' + d).trigger('change');
        }

        // event: saat tgl_masuk atau total_bulan berubah
        $(document).on('change keyup', '#tgl_masuk, #total_bulan', function() {
            computeTglAkhirKontrak();
        });

        // hitung awal jika sudah ada nilai saat load
        $(document).ready(function() {
            computeTglAkhirKontrak();
        });

    $('.datepicker-tgl-lahir').datepicker({
        dateFormat: 'yy-mm-dd', // atau format lain sesuai kebutuhan
        changeMonth: true,
        yearRange: "1900:2099", // bisa disesuaikan
        changeYear: true,
        autoclose: true
    });

    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd', // atau format lain sesuai kebutuhan
        changeMonth: true,
        yearRange: "1900:2099", // bisa disesuaikan
        changeYear: true,
        autoclose: true,
        onSelect: function() {
            // panggil compute segera setelah datepicker memilih tanggal
            if (typeof computeTglAkhirKontrak === 'function') computeTglAkhirKontrak();
        }
    });

    function initKeluargaDatepicker() {
        $('.tgl-lahir-kel').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "1900:2099",
            autoclose: true
        });
    }

    $('#status_kerja').on('change', function() {
        if ($(this).val() == 'Non Aktif') {
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
        $('#nama_lengkap').select2({
            placeholder: "Pilih User",
            allowClear: true
        });
    });

    function handleTagInput(inputId, addBtnId, tagsDivId, hiddenInputId) {
        let tags = [];

        // Jika edit, load data dari value hidden
        let initial = $('#' + hiddenInputId).val();
        if (initial) {
            tags = initial.split(',').map(e => e.trim()).filter(e => e);
            renderTags();
        }

        function renderTags() {
            let html = '';
            tags.forEach((tag, idx) => {
                html += `<span class="tag">${tag}<span class="remove-tag" data-idx="${idx}">&times;</span></span>`;
            });
            $('#' + tagsDivId).html(html);
            $('#' + hiddenInputId).val(tags.join(', '));
        }

        // Hapus event handler lama sebelum pasang baru
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
    }

    // Inisialisasi untuk masing-masing input
    $(document).ready(function() {
        handleTagInput('keahlian_input', 'add_keahlian', 'keahlian_tags', 'keahlian');
        handleTagInput('pelatihan_internal_input', 'add_pelatihan_internal', 'pelatihan_internal_tags', 'pelatihan_internal');
        handleTagInput('pelatihan_eksternal_input', 'add_pelatihan_eksternal', 'pelatihan_eksternal_tags', 'pelatihan_eksternal');
    });

    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('#customer_id').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='group']").hide();
            $.ajax({
                url: "<?php echo site_url('kps_karyawan/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    moment.locale('id');
                    $('#id').val(data['master'].id);
                    // // ISI FIELD PRODUK AGEN
                    $('#status_kerja').val(data['master'].status_kerja).change();
                    $('#npk').val(data['master'].npk);
                    $('#id_user').val(data['master'].id_user).change();
                    $('#nama_lengkap').val(data['master'].nama_lengkap + data['master'].id_user).change();
                    $('#jenis_kelamin').val(data['master'].jenis_kelamin).change();
                    $('#tempat_lahir').val(data['master'].tempat_lahir);
                    $('#tgl_lahir').val(moment(data['master'].tgl_lahir).format('YYYY-MM-DD'));
                    $('#umur').val(data['master'].umur);
                    $('#pendidikan').val(data['master'].pendidikan);
                    $('#no_ktp').val(data['master'].no_ktp);
                    $('#status_pernikahan').val(data['master'].status_pernikahan);
                    $('#ktk').val(data['master'].ktk);
                    $('#alamat_ktp').val(data['master'].alamat_ktp);
                    $('#domisili').val(data['master'].domisili);
                    $('#telp_klrga_serumah').val(data['master'].telp_klrga_serumah);
                    $('#telp_klrga_tdk_serumah').val(data['master'].telp_klrga_tdk_serumah);
                    $('#gol_darah').val(data['master'].gol_darah);
                    $('#no_hp').val(data['master'].no_hp);
                    $('#lokasi_kerja').val(data['master'].lokasi_kerja);
                    $('#wilayah_kerja').val(data['master'].wilayah_kerja);
                    $('#unit_bisnis').val(data['master'].unit_bisnis);
                    $('#posisi').val(data['master'].posisi);
                    $('#jabatan').val(data['master'].jabatan).change();
                    $('#department').val(data['master'].department);
                    $('#grade').val(data['master'].grade).change();
                    $('#status_karyawan').val(data['master'].status_karyawan);
                    $('#tgl_masuk').val(moment(data['master'].tgl_masuk).format('YYYY-MM-DD'));
                    $('#tgl_rekrut').val(moment(data['master'].tgl_rekrut).format('YYYY-MM-DD'));
                    $('#tgl_permanen').val(moment(data['master'].tgl_permanen).format('YYYY-MM-DD'));
                    $('#tgl_akhir_kontrak').val(moment(data['master'].tgl_akhir_kontrak).format('YYYY-MM-DD'));
                    $('#tgl_phk').val(moment(data['master'].tgl_phk).format('YYYY-MM-DD'));
                    $('#masa_kerja').val(data['master'].masa_kerja);
                    $('#total_bulan').val(data['master'].total_bulan);
                    $('#no_rek').val(data['master'].no_rek);
                    $('#nama_pemilik_rek').val(data['master'].nama_pemilik_rek);
                    $('#asal_karyawan').val(data['master'].asal_karyawan);
                    $('#nama_bank').val(data['master'].nama_bank).change();
                    $('#keahlian').val(data['master'].keahlian);
                    $('#pelatihan_internal').val(data['master'].pelatihan_internal);
                    $('#pelatihan_eksternal').val(data['master'].pelatihan_eksternal);

                    // Pas Foto
                    if (data['master'].foto) {
                        $('#foto_preview').attr('src', '<?= base_url("assets/backend/document/data_karyawan/foto/") ?>' + data['master'].foto);
                        $('#foto_preview_container').show();
                    } else {
                        $('#foto_preview_container').hide();
                    }

                    // Kartu Keluarga
                    if (data['master'].kk) {
                        $('#kk_preview').attr('src', '<?= base_url("assets/backend/document/data_karyawan/kk/") ?>' + data['master'].kk);
                        $('#kk_preview_container').show();
                    } else {
                        $('#kk_preview_container').hide();
                    }

                    // KTP
                    if (data['master'].ktp) {
                        $('#ktp_preview').attr('src', '<?= base_url("assets/backend/document/data_karyawan/ktp/") ?>' + data['master'].ktp);
                        $('#ktp_preview_container').show();
                    } else {
                        $('#ktp_preview_container').hide();
                    }

                    // NPWP
                    if (data['master'].npwp) {
                        $('#npwp_preview').attr('src', '<?= base_url("assets/backend/document/data_karyawan/npwp/") ?>' + data['master'].npwp);
                        $('#npwp_preview_container').show();
                    } else {
                        $('#npwp_preview_container').hide();
                    }

                    // Ijazah
                    if (data['master'].ijazah) {
                        $('#ijazah_preview').attr('src', '<?= base_url("assets/backend/document/data_karyawan/ijazah/") ?>' + data['master'].ijazah);
                        $('#ijazah_preview_container').show();
                    } else {
                        $('#ijazah_preview_container').hide();
                    }

                    handleTagInput('keahlian_input', 'add_keahlian', 'keahlian_tags', 'keahlian');
                    handleTagInput('pelatihan_internal_input', 'add_pelatihan_internal', 'pelatihan_internal_tags', 'pelatihan_internal');
                    handleTagInput('pelatihan_eksternal_input', 'add_pelatihan_eksternal', 'pelatihan_eksternal_tags', 'pelatihan_eksternal');

                    // Hapus baris keluarga lama
                    $('#keluargaContainer').empty();
                    if (data.keluarga && data.keluarga.length > 0) {
                        data.keluarga.forEach(function(item, idx) {
                            var html = $(getKeluargaTemplate(idx + 1));
                            html.find('input[name="id_keluarga[]"]').val(item.id);
                            html.find('input[name="nama_anggota[]"]').val(item.nama_anggota);
                            html.find('select[name="status_wp_kel[]"]').val(item.status_wp);
                            html.find('select[name="jenis_kelamin_kel[]"]').val(item.jenis_kelamin);
                            html.find('input[name="tgl_lahir_kel[]"]').val(item.tgl_lahir);
                            html.find('input[name="umur_kel[]"]').val(item.umur);
                            html.find('select[name="pendidikan_kel[]"]').val(item.pendidikan);
                            html.find('select[name="keanggotaan[]"]').val(item.keanggotaan);
                            html.find('select[name="lokasi_kerja_kel[]"]').val(item.lokasi_kerja);
                            html.find('select[name="wilayah_kerja_kel[]"]').val(item.wilayah_kerja);
                            $('#keluargaContainer').append(html);
                        });
                        initKeluargaDatepicker();
                    } else {
                        $('#keluargaContainer').append(getKeluargaTemplate(1));
                        $('#keluargaContainer .removeKeluargaBtn').hide();
                        initKeluargaDatepicker();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        // Script delete data file gambar

        function deleteFile(field) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "File ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo base_url("kps_karyawan/delete_file"); ?>', // URL controller untuk menghapus file
                        type: 'POST',
                        data: {
                            id: id, // Kirim ID customer untuk diupdate
                            field: field // Kirim nama field yang akan dihapus
                        },
                        success: function(response) {
                            Swal.fire(
                                'Terhapus!',
                                'File berhasil dihapus.',
                                'success'
                            );
                            // Update tampilan untuk field yang dihapus
                            $('#' + field + '_foto').hide();
                            $('#' + field + '_image').hide();
                            $('#' + field + '_tidak_ada').text('Tidak ada').show();
                            $('#delete_' + field).hide();
                        },
                        error: function() {
                            Swal.fire('Error', 'Tidak dapat menghapus file.', 'error');
                        }
                    });
                }
            });
        }

        if (aksi == "read") {
            $('#form input, #form select, #form textarea').prop('disabled', true).css('cursor', 'not-allowed');
            $('.aksi').hide(); // Sembunyikan tombol aksi jika mode read
        }

        // Template baris keluarga
        function getKeluargaTemplate(no) {
            return `
            <div class="keluarga-item" style="border:1px solid #e9e9e9ff; padding:16px; margin-bottom:12px; border-radius:8px; position:relative;">
                <button type="button" class="btn btn-danger btn-sm removeKeluargaBtn" style="position:absolute;top:8px;right:8px;display:none;"><i class="fas fa-trash"></i></button>
                <div style="font-weight:bold; font-size:1.1em; margin-bottom:12px; color:#1a2035;">Anggota ke-${no}</div>
                <div class="row" style="margin-top: 0;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-5">Nama Anggota</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="nama_anggota[]" required placeholder="Nama Anggota">
                                <input type="hidden" class="form-control" name="id_keluarga[]" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5">Jenis Kelamin</label>
                            <div class="col-sm-7">
                                <select class="form-control" name="jenis_kelamin_kel[]" required>
                                    <option value="" hidden>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5">Tanggal Lahir</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control tgl-lahir-kel" name="tgl_lahir_kel[]" autocomplete="off" required placeholder="Pilih Tanggal Lahir" style="cursor: pointer">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-5">Pendidikan</label>
                            <div class="col-sm-7">
                                <select class="form-control" name="pendidikan_kel[]" required>
                                    <option value="" hidden>Pilih Pendidikan Terakhir</option>
                                    <option value="Tidak Sekolah">Tidak/Belum Sekolah</option>
                                    <option value="Belum Tamat SD">Belum Tamat SD/Sederajat</option>
                                    <option value="SD">SD/Sederajat</option>
                                    <option value="SMP">SMP/Sederajat</option>
                                    <option value="SMA">SMA/SMK/MA/Sederajat</option>
                                    <option value="Diploma I (D1)">Diploma I (D1)</option>
                                    <option value="Diploma II (D2)">Diploma II (D2)</option>
                                    <option value="Diploma III (D3)">Diploma III (D3)</option>
                                    <option value="Sarjana (S1)">Sarjana (S1)/Diploma IV (D4)</option>
                                    <option value="Magister (S2)">Magister (S2)</option>
                                    <option value="Doktor (S3)">Doktor (S3)</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5">Keanggotaan</label>
                            <div class="col-sm-7">
                                <select class="form-control" name="keanggotaan[]" required>
                                    <option value="" hidden>Pilih Keanggotaan</option>
                                    <option value="Pasangan">Pasangan</option>
                                    <option value="Anak">Anak</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5">Umur</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control only-number" name="umur_kel[]" readonly placeholder="Umur">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
        }

        $(document).ready(function() {
            // Tambahkan satu baris keluarga saat load
            $('#keluargaContainer').append(getKeluargaTemplate(1));
            $('#keluargaContainer .removeKeluargaBtn').hide();
            initKeluargaDatepicker();

            // Add keluarga
            $('#addKeluargaBtn').on('click', function() {
                var no = $('#keluargaContainer .keluarga-item').length + 1;
                $('#keluargaContainer').append(getKeluargaTemplate(no));
                $('#keluargaContainer .removeKeluargaBtn').show();
                initKeluargaDatepicker();
            });

            // Remove keluarga
            $('#keluargaContainer').on('click', '.removeKeluargaBtn', function() {
                $(this).closest('.keluarga-item').remove();
                // Update judul anggota setelah hapus
                $('#keluargaContainer .keluarga-item').each(function(idx) {
                    $(this).find('div[style*="font-weight:bold"]').text('Anggota ke-' + (idx + 1));
                });
                // Sembunyikan tombol remove jika hanya satu baris tersisa
                if ($('#keluargaContainer .keluarga-item').length === 1) {
                    $('#keluargaContainer .removeKeluargaBtn').hide();
                }
            });

            // Otomatis hitung umur keluarga
            $('#keluargaContainer').on('change', 'input[name="tgl_lahir_kel[]"]', function() {
                var tgl = $(this).val();
                var umurInput = $(this).closest('.keluarga-item').find('input[name="umur_kel[]"]');
                if (tgl) {
                    var birthYear = new Date(tgl).getFullYear();
                    var nowYear = new Date().getFullYear();
                    var umur = nowYear - birthYear;
                    if (umur < 0) umur = 0;
                    umurInput.val(umur);
                } else {
                    umurInput.val('');
                }
            });

            $('#keluargaContainer').on('change', 'select[name="lokasi_kerja_kel[]"]', function() {
                var $row = $(this).closest('.keluarga-item');
                var $wilayah = $row.find('select[name="wilayah_kerja_kel[]"]');
                // Simpan semua opsi cabang (selain Nasional)
                if (!$wilayah.data('cabangOptions')) {
                    var cabangOptions = '';
                    $wilayah.find('option').each(function() {
                        if ($(this).val() !== 'Nasional' && $(this).val() !== '') {
                            cabangOptions += this.outerHTML;
                        }
                    });
                    $wilayah.data('cabangOptions', cabangOptions);
                } else {
                    var cabangOptions = $wilayah.data('cabangOptions');
                }

                var val = $(this).val();
                if (val === 'Kantor Pusat') {
                    // Hanya tampilkan Nasional saja
                    $wilayah.html('<option value="Nasional">Nasional</option>');
                    $wilayah.val('Nasional').trigger('change');
                } else if (val === 'Cabang') {
                    // Hilangkan Nasional, tampilkan hanya cabang
                    $wilayah.html('<option value="" hidden>Pilih Wilayah Kerja</option>' + cabangOptions);
                    $wilayah.val('').trigger('change');
                }
            });
        });

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;


            var url;
            if (id == 0) {
                url = "<?php echo site_url('kps_karyawan/add_data_karyawan') ?>";
            } else {
                url = "<?php echo site_url('kps_karyawan/update') ?>";
            }

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
                url: url,
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
                foto: {
                    required: true,
                },
                kk: {
                    required: true,
                },
                ktp: {
                    required: true,
                },
                ijazah: {
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

    });
</script>