<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dokumen A4</title>
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/kps_pkwt_style.css') ?>">
</head>

<style>
    /* CSS Reset */
    html,
    body,
    div,
    span,
    applet,
    object,
    iframe,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    blockquote,
    pre,
    a,
    abbr,
    acronym,
    address,
    big,
    cite,
    code,
    del,
    dfn,
    em,
    img,
    ins,
    kbd,
    q,
    s,
    samp,
    small,
    strike,
    strong,
    sub,
    sup,
    tt,
    var,
    b,
    u,
    i,
    center,
    dl,
    dt,
    dd,
    fieldset,
    form,
    label,
    legend,
    table,
    caption,
    tbody,
    tfoot,
    thead,
    tr,
    th,
    td,
    article,
    aside,
    canvas,
    details,
    embed,
    figure,
    figcaption,
    footer,
    header,
    hgroup,
    menu,
    nav,
    output,
    ruby,
    section,
    summary,
    time,
    mark,
    audio,
    video {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        vertical-align: baseline;
    }

    /* HTML5 display-role reset for older browsers */
    article,
    aside,
    details,
    figcaption,
    figure,
    footer,
    header,
    hgroup,
    menu,
    nav,
    section {
        display: block;
    }

    body {
        line-height: 1;
    }

    blockquote,
    q {
        quotes: none;
    }

    blockquote:before,
    blockquote:after,
    q:before,
    q:after {
        content: '';
        content: none;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
    }

    /* @page {
        size: A4;
        margin: 20mm;
    } */

    .page-break {
        break-after: page;
    }

    .page {
        font-family: 'Courier New', Courier, monospace;
        background: white;
        color: #000;
        margin: auto;
        padding: 25mm;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        position: relative;
        margin-bottom: 35px;
        font-size: 6.2pt;
    }

    .page p {
        line-height: 10px;
    }

    .header {
        text-align: center;
    }

    .header p {
        margin-bottom: 4px;
    }

    .header p:nth-child(3) {
        margin-top: 8px;
        font-weight: bold;
        text-decoration: underline;
        margin-bottom: 10px;
    }

    .content-header p {
        text-align: justify;
    }

    .content-header p i {
        font-weight: bold;
    }

    .content-header table {
        margin: 15px 35px 10px 35px;
        width: 94%;
    }

    .content-header table tr td {
        padding-bottom: 2px;
    }

    .content-header table tr td:nth-child(1) {
        width: 180px;
    }

    .content-header table tr td:nth-child(2) {
        padding-right: 7px;
    }

    .content-header table tr td:nth-child(3) {
        font-weight: bold;
    }

    .pasal-main {
        margin-top: 14px;
    }

    .pasal-main p {
        text-align: center;
        font-weight: bold;
    }

    .pasal-main ol {
        margin-top: 3px;
        padding-left: 14px;
    }

    .pasal-main ol li {
        margin-bottom: 2px;
        text-align: justify;
    }

    /* Signature */
    .signature {
        margin-top: 50px;
        font-weight: bold;
    }

    .signature table {
        width: 100%;
    }

    .signature table tr:nth-child(1) {
        padding-bottom: 50px;
    }

    .signature table tr:nth-child(2) td {
        padding: 30px 0;
    }

    .signature table tr td {
        width: 50%;
        text-align: center;
    }

    /* Lampiran 1 */
    .lampiran-1 .header-lampiran-1 p {
        margin-bottom: 10px;
    }

    .lampiran-1 .header-lampiran-1 p span {
        text-decoration: underline;
    }

    .lampiran-1 .main-content {
        margin: 15px 20px;
    }

    .lampiran-1 .main-content table tr td {
        padding-bottom: 2px;
    }

    .lampiran-1 .main-content table tr td:nth-child(1) {
        width: 310px;
    }

    .lampiran-1 .main-content table tr td:nth-child(2) {
        width: 15px;
    }

    .lampiran-1 .main-content table tr td:nth-child(3) {
        font-weight: bold;
    }

    .lampiran-1 .main-content table tr td span {
        padding-right: 17px;
    }

    .lampiran-1 .main-content table tr td span.detail {
        padding-left: 28px;
        padding-right: 14px;
    }

    .lampiran-1 .main-content table tr td span.detail-2 {
        padding-left: 50px;
        padding-right: 14px;
    }

    .lampiran-1 .main-content p.before-signature {
        margin: 30px 0 20px;
        text-align: justify;
    }

    .lampiran-1 .main-content table.signature-lampiran-1 {
        margin-left: 40px;
    }

    .lampiran-1 .main-content table.signature-lampiran-1 tr:nth-child(2) td {
        padding: 15px 0;
    }

    .lampiran-1 .main-content .line {
        height: 2.3px;
        width: 100%;
        margin: 20px 0;
        background-color: #000;
    }

    .perjanjian-b {
        margin: 0 20px;
    }

    .perjanjian-b p:nth-child(2) {
        margin: 10px 0 15px;
    }

    .perjanjian-b table {
        width: 100%;
        border-bottom: 1px solid #000;
    }

    .perjanjian-b table tr:nth-child(2) {
        height: 20px;
    }

    .perjanjian-b table tr td:nth-child(1) {
        width: 20%;
    }

    .perjanjian-b table tr td:nth-child(2) {
        width: 10px;
    }

    .perjanjian-b table tr td:nth-child(3) {
        font-weight: bold;
        padding: 0;
    }

    .perjanjian-b p.dalam-rangka {
        text-align: justify;
        margin-left: 50px;
        border-bottom: 1px solid #000;
    }

    .perjanjian-b h1 {
        margin-top: 10px;
        font-weight: bold;
    }

    .perjanjian-b h2 {
        font-weight: bold;
        margin: 10px 0 15px;
    }

    .perjanjian-b p.text-space {
        margin-bottom: 7px;
        text-align: justify;
    }

    .perjanjian-b .signature-lampiran-2 {
        margin-left: 50px;
        border: none;
    }

    .perjanjian-b table.signature-lampiran-2 tr:nth-child(2) td {
        padding: 15px 0;
    }

    .perjanjian-b .line-2 {
        width: 100%;
        height: 1px;
        background-color: #000;
    }

    /* Lampiran 2 */
    .lampiran-2 {
        font-size: 10pt;
    }

    .lampiran-2 .header-lampiran-2 h6 {
        font-weight: bold;
    }

    .lampiran-2 .header-lampiran-2 h6 span {
        font-size: 12pt;
    }

    .lampiran-2 .header-lampiran-2 p {
        margin: 25px 0 15px;
        font-weight: bold;
    }

    .lampiran-2 .header-lampiran-2 h4 {
        font-weight: bold;
    }

    .lampiran-2 .header-lampiran-2 h4 span {
        margin-left: 10px;
    }

    .lampiran-2 .header-lampiran-2 h5 {
        font-weight: bold;
        margin-left: 20px;
        margin-top: 10px;
        margin-bottom: 55px;
        font-size: 12pt;
    }

    .lampiran-2 .header-lampiran-2 table tr td:nth-child(2) {
        padding-left: 70px;
        padding-right: 10px;
    }

    .lampiran-2 .header-lampiran-2 table tr td:nth-child(3) {
        font-weight: bold;
        text-decoration: underline;
    }

    .lampiran-2 .content p {
        margin: 30px 0 20px;
    }

    .lampiran-2 .content h2 {
        text-align: justify;
    }

    .lampiran-2 .content h2 span {
        font-weight: bold;
    }

    .lampiran-2 .content table.table-main {
        margin: 20px 25px 15px;
    }

    .lampiran-2 .content table.table-main tr td {
        padding-bottom: 2px;
    }

    .lampiran-2 .content table.table-main tr td:nth-child(2) {
        padding-left: 20px;
        padding-bottom: 2px;
        padding-right: 5px;
    }

    .lampiran-2 .content table.table-main tr td:nth-child(3) {
        font-weight: bold;
    }

    .lampiran-2 .content h1 {
        text-align: justify;
    }

    .lampiran-2 .content h3 {
        margin-top: 20px;
        margin-bottom: 50px;
    }

    .lampiran-2 .content table.signature-content tr:nth-child(2) td {
        font-weight: bold;
    }

    .lampiran-2 .content table.signature-content tr:nth-child(3) td {
        padding: 35px 0;
    }

    .lampiran-2 .content table.signature-content tr:nth-child(4) td {
        font-weight: bold;
        text-decoration: underline;
    }

    .lampiran-2 .content .line {
        width: 100%;
        height: 1px;
        background-color: #000;
        margin-top: 150px;
    }
</style>

<body>
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
    <div class="page">
        <div class="header">
            <p>PT. KOLABORASI PARA SAHABAT</p>
            <p>PERJANJIAN KERJA WAKTU TERTENTU</p>
            <p>(No: <?= $transaksi->no_perjanjian ?>)</p>
        </div>
        <div class="content-header">
            <p>Perjanjian Kerja Waktu Tertentu ini (selanjutnya disebut “PERJANJIAN”) dibuat dan ditandatangani pada hari ini <i><?= $transaksi->hari ?></i> tanggal <i><?= $transaksi->tanggal ?></i> bulan <i><?= $transaksi->bulan ?></i> tahun <i><?= $transaksi->tahun ?></i> <i style="font-weight: normal">(<?= date('d-m-Y', strtotime($transaksi->jk_awal)) ?>)</i> oleh dan di antara :</p>
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>ANINDYAGUNA PUTRAWAN</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>LAKI-LAKI</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>Direktur</td>
                </tr>
                <tr>
                    <td>Alamat Kedudukan / Kantor</td>
                    <td>:</td>
                    <td>WISMA HARAMAIN. JL. MAHONI RAYA NO. 13 KEL. BAKTIJAYA KEC. SUKMAJAYA, KOTA DEPOK</td>
                </tr>
            </table>
        </div>
        <div class="content-header">
            <p>Dalam hal ini bertindak untuk dan atas nama <b>PT. KOLABORASI PARA SAHABAT</b>, perseroan terbatas yang didirikan menurut hukum Indonesia, selanjutnya disebut sebagai <b>PERUSAHAAN;</b></p>
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><?= $master->nama_lengkap ?></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td><?= $master->jenis_kelamin ?></td>
                </tr>
                <tr>
                    <td>Tempat/Tanggal lahir</td>
                    <td>:</td>
                    <td><?= $master->tempat_lahir ?>, <?= formatTanggalIndo($master->tgl_lahir) ?></td>
                </tr>
                <tr>
                    <td>No. KTP</td>
                    <td>:</td>
                    <td><?= $master->no_ktp ?></td>
                </tr>
                <tr>
                    <td>Alamat Tempat Tinggal</td>
                    <td>:</td>
                    <td><?= $master->alamat_ktp ?></td>
                </tr>
            </table>
        </div>
        <p style="margin: 16px 0 20px; text-align: justify">Dalam hal ini bertindak untuk dan atas nama pribadi, selanjutnya dalam perjanjian ini disebut sebagai <b>KARYAWAN</b></p>
        <p style="text-align: justify">PERUSAHAAN dan KARYAWAN (selanjutnya secara bersama-sama disebut <b>“PARA PIHAK”</b>) telah bersepakat untuk saling mengikat diri dalam perjanjian ini dengan ketentuan-ketentuan sebagaimana tersebut dalam pasal-pasal sebagai berikut:</p>
        <div class="pasal-main">
            <p>Pasal 1</p>
            <p>JANGKA WAKTU PERJANJIAN</p>
            <ol>
                <li>PERUSAHAAN dengan ini memberikan pekerjaan kepada KARYAWAN sebagai <span><b><?= $master->posisi ?></b></span></li>
                <li>Jangka waktu perjanjian ini berlaku selama <span><?= $transaksi->jangka_waktu ?></span> terhitung sejak tanggal <span><b><?= formatTanggalIndo($transaksi->jk_awal) ?></b></span> sampai dengan <span><b><?= formatTanggalIndo($transaksi->jk_akhir) ?></b></span></li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 2</p>
            <p>KOMPENSASI DAN BENEFIT</p>
            <ol>
                <li>PERUSAHAAN memberi kompensasi dan benefit dari pekerjaan yang diberikan kepada KARYAWAN dengan perincian terlampir dalam Lampiran-1;</li>
                <li>Kompensasi bagi KARYAWAN berupa gaji pokok serta tunjangan tetap dan tunjangan tidak tetap;</li>
                <li>Benefit bagi KARYAWAN antara lain kepesertaan dalam program jamsostek dan jaminan pemeliharaan kesehatan yang diselenggarakan tersendiri oleh perusahaan;</li>
                <li>KARYAWAN tidak berhak atas pembayaran apapun selain yang diatur perjanjian ini.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 3</p>
            <p>HUBUNGAN KERJA</p>
            <ol>
                <li>Para pihak sepakat untuk terikat dalam suatu hubungan kerja, KARYAWAN mengikatkan diri kepada PERUSAHAAN untuk menunjukkan kinerja kerja yang baik, dan tunduk pada semua perintah kerja yang diberikan PERUSAHAAN baik dalam bentuk tertulis ataupun tidak tertulis dengan tetap memperhatikan pada etika kerja dan etika bisnis serta berpedoman kepada Peraturan Perundang-undangan;</li>
                <li>KARYAWAN bersedia mengikuti Pelatihan Khusus yang diadakan oleh PERUSAHAAN maupun KLIEN, yang berisi Materi Dasar dalam menjalani pekerjaan;</li>
                <li>KARYAWAN wajib menjalankan tugas sesuai dengan jabatan dan uraian pekerjaan serta senantiasa menjaga kepentingan PERUSAHAAN maupun PERUSAHAAN MITRA/KLIEN;</li>
                <li>KARYAWAN wajib menjaga dan menghindari terjadinya kerusakan dan atau kehilangan perlengkapan dan peralatan kerja milik PERUSAHAAN yang dipercayakan kepada KARYAWAN;</li>
                <li>KARYAWAN bertanggung jawab sepenuhnya atas kerusakan dan atau kehilangan perlengkapan dan peralatan kerja serta barang-barang milik PERUSAHAAN yang dipergunakan atau diperlakukan tidak sesuai dengan tata cara penggunaan perlengkapan;</li>
                <li>Pada saat Perjanjian ini berakhir dan atau diakhiri sepihak, maka KARYAWAN wajib mengembalikan seluruh perlengkapan dan peralatan kerja (termasuk perlengkapan keselamatan kerja, seragam, ID Card, Produk, dll)kepada PERUSAHAAN.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 4</p>
            <p>WAKTU KERJA & KERJA LEMBUR</p>
            <ol>
                <li>KARYAWAN wajib mengikuti jam kerja yang ditetapkan oleh Perusahaan dan atau yang ditetapkan oleh PERUSAHAAN MITRA/KLIEN dimana KARYAWAN ditempatkan;</li>
                <li>Kehadiran KARYAWAN di tempat kerja untuk pelaksanaan pekerjaan dalam periode dimana ia bekerja dibuktikan dengan absensi/time sheet bulanan yang telah disetujui PERUSAHAAN;</li>
                <li>Sesuai maksud perjanjian ini, maka hari kerja ditetapkan selama 5 atau 6 hari dalam seminggu yaitu mulai hari Senin s/d Jumat atau Senin s/d Sabtu (kecuali ditentukan lain oleh Perusahaan), namun tidak terbatas pada kebijaksanaan kerja shift dan/atau perubahan kebijakan jam kerja dari PERUSAHAAN, termasuk di hari libur yang ditetapkan Pemerintah;</li>
                <li>KARYAWAN selama hari kerja sebagaimana dimaksud dalam ayat 1 diatas diwajibkan untuk melaksanakan seluruh kewajibannya mulai pukul 08.00 Wib s/d pukul 17.00 Wib atau mulai pukul 08.00 Wib s/d pukul 16.00 WIB dengan hak istirahat 1 jam yaitu pukul 12.00 Wib s/d 13.00 Wib;</li>
                <li>Pada prinsipnya kerja lembur dilakukan berdasarkan keperluan dan sifat pekerjaan. Apabila diperlukan, KARYAWAN dapat melaksanakan kerja lembur untuk menyelesaikan tugas dan pekerjaan yang menjadi tanggung jawabnya dengan persetujuan atau perintah Atasan dan di tulis dalam time sheet bulanan;</li>
                <li>Pengaturan mengenai kerja lembur ini mengikuti peraturan perundangan yang berlaku.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 5</p>
            <p>IZIN MENINGGALKAN PEKERJAAN</p>
            <ol>
                <li>Ijin meninggalkan Pekerjaan yang diperbolehkan dan tetap dibayar adalah sesuai dengan ketentuan perundang-undangan yang berlaku;</li>
                <li>Izin karena sakit, KARYAWAN wajib menyerahkan Surat Keterangan Dokter kepada PERUSAHAAN di hari pertama KARYAWAN kembali bekerja;</li>
                <li>Apabila KARYAWAN tidak dapat menyerahkan surat keterangan dokter seperti yang tertera pada ayat (2) maka KARYAWAN sepakat untuk dianggap sebagai mangkir dan PERUSAHAAN berhak untuk tidak membayar gaji pada hari tersebut;</li>
                <li>Setelah menjalani masa kerja selama 12 (dua belas) bulan terus menerus, KARYAWAN berhak untuk mendapatkan cuti selama 12 (dua belas) hari kerja yang tatacara perlaksanaan hak cuti tersebut sesuai ketentuan PERUSAHAAN sepanjang tidak mengganggu kelancaran pelaksanaan operasional tugas dan pekerjaan di lokasi kerja.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 6</p>
            <p>CARA PEMBAYARAN UPAH</p>
            <ol>
                <li>PERUSAHAAN melakukan pembayaran gaji pokok atau tunjangan tetap pada tanggal 25 setiap bulannya setelah KARYAWAN memberikan hasil pekerjaannya, jika tanggal pada hari tersebut hari libur maka pembayaran dilakukan setelah tanggal tersebut;</li>
            </ol>
        </div>
    </div>
    <div class="page">
        <div class="pasal-main">
            <ol>
                <li>Pembayaran tunjangan tidak tetap,jika ada dalam struktur kompensasi, akan dibayarkan di penggajian periode berikutnya dimana KARYAWAN wajib menyerahkan bukti kehadiran dan lembur (timesheet) yang telah ditandatangani atasan <b>paling lambat tanggal 5 di bulan berikutnya;</b></li>
                <li>Penyerahan bukti kehadiran dan lembur (timesheet) untuk pembayaran tunjangan tidak tetap yang dikumpulkan melebihi tanggal 5 akan dibayarkan PERUSAHAAN dalam penggajian bulan berikutnya;</li>
                <li>KARYAWAN wajib membuka rekening atas nama sendiri pada yang ditunjuk oleh PERUSAHAAN untuk proses pembayaran kompensasi dari PERUSAHAAN pada KARYAWAN setiap bulan.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 7</p>
            <p>PERUBAHAN JABATAN/PENEMPATAN</p>
            <p style="text-align: justify; font-weight: normal">PERUSAHAAN, berwenang sewaktu-waktu merubah tanggung jawab pekerjaan dan lokasi kerja KARYAWAN sesuai dengan kepentingan dan kebutuhan bisnis PERUSAHAAN dengan pemberitahuan sebelumnya terhadap KARYAWAN.</p>
        </div>
        <div class="pasal-main">
            <p>Pasal 8</p>
            <p>EVALUASI KARYAWAN</p>
            <ol>
                <li>PERUSAHAAN berwenang untuk mengevaluasi hasil kerja KARYAWAN, dan berwenang untuk memberikan penilaian atas hasil kerja tersebut tanpa kewajiban untuk menjelaskan dasar dari penilaian tersebut kepada KARYAWAN;</li>
                <li>Hasil evaluasi akan dijadikan dasar penilaian kerja KARYAWAN pada akhir periode Perjanjian.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 9</p>
            <p>RAHASIA PERUSAHAAN</p>
            <ol>
                <li>KARYAWAN wajib menjaga kerahasiaan setiap informasi yang diterimanya sehubungan dengan usaha perusahaan terhadap pihak ketiga selama berlakunya perjanjian kerja, dan kewajiban itu akan terus melekat dan berlaku setelah berakhirnya atau diakhirinya perjanjian kerja;</li>
                <li style="font-weight: bold">KARYAWAN bersedia menandatangani Surat Tentang Kerahasiaan yang berisi Perjanjian Hak Paten dan Kerahasiaan Informasi yang terlampir dalam Lampiran-I Perjanjian ini;</li>
                <li style="font-weight: bold">Semua informasi dan atau bahan-bahan yang didapat dari pekerjaan yang dilakukan berdasarkan Perjanjian ini baik yang sudah selesai atau masih dalam proses penyelesaian adalah merupakan milik dari PERUSAHAAN atau PERUSAHAAN MITRA/ KLIEN. Untuk segala keperluan termasuk dan tidak terbatas penggandaan, modifikasi atau pengungkapan dari informasi dan atau bahan-bahan tersebut harus mendapatkan persetujuan tertulis dari PERUSAHAAN atau PERUSAHAAN MITRA/ KLIEN;</li>
                <li>KARYAWAN berjanji untuk melindungi PERUSAHAAN atau PERUSAHAAN MITRA/ KLIEN dari dan terhadap kerugian atau kewajiban yang timbul dari tuntutan apapun akibat dari penyalahgunaan ‘Rahasia Dagang’, ‘Hak Patent’, ‘Hak Cipta’ atau pelanggaran hak-hak kepemilikan dari informasi dan atau bahan-bahan yang diberikan kepada KARYAWAN atau penggunaan dari informasi atau bahan-bahan tersebut.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 10</p>
            <p>KETENTUAN HAK CIPTA</p>
            <ol>
                <li>Pekerjaan-pekerjaan yang merupakan ciptaan atau karya yang dihasilkan KARYAWAN sehubungan dengan tugas KARYAWAN selama terikat hubungan kerja dengan PERUSAHAAN merupakan milik dan wajib diserahkan kepada PERUSAHAAN, termasuk tapi tidak terbatas pada hak cipta yang terkait dengan hak cipta atau karya tersebut;</li>
                <li>KARYAWAN dilarang menyalin atau menduplikasi dokumen, program, dan atau produk usaha milik PERUSAHAAN</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 11</p>
            <p>MEMATUHI PERATURAN</p>
            <p style="text-align: justify; font-weight: normal">KARYAWAN menerima pekerjaan, menjalankan dengan sebaik – baiknya dengan penuh tanggung jawab, mematuhi seluruh ketentuan – ketentuan yang diatur dalam Peraturan di PERUSAHAAN selama tidak bertentangan atau melanggar Peraturan perundangan yang berlaku.</p>
        </div>
        <div class="pasal-main">
            <p>Pasal 12</p>
            <p>SANKSI</p>
            <ol>
                <li>PERUSAHAAN dapat mengenakan tindakan disiplin kepada KARYAWAN yang berkaitan dengan performa kerja selama bertugas atau apabila KARYAWAN melakukan pelanggaran terhadap aturan yang telah ditetapkan oleh PERUSAHAAN;</li>
                <li>Apabila KARYAWAN melakukan pelanggaran terhadap Peraturan Perusahaan dan atau tata tertib PERUSAHAAN di lingkungan Perusahaan, sedangkan KARYAWAN telah diperingatkan secara layak oleh PERUSAHAAN namun KARYAWAN masih melakukan pelanggaran, maka PERUSAHAAN dapat memberikan sanksi berupa pengakhiran perjanjian kerja secara sepihak.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 13</p>
            <p>BERAKHIRNYA PERJANJIAN KERJA</p>
            <ol>
                <li>PERUSAHAAN maupun KARYAWAN dengan alasan tertentu dapat memutuskan perjanjian kerja ini dengan pemberitahuan tertulis;</li>
                <li>Dalam hal KARYAWAN mengundurkan diri sebelum jangka waktu perjanjian berakhir, KARYAWAN harus menyampaikan pemberitahuan secara tertulis kepada perusahaan selambat-lambatnya 1 (satu) bulan sebelum tanggal efektif mengundurkan diri;</li>
                <li>Perjanjian kerja dapat berakhir apabila terjadi Pelanggaran terhadap perjanjian kerja ini atau pelanggaran terhadap Peraturan Perusahaan;</li>
                <li>Ketentuan ayat 3 diatas tidak mengenyampingkan tuntutan ganti rugi maupun tuntutan pidana kepada KARYAWAN apabila, dikemudian hari ditemukan sesuatu tindakan/perbuatan KARYAWAN yang membawa kerugian baik langsung maupun tidak langsung kepada PERUSAHAAN maupun PERUSAHAAN MITRA/ KLIEN yang dilakukan oleh KARYAWAN.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 14</p>
            <p>AKIBAT PENGAKHIRAN PERJANJIAN</p>
            <ol>
                <li>Dalam hal terjadi pemutusan perjanjian kerja sebagaimana tersebut dalam pasal 13 ayat (3) diatas, maka menghapuskan kewajiban pembayaran kompensasi maupun ganti rugi dalam bentuk apapun;</li>
                <li>Perjanjian berakhir demi hukum dengan berakhirnya waktu yang diperjanjikan didalam perjanjian.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 15</p>
            <p>ADENDUM DAN LAMPIRAN</p>
            <ol>
                <li>Setiap perubahan isi Perjanjian ini, akan mengikat apabila dinyatakan secara tertulis dan disetujui oleh PARA PIHAK dengan membuat dan menandatangani ADENDUM terhadap Perjanjian ini;</li>
                <li>Lampiran-lampiran dari Perjanjian ini mempunyai kekuatan hukum yang tetap dan mengikat seperti halnya pasal-pasal lain dari Perjanjian ini.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 16</p>
            <p>PENGALIHAN KEWAJIBAN</p>
            <ol>
                <li>Para pihak sepakat, bila karena sesuatu hal PERUSAHAAN sebagai wakil PT. KOLABORASI PARA SAHABAT dalam perjanjian ini berhalangan untuk menjalankan fungsinya sebagai wakil PERUSAHAAN maka siapapun pejabat yang ditunjuk secara resmi oleh PERUSAHAAN, secara sah adalah pihak yang mewakili PERUSAHAAN;</li>
                <li>Para pihak sepakat bahwa perjanjian ini bersifat tunggal, dimana KARYAWAN dilarang untuk membuat perjanjian kerja dan/atau kesepakatan kerja dalam bentuk apapun dengan pihak lain dalam masa hubungan kerja, pelanggaran ayat ini dapat mengakibatkan pengakhiran perjanjian kerja sepihak dari PERUSAHAAN dan PERUSAHAAN tidak mempunyai kewajiban apapun untuk membayar kompensasi akibat pengakhiran perjanjian tersebut.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 17</p>
            <p>PEMISAHAN KETENTUAN</p>
            <p style="text-align: justify; font-weight: normal">Pembatalan salah satu pasal dalam Perjanjian ini, yang disebabkan oleh ditemukan dan atau timbulnya peraturan baru yang </p>
        </div>
    </div>

    <div class="page" style="margin-bottom: 260px">
        <div class="pasal-main">
            <p style="text-align: justify; font-weight: normal">menjadikan salah satu pasal tersebut batal demi hukum, maka pembatalan salah satu pasal tersebut tidak menjadikan pasal-pasal lain dalam Perjanjian ini menjadi batal, dan pasal-pasal lainnya tetap berlaku selama tidak berkaitan langsung dengan salah satu pasal yang dibatalkan.</p>
        </div>
        <div class="pasal-main">
            <p>Pasal 18</p>
            <p>PENULISAN</p>
            <p style="text-align: justify; font-weight: normal">Segala bentuk coretan atau tulisan yang berakibat menggantikan tulisan yang terdahulu adalah membuat perjanjian kerja tidak sah, kecuali ditandai dengan tanda tangan para pihak diatas atau disamping coretan/ tulisan pengganti yang termaksud.</p>
        </div>
        <div class="pasal-main">
            <p>Pasal 19</p>
            <p>HUKUM YANG BERLAKU</p>
            <p style="text-align: center; font-weight: normal">Perjanjian ini beserta penerapannya tunduk pada peraturan perundang-undangan yang berlaku di Indonesia.</p>
        </div>
        <div class="pasal-main">
            <p>Pasal 20</p>
            <p>PERSELISIHAN</p>
            <ol>
                <li style="font-weight: bold">Apabila dikemudian hari terjadi perselisihan atau perbedaan pendapat dalam hal pelaksanaan perjanjian ini, maka akan diselesaikan secara musyawarah dan kekeluargaan;</li>
                <li style="font-weight: bold">Bila perselisihan tersebut tidak dapat diselesaikan secara kekeluargaan maka para pihak sepakat untuk memilih penyelesaian perselisihan atas Perjanjian ini beserta dengan segala akibat yang timbul, diwilayah hukum pengadilan negeri tempat perjanjian ini ditandatangani;</li>
                <li>Dalam hal pengakhiran berdasarkan ketentuan-ketentuan dalam Perjanjian ini, PARA PIHAK dengan tegas dan setuju untuk membatalkan dan mengabaikan hak dan kewajiban masing-masing bedasarkan Pasal 1266 dan 1267 Kitab Undang-undang Hukum Perdata.</li>
            </ol>
        </div>
        <div class="pasal-main">
            <p>Pasal 21</p>
            <p>PENUTUP</p>
            <p style="text-align: justify">Perjanjian ini dibuat dan ditandatangani oleh PARA PIHAK atas keinginan PARA PIHAK sendiri, KARYAWAN menyatakan telah menerima seluruh informasi dan/atau penjelasan dari seluruh isi Perjanjian ini dari PERUSAHAAN dalam keadaan sadar, sehat jasmani dan rohani dan tanpa paksaan dari orang lain, pada tanggal seperti yang tertulis pada bagian awal Perjanjian ini, dibuat dalam rangkap 2 (dua) yang mempunyai kekuatan hukum yang sama, masing - masing untuk PERUSAHAAN dan KARYAWAN.</p>
        </div>
        <div class="signature">
            <table border="1">
                <tr>
                    <td>PERUSAHAAN</td>
                    <td>KARYAWAN</td>
                </tr>
                <tr>
                    <td>
                        <!-- Approved Perusahaan -->
                        <div style="display: inline-block; background-color: #28a745; color: white; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 13px;">
                            <div style="font-weight: bold;">Approved</div>
                            <div style="font-size: 11px;"><?= formatTanggalIndo($transaksi->created_at) ?></div>
                        </div>
                    </td>
                    <td>
                        <?php if ($transaksi->app_status == 'approved') { ?>
                            <!-- Approved Karyawan -->
                            <div style="display: inline-block; background-color: #28a745; color: white; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 13px;">
                                <div style="font-weight: bold;"><?= ucfirst($transaksi->app_status) ?></div>
                                <div style="font-size: 11px;"><?= formatTanggalIndo($transaksi->app_date) ?></div>
                            </div>
                        <?php } else if ($transaksi->app_status == 'reject') { ?>
                            <!-- Rejected Karyawan -->
                            <div style="display: inline-block; background-color: #dc3545; color: white; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 13px;">
                                <div style="font-weight: bold;"><?= ucfirst($transaksi->app_status) ?></div>
                                <div style="font-size: 11px;"><?= formatTanggalIndo($transaksi->app_date) ?></div>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>ANINDYAGUNA PUTRAWAN</td>
                    <td><?= $master->nama_lengkap ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="page">
        <div class="lampiran-1">
            <div class="header-lampiran-1">
                <p>LAMPIRAN I <span><i>(No. <?= $transaksi->no_perjanjian ?>)</i></span></p>
                <p>A.PERINCIAN KOMPENSASI & BENEFIT</p>
                <p style="font-weight: bold;">PERUSAHAAN memberi kompensasi dan benefit dari pekerjaan yang diberikan kepada KARYAWAN dengan perincian sebagai berikut,</p>
            </div>
            <div class="main-content">
                <table>
                    <tr>
                        <td><span>1.</span>Jabatan</td>
                        <td>:</td>
                        <td><?= $master->jabatan ?></td>
                    </tr>
                    <tr>
                        <td><span>2.</span>Unit Kerja</td>
                        <td>:</td>
                        <td>PT. KOLABORASI PARA SAHABAT</td>
                    </tr>
                    <tr>
                        <td><span>3.</span>Lokasi Kerja</td>
                        <td>:</td>
                        <td><?= $master->wilayah_kerja ?></td>
                    </tr>
                    <tr>
                        <td><span>4.</span>Periode Kerja</td>
                        <td>:</td>
                        <td><?= formatTanggalIndo($transaksi->jk_awal) ?> - <?= formatTanggalIndo($transaksi->jk_akhir) ?></td>
                    </tr>
                    <tr>
                        <td><span>5.</span>Kompensasi</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span class="detail">a.</span>Gaji</td>
                        <td>:</td>
                        <td>Rp.<?= number_format($transaksi->gaji, 0, ',', '.') ?> per bulan</td>
                    </tr>
                    <tr>
                        <td><span class="detail">b.</span>Tunjangan Tetap</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span class="detail-2">1.</span>Tunjangan Pulsa</td>
                        <td>:</td>
                        <td>Rp <?= number_format($transaksi->tj_pulsa, 0, ',', '.') ?>,00 per bulan</td>
                    </tr>
                    <tr>
                        <td><span class="detail-2">2.</span>Tunjangan Operasional Tenaga Lapangan</td>
                        <td>:</td>
                        <td>Rp <?= number_format($transaksi->tj_ops, 0, ',', '.') ?>,00 per bulan</td>
                    </tr>
                    <tr>
                        <td><span class="detail-2">3.</span>Tunjangan Hari Raya</td>
                        <td>:</td>
                        <td><?= $transaksi->thr ?></td>
                    </tr>
                    <tr>
                        <td><span class="detail">b.</span>Tunjangan Tidak Tetap</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span class="detail-2">4.</span>Tunjangan Kehadiran</td>
                        <td>:</td>
                        <td>Rp <?= number_format($transaksi->tj_kehadiran, 0, ',', '.') ?>,00 per kehadiran</td>
                    </tr>
                    <tr>
                        <td><span class="detail-2">5.</span>Insentif</td>
                        <td>:</td>
                        <td><?= $transaksi->insentif ?></td>
                    </tr>
                    <tr>
                        <td><span>6.</span>Benefit</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span class="detail-2">1.</span>Kesehatan</td>
                        <td>:</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td><span class="detail-2">2.</span>Jamsostek</td>
                        <td>:</td>
                        <td>0</td>
                    </tr>
                </table>
                <p class="before-signature">KARYAWAN memberikan kuasa kepada PERUSAHAAN untuk memotong dari kompensasi bulanan KARYAWAN untuk pembayaran iuran Jamsostek dari Upah dan Pemotongan Kewajiban Pajak sesuai ketentuan perundangan-undangan yang berlaku.</p>
                <table class="signature-lampiran-1">
                    <tr>
                        <td>Menyetujui</td>
                    </tr>
                    <tr>
                        <td>
                            <?php if ($transaksi->app_status == 'approved') { ?>
                                <!-- Approved Karyawan -->
                                <div style="display: inline-block; background-color: #28a745; color: white; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 13px;">
                                    <div style="font-weight: bold;"><?= ucfirst($transaksi->app_status) ?></div>
                                    <div style="font-size: 11px;"><?= formatTanggalIndo($transaksi->app_date) ?></div>
                                </div>
                            <?php } else if ($transaksi->app_status == 'reject') { ?>
                                <!-- Rejected Karyawan -->
                                <div style="display: inline-block; background-color: #dc3545; color: white; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 13px;">
                                    <div style="font-weight: bold;"><?= ucfirst($transaksi->app_status) ?></div>
                                    <div style="font-size: 11px;"><?= formatTanggalIndo($transaksi->app_date) ?></div>
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold">
                            <?= $master->nama_lengkap ?>
                        </td>
                    </tr>
                    <tr>
                        <td>KARYAWAN</td>
                    </tr>
                </table>
                <div class="line"></div>
            </div>
            <div class="perjanjian-b">
                <p><b>B. PERJANJIAN HAK PATEN DAN KERAHASIAAN INFORMASI</b></p>
                <p><i><b>(No: <?= $transaksi->no_perjanjian ?>)</b></i></p>
                <table>
                    <tr>
                        <td>Pernyataan dari</td>
                        <td>:</td>
                        <td><?= $master->nama_lengkap ?></td>
                    </tr>
                    <tr>
                        <td>Tentang</td>
                        <td>:</td>
                        <td>Perjanjian Informasi Rahasia dan Hak Milik</td>
                    </tr>
                </table>
                <p class="dalam-rangka">Dalam rangka penugasan Saudara bekerja di PT. Kolaborasi Para Sahabat atau Perusahaan Mitra/Klien atau anak perusahaannya, Saudara akan mempunyai peluang mengakses informasi yang dianggap rahasia atau yang merupakan hak milik. Secara luas, informasi rahasia atau hak milik diartikan sebagai setiap informasi yang memberikan keuntungan lebih kepada Perusahaan dibandingkan dengan para pesaingnya. Diakui bahwa seringkali tidak mudah untuk membedakan secara tegas antara informasi hak milik, yang merupakan milik eksklusif dari Perusahaan maupun Perusahaan Mitra/Klien atau salah satu anak perusahaan atau afiliasinya, dengan informasi yang merupakan bagian dari latar belakang keterampilan dan pengalaman yang umumnya diperoleh seiring dengan pengalaman usaha pribadi yang dapat Saudara gunakan secara bebas dalam pekerjaan Saudara di mana pun. Pemahaman Saudara sendiri tentang hak milik biasanya merupakan petunjuk yang paling aman dalam menginterpretasikan hal tersebut. Apabila ada keraguan, Saudara disarankan untuk membicarakan masalah tersebut dengan PT. Kolaborasi Para Sahabat. Ketika Saudara ditugaskan pada Perusahaan Mitra/Klien atau salah satu anak perusahaan atau afiliasinya, Saudara perlu menandatangani suatu Perjanjian Hak Paten dan Informasi Rahasia yang bentuknya seperti ditunjukkan di bawah ini. Perjanjian ini akan disimpan untuk mengingatkan Saudara pada kewajiban yang berkaitan dengan kerahasiaan dan tentang penemuan-penemuan yang dipatenkan, yang dibuat atau dihasilkan pada saat memproses transaksi Perusahaan Mitra/Klien.</p>
                <h1>PT. KOLABORASI PARA SAHABAT</h1>
                <h2>PERJANJIAN HAK PATEN DAN KERAHASIAAN INFORMASI</h2>
                <p class="text-space">Sehubungan dengan penugasan Saya pada Perusahaan atau afiliasinya oleh PT. KOLABORASI PARA SAHABAT, Saya setuju untuk merahasiakan dan tidak mengungkapkan informasi rahasia kepada orang lain selama masa penugasan Saya. Saya juga setuju untuk tidak mengungkapkan atau menggunakan rencana – rencana usaha atau metode-metode dan strategi-strategi pemasaran, biaya atau informasi rahasia dan hak milik lainnya menyangkut para calon nasabah, nasabah, klien dan vendor Perusahaan Mitra/Klien.</p>
                <p class="text-space">Apabila penugasan Saya pada Perusahaan berakhir, Saya setuju untuk tidak mengungkapkan atau menggunakan informasi rahasia tersebut dan segera mengembalikan kepada Perusahaan semua dokumen dan hal-hal lain yang dimiliki oleh Perusahaan Mitra/Klien dan anak perusahaannya.</p>
                <p class="text-space">Selain itu Saya memahami bahwa pada saat Saya ditugaskan di Perusahaan Saya akan segera mengungkapkan dan mengalihkan kepada Perusahaan, kepentingan Saya dalam setiap penemuan atau pengembangan yang dibuat atau dihasilkan oleh Saya, baik sendiri atau bersama-sama dengan orang lain, yang timbul sebagai akibat dari penugasan Saya dan setelahnya berkaitan dengan setiap proses hukum yang berhubungan dengan penemuan atau pengembangan tersebut dan dalam memperoleh hak paten dalam dan luar negeri atau perlindungan lain yang mencakup hal tersebut.</p>
                <table class="signature-lampiran-2">
                    <tr>
                        <td>Menyatakan</td>
                    </tr>
                    <tr>
                        <td>
                            <?php if ($transaksi->app_status == 'approved') { ?>
                                <!-- Approved Karyawan -->
                                <div style="display: inline-block; background-color: #28a745; color: white; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 13px;">
                                    <div style="font-weight: bold;"><?= ucfirst($transaksi->app_status) ?></div>
                                    <div style="font-size: 11px;"><?= formatTanggalIndo($transaksi->app_date) ?></div>
                                </div>
                            <?php } else if ($transaksi->app_status == 'reject') { ?>
                                <!-- Rejected Karyawan -->
                                <div style="display: inline-block; background-color: #dc3545; color: white; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 13px;">
                                    <div style="font-weight: bold;"><?= ucfirst($transaksi->app_status) ?></div>
                                    <div style="font-size: 11px;"><?= formatTanggalIndo($transaksi->app_date) ?></div>
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; position: relative; right: 2px">(<?= $master->nama_lengkap ?>)</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold">KARYAWAN</td>
                    </tr>
                </table>
                <div class="line-2"></div>
            </div>
        </div>
    </div>
    <div class="page">
        <div class="lampiran-2">
            <div class="header-lampiran-2">
                <h6>Lampiran II. <span>(No:<?= $transaksi->no_perjanjian ?>)</span></h6>
                <p>Jakarta, <?= formatTanggalIndo($transaksi->jk_awal) ?></p>
                <h4>Kepada</h4>
                <h4>Saudara/i. <span><?= $master->nama_lengkap ?></span></h4>
                <h5><?= $master->alamat_ktp ?></h5>
                <table>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td>Surat Tugas</td>
                    </tr>
                </table>
            </div>
            <div class="content">
                <p>Dengan hormat,</p>
                <h2>Setelah mempertimbangkan latar belakang dan kualifikasi yang Saudara/i <span><?= $master->nama_lengkap ?></span> miliki, dengan ini kami memberikan tugas kepada Saudara/i untuk memberikan pelayanan jasa dengan ketentuan sebagai berikut:</h2>
                <table class="table-main">
                    <tr>
                        <td><span>1.</span>Jabatan</td>
                        <td>:</td>
                        <td><?= $master->jabatan ?></td>
                    </tr>
                    <tr>
                        <td><span>2.</span>Unit Tugas</td>
                        <td>:</td>
                        <td>PT. KOLABORASI PARA SAHABAT</td>
                    </tr>
                    <tr>
                        <td><span>3.</span>Lokasi Tugas</td>
                        <td>:</td>
                        <td><?= $master->wilayah_kerja ?></td>
                    </tr>
                    <tr>
                        <td><span>4.</span>Periode tugas</td>
                        <td>:</td>
                        <td><?= formatTanggalIndo($transaksi->jk_awal) ?> - <?= formatTanggalIndo($transaksi->jk_akhir) ?></td>
                    </tr>
                </table>
                <h1>Surat Tugas ini berakhir tanpa syarat hingga akhir periode perjanjian kerja dan atau diterbitkan surat tugas baru sebagai penggantinya. Saudara/i diharapkan menyelesaikan seluruh pekerjaan yang sudah menjadi tanggung jawab sampai dengan berakhirnya masa penugasan ini.</h1>
                <h3>Demikian kami sampaikan, atas perhatian kami ucapkan terima kasih.</h3>
                <table class="signature-content">
                    <tr>
                        <td>Hormat kami,</td>
                    </tr>
                    <tr>
                        <td>PT. KOLABORASI PARA SAHABAT</td>
                    </tr>
                    <tr>
                        <td>
                            <!-- Approved Perusahaan -->
                            <div style="display: inline-block; background-color: #28a745; color: white; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 13px;">
                                <div style="font-weight: bold;">Approved</div>
                                <div style="font-size: 11px;"><?= formatTanggalIndo($transaksi->created_at) ?></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Anindyaguna Putrawan</td>
                    </tr>
                    <tr>
                        <td>Direktur</td>
                    </tr>
                </table>
                <div class="line"></div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("date-print").textContent = new Date().toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    </script>
</body>

</html>