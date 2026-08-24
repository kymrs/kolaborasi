<?php
function tgl_indonesia($date) {
    $bulan = array(
        1  => 'Januari',   2  => 'Februari', 3  => 'Maret',
        4  => 'April',     5  => 'Mei',       6  => 'Juni',
        7  => 'Juli',      8  => 'Agustus',   9  => 'September',
        10 => 'Oktober',   11 => 'November',  12 => 'Desember'
    );
    $d = date('d', strtotime($date));
    $m = date('n', strtotime($date));
    $y = date('Y', strtotime($date));
    return $d . ' ' . $bulan[$m] . ' ' . $y;
}

function terbilang($angka)
{
    $angka = abs((int)$angka); // cast ke int di awal
    $huruf = array('', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM', 'TUJUH', 'DELAPAN', 'SEMBILAN', 'SEPULUH', 'SEBELAS');

    if ($angka < 12)            return ' ' . $huruf[$angka];
    elseif ($angka < 20)        return terbilang($angka - 10) . ' BELAS';
    elseif ($angka < 100)       return terbilang((int)($angka / 10)) . ' PULUH' . terbilang($angka % 10);
    elseif ($angka < 200)       return ' SERATUS' . terbilang($angka - 100);
    elseif ($angka < 1000)      return terbilang((int)($angka / 100)) . ' RATUS' . terbilang($angka % 100);
    elseif ($angka < 2000)      return ' SERIBU' . terbilang($angka - 1000);
    elseif ($angka < 1000000)   return terbilang((int)($angka / 1000)) . ' RIBU' . terbilang($angka % 1000);
    elseif ($angka < 1000000000)       return terbilang((int)($angka / 1000000)) . ' JUTA' . terbilang($angka % 1000000);
    elseif ($angka < 1000000000000)    return terbilang((int)($angka / 1000000000)) . ' MILIAR' . terbilang($angka % 1000000000);
    else return 'Angka terlalu besar';
}

// ========== HITUNG TOTAL JASA & PART ==========
$total_jasa = 0;
$total_part = 0;
if (!empty($items)) {
    foreach ($items as $item) {
        if (strtolower($item->tipe_item) == 'jasa') $total_jasa += $item->total;
        if (strtolower($item->tipe_item) == 'sparepart') $total_part += $item->total;
        if (strtolower($item->tipe_item) == 'pelumas') $total_part += $item->total;
        if (strtolower($item->tipe_item) == 'bahan') $total_part += $item->total;
    }
}
?>
<style>
    body {
        font-family: Courier, monospace;
        font-size: 11px;
    }
    .invoice-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 25px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    .top-section td {
        vertical-align: top;
    }
    .invoice-number {
        padding: 3px 8px;
        display: inline-block;
        font-weight: bold;
        font-size: 16px;
    }
    .item-table {
        margin-top: 25px;
        border: 1px solid #000;
    }
    .item-table th {
        border: 1px solid #000;
        padding: 5px;
        font-weight: bold;
        text-align: left;
    }
    .item-table td {
        padding: 5px;
        vertical-align: top;
    }
    .item-table .harga {
        text-align: right;
        width: 150px;
    }
    .terbilang {
        margin-top: 20px;
    }
    .terbilang-box {
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        padding: 5px 0;
        font-style: italic;
        margin-top: 5px;
    }
    .signature {
        margin-top: 80px;
        width: 100%;
    }
    .signature td {
        vertical-align: top;
    }
    .signature-img {
        width: 185px;
        position: relative;
    }
    .footer-bank {
        margin-top: 50px;
        font-size: 11px;
        line-height: 1.6;
    }
    .logo-paid {
        position: absolute;
        top: 350px;
        right: 120px;
        opacity: 0.3;
        width: 150px;
    }
</style>

<!-- ========== HALAMAN 1: INVOICE ========== -->

<div class="invoice-title">INVOICE</div>

<table class="top-section">
    <tr>
        <td width="60%">
            <div><b>Kepada</b></div>
            <br>
            <div><b><?= $customer_name ?></b></div>
            <br>
            <div><?= $address ?></div>
            <br>
        </td>
        <td width="40%" align="right">
            <div class="invoice-number"><?= $invoice_number ?></div>
            <br><br>
            <table>
                <tr>
                    <td align="right"><b>Tanggal Service :</b></td>
                    <td width="10"></td>
                    <td><?= tgl_indonesia($awal_service) ?></td>
                </tr>
                <tr>
                    <td align="right"><b>PIC :</b></td>
                    <td></td>
                    <td><?= ucwords($pic) ?></td>
                </tr>
                <tr>
                    <td align="right"><b>Jatuh Tempo :</b></td>
                    <td></td>
                    <td><?= tgl_indonesia($due_date) ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table class="item-table">
    <tr>
        <th width="50">NO</th>
        <th>URAIAN</th>
        <th width="170" align="right">HARGA</th>
    </tr>
    <tr>
        <td>1</td>
        <td>
            Tagihan Biaya <?= $uraian ?>
            <br><br>
            &nbsp;&nbsp;&nbsp;&nbsp;1 Kendaraan <?= $tipe ?><br>
            &nbsp;&nbsp;&nbsp;&nbsp;No. Polisi &nbsp;&nbsp;&nbsp;: <?= $nopol ?><br>
            &nbsp;&nbsp;&nbsp;&nbsp;Rincian Biaya : <?= strtolower($lampiran) == 'ya' ? 'Terlampir' : '-' ?>
        </td>
        <td class="harga">
            Rp. &nbsp;&nbsp;&nbsp; <?= number_format($sub_total, 0, ',', '.') ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="right"><b>Total Biaya</b></td>
        <td class="harga"><b>Rp. &nbsp;&nbsp;&nbsp; <?= number_format($sub_total, 0, ',', '.') ?></b></td>
    </tr>
</table>

<?php if (strtolower($payment_status) == 'paid') : ?>
    <div class="logo-paid">
        <img src="assets/backend/img/logo-paid-mac2.png" alt="line" width="100%">
    </div>
<?php endif; ?>

<div class="terbilang">
    <div>Terbilang</div>
    <div class="terbilang-box">
        <?= '"' . trim(terbilang($sub_total)) . ' RUPIAH"' ?>
    </div>
</div>

<table class="signature">
    <tr>
        <td align="right">Jakarta, <?= tgl_indonesia($invoice_date) ?></td>
    </tr>
    <tr>
        <td align="right" style="padding-top: 10px">
            <img class="signature-img" src="assets/backend/img/stempel-signature.png" alt="signature">
        </td>
    </tr>
    <tr>
        <td align="right" style="padding-top: 10px; padding-right: 20px"><u>Dwi Setiawan</u></td>
    </tr>
    <tr>
        <td align="right" style="padding-right: 14px">Service & Area</td>
    </tr>
</table>

<div class="footer-bank">
    Pembayaran dapat dilakukan via bank :<br><br>
    BCA Cab : Cibodas, Tangerang<br>
    No. Rekening : 713-221-2000<br>
    A/N : PT. Otoservis Lintas Indonesia<br><br>
    Bukti Pembayaran Email ke : <u>cs@mobileautocare.co.id</u>
</div>

<?php if (strtolower($lampiran) == 'ya') : ?>
<pagebreak />

<!-- ========== HALAMAN 2: LAMPIRAN ========== -->

<style>
    .title {
        text-align: center;
        font-size: 22px;
        font-weight: bold;
    }
    .subtitle {
        text-align: center;
        margin-bottom: 20px;
    }
    .line {
        border-top: 1px solid #4a72d8;
        margin: 10px 0 15px 0;
    }
    .info-table td {
        padding: 2px 0;
        vertical-align: top;
    }
    .service-table,
    .part-table,
    .total-table {
        margin-top: 15px;
    }
    .service-table th, .service-table td,
    .part-table th,   .part-table td,
    .total-table td {
        border: 1px solid #000;
        padding: 4px 6px;
    }
    .service-table th,
    .part-table th {
        text-align: center;
        font-weight: bold;
    }
    .right  { text-align: right; }
    .center { text-align: center; }
    .bold   { font-weight: bold; }
    .signature {
        margin-top: 70px;
    }
</style>

<div class="title">Lampiran Tagihan</div>
<div class="subtitle"><?= $invoice_number ?></div>

<div class="line"></div>

<table class="info-table">
    <tr>
        <td width="150"><b>Nomor Polisi</b></td>
        <td width="10">:</td>
        <td><?= $nopol ?></td>
    </tr>
    <tr>
        <td><b>Tipe</b></td>
        <td>:</td>
        <td><?= ucwords($tipe) ?></td>
    </tr>
    <tr>
        <td><b>Kilometer</b></td>
        <td>:</td>
        <td><?= number_format($km, 0, ',', '.') ?> KM</td>
    </tr>
    <tr>
        <td><b>Customer</b></td>
        <td>:</td>
        <td><?= $customer_name ?></td>
    </tr>
    <tr>
        <td><b>PIC</b></td>
        <td>:</td>
        <td><?= ucwords($pic) ?></td>
    </tr>
    <tr>
        <td><b>Tanggal Servis</b></td>
        <td>:</td>
        <td><?= tgl_indonesia($awal_service) ?></td>
    </tr>
    <tr>
        <td><b>Lokasi</b></td>
        <td>:</td>
        <td><?= ucwords($lokasi_service) ?></td>
    </tr>
</table>

<div class="line"></div>

<!-- Tabel Jasa -->
<table class="service-table">
    <tr>
        <th width="40">No</th>
        <th>Nama Jasa</th>
        <th width="120">Biaya</th>
        <th width="100">Disc</th>
        <th width="120">Total</th>
    </tr>
    <?php if (!empty($items)) : ?>
        <?php $no_jasa = 1; foreach ($items as $item) : ?>
            <?php if($item->is_active == 1) : ?>
                <?php if (strtolower($item->tipe_item) == 'jasa') : ?>
                    <tr>
                        <td class="center"><?= $no_jasa++ ?></td>
                        <td><?= ucwords($item->item) ?></td>
                        <td class="right">Rp &nbsp; <?= number_format($item->biaya, 0, ',', '.') ?></td>
                        <td class="right">Rp <?= number_format($item->diskon, 0, ',', '.') ?></td>
                        <td class="right">Rp &nbsp; <?= number_format($item->total, 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <tr>
        <td colspan="4" class="right bold">Total Biaya Jasa</td>
        <td class="right bold">Rp &nbsp; <?= number_format($total_jasa, 0, ',', '.') ?></td>
    </tr>
</table>

<!-- Tabel Part -->
<table class="part-table">
    <tr>
        <th width="40">No</th>
        <th>Part</th>
        <th width="50">Qty</th>
        <th width="120">Biaya</th>
        <th width="100">Disc</th>
        <th width="120">Total</th>
    </tr>
    <?php if (!empty($items)) : ?>
        <?php $no_part = 1; foreach ($items as $item) : ?>
            <?php if($item->is_active == 1) : ?>
                <?php if (strtolower($item->tipe_item) == 'sparepart' || strtolower($item->tipe_item) == 'pelumas' || strtolower($item->tipe_item) == 'bahan') : ?>
                    <tr>
                        <td class="center"><?= $no_part++ ?></td>
                        <td><?= ucwords($item->item) ?></td>
                        <td class="center"><?= $item->qty ?></td>
                        <td class="right">Rp &nbsp; <?= number_format($item->biaya, 0, ',', '.') ?></td>
                        <td class="right">Rp <?= number_format($item->diskon, 0, ',', '.') ?></td>
                        <td class="right">Rp &nbsp; <?= number_format($item->total, 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <tr>
        <td colspan="5" class="right bold">Total Biaya Part</td>
        <td class="right bold">Rp &nbsp; <?= number_format($total_part, 0, ',', '.') ?></td>
    </tr>
</table>

<!-- Grand Total -->
<table class="total-table">
    <tr>
        <td class="right bold">Grand Total Biaya Jasa & Part</td>
        <td width="120" class="right bold">Rp &nbsp; <?= number_format($sub_total, 0, ',', '.') ?></td>
    </tr>
</table>

<table class="signature">
    <tr>
        <td align="left">Penanggung Jawab</td>
    </tr>
    <tr>
        <td align="left" style="padding-top: 10px">
            <img class="signature-img" src="assets/backend/img/stempel-signature.png" alt="signature">
        </td>
    </tr>
    <tr>
        <td align="left" style="padding-top: 10px; padding-left: 15px"><u>Dwi Setiawan</u></td>
    </tr>
    <tr>
        <td align="left" style="padding-left: 9px">Service & Area</td>
    </tr>
</table>

<?php endif; ?>