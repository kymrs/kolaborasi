<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
<style>
    .ck.ck-content[role='textbox'] {
        font-size: 13px;
        line-height: 1.5;
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('sml_kertas_kerja') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">

<div class="row" id="marketing">

    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">No. Dokumen</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="no_dok" name="no_dok" placeholder="Otomatis / Input Manual">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Periode</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="periode" name="periode" placeholder="Contoh: Jan-2024">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tanggal Input</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tanggal" name="tanggal">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Konsumen</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="konsumen" name="konsumen" placeholder="Nama Customer">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Project</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="project" name="project" placeholder="Nama Project">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">No. SPK/DO</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="no_spk_do" name="no_spk_do" placeholder="Nomor Referensi">
            </div>
        </div>
        
        <hr class="my-4" style="border-top: 1px dashed #ccc;">

        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Origin (Asal)</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="origin" name="origin" placeholder="Kota Asal">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Destinasi (Tujuan)</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="destinasi" name="destinasi" placeholder="Kota Tujuan">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Wilayah</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="wilayah" name="wilayah">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Jenis Unit</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="jenis_unit" name="jenis_unit" placeholder="CDE / CDD / Fuso">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Service</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="service" name="service" placeholder="Charter / Reguler">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tipe Kiriman</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="tipe_kiriman" name="tipe_kiriman">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tgl. Muat</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_muat" name="tgl_muat">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Qty</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="qty" name="qty" placeholder="0">
            </div>
        </div>

        <div class="p-2 mb-3 mt-3 rounded" style="background-color: #f8fbff; border: 1px solid #e9ecef;">
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label font-weight-bold text-primary">Selling Base</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="selling" name="selling">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label small text-muted">Multidrop Selling</label>
                <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="multidrop_selling" name="multidrop_selling">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label small text-muted">TKBM Selling</label>
                <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="tkbm_selling" name="tkbm_selling">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-sm-4 col-form-label small text-muted">Inap Selling</label>
                <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="inap_selling" name="inap_selling">
                    </div>
                </div>
            </div>
        </div>

        <div class="p-2 mb-2 rounded" style="background-color: #fffbfb; border: 1px solid #e9ecef;">
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label font-weight-bold text-danger">Buying / UJ</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="buying_uj" name="buying_uj">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label small text-muted">Multidrop Buying</label>
                <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="multidrop_buying" name="multidrop_buying">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label small text-muted">TKBM Buying</label>
                <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="tkbm_buying" name="tkbm_buying">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-sm-4 col-form-label small text-muted">Inap Buying</label>
                <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="inap_buying" name="inap_buying">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4 mb-4">
        <div class="form-group row p-3 rounded" style="background-color: #e9ecef;">
            <label class="col-sm-2 col-form-label font-weight-bold text-dark">TOTAL MARGIN</label>
            <div class="col-sm-10">
                <div class="input-group input-group-lg">
                    <div class="input-group-prepend"><span class="input-group-text font-weight-bold">Rp</span></div>
                    <input type="number" class="form-control font-weight-bold text-success" id="margin" name="margin" readonly placeholder="Auto Calculated">
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row" id="plotting">

    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">ID Asset</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="id_asset" name="id_asset" placeholder="Kode Asset">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">No. Polisi (Nopol)</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="nopol" name="nopol" placeholder="B 1234 XYZ">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tipe Unit</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="tipe_unit" name="tipe_unit" placeholder="Contoh: Wingbox / CDE">
            </div>
        </div>

        <hr class="my-3" style="border-top: 1px dashed #ccc;">

        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tgl. STNK</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_stnk" name="tgl_stnk">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tgl. KEUR</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_keur" name="tgl_keur">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Nama Driver 1</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="driver1" name="driver1">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">No. HP Driver 1</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="no_hp1" name="no_hp1" placeholder="0812...">
            </div>
        </div>

        <hr class="my-2">

        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Nama Driver 2</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="driver2" name="driver2" placeholder="(Opsional)">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">No. HP Driver 2</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="no_hp2" name="no_hp2" placeholder="0812...">
            </div>
        </div>

        <hr class="my-3" style="border-top: 1px dashed #ccc;">

        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tgl. Berangkat</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_berangkat" name="tgl_berangkat">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">SLA (Hari)</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="sla" name="sla" placeholder="Estimasi Lama Jalan">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Est. Tgl. Tujuan</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="est_tgl_tujuan" name="est_tgl_tujuan">
            </div>
        </div>
    </div>

</div>

<div class="row" id="monitoring">

    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tgl. Muat (Mon)</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_muat_monitoring" name="tgl_muat_monitoring">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Waktu Muat</label>
            <div class="col-sm-8">
                <input type="time" class="form-control" id="waktu_muat" name="waktu_muat">
            </div>
        </div>

        <hr class="my-3" style="border-top: 1px dashed #ccc;">

        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Lokasi Bongkar</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="lokasi_bongkar" name="lokasi_bongkar" placeholder="Nama Gudang / Area">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tgl. Bongkar</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_bongkar" name="tgl_bongkar">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Waktu Bongkar</label>
            <div class="col-sm-8">
                <input type="time" class="form-control" id="waktu_bongkar" name="waktu_bongkar">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Aktual Jam</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="aktual_jam" name="aktual_jam" placeholder="Durasi / Realisasi">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        
        <div class="p-2 mb-3 rounded" style="background-color: #f8fbff; border: 1px solid #e9ecef;">
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label font-weight-bold text-primary">Uang Jalan</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="uang_jalan" name="uang_jalan">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label font-weight-bold text-success">Uang Balikan</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="uang_balikan" name="uang_balikan">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-sm-4 col-form-label font-weight-bold text-dark">Tgl. Transfer</label>
                <div class="col-sm-8">
                    <input type="date" class="form-control" id="tgl_transfer" name="tgl_transfer">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tgl. Masuk Pool</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_masuk_pool" name="tgl_masuk_pool">
            </div>
        </div>
        
        <hr class="my-2">

        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tgl. Kirim SJ</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_kirim_sj" name="tgl_kirim_sj">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">No. Resi Kirim</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="no_resi_kirim" name="no_resi_kirim" placeholder="Nomor Resi Ekspedisi">
            </div>
        </div>
    </div>

</div>

                        <div class="row" id="finance">

    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">No. Invoice</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="no_invoice" name="no_invoice" placeholder="INV/2024/...">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tgl. Invoice</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_invoice" name="tgl_invoice">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Tgl. Kirim Inv</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_kirim_inv" name="tgl_kirim_inv" title="Tanggal Invoice dikirim ke Customer">
            </div>
        </div>
        
        <hr class="my-3" style="border-top: 1px dashed #ccc;">

        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Customer</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="customer" name="customer">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Jatuh Tempo</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="jatuh_tempo" name="jatuh_tempo">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label text-muted">Status Bayar</label>
            <div class="col-sm-8">
                <select class="form-control" id="status_pembayaran" name="status_pembayaran">
                    <option value="">- Pilih Status -</option>
                    <option value="Unpaid">Unpaid (Belum Bayar)</option>
                    <option value="Partial">Partial (Cicil)</option>
                    <option value="Paid">Paid (Lunas)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        
        <div class="p-3 mb-3 rounded" style="background-color: #f8fbff; border: 1px solid #e9ecef;">
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label text-muted">Nominal Dasar</label>
                <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="nominal" name="nominal">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label text-muted small">PPh 2%</label>
                <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="pph_2" name="pph_2">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label text-muted small">PPN 11%</label>
                <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="ppn_11" name="ppn_11">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-sm-4 col-form-label font-weight-bold text-primary">Total Tagihan</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-primary text-white">Rp</span></div>
                        <input type="number" class="form-control font-weight-bold" id="total_tagihan" name="total_tagihan">
                    </div>
                </div>
            </div>
        </div>

        <div class="p-3 rounded" style="background-color: #fffbfb; border: 1px solid #e9ecef;">
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label text-muted">Tgl. Bayar</label>
                <div class="col-sm-8">
                    <input type="date" class="form-control" id="tgl_bayar" name="tgl_bayar">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-sm-4 col-form-label font-weight-bold text-success">Nominal Bayar</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control" id="nominal_pembayaran" name="nominal_pembayaran">
                    </div>
                </div>
            </div>
            
            <hr class="my-2">
            
            <div class="form-group row mb-0">
                <label class="col-sm-4 col-form-label font-weight-bold text-danger">Selisih (Sisa)</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-white">Rp</span></div>
                        <input type="number" class="form-control font-weight-bold text-danger" id="selisih_pembayaran" name="selisih_pembayaran" readonly placeholder="Auto Calculated">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <button type="submit" class="btn btn-primary btn-sm aksi"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.0/"
        }
    }
</script>

<script type="module">
    var aksi = $('#aksi').val();

    import {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Alignment,
        Font,
        Paragraph,
        Indent,
        IndentBlock,
    } from 'ckeditor5';

    ClassicEditor
        .create(document.querySelector('#peserta'), {
            plugins: [Essentials, Bold, Italic, Alignment, Font, Paragraph, Indent, IndentBlock],
            toolbar: {
                items: [
                    'undo', 'redo', '|', 'bold', 'italic', '|', 'alignment', '|',
                    'outdent', 'indent', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                ],
            },
        })
        .then(editor => {
            if (aksi == "read") {
                editor.enableReadOnlyMode("peserta");
                console.log(editor);
            }
        }).catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#konten'), {
            plugins: [Essentials, Bold, Italic, Alignment, Font, Paragraph, Indent, IndentBlock],
            toolbar: {
                items: [
                    'undo', 'redo', '|', 'bold', 'italic', '|', 'alignment', '|',
                    'outdent', 'indent', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                ],
            },
        })
        .then(editor => {
            if (aksi == "read") {
                editor.enableReadOnlyMode("konten");
                console.log(editor);
            }
        }).catch(error => {
            console.error(error);
        });
</script>

<script>
    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('#no_dok').val(kode).attr('readonly', true);
            $('#lihat_foto').hide();
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $('#lihat_foto').hide();
            $.ajax({
                url: "<?php echo site_url('sml_kertas_kerja/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id')
                    $('#id').val(data.id);
                    $('#no_dok').val(data.no_dok).attr('readonly', true);
                    $('#agenda').val(data.agenda);
                    $('#date').val(moment(data.date).format('DD-MM-YYYY'));
                    $('#start_time').val(data.start_time);
                    $('#end_time').val(data.end_time);
                    $('#lokasi').val(data.lokasi);
                    $('#peserta').val(data.peserta);
                    $("#foto").prop('required', false);
                    $('#foto_label').text(data.foto);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        if (aksi == "read") {
            $('.aksi').hide();
            $('#lihat_foto').show();
            $('#id').prop('readonly', true);
            $('#no_dok').prop('readonly', true);
            $('#agenda').prop('readonly', true);
            $('#date').prop('disabled', true);
            $('#start_time').prop('readonly', true);
            $('#end_time').prop('readonly', true);
            $('#lokasi').prop('readonly', true);
            $('#foto').prop('disabled', true);
        }


        $("#form").submit(function(e) {
            e.preventDefault();
            var url;
            if (id == 0) {
                url = "<?php echo site_url('sml_kertas_kerja/add') ?>";
            } else {
                url = "<?php echo site_url('sml_kertas_kerja/update') ?>";
            }

            if ($('#peserta').val() == "" || $('#konten').val() == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oopss...',
                    text: 'Peserta atau konten harus diisi!!!',
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: false,
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status) //if success close modal and reload ajax table
                        {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Your data has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result) => {
                                location.href = "<?= base_url('sml_kertas_kerja') ?>";
                            })
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    }
                });
            }
        });
    })

    $('#date').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date()
    });

    $('#st').datetimepicker({
        format: 'HH:mm',
    });

    $('#et').datetimepicker({
        format: 'HH:mm',
    });

    function cek1() {
        start = $('#start_time').val();
        end = $('#end_time').val();
        hours = end.split(':')[0] - start.split(':')[0];
        minutes = end.split(':')[1] - start.split(':')[1];

        minutes = minutes.toString().length < 2 ? '0' + minutes : minutes;
        if (minutes < 0) {
            hours--;
            minutes = 60 + minutes;
        }
        hours = hours.toString().length < 2 ? '0' + hours : hours;

        if ($('#end_time').val() != "") {
            if (hours < 0) {
                Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    title: 'Jam Mulai tidak boleh lebih dari Jam Selesai',
                    showConfirmButton: false,
                    timer: 2000
                });
                $('#start_time').val('');
            }
        }
    }

    function cek2() {
        start = $('#start_time').val();
        end = $('#end_time').val();
        hours = end.split(':')[0] - start.split(':')[0];
        minutes = end.split(':')[1] - start.split(':')[1];

        minutes = minutes.toString().length < 2 ? '0' + minutes : minutes;
        if (minutes < 0) {
            hours--;
            minutes = 60 + minutes;
        }
        hours = hours.toString().length < 2 ? '0' + hours : hours;

        if (hours < 0) {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'Jam Selesai tidak boleh kurang dari Jam Mulai',
                showConfirmButton: false,
                timer: 2000
            });
            $('#end_time').val('');
        }
    }

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        var filePath = $(this).val();
        var allowedExtensions =
            /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
        const size =
            (this.files[0].size / 1024 / 1024).toFixed(2);

        if (!allowedExtensions.exec(filePath)) {
            Swal.fire({
                icon: 'error',
                title: 'Sorry',
                text: 'Invalid file type',
            })
            $(this).val("");
            $(this).siblings(".custom-file-label").addClass("selected").html("Choose file");
            return false;
        }

        if (size > 3) {
            Swal.fire({
                icon: 'error',
                title: 'Sorry',
                text: 'File size must be under 3 MB',
            });
            $(this).val("");
            $(this).siblings(".custom-file-label").addClass("selected").html("Choose file");
        } else {
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        }
    });
</script>