<?php
$row = isset($row) ? $row : null;

$getVal = function ($obj, $key, $default = '-') {
    return (is_object($obj) && isset($obj->{$key}) && $obj->{$key} !== null && trim((string)$obj->{$key}) !== '') ? $obj->{$key} : $default;
};

$isFilled = function ($v) {
    if ($v === null) return false;
    $s = trim((string) $v);
    if ($s === '' || $s === '0') return false;
    if ($s === '0000-00-00' || $s === '0000-00-00 00:00:00') return false;
    return true;
};

$formatIdDatetime = function ($v) use ($isFilled) {
    if (!$isFilled($v) || $v === '-') return '-';
    try {
        $dt = new DateTime((string) $v);
    } catch (Exception $e) {
        return (string) $v;
    }

    $months = array(
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    );

    $day = (int) $dt->format('d');
    $monthNum = (int) $dt->format('n');
    $year = $dt->format('Y');
    $time = $dt->format('H:i:s');
    $monthName = isset($months[$monthNum]) ? $months[$monthNum] : $dt->format('m');

    return $day . ' ' . $monthName . ' ' . $year . ' ' . $time;
};

$origin = $getVal($row, 'origin', '');
$destinasi = $getVal($row, 'destinasi', '');
$route = trim($origin . (empty($origin) || empty($destinasi) ? '' : ' - ') . $destinasi);
$route = $route !== '' ? $route : '-';

$sections = array(
    'marketing' => 'Marketing',
    'plotting' => 'Plotting',
    'monitoring' => 'Monitoring',
    'finance' => 'Finance',
);
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
        <div>
            <a class="btn btn-sm btn-primary" href="<?= base_url('sml_kertas_kerja/transaksi_list') ?>"><i class="fa fa-chevron-left"></i>&nbsp;Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class="row">
                        <!-- <div class="col-md-4"><b>ID</b>: <?= (int) $getVal($row, 'id', 0) ?></div> -->
                        <div class="col-md-3"><b>Periode</b>: <?= htmlspecialchars((string) $getVal($row, 'periode', '-')) ?></div>
                        <div class="col-md-5"><b>Route</b>: <?= htmlspecialchars((string) $route) ?></div>
                    </div>
                    <!-- <div class="row mt-2">
                        <div class="col-md-4"><b>Input Role Terakhir</b>: <?= htmlspecialchars((string) $getVal($row, 'input_role', '-')) ?></div>
                        <div class="col-md-8"><b>User Terakhir</b>: <?= htmlspecialchars((string) $getVal($row, 'user', '-')) ?></div>
                    </div> -->
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 180px;">Section</th>
                                    <th>Input Pertama</th>
                                    <th>Update Terakhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sections as $key => $label) {
                                    $c = $formatIdDatetime($getVal($row, 'created_at_' . $key, '-'));
                                    $u = $formatIdDatetime($getVal($row, 'updated_at_' . $key, '-'));
                                ?>
                                    <tr>
                                        <td><b><?= htmlspecialchars($label) ?></b></td>
                                        <td><?= htmlspecialchars($c) ?></td>
                                        <td><?= htmlspecialchars($u) ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- <div class="alert alert-info mb-0">
                        Global timestamp (legacy): Created <?= htmlspecialchars($formatIdDatetime($getVal($row, 'created_at', '-'))) ?> | Updated <?= htmlspecialchars($formatIdDatetime($getVal($row, 'updated_at', '-'))) ?> | Edit <?= htmlspecialchars($formatIdDatetime($getVal($row, 'edit_at', '-'))) ?>
                    </div> -->

                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>
