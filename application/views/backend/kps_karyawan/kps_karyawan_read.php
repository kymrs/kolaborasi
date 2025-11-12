<style>
    body {
        margin: 0;
        padding: 0;
        background: #f9fafc;
        color: #333;
    }

    .container {
        max-width: 1000px;
        margin: 25px auto;
        display: flex;
        gap: 30px;
        background: #fff;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        overflow: hidden;
    }

    .left {
        width: 35%;
        background-color: #242c49;
        color: white;
        padding: 30px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .left table {
        width: 100%;
    }

    .left table.status tr td:nth-child(1) {
        width: 43%;
    }

    .left img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        margin-bottom: 20px;
    }

    .left h2 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .left p {
        font-size: 14px;
        margin-bottom: 6px;
        opacity: 0.9;
        width: 100%;
    }

    .left .section-title {
        margin-top: 20px;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        padding-bottom: 4px;
        width: 100%;
        text-align: left;
    }

    .right {
        width: 65%;
        padding: 30px;
    }

    .section {
        margin-bottom: 25px;
    }

    table {
        font-size: 0.9rem;
    }

    .section table tr th:nth-child(1) {
        width: 185px;
    }

    .section table tr th:nth-child(2) {
        padding-right: 5px;
    }

    .section table tr th {
        vertical-align: top;
    }

    .section h3 {
        color: #242c49;
        border-left: 4px solid #242c49;
        padding-left: 10px;
        font-size: 18px;
        margin-bottom: 15px;
    }

    .section .field-karyawan {
        margin-bottom: 10px;
    }

    .field-karyawan span {
        display: inline-block;
        width: 200px;
        font-weight: bold;
    }

    @media screen and (max-width: 768px) {
        .container {
            flex-direction: column;
        }

        .left,
        .right {
            width: 100%;
        }

        .field-karyawan span {
            width: 100%;
            display: block;
            margin-bottom: 4px;
        }
    }

    /* Kontrak */

    .container-kontrak {
        max-width: 1000px;
        margin: 25px auto;
        background: #fff;
        padding: 40px;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.05);
        border-radius: 16px;
    }

    h2.title {
        text-align: center;
        color: #242c49;
        margin-bottom: 30px;
    }

    .contract-card {
        background: #ffffff;
        border-left: 6px solid #313d62;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .contract-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    .contract-card h3 {
        margin-bottom: 15px;
        color: #313d62;
        font-size: 18px;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px 30px;
    }

    .field-kontrak {
        font-size: 14px;
        color: #313d62;
    }

    .field-kontrak span {
        display: inline-block;
        font-weight: bold;
        width: 140px;
        color: #313d62;
    }

    @media (max-width: 768px) {
        .grid {
            grid-template-columns: 1fr;
        }

        .field-kontrak span {
            width: 100%;
            display: block;
            margin-bottom: 4px;
        }
    }

    /* Keluarga */

    .container-keluarga {
        max-width: 1000px;
        margin: 25px auto;
        background: #fff;
        padding: 40px;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.05);
        border-radius: 16px;
    }

    .family-card {
        background: #ffffff;
        border-left: 6px solid #313d62;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .family-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    .family-card h3 {
        margin-bottom: 15px;
        color: #313d62;
        font-size: 20px;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px 30px;
    }

    .field-keluarga {
        font-size: 14px;
        color: #313d62;
    }

    .field-keluarga span {
        display: inline-block;
        font-weight: bold;
        width: 160px;
        color: #313d62;
    }

    @media (max-width: 768px) {
        .grid {
            grid-template-columns: 1fr;
        }

        .field-keluarga span {
            width: 100%;
            margin-bottom: 4px;
            display: block;
        }
    }
</style>
<?php
function formatTanggalIndo($tanggal)
{
    $bulanIndo = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];

    $tgl = date('d', strtotime($tanggal));
    $bln = date('m', strtotime($tanggal));
    $thn = date('Y', strtotime($tanggal));

    return $tgl . ' ' . $bulanIndo[$bln] . ' ' . $thn;
}
?>
<a style="background-color: rgb(36, 44, 73); float: right; margin-right: 5%" class="btn btn-secondary btn-sm" href="<?= base_url('kps_karyawan') ?>">
    <i class="fas fa-chevron-left"></i>&nbsp;Back
</a>
<div style="clear: both"></div>
<div class="container">
    <div class="left">
        <img src="<?= base_url('assets/backend/document/data_karyawan/' . $master->foto) ?>" alt="Foto Karyawan">
        <h2 style="text-align: center;"><?= $master->nama_lengkap ?></h2>
        <p style="text-align: center;"><?= $master->posisi ?></p>

        <div class="section-title">Kontak</div>
        <table>
            <tr>
                <td>📞</td>
                <td><?= $master->no_hp ?></td>
            </tr>
            <tr>
                <td>🏠</td>
                <td><?= $master->alamat_ktp ?></td>
            </tr>
        </table>
        <div class="section-title">Status</div>
        <table class="status">
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td><?= $master->no_ktp ?></td>
            </tr>
            <tr>
                <td>Status Kerja</td>
                <td>:</td>
                <td><?= $master->status_kerja ?></td>
            </tr>
            <tr>
                <td>Status Pernikahan</td>
                <td>:</td>
                <td><?= $master->status_pernikahan ?></td>
            </tr>
            <tr>
                <td>Golongan Darah</td>
                <td>:</td>
                <td><?= $master->gol_darah ?></td>
            </tr>
        </table>
    </div>

    <div class="right">
        <div class="section">
            <h3>Informasi Pribadi</h3>
            <table>
                <tr>
                    <th>Tempat, Tanggal Lahir</th>
                    <th>:</th>
                    <td><?= $master->tempat_lahir ?>, <?= formatTanggalIndo($master->tgl_lahir) ?></td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <th>:</th>
                    <td><?= $master->jenis_kelamin ?></td>
                </tr>
                <tr>
                    <th>Umur</th>
                    <th>:</th>
                    <td><?= $master->umur ?></td>
                </tr>
                <tr>
                    <th>Pendidikan</th>
                    <th>:</th>
                    <td><?= $master->pendidikan ?></td>
                </tr>
                <tr>
                    <th>Status Wajib Pajak</th>
                    <th>:</th>
                    <td><?= $master->ktk ?></td>
                </tr>
                <tr>
                    <th>No. KTP</th>
                    <th>:</th>
                    <td><?= $master->no_ktp ?></td>
                </tr>
                <tr>
                    <th>Alamat KTP</th>
                    <th>:</th>
                    <td><?= $master->alamat_ktp ?></td>
                </tr>
                <tr>
                    <th>Domisili</th>
                    <th>:</th>
                    <td><?= $master->domisili ?></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Informasi Karyawan</h3>
            <table>
                <tr>
                    <th>NPK</th>
                    <th>:</th>
                    <td><?= $master->npk ?></td>
                </tr>
                <tr>
                    <th>Lokasi Kerja</th>
                    <th>:</th>
                    <td><?= $master->lokasi_kerja ?></td>
                </tr>
                <tr>
                    <th>Wilayah Kerja</th>
                    <th>:</th>
                    <td><?= $master->wilayah_kerja ?></td>
                </tr>
                <tr>
                    <th>Posisi</th>
                    <th>:</th>
                    <td><?= $master->posisi ?></td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <th>:</th>
                    <td><?= $master->jabatan ?></td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <th>:</th>
                    <td><?= $master->department ?></td>
                </tr>
                <tr>
                    <th>Grade</th>
                    <th>:</th>
                    <td><?= $master->grade ?></td>
                </tr>
                <tr>
                    <th>Unit Bisnis</th>
                    <th>:</th>
                    <td><?= $master->unit_bisnis ?></td>
                </tr>
                <tr>
                    <th>Tanggal Rekrut</th>
                    <th>:</th>
                    <td><?= formatTanggalIndo($master->tgl_rekrut) ?></td>
                </tr>
                <tr>
                    <th>Tanggal Masuk</th>
                    <th>:</th>
                    <td><?= formatTanggalIndo($master->tgl_masuk) ?></td>
                </tr>
                <tr>
                    <th>Tanggal Akhir Kontrak</th>
                    <th>:</th>
                    <td><?= formatTanggalIndo($master->tgl_akhir_kontrak) ?></td>
                </tr>
                <tr>
                    <th>Masa Kerja</th>
                    <th>:</th>
                    <td><?= $master->masa_kerja ?> Tahun (<?= $master->total_bulan ?> Bulan)</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Rekening & Asal</h3>
            <table>
                <tr>
                    <th>No. Rekening</th>
                    <th>:</th>
                    <td><?= $master->no_rek ?></td>
                </tr>
                <tr>
                    <th>Nama Rekening</th>
                    <th>:</th>
                    <td><?= $master->nama_pemilik_rek ?></td>
                </tr>
                <tr>
                    <th>Nama Bank</th>
                    <th>:</th>
                    <td><?= $master->nama_bank ?></td>
                </tr>
                <tr>
                    <th>Asal Karyawan</th>
                    <th>:</th>
                    <td><?= $master->asal_karyawan ?></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Keahlian & Pelatihan</h3>
            <table>
                <tr>
                    <th>Keahlian</th>
                    <th>:</th>
                    <td><?= $master->keahlian ?></td>
                </tr>
                <tr>
                    <th>Pelatihan Internal</th>
                    <th>:</th>
                    <td><?= $master->pelatihan_internal ?></td>
                </tr>
                <tr>
                    <th>Pelatihan Eksternal</th>
                    <th>:</th>
                    <td><?= $master->pelatihan_eksternal ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php if ($kontrak) : ?>
    <div class="container-kontrak">
        <h2 class="title">Data Kontrak Karyawan</h2>

        <?php
        $i = 1;
        foreach ($kontrak as $data) :
        ?>
            <div class="contract-card">
                <h3>Kontrak Tahap ke-<?= $i++ ?></h3>
                <div class="grid">
                    <div class="field-kontrak"><span>Tanggal Awal</span>: <?= formatTanggalIndo($data->jk_awal) ?></div>
                    <div class="field-kontrak"><span>Tanggal Akhir</span>: <?= formatTanggalIndo($data->jk_akhir) ?></div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>


<?php if ($keluarga) : ?>
    <div class="container-keluarga">
        <h2 class="title">Data Keluarga Karyawan</h2>

        <?php foreach ($keluarga as $data) : ?>
            <div class="family-card">
                <h3><?= $data->nama_anggota ?> (<?= $data->keanggotaan ?>)</h3>
                <div class="grid">
                    <div class="field-keluarga"><span>Status Wajib Pajak</span>: <?= $data->status_wp ?></div>
                    <div class="field-keluarga"><span>Jenis Kelamin</span>: <?= $data->jenis_kelamin ?></div>
                    <div class="field-keluarga"><span>Tanggal Lahir</span>: <?= formatTanggalIndo($data->tgl_lahir)  ?></div>
                    <div class="field-keluarga"><span>Umur</span>: <?= $data->umur ?> Tahun</div>
                    <div class="field-keluarga"><span>Pendidikan</span>: <?= $data->pendidikan ?></div>
                    <div class="field-keluarga"><span>Keanggotaan</span>: <?= $data->keanggotaan ?></div>
                    <div class="field-keluarga"><span>Lokasi Kerja</span>: <?= $data->lokasi_kerja ?></div>
                    <div class="field-keluarga"><span>Wilayah Kerja</span>: <?= $data->wilayah_kerja ?></div>
                </div>
            </div>
        <?php endforeach ?>
        <!-- Tambah anggota keluarga lain tinggal duplikat family-card -->
    </div>
<?php endif ?>