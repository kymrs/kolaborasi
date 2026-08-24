<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body         { font-family: Arial, sans-serif; font-size: 9pt; color: #333; }
    h2           { text-align: center; margin: 0 0 2px; font-size: 13pt; }
    .subtitle    { text-align: center; font-size: 9pt; color: #666; margin-bottom: 10px; }
    .info-table  { width: 100%; margin-bottom: 10px; border-collapse: collapse; }
    .info-table td { padding: 2px 5px; font-size: 9pt; }
    .info-table .label { font-weight: bold; width: 130px; }
    .summary-box { display: inline-block; text-align: center; border: 1px solid #ccc;
                   border-radius: 4px; padding: 5px 12px; margin-right: 8px;
                   background: #f8f9fc; font-size: 8.5pt; }
    .summary-box .val { font-size: 12pt; font-weight: bold; }
    .summary-box.masuk  { background: #d4edda; }
    .summary-box.keluar { background: #f8d7da; }
    .summary-box.akhir  { background: #cce5ff; }
    table.main   { width: 100%; border-collapse: collapse; margin-top: 10px; }
    table.main th { background: #242d4a; color: white; padding: 5px 4px;
                    text-align: center; font-size: 8pt; border: 1px solid #242d4a; }
    table.main td { padding: 4px 4px; border: 1px solid #dee2e6; font-size: 8pt;
                    vertical-align: middle; }
    table.main tr:nth-child(even) { background: #f8f9fc; }
    table.main tfoot td { background: #242d4a; color: white; font-weight: bold;
                          padding: 5px 4px; border: 1px solid #242d4a; }
    .badge-masuk   { color: #155724; background: #d4edda; padding: 1px 6px;
                     border-radius: 3px; font-size: 7.5pt; }
    .badge-keluar  { color: #721c24; background: #f8d7da; padding: 1px 6px;
                     border-radius: 3px; font-size: 7.5pt; }
    .badge-adj     { color: #0c5460; background: #d1ecf1; padding: 1px 6px;
                     border-radius: 3px; font-size: 7.5pt; }
    .badge-awal    { color: #383d41; background: #e2e3e5; padding: 1px 6px;
                     border-radius: 3px; font-size: 7.5pt; }
    .saldo-awal-row td { background: #f0f0f0 !important; font-style: italic; color: #666; }
    .text-right  { text-align: right; }
    .text-center { text-align: center; }
    .text-success { color: #1cc88a; }
    .text-danger  { color: #e74a3b; }
</style>
</head>
<body>

<h2>KARTU STOK</h2>
<p class="subtitle">
    <?php
    $perusahaan = 'Mobile Auto Care';
    echo $perusahaan;
    if (!empty($tgl_dari) || !empty($tgl_sampai)):
        echo ' &nbsp;|&nbsp; Periode: ' . ($tgl_dari ?: '—') . ' s/d ' . ($tgl_sampai ?: '—');
    else:
        echo ' &nbsp;|&nbsp; Semua Periode';
    endif;
    ?>
</p>

<!-- INFO BARANG -->
<table class="info-table">
    <tr>
        <td class="label">Kode Produk</td>
        <td>: <?= $barang->kode_produk ?></td>
        <td class="label">Satuan</td>
        <td>: <?= $barang->satuan ?></td>
    </tr>
    <tr>
        <td class="label">Nama Produk</td>
        <td>: <?= $barang->nama_produk ?></td>
        <td class="label">Stok Minimal</td>
        <td>: <?= $barang->stok_minimal ?></td>
    </tr>
    <tr>
        <td class="label">Kategori</td>
        <td>: <?= $barang->kategori ?></td>
        <td class="label">Stok Saat Ini</td>
        <td>: <strong><?= $barang->stok_saat_ini ?> <?= $barang->satuan ?></strong></td>
    </tr>
</table>

<!-- SUMMARY -->
<div style="margin-bottom:12px;">
    <div class="summary-box">
        <div class="val"><?= $stok_awal ?></div>
        <div>Stok Awal</div>
    </div>
    <?php
    $total_masuk  = 0;
    $total_keluar = 0;
    foreach ($transaksi as $r) {
        $tipe = strtolower($r['tipe']);
        if ($tipe === 'masuk' || $tipe === 'penyesuaian_masuk' || $tipe === 'stok_awal') {
            $total_masuk += $r['jumlah'];
        } else {
            $total_keluar += $r['jumlah'];
        }
    }
    ?>
    <div class="summary-box masuk">
        <div class="val text-success"><?= $total_masuk ?></div>
        <div>Total Masuk</div>
    </div>
    <div class="summary-box keluar">
        <div class="val text-danger"><?= $total_keluar ?></div>
        <div>Total Keluar</div>
    </div>
    <div class="summary-box akhir">
        <div class="val"><?= $stok_akhir ?></div>
        <div>Stok Akhir</div>
    </div>
</div>

<!-- TABEL HISTORI -->
<table class="main">
    <thead>
        <tr>
            <th width="3%">No</th>
            <th width="10%">Tanggal</th>
            <th width="8%">Tipe</th>
            <th width="22%">Keterangan</th>
            <th width="10%">Dokumen</th>
            <th width="7%">Masuk</th>
            <th width="7%">Keluar</th>
            <th width="7%">Saldo</th>
            <th width="11%">Harga Beli</th>
            <th width="15%">Nilai</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($tgl_dari)): ?>
        <tr class="saldo-awal-row">
            <td colspan="5" class="text-right">Saldo awal per <?= $tgl_dari ?></td>
            <td class="text-center">—</td>
            <td class="text-center">—</td>
            <td class="text-center"><strong><?= $stok_awal ?></strong></td>
            <td class="text-center">—</td>
            <td class="text-center">—</td>
        </tr>
        <?php endif; ?>

        <?php if (empty($transaksi)): ?>
        <tr>
            <td colspan="10" class="text-center" style="color:#999; padding:10px;">
                Tidak ada transaksi di periode ini
            </td>
        </tr>
        <?php endif; ?>

        <?php
        $bulan_indo = ['01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei',
                       '06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt',
                       '11'=>'Nov','12'=>'Des'];
        $no = 1;
        $total_nilai_masuk = 0;

        foreach ($transaksi as $row):
            $tipe    = strtolower($row['tipe']);
            $isMasuk = in_array($tipe, ['masuk', 'penyesuaian_masuk', 'stok_awal']);
            $harga   = floatval($row['harga_beli_saat_transaksi']);
            $nilai   = $harga * floatval($row['jumlah']);
            if ($isMasuk) $total_nilai_masuk += $nilai;

            // Format tanggal
            $dt = new DateTime($row['transaksi_date']);
            $tgl_fmt = $dt->format('d') . ' ' . $bulan_indo[$dt->format('m')] . ' ' . $dt->format('Y H:i');

            // Badge tipe
            $badge_class = $isMasuk ? 'badge-masuk' : 'badge-keluar';
            if ($tipe === 'stok_awal') $badge_class = 'badge-awal';
            if (strpos($tipe, 'penyesuaian') !== false) $badge_class = 'badge-adj';
            $tipe_label = ucfirst(str_replace('_', ' ', $row['tipe']));

            // Keterangan
            $ket = $row['keterangan'] ?? '';
            if (!empty($row['nama_pelapor']))  $ket .= "\nPelapor: " . $row['nama_pelapor'];
            if (!empty($row['customer_name'])) $ket .= "\nCustomer: " . $row['customer_name'];
            if (!empty($row['kode_batch']))    $ket .= "\nBatch: " . $row['kode_batch'];

            // Dokumen
            $dok = '—';
            if (!empty($row['kode_reimbust']))  $dok = $row['kode_reimbust'];
            if (!empty($row['invoice_number'])) $dok = $row['invoice_number'];
            if (!empty($row['referensi']) && $dok === '—') $dok = $row['referensi'];
        ?>
        <tr>
            <td class="text-center"><?= $no++ ?></td>
            <td><?= $tgl_fmt ?></td>
            <td class="text-center"><span class="<?= $badge_class ?>"><?= $tipe_label ?></span></td>
            <td style="white-space: pre-line; font-size:7.5pt;"><?= htmlspecialchars($ket) ?></td>
            <td class="text-center" style="font-size:7.5pt;"><?= $dok ?></td>
            <td class="text-center text-success"><?= $isMasuk  ? $row['jumlah'] : '—' ?></td>
            <td class="text-center text-danger"><?= !$isMasuk ? $row['jumlah'] : '—' ?></td>
            <td class="text-center"><strong><?= $row['saldo'] ?></strong></td>
            <td class="text-right"><?= $harga > 0 ? 'Rp ' . number_format($harga, 0, ',', '.') : '—' ?></td>
            <td class="text-right"><?= $nilai > 0 ? 'Rp ' . number_format($nilai, 0, ',', '.') : '—' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-right">TOTAL</td>
            <td class="text-center"><?= $total_masuk ?></td>
            <td class="text-center"><?= $total_keluar ?></td>
            <td class="text-center"><?= $stok_akhir ?></td>
            <td>—</td>
            <td class="text-right">Rp <?= number_format($total_nilai_masuk, 0, ',', '.') ?></td>
        </tr>
    </tfoot>
</table>

<div style="margin-top:15px; font-size:8pt; color:#999; text-align:right;">
    Dicetak pada: <?= date('d-m-Y H:i:s') ?>
</div>

</body>
</html>
