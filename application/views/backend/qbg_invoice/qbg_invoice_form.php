<style>
    .btn-special {
        transform: translateY(-6px);
        /* background: hsl(134deg 61% 41%); */
        border: none;
        border-radius: 12px;
        padding: 0;
        cursor: pointer;
    }

    .front {
        will-change: transform;
        transition: transform 250ms;
        display: block;
        padding: 8px 20px;
        border-radius: 12px;
        font-size: 12px;
        /* background-color: green; */
        color: white;
        transform: translateY(-4px);
    }

    .front-add {
        background-color: #242D4A;
    }

    .front-aksi {
        background-color: #242D4A;
    }


    .btn-special:focus:not(:focus-visible) {
        outline: none;
    }

    .btn-special:active .front {
        transform: translateY(-2px);
    }

    #rekening {
        width: 100%;
    }

    .rekening-text {
        margin-bottom: -2px;
    }

    /* Mengubah gaya dropdown */
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #D1D3E2;
        border-radius: 4px;
        font-size: 14px;
        padding: 5px;
        height: 38px;
        line-height: 38px;
    }

    /* Mengubah warna teks dalam opsi dropdown */
    .select2-container--default .select2-results__option {
        color: #777;
    }

    /* Mengubah posisi ikon panah */
    .select2-selection__arrow {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(20%);
    }

    .colomn-kanan {
        height: 20px;
    }

    .table-produk {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .table-produk td {
        border: 1px solid rgba(26, 32, 53, 0.1);
    }

    .table-produk tbody tr {
        transition: 200ms;
    }

    .table-produk tbody tr:hover {
        background-color: rgba(234, 236, 244, 0.5);
    }

    .table-produk thead {
        background-color: rgb(36, 44, 73);
        color: white;
    }

    .table-produk thead th {
        border: 1px solid rgb(255, 255, 255, 0.2);
        font-weight: 400;
        text-align: center;
    }

    .table-produk tbody tr td:nth-child(4) {
        width: 240px;
    }

    .btn-style {
        background-color: rgb(36, 44, 73);
        color: white;
        border: none;
        padding: 7px 23px;
        font-size: 12px;
        border-radius: 5px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.15), -4px 4px 6px rgba(0, 0, 0, 0.15), 4px 4px 6px rgba(0, 0, 0, 0.15);
        /* Bayangan bawah dan kiri-kanan */
        cursor: pointer;
        transition: all 0.055s ease;
        margin-bottom: 5px;
        position: relative;
        bottom: -2px;
    }

    .btn-style:hover {
        scale: 1.020;
    }

    .btn-style:active {
        transform: translateY(2px);
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1), -2px 2px 6px rgba(0, 0, 0, 0.1), 2px 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-delete {
        background-color: #DC0808;
        position: relative;
        top: 1px;
    }

    /* Table Produk Modal Style */
    #produkModal table tbody tr {
        transition: 250ms;
    }


    #produkModal table tbody tr:hover {
        background-color: rgba(49, 55, 78, 0.94);
        color: white;
        scale: 0.990;
        cursor: pointer;
    }

    #produkModal table tbody tr:active {
        background-color: rgb(36, 44, 73);
        scale: 0.980;
    }

    @media (min-width: 768px) {

        .tujuan-field,
        .prepayment-field {
            margin-left: 15px;
        }
    }

    @media (max-width: 768px) {

        .table-transaksi {
            overflow-x: scroll;
            background-color: #fff;
        }

        .colomn-kanan {
            height: auto;
        }

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
                    <a class="btn btn-primary btn-sm" href="<?= base_url('qbg_invoice') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tanggal Invoice</label>
                                    <div class="col-sm-8">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_invoice" id="tgl_invoice" placeholder="DD-MM-YYYY" autocomplete="off" readonly style="cursor: pointer; background-color: #fff">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Kode Invoice</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="kode_invoice" name="kode_invoice" readonly placeholder="Kode Invoice" style="cursor: not-allowed;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tanggal Jatuh Tempo</label>
                                    <div class="col-sm-8">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_tempo" id="tgl_tempo" placeholder="DD-MM-YYYY" autocomplete="off" readonly style="cursor: pointer; background-color: #fff">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nama Customer</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nama_customer" name="nama_customer" placeholder="Nama Customer">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nomor Customer</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nomor_customer" name="nomor_customer" placeholder="Nomor Customer">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Email Customer</label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control" id="email_customer" name="email_customer" placeholder="Email Customer">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Alamat Customer</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="alamat_customer" name="alamat_customer" rows="3" placeholder="Alamat Customer"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6 colomn-kanan">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Harga Produk</label>
                                    <div class="col-sm-7">
                                        <select name="jenis_harga" id="jenis_harga" class="form-control" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Harga</option>
                                            <option value="qubagift">QubaGift</option>
                                            <option value="reseller">Reseller</option>
                                            <option value="distributor">Distributor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Biaya Pengiriman</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="ongkir" name="ongkir" placeholder="Biaya Pengiriman">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Potongan Harga</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="potongan_harga" name="potongan_harga" placeholder="Potongan Harga">
                                        <input type="hidden" class="form-control" id="total_akhir" name="total_akhir">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">No Rekening</label>
                                    <div class="col-sm-7">
                                        <div class="input-group mb-3">
                                            <!-- RADIO BUTTON UNTUK PEMILIHAN INPUTAN REKENING -->
                                            <div class="form-check form-check-inline" style="margin-bottom: 5px;">
                                                <input class="form-check-input" type="radio" name="radioNoLabel" id="exist" value="" aria-label="..." checked><label for="exist" style="margin-right: 14px; margin-top: 8px; cursor: pointer">Rekening terdaftar</label>
                                                <input class="form-check-input" type="radio" name="radioNoLabel" id="new" value="" aria-label="..."><label for="new" style="margin-top: 8px; cursor: pointer">Rekening baru</label>
                                            </div>
                                            <select class="js-example-basic-single" id="rekening" name="rekening">
                                                <option value="" selected disabled>Pilih rekening tujuan</option>
                                                <?php foreach ($rek_options as $option) { ?>
                                                    <option data-bank="<?= $option->nama_bank ?>" data-rek="<?= $option->no_rek ?>" value=""><?= $option->nama_bank . '-' . $option->no_rek ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group rekening-text">
                                                <input type="text" class="form-control col-sm-4" style="font-size: 13px;" id="nama_bank" name="nama_bank" placeholder="Nama Bank">&nbsp;
                                                <span class="py-2">-</span>&nbsp;
                                                <input type="text" class="form-control col-sm-6" style="font-size: 13px;" id="nomor_rekening" name="nomor_rekening" placeholder="No Rekening">
                                                <span class="py-2"></span>&nbsp;
                                                <button type="button" class="btn-primary" id="btn-rek" style="height: 33.5px; width: 40px"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <table id="rek-table" class=" table table-bordered">
                                            <thead>
                                                <th>No</th>
                                                <th class="col-sm-4">Nama Bank</th>
                                                <th class="col-sm-8">No Rekening</th>
                                                <th>Delete</th>
                                            </thead>
                                            <tbody class="tbody-rekening">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BUTTON TAMBAH FORM -->
                        <div class="mt-4">
                            <button disabled type="button" class="btn-special btn-sm" id="add-row" style="background-color:rgb(53, 65, 107);"><span class="front front-add"><i class="fa fa-plus" aria-hidden="true"></i> Add</span></button>
                        </div>
                        <!-- TABLE INPUT -->
                        <div class="mt-3 mb-3" style="overflow-x: scroll;">
                            <table class="table table-bordered table-hover table-produk" id="table-transaksi">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">No</th>
                                        <th scope="col">Produk</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Total</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="input-container">
                                    <!-- CONTAINER INPUTAN -->
                                </tbody>
                            </table>
                        </div>

                        <!-- KETERANGAN -->
                        <div class="mt-3 mb-3 row">
                            <!-- Layanan Termasuk -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Catatan:</label>
                                <div id="catatan" name="catatan" class="border p-2" style="height: 200px;"></div>
                                <input type="hidden" name="catatan_item" id="catatan_item">
                            </div>
                        </div>

                        <!-- Loading indicator -->
                        <div id="loading" style="display: none;">
                            <p>Loading...</p>
                        </div>

                        <!-- Modal Data Table Produk -->
                        <div class="modal fade" id="produkModal" tabindex="-1" aria-labelledby="produkModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="produkModalLabel">Pilih Data Produk</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span style="position: relative; bottom: 5px" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table id="produk-table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Produk</th>
                                                    <th>Nama Produk</th>
                                                    <th>Stok Akhir</th>
                                                    <th>Harga Qubagift</th>
                                                    <th>Harga Reseller</th>
                                                    <th>Harga Distributor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Produk</th>
                                                    <th>Nama Produk</th>
                                                    <th>Stok Akhir</th>
                                                    <th>Harga Qubagift</th>
                                                    <th>Harga Reseller</th>
                                                    <th>Harga Distributor</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PENENTUAN UPDATE ATAU ADD -->
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="">
                            <button type="submit" class="btn-special btn-sm aksi" style="background-color: #242D4A;" disabled></button>
                        <?php } else { ?>
                            <button type="submit" class="btn-special btn-sm aksi" style="background-color: #242D4A;"></button>
                        <?php } ?>
                        <!-- END PENENTUAN UPDATE ATAU ADD -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'], // toggled buttons
        ['blockquote', 'code-block'],
        ['link', 'image', 'video', 'formula'],

        [{
            'header': 1
        }, {
            'header': 2
        }], // custom button values
        [{
            'list': 'ordered'
        }, {
            'list': 'bullet'
        }, {
            'list': 'check'
        }],
        [{
            'script': 'sub'
        }, {
            'script': 'super'
        }], // superscript/subscript
        [{
            'indent': '-1'
        }, {
            'indent': '+1'
        }], // outdent/indent
        [{
            'direction': 'rtl'
        }], // text direction

        [{
            'size': ['small', false, 'large', 'huge']
        }], // custom dropdown
        [{
            'header': [1, 2, 3, 4, 5, 6, false]
        }],

        [{
            'color': []
        }, {
            'background': []
        }], // dropdown with defaults from theme
        [{
            'font': []
        }],
        [{
            'align': []
        }],

        ['clean']
    ];

    // untuk menghilangkan data produk pada saat data di klik / dipilih
    $('#produkModal table tbody').on('click', 'tr', function() {
        $(this).fadeOut(function() {
            $(this).remove(); // Setelah fadeOut, hapus elemen tr
        });
    });


    $(document).ready(function() {

        $('#jenis_harga').on('change', function() {
            if ($(this).val().trim() !== '') {
                $('#add-row').prop('disabled', false); // Menghilangkan disabled
                $(this).css({
                    'background-color': '#EAECF4',
                    'pointer-events': 'none'
                });
            } else {
                $('#add-row').prop('disabled', true); // Mengaktifkan kembali disabled jika belum dipilih
            }
        });
    });



    // untuk menghitung seluruh total produk, dan disimpan ke input total akhir
    function calculateTotalNominal() {
        let total = 0;
        $('.total').each(function() {
            let value = $(this).val().replace(/[^\d]/g, ''); // Buang karakter selain angka
            value = parseInt(value) || 0; // Konversi ke integer
            total += value;
        });

        $('#total_akhir').val(total); // Format ke rupiah (ribuan)
    }

    // untuk memastikan inputan email customer dan jumlah tidak bisa mengetikan selain angka
    $(document).on('input', '#nomor_customer, .jumlah', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // agar inputan ongkir dan potongan harga menjadi format rupiah
    $(document).on('input', '#ongkir, #potongan_harga', function() {
        let value = this.value.replace(/[^0-9]/g, ''); // Hanya angka
        if (value) {
            this.value = formatRupiah(value, 'Rp. ');
        } else {
            this.value = '';
        }
    });

    function formatRupiah(angka, prefix) {
        let numberString = angka.replace(/[^,\d]/g, '').toString(),
            split = numberString.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return prefix + rupiah;
    }


    // Variabel untuk menyimpan rowCount
    var currentRowCount;

    // Event listener untuk tombol modal produk
    $(document).on('click', '[id^=produk-modal]', function() {
        currentRowCount = $(this).data('id');
    });

    // Data table produk

    // METHOD POST MENAMPILKAN DATA KE DATA TABLE
    $(document).ready(function() {
        var table = $('#produk-table').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?php echo site_url('qbg_invoice/get_list2') ?>",
                type: "POST"
            },
            columnDefs: [{
                    targets: [],
                    className: 'dt-head-nowrap'
                },
                {
                    targets: [],
                    className: 'dt-body-nowrap'
                },
                {
                    targets: [0, 1, 2, 3, 4, 5, 6],
                    orderable: false
                }
            ],
            language: {
                emptyTable: "Belum ada data produk tersedia."
            }
        });


        // Variabel untuk menyimpan rowCount
        var currentRowCount;

        // Event listener untuk tombol modal deklarasi
        $(document).on('click', '[id^=produk-modal]', function() {
            currentRowCount = $(this).data('id');
        });

        // Event listener untuk baris tabel dalam modal
        $('#produk-table tbody').on('click', 'tr', function() {
            console.log(currentRowCount)
            let data = table.row(this).data();

            // jika stok tidak kosong jalankan, jika kosong, tampilkan swal
            if (data[3] != 0) {

                $('#produk-modal' + currentRowCount).text(`${data[2]} (${data[3]})`);

                $('#kode_produk' + currentRowCount).val(data[1]);

                if ($('#kode_produk' + currentRowCount).val() !== '') {
                    $('#jumlah' + currentRowCount).prop('readonly', false).attr({
                        'data-stok': data[3],
                        'data-produk': data[2]
                    });

                    // Set initial harga berdasarkan pilihan awal
                    var selectedOption = $('#jenis_harga').val();
                    var harga;

                    if (selectedOption === 'qubagift') {
                        harga = data[4]; // Ganti dengan data yang sesuai
                    } else if (selectedOption === 'reseller') {
                        harga = data[5]; // Ganti dengan data yang sesuai
                    } else if (selectedOption === 'distributor') {
                        harga = data[6]; // Ganti dengan data yang sesuai
                    }

                    // Set harga ke input dengan ID yang sesuai saat halaman pertama kali dimuat
                    $('#harga' + currentRowCount).val(harga);

                    // Update harga saat opsi dropdown berubah
                    $('#jenis_harga').on('change', function() {
                        selectedOption = $(this).val();

                        if (selectedOption === 'qubagift') {
                            harga = data[4];
                        } else if (selectedOption === 'reseller') {
                            harga = data[5];
                        } else if (selectedOption === 'distributor') {
                            harga = data[6];
                        }

                        $('#harga' + currentRowCount).val(harga);
                    });
                }
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: `Stok Habis`,
                    text: `Untuk Produk : ${data[2]}`, // Tampilkan semua pesan
                    confirmButtonColor: '#242c49',
                    confirmButtonText: 'OK'
                });
            }
            // Tutup modal setelah data dipilih
            $('#produkModal').modal('hide');
        });
    });

    // Untuk memastikan value dari jumlah tidak lebih dari stok akhir
    $('#table-transaksi').on('click select', 'input.jumlah', function() {
        // Ambil data-id dari input yang sedang diklik
        let dataId = $(this).data('id');

        $(`#jumlah${dataId}`).on('input', function() {
            let maxVal = $(this).data('stok'); // Batas maksimal angka
            let jumlah = parseFloat($(this).val()) || 0;
            let produk = $(this).data('produk'); // Batas maksimal angka

            if (jumlah > maxVal) {
                $(this).val(maxVal);
                jumlah = maxVal;

                Swal.fire({
                    icon: 'warning',
                    title: `Stok Hanya Tersedia : ${maxVal}`,
                    text: `Untuk Produk : ${produk}`, // Tampilkan semua pesan
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#242c49'
                });

            } else if (jumlah == 0) {
                jumlah = 1;
            }

            // Bersihkan harga dari karakter non-angka atau titik
            let harga = $('#harga' + dataId).val().replace(/[^0-9]/g, '') || 0;

            // Hitung total: harga × jumlah
            let total = harga * jumlah;

            // Format total menjadi dua angka desimal dengan tanda ribuan
            $('#total' + dataId).val('Rp. ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
            calculateTotalNominal()
        });
    });

    const quill = new Quill('#catatan', {
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Catatan...',
        theme: 'snow',
    });

    document.getElementById("form").onsubmit = function() {
        // Get HTML content from Quill editor
        var catatanItem = quill.root.innerHTML;
        // Set it to hidden input
        document.getElementById("catatan_item").value = catatanItem;
    };

    $('#tgl_invoice').datepicker({
        dateFormat: 'dd-mm-yy',
        // minDate: new Date(),
        // maxDate: new Date(),

        // MENGENERATE KODE INVOICE SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $('#tgl_invoice').removeClass("is-invalid");

            // Menghapus label error secara manual jika ada
            if ($("#tgl_invoice-error").length) {
                $("#tgl_invoice-error").remove(); // Menghapus label error
            }
            $.ajax({
                url: "<?php echo site_url('qbg_invoice/generate_kode') ?>",
                type: "POST",
                data: {
                    "date": dateText
                },
                dataType: "JSON",
                success: function(data) {
                    $('#kode_invoice').val(data.toUpperCase());
                    $('#kode').val(data);
                },
                error: function(error) {
                    alert("error" + error);
                }
            });
        }
    });

    $(document).ready(function() {
        // Event ketika tanggal diubah
        $('#tgl_invoice, #tgl_tempo').on('change', function() {
            let tglInvoice = $('#tgl_invoice').val(); // Ambil nilai tanggal invoice
            let tglTempo = $('#tgl_tempo').val(); // Ambil nilai tanggal tempo

            if (tglInvoice && tglTempo) { // Cek jika kedua tanggal sudah terisi
                // Format tanggal ke objek Date
                let dateInvoice = moment(tglInvoice, "DD-MM-YYYY");
                let dateTempo = moment(tglTempo, "DD-MM-YYYY");

                if (dateTempo.isBefore(dateInvoice)) { // Cek apakah tanggal tempo sebelum tanggal invoice
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Tanggal tempo tidak boleh mundur dari tanggal invoice!',
                        confirmButtonColor: '#242c49'
                    });

                    // Reset input tanggal
                    $('#tgl_invoice').val('');
                    $('#tgl_tempo').val('');
                }
            }
        });
    });

    $('#tgl_tempo').datepicker({
        dateFormat: 'dd-mm-yy',
        // minDate: new Date(),
        // maxDate: new Date(),
    });

    $(document).ready(function() {

        // INISIASI VARIABEL JAVASCRIPT/JQUERY
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        let inputCount = 0;
        let deletedRows = [];
        let deletedRekRows = [];

        $('.js-example-basic-single').select2();

        // Fungsi untuk mengatur enabled/disabled elemen berdasarkan radio button yang dipilih
        function toggleInputs() {
            const isExistChecked = $('#exist').is(':checked');

            // Atur visibility dropdown dan input fields
            if (isExistChecked) {
                $('#rekening').prop('disabled', false).show(); // Aktifkan dan tampilkan elemen asli
                $('#rekening').next('.select2-container').show(); // Tampilkan elemen Select2
                $('.input-group.rekening-text input[type="text"]').prop('disabled', true).parent().hide(); // Sembunyikan input fields
            } else {
                $('#rekening').prop('disabled', true).hide(); // Nonaktifkan dan sembunyikan elemen asli
                $('#rekening').next('.select2-container').hide(); // Sembunyikan elemen Select2
                $('.input-group.rekening-text input[type="text"]').prop('disabled', false).parent().show(); // Tampilkan input fields
            }
        }

        // Panggil fungsi saat halaman dimuat
        toggleInputs();

        // Panggil fungsi saat radio button berubah
        $('input[name="radioNoLabel"]').change(toggleInputs);

        // AGAR INPUT FIELD HANYA BISA NOMOR
        document.getElementById('nomor_rekening').addEventListener('input', function(e) {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value.length > 14) {
                value = value.slice(0, 10);
            }
            this.value = value;
        });

        // Tambahkan fungsi untuk memformat input nominal memiliki titik
        function formatJumlahInput(selector) {
            $(document).on('input', selector, function() {
                let value = $(this).val().replace(/[^,\d]/g, '');
                let parts = value.split(',');
                let integerPart = parts[0];

                // Format tampilan dengan pemisah ribuan
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Set nilai yang diformat ke tampilan
                $(this).val(parts[1] !== undefined ? integerPart + ',' + parts[1] : integerPart);

                // Hapus semua pemisah ribuan untuk pengiriman ke server
                let cleanValue = value.replace(/\./g, '');

                // Pastikan elemen hidden dengan ID yang benar diperbarui
                const hiddenId = `#hidden_${$(this).attr('id').replace('nominal-', 'nominal')}`;
                $(hiddenId).val(cleanValue);
            });
        }

        //MENAMBAH FORM INPUTAN DI ADD FORM
        let rowCount = 0;
        let rowRekCount = 0;

        //ADD ROW NOMOR REKENING
        function addRekRow(bank, rek) {
            // Ambil nilai dari input
            const namaBank = bank;
            const nomorRekening = rek;

            rowRekCount++;
            if (namaBank != '' && nomorRekening != '') {
                const rekRow = `
                <tr id="rek-${rowRekCount}">
                    <td class="rek-number">${rowRekCount}</td>
                    <td>
                    <input name="nama_bank[${rowRekCount}]" id="nama_bank-${rowRekCount}" value="${namaBank}" style="border: none; pointer-events: none; color: #666">
                    <input type="hidden" id="hidden_rekId${rowRekCount}" name="hidden_rekId[${rowRekCount}]" value="">
                    </td>
                    <td><input name="no_rek[${rowRekCount}]" id="no_rek-${rowRekCount}" value="${nomorRekening}" style="border: none; pointer-events: none; color: #666"></td>
                    <td><button type="button" class="btn rek-delete btn-danger" data-id="${rowRekCount}">Delete</button></td>
                </tr>
            `;
                $('#rek-table tbody').append(rekRow);
            }
        }

        function addRow() {
            rowCount++;

            const row = `
                <tr id="row-${rowCount}">
                    <td class="row-number">${rowCount}</td>
                    <td>
                    <div class="btn btn-primary btn-lg btn-block btn-sm btn-style produk-modal" data-toggle="modal" data-target="#produkModal" data-id="${rowCount}" id="produk-modal${rowCount}">Pilih Produk</div>
                    <input type="hidden" id="kode_produk${rowCount}" name="kode_produk[${rowCount}]" value="">
                    <input type="hidden" id="hidden_id" name="hidden_id[${rowCount}]" value="">
                    <input type="hidden" name="hidden_invoiceId[${rowCount}]" id="hidden_invoiceId${rowCount}" value="${id}">
                    </td>
                    <td>
                        <input type="text" class="form-control jumlah" id="jumlah${rowCount}" name="jumlah[${rowCount}]" value="" placeholder="Jumlah" data-id="${rowCount}" data-stok="" data-produk="" readonly autocomplete="off">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="harga${rowCount}" name="harga[${rowCount}]" value="" placeholder="Harga" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control total" id="total${rowCount}" name="total[${rowCount}]" placeholder="Total" readonly>
                    </td>
                        <td><span class="btn delete-btn btn-danger btn-delete" data-id="${rowCount}">Delete</span></td>
                    </tr>
                `;
            $('#input-container').append(row);

            // Tambahkan format ke input nominal yang baru
            formatJumlahInput(`#nominal-${rowCount}`);
            updateSubmitButtonState(); // Perbarui status tombol submit
            //checkDeleteButtonState(); // Cek tombol delete setelah baris ditambahkan

            // Hitung total nominal setelah baris baru ditambahkan
            calculateTotalNominal();

            //VALIDASI ROW YANG TELAH DI APPEND
            $("#form").validate().settings.rules[`produk[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`jumlah[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`harga[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`harga[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`total[${rowCount}]`] = {
                required: true
            };
        }

        // MENGHAPUS ROW
        function deleteRow(id) {
            // Simpan ID dari row yang dihapus
            const rowId = $(`#row-${id}`).find('input:hidden[id^="hidden_id"]').val();
            if (rowId) {
                deletedRows.push(rowId);
            }

            console.log(rowId);

            $(`#row-${id}`).remove();
            // Reorder rows and update row numbers
            reorderRows();
            updateSubmitButtonState(); // Perbarui status tombol 
            //checkDeleteButtonState(); // Cek tombol delete setelah baris dihapus

            // Hitung total nominal setelah baris dihapus
            calculateTotalNominal();
        }

        // MENGHAPUS REKENING
        function deleteRekRow(id) {
            // Simpan ID dari row yang dihapus
            const rowRekId = $(`#rek-${id}`).find('input:hidden[id^="hidden_rekId"]').val();
            if (rowRekId) {
                deletedRekRows.push(rowRekId);
            }

            $(`#rek-${id}`).remove();
            // Reorder rows and update row numbers
            reorderRekRows();
        }

        // REORDER DETAIL INVOICE
        function reorderRows() {
            $('#input-container tr').each(function(index) {
                //INISIASI VARIABLE UNTUK reorderRows
                const newRowNumber = index + 1;
                const kodeProdukValue = $(this).find('input[name^="kode_produk"]').val();
                const hargaValue = $(this).find('input[name^="harga"]').val();
                const jumlahValue = $(this).find('input[name^="jumlah"]').val();
                const totalValue = $(this).find('input[name^="total"]').val();
                const hiddenInvoiceIdValue = $(this).find('input[name^="hidden_invoiceId"]').val();
                const hiddenIdValue = $(this).find('input[name^="hidden_id"]').val();

                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('input[name^="kode_produk"]').attr('name', `kode_produk[${newRowNumber}]`).attr('id', `kode_produk${newRowNumber}`).val(kodeProdukValue);
                $(this).find('input[name^="jumlah"]').attr('name', `jumlah[${newRowNumber}]`).attr('id', `jumlah${newRowNumber}`).attr('placeholder', `Jumlah`).attr('data-id', newRowNumber).val(jumlahValue);
                $(this).find('input[name^="harga"]').attr('name', `harga[${newRowNumber}]`).attr('id', `harga${newRowNumber}`).attr('placeholder', 'Harga').val(hargaValue);
                $(this).find('input[name^="total"]').attr('name', `total[${newRowNumber}]`).attr('id', `total${newRowNumber}`).attr('placeholder', `Total`).val(totalValue);
                $(this).find('input[name^="hidden_invoiceId"]').attr('name', `hidden_invoiceId[${newRowNumber}]`).attr('id', `hidden_invoiceId${newRowNumber}`).val(hiddenInvoiceIdValue);
                $(this).find('input[name^=hidden_id]').attr('name', `hidden_id[${newRowNumber}]`).attr('id', `hidden_id`).val(hiddenIdValue);
                // <div class="btn btn-primary btn-lg btn-block btn-sm btn-style produk-modal" data-toggle="modal" data-target="#produkModal" data-id="${rowCount}" id="produk-modal${rowCount}">Pilih Produk</div>
                $(this).find('.produk-modal').attr('data-id', `${newRowNumber}`).attr('id', `produk-modal${newRowNumber}`);
                $(this).find('.delete-btn').attr('data-id', newRowNumber).text('Delete');
            });
            rowCount = $('#input-container tr').length; // Update rowCount to the current number of rows
        }

        // REORDER NOMOR REKENING
        function reorderRekRows() {
            $('#rek-table tbody tr').each(function(index) {
                const newRekRowNumber = index + 1;
                const hiddenRekIdValue = $(this).find('input[name^="hidden_rekId"]').val();
                const namaBankValue = $(this).find('input[name^="nama_bank"]').val();
                const noRekValue = $(this).find('input[name^="no_rek"]').val();

                $(this).attr('id', `rek-${newRekRowNumber}`);
                $(this).find('.rek-number').text(newRekRowNumber);
                $(this).find('input[name^="nama_bank"]').attr('name', `nama_bank[${newRekRowNumber}]`).attr('id', `nama_bank-${newRekRowNumber}`).attr('placeholder', `Nama Bank...`).val(namaBankValue);
                $(this).find('input[name^="no_rek"]').attr('name', `no_rek[${newRekRowNumber}]`).attr('id', `no_rek-${newRekRowNumber}`).attr('placeholder', `Nomor Rekening...`).val(noRekValue);
                $(this).find('input[name^="hidden_rekId"]').attr('name', `hidden_rekId[${newRekRowNumber}]`).attr('id', `hidden_rekId${newRekRowNumber}`).val(hiddenRekIdValue);
                $(this).find('.rek-delete').attr('data-id', newRekRowNumber).text('Delete');
            });
            rowRekCount = $('#rek-table tbody tr').length;
        }

        // BUTTON ADD ROW DETAIL TRANSAKSI
        $('#add-row').click(function() {
            addRow();
        });

        // BUTTON ADD ROW NOMOR REKENING
        $('#btn-rek').click(function() {
            var bank = $('#nama_bank').val();
            var rek = $('#nomor_rekening').val();
            addRekRow(bank, rek);
            $('#nama_bank').val('');
            $('#nomor_rekening').val('');
        });

        // SELECT ADD ROW NOMOR REKENING
        $('#rekening').change(function() {
            // Ambil elemen yang dipilih
            var selectedOption = $(this).find(':selected');

            // Ambil nilai atribut data
            var bank = selectedOption.data('bank');
            var rek = selectedOption.data('rek');

            addRekRow(bank, rek);
        });

        function updateSubmitButtonState() {
            const rowCount = $('#input-container tr').length;
            if (rowCount > 0) {
                $('.aksi').prop('disabled', false); // Enable submit button
            } else {
                $('.aksi').prop('disabled', true); // Disable submit button
            }
        }

        // BUTTON HAPUS TRANSAKSI INVOICE
        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            deleteRow(id);
        });

        // BUTTON HAPUS NOMOR REKENING
        $(document).on('click', '.rek-delete', function() {
            const rowId = $(this).data('id');
            // console.log(rowId);
            deleteRekRow(rowId);
            reorderRekRows();
        });

        $('#form').submit(function(event) {
            // Tambahkan array deletedRows ke dalam form data sebelum submit
            $('<input>').attr({
                type: 'hidden',
                name: 'deleted_rows',
                value: JSON.stringify(deletedRows)
            }).appendTo('#form');

            $('<input>').attr({
                type: 'hidden',
                name: 'deletedRekRows',
                value: JSON.stringify(deletedRekRows)
            }).appendTo('#form');

            // Lanjutkan dengan submit form
        });

        // MENGISI FORM UPDATE
        if (id == 0) {
            $('.aksi').append('<span class="front front-aksi">Save</span>');
        } else {
            $('.aksi').append('<span class="front front-aksi">Update</span>');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('qbg_invoice/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    //SET VALUE DATA MASTER INVOICE
                    $('#id').val(data['master']['id']);
                    let dateParts = data['master']['tgl_invoice'].split('-'); // Pisahkan berdasarkan "-"
                    let formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`; // Susun jadi DD-MM-YYYY
                    $('#tgl_invoice').val(formattedDate); // Masukkan ke input
                    $('#kode_invoice').val(data['master']['kode_invoice']);
                    $('#tgl_tempo').val(data['master']['tgl_tempo']);
                    $('#nama_customer').val(data['master']['nama_customer']);
                    $('#nomor_customer').val(data['master']['nomor_customer']);
                    $('#email_customer').val(data['master']['email_customer']);
                    $('#alamat_customer').val(data['master']['alamat_customer']);
                    $('#jenis_harga').val(data['master']['jenis_harga']).trigger('change');
                    $('#total_akhir').val(data['master']['total']);
                    $('#ongkir').val('Rp. ' + data['master']['ongkir'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#potongan_harga').val('Rp. ' + data['master']['potongan_harga'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

                    quill.clipboard.dangerouslyPasteHTML(data['master']['keterangan']);
                    //APPEND DATA pu_rek_invoice DETAIL INVOICE
                    // console.log(data['rek_invoice']);
                    if (aksi == 'update') {
                        // Rekening
                        $(data['rek_invoice']).each(function(index) {
                            const row = `
                            <tr id="rek-${index + 1}">
                                <td class="rek-number">${index + 1}</td>
                                <td>
                                <input name="nama_bank[${index+1}]" id="nama_bank-${index + 1}" value="${data['rek_invoice'][index]['nama_bank']}" style="border: none; pointer-events: none; color: #666">
                                <input type="hidden" id="hidden_rekId${index + 1}" name="hidden_rekId[${index + 1}]" value="${data['rek_invoice'][index]['id']}">
                                </td>
                                <td><input name="no_rek[${index+1}]" id="no_rek-${index + 1}" value="${data['rek_invoice'][index]['no_rek']}" style="border: none; pointer-events: none; color: #666"></td>
                                <td><button type="button" class="btn rek-delete btn-danger" data-id="${index + 1}">Delete</button></td>
                            </tr>
                            `;
                            $('#rek-table tbody').append(row);
                            rowRekCount = index + 1;
                        });

                        // Detail pemesanan
                        $(data['detail_invoice']).each(function(index) {
                            const hargaFormatted = 'Rp. ' + data['detail_invoice'][index]['harga'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const totalFormatted = 'Rp. ' + data['detail_invoice'][index]['total'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const row = `
                            <tr id="row-${index + 1}">
                                <td class="row-number">${index + 1}</td>
                                <td>
                                    <div class="btn btn-primary btn-lg btn-block btn-sm btn-style produk-modal" data-toggle="modal" data-target="#produkModal" data-id="${index + 1}" id="produk-modal${index + 1}">${data['detail_invoice'][index]['nama_produk']} ${data['detail_invoice'][index]['berat']} ${data['detail_invoice'][index]['satuan']} (${data['detail_invoice'][index]['stok_tersedia']})</div>
                                    <input type="hidden" id="kode_produk${index + 1}" name="kode_produk[${index + 1}]" value="${data['detail_invoice'][index]['kode_produk']}">
                                    <input type="hidden" id="hidden_id" name="hidden_id[${index + 1}]" value="${data['detail_invoice'][index]['id']}">
                                    <input type="hidden" name="hidden_invoiceId[${index + 1}]" id="hidden_invoiceId${index + 1}" value="${data['detail_invoice'][index]['invoice_id']}">
                                </td>
                                <td>
                                    <input type="text" style="user-select: none;" class="form-control jumlah" id="jumlah${index + 1}" name="jumlah[${index + 1}]" value="${data['detail_invoice'][index]['jumlah']}" data-id="${index + 1}" data-stok="${data['detail_invoice'][index]['stok_tersedia']}" data-produk="${data['detail_invoice'][index]['nama_produk']} ${data['detail_invoice'][index]['berat']} ${data['detail_invoice'][index]['satuan']}" autocomplete="off">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="harga${index + 1}" name="harga[${index + 1}]" value="${hargaFormatted}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control total" id="total${index + 1}" name="total[${index + 1}]" value="${totalFormatted}" readonly>
                                </td>
                                    <td><span class="btn delete-btn btn-danger btn-delete" data-id="${index + 1}">Delete</span></td>
                                </tr>
                            </tr>
                            `;
                            $('#input-container').append(row);
                            rowCount = index + 1;
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            // Cek opsi jenis_harga dan total_akhir
            let selectedOption = $('#jenis_harga').val();
            let totalAkhir = parseFloat($('#total_akhir').val()) || 0;

            if (selectedOption === 'reseller' && totalAkhir < 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Total Tidak Memenuhi Syarat',
                    text: 'Total harus minimal Rp. 1.500.000 untuk memilih harga Reseller.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#242c49'
                });
                return false; // Hentikan proses submit
            }

            if ($(".tbody-rekening").children().length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Rekening Kosong!',
                    text: 'Silakan tambahkan minimal satu rekening sebelum submit.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#242c49'
                });
                return false;
            }

            var url;
            if (id == 0) {
                url = "<?php echo site_url('qbg_invoice/add') ?>";
            } else {
                url = "<?php echo site_url('qbg_invoice/update') ?>";
            }

            // Tampilkan loading
            $('#loading').show();
            // $('.aksi').prop('disabled', true);

            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    // Sembunyikan loading saat respons diterima
                    $('#loading').hide();

                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            confirmButtonColor: '#242c49',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            checkNotifications();
                            location.href = "<?= base_url('qbg_invoice') ?>";
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Sembunyikan loading saat respons diterima
                    $('#loading').hide();

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        confirmButtonColor: '#242c49',
                        text: 'Error adding / updating data: ' + textStatus
                    });
                }
            });
        });


        $("#form").validate({
            rules: {
                tgl_invoice: {
                    required: true,
                },
                kode_invoice: {
                    required: true,
                },
                tgl_tempo: {
                    required: true,
                },
                nama_customer: {
                    required: true,
                },
                nomor_customer: {
                    required: true,
                },
                email_customer: {
                    required: true,
                },
                alamat_customer: {
                    required: true,
                },
                ongkir: {
                    required: true,
                },
                potongan_harga: {
                    required: true,
                }
            },
            messages: {
                tgl_invoice: {
                    required: "Tanggal Invoice is required",
                },
                kode_invoice: {
                    required: "Kode Invoice is required",
                },
                tgl_tempo: {
                    required: "Tanggal Tempo is required",
                },
                nama_customer: {
                    required: "Nama Customer is required",
                },
                nomor_customer: {
                    required: "Nomor Customer is required",
                },
                email_customer: {
                    required: "Email Customer is required",
                },
                alamat_customer: {
                    required: "Alamat Customer is required",
                },
                ongkir: {
                    required: "Biaya Pengiriman is required",
                },
                potongan_harga: {
                    required: "Potongan Harga is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent().hasClass('input-group')) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid'); // Tambahkan kelas untuk menandai input tidak valid
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid'); // Hapus kelas jika input valid
            },
            focusInvalid: false, // Disable auto-focus on the first invalid field
        });


    })
</script>