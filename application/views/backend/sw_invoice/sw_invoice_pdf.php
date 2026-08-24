<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
    .title {
        text-align: center;
        font-weight: bold;
        font-size: 18px;
        margin: 20px 0;
        text-decoration: underline;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #000;
        padding: 6px;
        font-size: 11px;
    }
    .no-border td {
        border: none;
    }
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
    .bold {
        font-weight: bold;
    }
    .highlight {
        font-weight: bold;
    }
    .footer {
        margin-top: 40px;
    }
</style>

<table width="100%" style="border: none; border-collapse: collapse;">
    <tr>
        <td style="border: none;">No : <?= $letter_number; ?></td>
        <td style="text-align:right; border: none;"><?= date('l, d F Y', strtotime($letter_date)); ?></td>
    </tr>
</table>

<div class="title" style="margin-top: 5px"><i>INVOICE</i></div>

<table class="no-border">
    <tr>
        <td>Company: <b><?= $company_name ?></b></td>
        <td class="text-right">Type event: <?= $event_type ?></td>
    </tr>
</table>

<br>

<table>
    <thead>
        <tr>
            <th width="3%">No</th>
            <th width="50%">Remarks</th>
            <th width="19%">Unit Price (IDR)</th>
            <th width="9%">Qty</th>
            <th width="19%">Total (IDR)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $index => $item): ?>
            <tr>    
                <td class="text-center"><?= $index + 1 ?></td>
                <td><?= $item->remarks ?></td>
                <td class="text-right">Rp <?= number_format($item->unit_price, 0, ',', '.') ?></td>
                <td class="text-center"><?= $item->qty ?></td>
                <td class="text-right">Rp<?= number_format($item->total_price, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4" class="text-right bold">Total</td>
            <td class="text-right">Rp <?= number_format($total_amount, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td colspan="4" class="text-right bold">Pph 23 (2%)</td>
            <td class="text-right">Rp <?= number_format($total_amount * 0.02, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td colspan="4" class="text-right bold">Grand Total</td>
            <td class="text-right bold">Rp <?= number_format($total_amount - ($total_amount * 0.02), 0, ',', '.') ?></td>
        </tr>
    </tbody>
</table>

<br>

<div class="highlight">
    Payment is to be paid on : 
    <?= ($final_date == "0000-00-00 00:00:00" || empty($final_date)) 
            ? '-' 
            : date('l, d F Y', strtotime($final_date)) . ' Rp. ' . number_format($total_amount - ($total_amount * 0.02), 0, ',', '.') . ',-' ?>
</div>

<br>

<b>METHOD OF PAYMENT:</b>
<p>
Payment to be settle by Cash, TT, Credit Card or Company Cheque to be made payable to:
</p>

<table class="table">
    <tr style="background-color: #b4c6e7;">
        <th><i>Bank</i></th>
        <th><i>MANDIRI</i></th>
        <th><i>BCA</i></th>
    </tr>
    <tr>
        <td>Branch</td>
        <td>ITC Fatmawati</td>
        <td>Cibodas Tangerang</td>
    </tr>
    <tr>
        <td>Account No</td>
        <td>127-001-463-6029</td>
        <td>713-225-222-2</td>
    </tr>
    <tr>
        <td>Name</td>
        <td>PT. SOBAT WISATA DUNIA</td>
        <td>SOBAT WISATA DUNIA PT</td>
    </tr>
</table>

<br>

<p>
The EO is not responsible for any loss or damage to any goods, property or equipment brought in the hotel. Please 
note that this quotation does not confirm your booking. We will reserve your booking after receiving 50% of deposit. 
Please do not hesitate to contact us at +62 21 84311622 or 081282229700 for any information you may need. 
</p>
<p>
We would like to thank you and look forward to the opportunity to organize your event and assure you the best 
service and attention at all times. 
</p>

<div class="footer">
    <p>With warmest regards,</p>

    <img src="assets/backend/img/sw-signature-letter.png" 
         alt="Signature" 
         width="190" 
         style="margin-left: -20px">

    <div style="margin-left: 31px;">
        <b>Pristiani</b>
    </div>
    <div style="margin-left: 17px;">
        SebelasWarna
    </div>
</div>