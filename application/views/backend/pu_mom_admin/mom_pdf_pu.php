<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M.O.M <?= $agenda ?></title>
</head>

<?php function tanggal_indo($tanggal, $cetak_hari = false)
{
    $hari = array(
        1 =>    'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
        'Minggu'
    );

    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $split = explode('-', $tanggal);
    $tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

    if ($cetak_hari) {
        $num = date('N', strtotime($tanggal));
        return $hari[$num] . ', ' . $tgl_indo;
    }
    return $tgl_indo;
} ?>

<body style="font-family: 'Poppins', sans-serif;">
    <img style="margin-top:-30px; margin-right:-56px; float: right;" src="assets/backend/img/header.png" width="600">
    <br><br><br><br><br>

    <p style="font-size:15px; text-align: center;"><b><u>MINUTES OF MEETING</u></b></p>

    <table style="font-size:13px; padding-left:-3px">
        <tr>
            <td>No. Dokumen</td>
            <td>:</td>
            <td><?= $no_dok ?></td>
        </tr>
        <tr>
            <td>Agenda</td>
            <td>:</td>
            <td><?= $agenda ?></td>
        </tr>
        <tr>
            <td>Hari, Tanggal</td>
            <td>:</td>
            <td><?= tanggal_indo(date_format(date_create($date), "Y-m-j"), true) ?></td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>Pukul <?= $start_time ?> WIB s.d <?= $end_time ?> WIB</td>
        </tr>
        <tr>
            <td>Lokasi</td>
            <td>:</td>
            <td><?= $lokasi ?></td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Peserta</td>
            <td style="vertical-align: top;">:</td>
            <td style="line-height: 1.5;"><?= $peserta ?></td>
        </tr>
    </table>

    <hr>

    <p style="font-size:15px; padding-top:-10px;"><b>Content :</b></p>

    <div style="font-size:13px; text-align: justify; line-height: 1.5; padding-top:-10px;"><?= $konten ?></div>

    <br>
    <div style="text-align: center;">
        <img src="assets/backend/document/mom/foto_mom_pu/<?= $foto ?>" style="max-width: 100%; max-height: 300px;">
    </div>

    <div style="padding-left:-56px; position: absolute; bottom: 25px;">
        <img src="assets/backend/img/footer.png" width="793">
    </div>
</body>

</html>