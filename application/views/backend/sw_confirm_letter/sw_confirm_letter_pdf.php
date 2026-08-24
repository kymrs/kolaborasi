<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
    }
    .title {
        text-align: center;
        font-weight: bold;
        margin-top: 20px;
        margin-bottom: 20px;
        text-decoration: underline;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    .table th, .table td {
        border: 1px solid black;
        padding: 6px;
    }
    .center {
        text-align: center;
    }
    .right {
        text-align: right;
    }
    .bold {
        font-weight: bold;
    }
</style>

<div style="margin-top:20px;">
    <table width="100%" style="margin-bottom: 30px;">
        <tr>
            <td>No : <?= $letter_number; ?></td>
            <td style="text-align:right;"><?= date('l, d F Y', strtotime($letter_date)); ?></td>
        </tr>
    </table>
    <p class="bold"><i>Confirmation Letter from SebelasWarna Event Organizer</i></p>
    <p><b><?= $company_name; ?></b></p>
    <p>Thank you for selecting Us as The EO for your forthcoming event. We are delighted to confirm your booking as follow:</p>

    <h3 class="title"><i>CONFIRMATION LETTER</i></h3>
    <table width="100%">
        <tr>
            <td>Company</td>
            <td>: <b><?= $company_name; ?></b></td>
            <td>Type event</td>
            <td>: Employee</td>
        </tr>
        <tr>
            <td>Venue</td>
            <td>: <?= $venue; ?></td>
            <td>Set-up</td>
            <td>: To be advised</td>
        </tr>
        <tr> 
            <td><b>Start</b></td>
            <td><b>: <?= date('d F Y', strtotime($end_time)); ?></b></td>
            <td><b>Start</b></td>
            <td>: <b><?= $start_time ?></b></td>
        </tr>
        <tr>
            <td><b>End</b></td>
            <td><b>: <?= date('d F Y', strtotime($end_date)); ?></b></td>
            <td><b>Finish</b></td>
            <td><b>: <?= $end_time; ?></b></td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <th width="50%">Remarks</th>
            <th width="20%">Unit Price (IDR)</th>
            <th width="10%">Quantity</th>
            <th width="20%">Total (IDR)</th>
        </tr>
        <?php
            $id_package = 1; 
            foreach ($package as $row): 
        ?>
        <tr>
            <td>
                <b><?= $row->package_name ?></b><br>
                <br>
                <?= $id_package++ . '. ' . $row->item_name ?>
            </td>
            <td class="right"><b>Rp <?= number_format($row->unit_price, 0, ',', '.') ?></b></td>
            <td class="center"><b><?= $row->qty ?></b></td>
            <td class="right"><b>Rp <?= number_format($row->total_price, 0, ',', '.') ?></b></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4" class="bold">Additional :</td>
        </tr>
        <?php 
            $id_additional = 1;
            foreach ($additional as $row): 
        ?>
            <tr>
                <td><?= $id_additional++ . '. ' . $row->item_name ?></td>
                <td class="right"><b>Rp <?= number_format($row->unit_price, 0, ',', '.') ?></b></td>
                <td class="center"><b><?= $row->qty ?></b></td>
                <td class="right"><b>Rp <?= number_format($row->total_price, 0, ',', '.') ?></b></td>
            </tr>
            <?php endforeach; ?>
            
            <tr>
                <td colspan="3" class="right bold">TOTAL</td>
                <td class="right bold">Rp <?= number_format($total_amount, 0, ',', '.') ?></td>
            </tr>
    </table>
    <br>
    <p>
        <b>
            The Down Payment is to be paid on :
            <?=
            ($dp_date == "0000-00-00 00:00:00" || empty($dp_date))
                ? '-'
                : date('l, d F Y', strtotime($dp_date)) .
                    ' Rp. ' .
                    number_format(($total_amount * $dp_percent) / 100, 0, ',', '.') .
                    ',-'
            ?>
        </b>
    </p>

    <p>
        <b>
            The Final Payment is to be paid on :
            <?=
            ($final_date == "0000-00-00 00:00:00" || empty($final_date))
                ? '-'
                : date('l, d F Y', strtotime($final_date)) .
                    ' Rp. ' .
                    number_format(($total_amount * $final_percent) / 100, 0, ',', '.') .
                    ',-'
            ?>
        </b>
    </p>
</div>
<pagebreak />

<!-- HALAMAN 2 -->
<div>

<style>
    .section-title {
        font-weight: bold;
        margin-top: 15px;
        text-decoration: underline;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    .table th, .table td {
        border: 1px solid #000;
        padding: 5px;
        font-size: 11px;
    }
    .center {
        text-align: center;
    }

    ol li {
        font-weight: bold;
    }
</style>

<p class="bold">METHOD OF PAYMENT:</p>
<p>
Payment to be settle by Cash, TT, Credit Card or Company Cheque to be made payable to :
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

<p class="center bold section-title" style="margin-top: 20px; margin-bottom: 10px;">TERM & CONDITIONS</p>

<p class="bold">CONFIRMATION & GUARANTEED ATTENDANCE</p>
<p style="text-indent: 30px; text-align:justify">All bookings are considered tentative until the present document is signed and endorsed with your company's stamp and a deposit (as per quotation) is paid.</p>
<p style="text-indent: 30px; text-align:justify">Upon receip of your deposit and this agreement signed by you, we will consider the agreement confirmed and definite.The EO reserves the right to release the tentative booking if no confirmation have been received on the deposit due date TBA Modification of the number of participants to be advise at least 7 (seven) days prior to the function, the billing will be based on the guaranteed attendance. However, should the actual attendance exceed that of the guaranteed, billing will be revised accordingly to the actual attendance.</p>

<p class="bold">POSTPONEMENT AND CANCELLATION</p>
<ol>
    <li>A post ponement fee of 25% of the total event cost of the intented function will be lieved should you postponed your function less than 21 days prior the intended date.</li>
    <li>Cancellation less than 21 days prior to the intended event date, 25% of the total event cost will be charged</li>
    <li>Cancellation less than 14 days prior to the intended event date, 50% of the total event cost will be charged</li>
</ol>

<p class="bold">OTHER TERMS & CONDITIONS</p>
<p style="text-indent: 20px;">The EO is not responsible for any loss or damage to any goods, property or equipment brought in the hotel. Please note that this quotation does not confirm your booking. We will reserve your booking after receiving 50% of deposit. Please do not hesitate to contact us at +62 21 84311622 or 081282229700 for any information you may need.</p>
<p style="text-indent: 20px;">We would like to thank you and look forward to the opportunity to organize your event and assure you the best service and attention at all times.</p>
<br>

<table>
    <tr>
        <td>With warmest regards,</td>
    </tr>
    <tr>
        <td><img src="assets/backend/img/sw-signature-letter.png" alt="Signature" width="190" style="margin-left: -20px; margin-top: 8px"></td>
    </tr>
    <tr>
        <td style="text-align: left; padding-left: 43px"><b>Pristiani</b></td>
    </tr>
    <tr>
        <td>SebelasWarna Head Unit</td>
    </tr>
</table>
</div>