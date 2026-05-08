<!-- Cost Structure PDF Template using mPDF -->
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
        margin: 20px 0;
        padding: 10px;
        background-color: #f0f0f0;
        border-radius: 4px;
    }

    .info-section {
        margin-bottom: 15px;
    }

    .info-row {
        display: flex;
        margin-bottom: 8px;
        font-size: 10px;
    }

    .info-label {
        width: 200px;
        font-weight: bold;
        color: #242d4a;
    }

    .info-value {
        flex: 1;
        color: #333;
    }

    .section-header {
        background-color: #28a745;
        color: white;
        padding: 10px;
        margin: 15px 0 10px 0;
        font-weight: bold;
        font-size: 12px;
        border-radius: 3px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    .items-table {
        font-size: 10px;
    }

    .items-table thead {
        background-color: #242d4a;
        color: white;
    }

    .items-table th {
        padding: 8px;
        text-align: left;
        font-weight: bold;
        border: 1px solid #ddd;
    }

    .items-table td {
        padding: 8px;
        border: 1px solid #ddd;
    }

    .items-table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .category-name {
        background-color: #28a745;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .item-row {
        background-color: white;
    }

    .amount-right {
        text-align: right;
        font-weight: bold;
    }

    .subtotal-row {
        background-color: #fff3cd;
        font-weight: bold;
        text-align: right;
    }

    .subtotal-label {
        text-align: right;
        font-weight: bold;
    }

    .calculation-section {
        margin-top: 20px;
        padding: 15px;
        background-color: #f0f7ff;
        border-left: 4px solid #0d6efd;
        border-radius: 3px;
    }

    .calc-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 10px;
    }

    .calc-label {
        font-weight: bold;
        color: #242d4a;
    }

    .calc-value {
        text-align: right;
        color: #333;
    }

    .selling-price-row {
        background-color: #ff6b35;
        color: white;
        padding: 10px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    .signature-section {
        margin-top: 30px;
        display: flex;
        justify-content: space-around;
    }

    .signature-box {
        width: 150px;
        text-align: center;
        font-size: 9px;
    }

    .signature-line {
        border-top: 1px solid #333;
        margin-top: 40px;
        padding-top: 5px;
        font-weight: bold;
        color: #242d4a;
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

<div class="header">
    <div class="company-info">
        <h1>PT SOBAT WISATA DUNIA</h1>
        <p>Kp. Tunggilis RT 001 RW 007, Situsari, Bogor</p>
        <p>Phone: 0812-8222-9700 | Website: www.sebelaswarna.com</p>
    </div>
</div>

<div class="title">
    <strong>COST STRUCTURE / EVENT QUOTATION</strong>
</div>

<!-- Basic Information -->
<div class="info-section">
    <div class="info-row">
        <div class="info-label">Company Name</div>
        <div class="info-value">: <?= $data->company_name ?? '-' ?></div>
    </div>
    <div class="info-row">
        <div class="info-label">Event Type</div>
        <div class="info-value">: <?= $data->event_type ?? '-' ?></div>
    </div>
    <div class="info-row">
        <div class="info-label">Number of Participants</div>
        <div class="info-value">: <?= $data->number_of_participants ?? '0' ?> Pax</div>
    </div>
    <div class="info-row">
        <div class="info-label">Generated Date</div>
        <div class="info-value">: <?= date('d-m-Y H:i:s') ?></div>
    </div>
</div>

<!-- Categories and Items -->
<?php if (!empty($data->categories)): ?>
    <div class="section-header">COST BREAKDOWN</div>
    
    <table class="items-table">
        <thead>
            <tr>
                <th width="30%">Description</th>
                <th width="15%">Qty</th>
                <th width="20%">Unit Price</th>
                <th width="20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data->categories as $category): ?>
                <!-- Category Header -->
                <tr>
                    <td colspan="4" class="category-name"><?= $category->name ?></td>
                </tr>
                
                <!-- Items -->
                <?php if (!empty($category->items)): ?>
                    <?php foreach ($category->items as $item): ?>
                        <tr class="item-row">
                            <td><?= $item->name ?></td>
                            <td style="text-align: center;"><?= $item->qty ?></td>
                            <td class="amount-right">Rp <?= number_format($item->price, 0, ',', '.') ?></td>
                            <td class="amount-right">Rp <?= number_format($item->subtotal, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <!-- Category Subtotal -->
                <tr>
                    <td colspan="3" class="subtotal-label"><?= $category->name ?> Subtotal</td>
                    <td class="amount-right" style="background-color: #fff3cd; font-weight: bold;">
                        Rp <?= number_format($category->subtotal, 0, ',', '.') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- Calculation Section -->
<div class="calculation-section">
    <div class="calc-row">
        <div class="calc-label">Grand Total (All Items)</div>
        <div class="calc-value">Rp <?= number_format($data->grand_total ?? 0, 0, ',', '.') ?></div>
    </div>
    
    <div class="calc-row">
        <div class="calc-label">Margin</div>
        <div class="calc-value"><?= $data->margin ?? 0 ?>%</div>
    </div>
    
    <div class="selling-price-row">
        <div>SELLING PRICE</div>
        <div>Rp <?= number_format($data->selling_price ?? 0, 0, ',', '.') ?></div>
    </div>
</div>

<!-- Signature Section -->
<div class="signature-section">
    <div class="signature-box">
        <div class="signature-line">
            _______________<br>
            Finance Manager
        </div>
    </div>
    
    <div class="signature-box">
        <div class="signature-line">
            _______________<br>
            Director
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer-text">
    <p>Document Generated by Sebelaswarna EO System - Confidential</p>
    <p>For inquiries, please contact: info@sebelaswarna.com</p>
</div>