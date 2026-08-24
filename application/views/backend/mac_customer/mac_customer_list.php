<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <a class="btn btn-primary btn-sm" id="add_btn" data-toggle="modal" data-target="#modal-default" onclick="add_data()">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="customer-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Nama Customer</th>
                                    <th>Type Customer</th>
                                    <th>Address</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Nama Customer</th>
                                    <th>Type Customer</th>
                                    <th>Address</th>
                                    <th>Created At</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== MODAL ADD / EDIT ========== -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="modalform">
                    <div class="modal-header bg-primary text-gray-100">
                        <h5 class="modal-title card-title" style="margin: 0;">Tambah Data Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -23px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">

                        <!-- Type Customer -->
                        <div class="form-group">
                            <label>Tipe Customer</label>
                            <select class="form-control" id="type_customer" name="type_customer">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Perusahaan">Perusahaan</option>
                                <option value="Perorangan">Perorangan</option>
                                <option value="Instansi">Instansi</option>
                            </select>
                        </div>

                        <!-- Title -->
                        <div class="form-group">
                            <label>Title</label>
                            <select class="form-control" id="title" name="title" disabled>
                                <option value="">-- Pilih Title --</option>
                            </select>
                        </div>

                        <!-- Customer Name -->
                        <div class="form-group">
                            <label>Nama Customer</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Nama Customer">
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" id="address" name="address" placeholder="Alamat" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer text-right">
                        <button type="submit" class="btn btn-primary aksi">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table;
    var method;

    $(document).ready(function() {

        // ========== DATATABLES ==========
        table = $('#customer-table').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('mac_customer/get_list') ?>",
                "type": "POST",
            },
            "language": {
                "infoFiltered": ""
            },
            "columnDefs": [
                {
                    "targets": [1],
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0, 1],
                    "orderable": false,
                }
            ],
        });

        // ========== TITLE OPTIONS PER TYPE CUSTOMER ==========
        var titleOptions = {
            'Perusahaan': [
                { value: 'PT', label: 'PT' },
                { value: 'CV', label: 'CV' },
                { value: '-',  label: '-'  },
            ],
            'Perorangan': [
                { value: 'Bapak', label: 'Bapak' },
                { value: 'Ibu',   label: 'Ibu'   },
            ],
            'Instansi': [
                { value: '-', label: '-' },
            ]
        };

        $(document).on('change', '#type_customer', function() {
            var type   = $(this).val();
            var $title = $('#title');

            $title.empty().prop('disabled', true);

            if (!type) {
                $title.append('<option value="">-- Pilih Title --</option>');
                return;
            }

            $title.append('<option value="">-- Pilih Title --</option>');
            $.each(titleOptions[type], function(i, opt) {
                $title.append('<option value="' + opt.value + '">' + opt.label + '</option>');
            });

            $title.prop('disabled', false);
        });

        // ========== FORM SUBMIT ==========
        $("#modalform").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url = (method == 'add')
                ? "<?php echo site_url('mac_customer/add') ?>"
                : "<?php echo site_url('mac_customer/update') ?>";

            $.ajax({
                url: url,
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    $('#modal-default').modal('hide');
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Data berhasil disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    reload_table();
                },
                error: function() {
                    alert('Error adding / updating data');
                }
            });
        });

        // ========== FORM VALIDATION ==========
        $("#modalform").validate({
            rules: {
                type_customer: { required: true },
                title:         { required: true },
                customer_name: { required: true },
                address:       { required: true },
            },
            messages: {
                type_customer: { required: "Tipe customer wajib dipilih" },
                title:         { required: "Title wajib dipilih" },
                customer_name: { required: "Nama customer wajib diisi" },
                address:       { required: "Alamat wajib diisi" },
            },
            errorPlacement: function(error, element) {
                error.appendTo(element.closest('.col-sm-9'));
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
        });

    });

    // ========== RELOAD TABLE ==========
    function reload_table() {
        table.ajax.reload(null, false);
    }

    // ========== ADD DATA ==========
    function add_data() {
        method = 'add';
        $('#modalform')[0].reset();
        $('#title').empty().prop('disabled', true)
            .append('<option value="">-- Pilih Title --</option>');
        var validator = $("#modalform").validate();
        validator.resetForm();
        $('.modal-title').text('Tambah Data Customer');
        $('.aksi').text('Save');
    }

    // ========== EDIT DATA ==========
    function edit_data(id) {
        method = 'update';
        $('#modalform')[0].reset();
        $('#title').empty().prop('disabled', true)
            .append('<option value="">-- Pilih Title --</option>');
        var validator = $("#modalform").validate();
        validator.resetForm();
        $('.form-control').removeClass('is-invalid');
        $('.modal-title').text('Edit Data Customer');
        $('.aksi').text('Update');
        $('#modal-default').modal('show');

        $.ajax({
            url: "<?php echo site_url('mac_customer/get_id') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#id').val(data.id);
                
                var rawName = data.customer_name;
                var dotIndex = rawName.indexOf('.');
                var cleanName = (dotIndex !== -1) ? rawName.substring(dotIndex + 1).trimStart() : rawName;

                $('#customer_name').val(cleanName);
                $('#address').val(data.address);

                // Set type_customer dulu agar options title ter-populate
                $('#type_customer').val(data.type_customer).trigger('change');

                // Set title setelah options sudah ada
                $('#title').val(data.title);
            },
            error: function() {
                alert('Error get data from ajax');
            }
        });
    }

    // ========== DELETE DATA ==========
    function delete_data(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo site_url('mac_customer/delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        reload_table();
                    },
                    error: function() {
                        alert('Error deleting data');
                    }
                });
            }
        });
    }
</script>