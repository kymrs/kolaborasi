<!-- Cost Structure PDF Template using mPDF -->

<?php
function numberToRoman($number) {
    $map = [
        'M'  => 1000,
        'CM' => 900,
        'D'  => 500,
        'CD' => 400,
        'C'  => 100,
        'XC' => 90,
        'L'  => 50,
        'XL' => 40,
        'X'  => 10,
        'IX' => 9,
        'V'  => 5,
        'IV' => 4,
        'I'  => 1
    ];

    $result = '';

    foreach ($map as $roman => $value) {
        while ($number >= $value) {
            $result .= $roman;
            $number -= $value;
        }
    }

    return $result;
}
?>

<style>
    * {
        font-family: 'helvetica';
        margin: 0;
        padding: 0;
    }

    body {
        font-size: 11px;
        color: #333;
    }

    .header {
        margin-bottom: 20px;
        padding: 15px 0;
        border-bottom: 3px solid #ff6b35;
    }

    .company-info {
        text-align: center;
        margin-bottom: 20px;
    }

    .company-info h1 {
        font-size: 18px;
        color: #242d4a;
        margin-bottom: 5px;
    }

    .company-info p {
        font-size: 10px;
        color: #666;
        margin: 2px 0;
    }

    .title {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        color: #242d4a;
        margin-bottom: 5px;
        padding: 10px;
        padding-top: 0;
        border-radius: 4px;
    }

    .info-section {
        margin-bottom: 15px;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 5px;
    }

    .info-table td {
        padding: 4px 0;
        font-size: 10px;
    }

    .info-label {
        width: 180px;
        color: #242d4a;
    }

    .section-header {
        background-color: #28a745;
        color: white;
        padding: 10px;
        margin: 15px 0 10px 0;
        font-weight: bold;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table {
        font-size: 10px;
        margin-bottom: 20px;
    }

    /* .items-table th {
        background-color: #ffe600;
        color: black;
        border: 1px solid #000;
        padding: 6px;
        text-align: center;
        font-weight: bold;
    }

    .items-table td {
        border: 1px solid #000;
        padding: 5px;
    }

    .category-row td {
        background-color: #00ff00;
        font-weight: bold;
        text-align: center;
    } */

    .items-table th {
        background-color: #f0cf33;
        color: black;
        border: 1px solid #000;
        padding: 6px;
        text-align: center;
        font-weight: bold;
    }

    .items-table td {
        border: 1px solid #000;
        padding: 5px;
    }

    .category-row td {
        background-color: #5a4193;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .subtotal-row td {
        background-color: #f4c99b;
        font-weight: bold;
    }

    .grand-total-row td {
        font-weight: bold;
        font-size: 11px;
    }

    .selling-price-row td {
        background-color: #c9272b;
        color: white;
        font-weight: bold;
    }

    .footer-text {
        margin-top: 20px;
        text-align: center;
        font-size: 9px;
        color: #666;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }
</style>

<div class="title">
    COST STRUCTURE
</div>

<!-- MASTER DATA -->
<table class="info-table">
    <tr>
        <td class="info-label"><b>Company Name :</b> <?= $data->company_name ?? '-' ?></td>
        <td class="info-label" align="right"><b>Event Type :</b> <?= $data->event_type ?? '-' ?></td>
    </tr>
</table>

<!-- TABLE -->
<?php if (!empty($data->categories)) : ?>

    <table class="items-table">

        <thead>
            <tr>
                <th width="35%">KEBUTUHAN</th>
                <th width="10%">JUMLAH</th>
                <th width="20%">Harga Satuan</th>
                <th width="20%">Jumlah</th>
                <th width="20%">Total</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($data->categories as $category) : ?>

            <?php
                preg_match('/^(\d+)\.\s*(.*)$/', $category->name, $matches);

                $number = $matches[1] ?? 0;
                $name = $matches[2] ?? $category->name;

                $roman = numberToRoman((int)$number);
                
                // Initialize category subtotal
                $category_subtotal = 0;
            ?>

            <tr class="category-row">
                <td colspan="5">
                    <?= $roman . '. ' . strtoupper($name) ?>
                </td>
            </tr>

                <?php if (!empty($category->items)) : ?>

                    <?php foreach ($category->items as $item) : ?>

                        <?php
                            // Sum up the subtotal per category
                            $category_subtotal += ($item->subtotal ?? 0);
                        ?>

                        <tr>
                            <td><?= $item->name ?? '-' ?></td>

                            <td class="text-center">
                                <?= $item->qty ?? 0 ?>
                            </td>

                            <td class="text-right">
                                Rp <?= number_format($item->price ?? 0, 0, ',', '.') ?>
                            </td>

                            <td class="text-right">
                                Rp <?= number_format($item->subtotal ?? 0, 0, ',', '.') ?>
                            </td>

                            <td></td>
                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                <!-- CATEGORY SUBTOTAL -->
                <tr class="subtotal-row">

                    <td colspan="4" class="text-right">
                        Total <?= preg_replace('/^\d+\.\s*/', '', $category->name ?? '-') ?>
                    </td>

                    <td class="text-right">
                        Rp <?= number_format($category_subtotal, 0, ',', '.') ?>
                    </td>

                </tr>

            <?php endforeach; ?>

            <!-- GRAND TOTAL -->
            <tr class="grand-total-row">

                <?php
                    $romanCategories = [];

                    if (!empty($data->categories)) {
                        foreach ($data->categories as $index => $category) {
                            $romanCategories[] = numberToRoman($index + 1);
                        }
                    }

                    $romanText = implode(', ', $romanCategories);
                ?>

                <td colspan="4" class="text-right">
                    Total Production Expenses (<?= $romanText ?>)
                </td>

                <td class="text-right">
                    Rp <?= number_format($data->grand_total ?? 0, 0, ',', '.') ?>
                </td>

            </tr>

            <!-- MARGIN -->
            <tr class="grand-total-row">
            <?php
                $margin_percent = 0;

                if (($data->grand_total ?? 0) > 0) {
                    $margin_percent = ($data->margin / $data->grand_total) * 100;
                }
            ?>

                <td colspan="4" class="text-right">
                    Margin (<?= round($data->margin, 0) ?>%)
                </td>

                <td class="text-right">
                    Rp <?= number_format(
                        (($data->grand_total ?? 0) * ($data->margin ?? 0)) / 100,
                        0,
                        ',',
                        '.'
                    ) ?>
                </td>

            </tr>

            <!-- TOTAL RECEIVED -->
            <tr class="grand-total-row">

                <td colspan="4" class="text-right">
                    DITERIMA OLEH EO
                </td>

                <td class="text-right">
                    Rp <?= number_format($data->received_by_eo ?? 0, 0, ',', '.') ?>
                </td>

            </tr>

            <?php
                $margin_amount = (($data->grand_total ?? 0) * ($data->margin ?? 0)) / 100;

                $fee_mediator_percent = $data->fee_mediator_percent ?? 10;

                $fee_mediator = ($margin_amount * $fee_mediator_percent) / 100;
            ?>

            <!-- FEE MEDIATOR -->
            <tr class="grand-total-row">

                <td colspan="4" class="text-right">
                    FEE MEDIATOR
                </td>

                <td class="text-right">
                    Rp <?= number_format($data->fee_mediator, 0, ',', '.') ?>
                </td>

            </tr>

            <!-- FEE MEDIATOR -->
            <tr class="grand-total-row">

                <td colspan="4" class="text-right">
                    CASHBACK
                </td>

                <td class="text-right">
                    Rp <?= number_format($data->cashback, 0, ',', '.') ?>
                </td>

            </tr>

            <?php if (($data->adjustment ?? 0) != 0) : ?>
                <!-- ADJUSTMENT -->
                <tr class="grand-total-row">

                    <td colspan="4" class="text-right">
                        ADJUSTMENT
                    </td>

                    <td class="text-right">
                        Rp -<?= number_format($data->adjustment, 0, ',', '.') ?>
                    </td>

                </tr>
            <?php endif; ?>

            <?php
                $total_final = ($data->received_by_eo ?? 0) + ($data->cashback ?? 0) + ($data->fee_mediator ?? 0) - ($data->adjustment ?? 0);
            ?>

            <!-- TOTAL FINAL -->
            <tr class="grand-total-row">

                <td colspan="4" class="text-right">
                    TOTAL FINAL
                </td>

                <td class="text-right">
                    Rp <?= number_format($total_final, 0, ',', '.') ?>
                </td>

            </tr>

            <?php
                $margin_amount = (($data->grand_total ?? 0) * ($data->margin ?? 0)) / 100;

                // $fee_mediator = $data->fee_mediator ?? 0;

                $net_profit_eo = $margin_amount - $fee_mediator;
            ?>

            <!-- NET PROFIT EO -->
            <!-- <tr class="grand-total-row">

                <td colspan="4" class="text-right">
                    NET PROFIT EO
                </td>

                <td class="text-right">
                    Rp <?= number_format($net_profit_eo, 0, ',', '.') ?>
                </td>

            </tr> -->

            <!-- PRICE PER PERSON -->
            <tr class="grand-total-row">

                <?php 
                    $harga_per_orang = ($data->received_by_eo + $data->cashback + $data->fee_mediator - $data->adjustment) / $data->number_of_participants;
                ?>

                <td colspan="4" class="text-right">
                    HARGA PERORANG
                </td>

                <td class="text-right">
                    Rp <?= number_format(
                        $harga_per_orang,
                        0,
                        ',',
                        '.'
                    ) ?>
                </td>

            </tr>

            <!-- SELLING PRICE -->
            <tr class="selling-price-row">

                <td colspan="4" class="text-right">
                    HARGA JUAL
                </td>

                <td class="text-right">
                    Rp <?= number_format($harga_per_orang + $data->rounding, 0, ',', '.') ?>
                </td>
            </tr>

        </tbody>

    </table>

<?php endif; ?>