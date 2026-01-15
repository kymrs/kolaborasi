<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        font-size: 2em;
    }

    .rating input {
        display: none;
    }

    .rating label {
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s;
        position: relative;
        right: 107px;
        bottom: 5px;
    }

    .rating input:checked~label,
    .rating label:hover,
    .rating label:hover~label {
        color: #f5b301;
    }
</style>
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
                
                <!-- Filter Buttons -->
                <div class="card-body pb-2 pt-3">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary filter-btn active" data-filter="">
                            <i class="fa fa-list"></i> All
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="SOP">
                            <i class="fa fa-file"></i> SOP
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="Juklak">
                            <i class="fa fa-folder"></i> Juklak
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="Juknis">
                            <i class="fa fa-folder-open"></i> Juknis
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- Added padding for spacing -->
                    <div class="table-responsive">
                        <!-- Table wrapper -->
                        <table id="hotel-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Order</th>
                                    <th>Jenis</th>
                                    <th>No SOP</th>
                                    <th>Nama</th>
                                    <th>File</th>
                                    <th>Dibuat pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Order</th>
                                    <th>Jenis</th>
                                    <th>No SOP</th>
                                    <th>Nama</th>
                                    <th>File</th>
                                    <th>Dibuat pada</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="modalform">
                    <div class="modal-header bg-primary text-gray-100">
                        <h5 class="card-title" style="margin: 0;">Tambah Data SOP</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -23px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- /.card-header -->
                    <div class="modal-body">
                        <input type="hidden" name="id" />
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Jenis</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="jenis" id="jenis">
                                    <option value="" hidden>Pilih Jenis</option>
                                    <option value="SOP">SOP</option>
                                    <option value="Juklak">Juklak</option>
                                    <option value="Juknis">Juknis</option>
                                </select>
                            </div>
                        </div>

                        <!-- Parent Field (Conditional) -->
                        <div class="form-group row" id="parent_group" style="display: none;">
                            <label for="" class="col-sm-3 col-form-label">Parent <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control select2" name="parent_no" id="parent_no" style="width: 100%;">
                                    <option value="">-- Pilih Parent --</option>
                                </select>
                                <!-- <small class="form-text text-muted">Pilih parent untuk menentukan hierarki</small> -->
                            </div>
                        </div>

                        <!-- Kode (Manual Input) -->
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Kode</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="Masukkan kode" required>
                                <!-- <small class="form-text text-muted">Masukkan kode secara manual</small> -->
                            </div>
                        </div>

                        <!-- No Hierarki (Readonly) -->
                        <div class="form-group row" id="no_container">
                            <label for="" class="col-sm-3 col-form-label">Order</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="no" name="no" readonly>
                                <!-- <small class="form-text text-muted">Contoh: 1, 1-1, 1-1-1</small> -->
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="nama" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">File</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" id="file" name="file">
                                <small class="form-text text-muted" id="current_file"></small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="submit" class="btn btn-primary aksi">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileModalLabel">File Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="fileContent" style="text-align: center;"></div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table;
    $(document).ready(function() {
        // Initialize Select2 for parent_no field
        $('#parent_no').select2({
            placeholder: '-- Cari atau Pilih Parent --',
            allowClear: true,
            width: '100%',
            language: {
                searching: function() {
                    return 'Mencari...';
                }
            }
        });

        table = $('#hotel-table').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('pu_sop/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    // Tambahkan filter jenis ke request
                    d.filter_jenis = filterJenis;
                }
            },
            "language": {
                "infoFiltered": "",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            },
            "columnDefs": [{
                    "targets": [], // Adjusted indices to match the number of columns
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1],
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0, 1], // Indices for non-orderable columns
                    "orderable": false,
                },
                {
                    "targets": [6], // File column
                    "render": function(data, type, row) {
                        if (data) {
                            return '<button class="btn btn-sm btn-info" onclick="previewFile(\'' + data + '\')" title="Preview file"><i class="fa fa-eye"></i> View</button>';
                        } else {
                            return '';
                        }
                    }
                }
            ],
            "dom": '<"top"lf>rt<"bottom"ip><"clear">'
        });

        // HANDLER JENIS CHANGE - LOAD PARENT OPTIONS
        $('#jenis').on('change', function() {
            var jenis = $(this).val();
            
            if (jenis == 'SOP') {
                // SOP tidak punya parent
                $('#parent_group').hide();
                $('#parent_no').val('');
            } else if (jenis == 'Juklak' || jenis == 'Juknis') {
                // Tampilkan parent field dan load options
                $('#parent_group').show();
                loadParentOptions(jenis);
            } else {
                $('#parent_group').hide();
            }
        });
    });

    /**
     * Load parent options via AJAX
     */
    function loadParentOptions(jenis, selectedValue = null) {
        $.ajax({
            url: '<?php echo site_url('pu_sop/get_parent_options'); ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                jenis: jenis
            },
            beforeSend: function() {
                $('#parent_no').html('<option value="">Loading...</option>');
                if ($('#parent_no').data('select2')) {
                    $('#parent_no').select2('destroy');
                }
            },
            success: function(data) {
                if (data.status && data.data.length > 0) {
                    var options = '<option value="">-- Pilih Parent --</option>';
                    
                    $.each(data.data, function(index, item) {
                        var label = '[' + item.kode + '] ' + item.no + ' - ' + item.nama;
                        var selected = (selectedValue && selectedValue == item.no) ? 'selected' : '';
                        options += '<option value="' + item.no + '" ' + selected + '>' + label + '</option>';
                    });
                    
                    $('#parent_no').html(options);
                    
                    // Reinitialize Select2
                    $('#parent_no').select2({
                        placeholder: '-- Cari atau Pilih Parent --',
                        allowClear: true,
                        width: '100%'
                    });
                    
                } else if (data.status && data.data.length == 0) {
                    $('#parent_no').html('<option value="">Tidak ada parent tersedia</option>');
                    $('#parent_no').select2({
                        placeholder: '-- Cari atau Pilih Parent --',
                        allowClear: true,
                        width: '100%'
                    });
                    $('#no').val('');
                    showAlert('warning', 'Tidak ada ' + (jenis == 'Juklak' ? 'SOP' : 'Juklak') + ' untuk dipilih sebagai parent');
                } else {
                    showAlert('error', data.message || 'Gagal load parent options');
                    $('#parent_no').html('<option value="">Error loading</option>');
                    $('#parent_no').select2({
                        placeholder: '-- Cari atau Pilih Parent --',
                        allowClear: true,
                        width: '100%'
                    });
                }
            },
            error: function() {
                showAlert('error', 'Terjadi kesalahan saat load parent options');
                $('#parent_no').html('<option value="">Error loading</option>');
                $('#parent_no').select2({
                    placeholder: '-- Cari atau Pilih Parent --',
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    }

    /**
     * Event listener untuk perubahan parent_no
     * Generate nomor hierarki otomatis ketika parent berubah
     * Menggunakan select2:select event karena field menggunakan Select2
     */
    $(document).on('select2:select', '#parent_no', function() {
        var jenis = $('#jenis').val();
        var parent_no = $(this).val();
        
        console.log('Parent changed - jenis:', jenis, 'parent_no:', parent_no, 'method:', method);
        
        if (!jenis || !parent_no) {
            return;
        }
        
        // Hanya generate nomor jika dalam mode edit (parent berubah)
        if (method === 'update') {
            $.ajax({
                url: '<?php echo site_url('pu_sop/generate_no_from_parent'); ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    jenis: jenis,
                    parent_no: parent_no
                },
                success: function(response) {
                    console.log('Generate response:', response);
                    if (response.status) {
                        // Remove readonly sementara untuk update nilai
                        $('#no').removeAttr('readonly').val(response.no).attr('readonly', 'readonly');
                        console.log('No updated to:', response.no);
                    } else {
                        showAlert('error', response.message || 'Gagal generate nomor');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX error:', status, error, xhr.responseText);
                    showAlert('error', 'Terjadi kesalahan saat generate nomor');
                }
            });
        } else {
            console.log('Not in update mode, skipping generate');
        }
    });

    /**
     * Show alert message via SweetAlert (compact toast)
     */
    function showAlert(type, message) {
        var iconType = type === 'error' ? 'error' : (type === 'success' ? 'success' : 'warning');

        Swal.fire({
            icon: iconType,
            title: message,
            toast: true,
            position: 'top-end',
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    }

    function reload_table() {
        table.ajax.reload(null, false);
    }

    // Variable untuk menyimpan filter jenis yang dipilih
    var filterJenis = '';

    // Handle filter button clicks
    $(document).on('click', '.filter-btn', function() {
        // Remove active class dari semua button
        $('.filter-btn').removeClass('active btn-secondary').addClass('btn-outline-secondary');
        
        // Add active class ke button yang di-klik
        $(this).removeClass('btn-outline-secondary').addClass('btn-secondary active');
        
        // Simpan filter yang dipilih
        filterJenis = $(this).data('filter');
        
        // Reload table dengan filter baru
        reload_table();
    });

    $("#modalform").submit(function(e) {
        e.preventDefault();
        var url;
        var $form = $(this);
        if (!$form.valid()) return false;

        // Validasi parent untuk Juklak dan Juknis
        var jenis = $('#jenis').val();
        var parent_no = $('#parent_no').val();

        if (jenis != 'SOP' && !parent_no) {
            showAlert('error', 'Parent harus dipilih untuk ' + jenis);
            return false;
        }

        if (method == 'add') {
            url = "<?php echo site_url('pu_sop/add') ?>";
        } else {
            url = "<?php echo site_url('pu_sop/update') ?>";
        }

        $.ajax({
            url: url,
            type: "post",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function(data) {
                $('#modal-default').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Your data has been saved',
                    showConfirmButton: false,
                    timer: 1500
                });
                reload_table();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    });

    function previewFile(filename) {
        var baseUrl = "<?php echo base_url(); ?>";
        var fileUrl = baseUrl + "assets/backend/document/pu_sop/" + filename;
        var ext = filename.split('.').pop().toLowerCase();
        if (ext === 'pdf') {
            window.open(fileUrl, '_blank');
        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
            $('#fileContent').html('<img src="' + fileUrl + '" class="img-fluid" />');
            $('#fileModal').modal('show');
        } else {
            $('#fileContent').html('<a href="' + fileUrl + '" target="_blank">Download ' + filename + '</a>');
            $('#fileModal').modal('show');
        }
    };

    function add_data() {
        method = 'add';
        $('#modalform')[0].reset();
        var validator = $("#modalform").validate();
        validator.resetForm();
        $('.card-title').text('Tambah Data SOP');
        $('.aksi').text('Save');
        $('#current_file').text('');
        $('#parent_group').hide();
        $('#no_container').hide();
    };

    // Mengambil URL saat ini
    const params = new URLSearchParams(window.location.search);

    // Mengambil parameter tertentu
    const action = params.get('action'); // "John"

    if (action == 'add') {
        $('#add_btn').click();
    }

    function edit_data(id) {
        method = 'update';
        $('#modalform')[0].reset();
        var validator = $("#modalform").validate();
        validator.resetForm();
        $('.form-control').removeClass('error');
        $('#modal-default').modal('show');
        $('.card-title').text('Edit Data SOP');
        $('.aksi').text('Update');
        $.ajax({
            url: "<?php echo site_url('pu_sop/get_id/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="jenis"]').val(data.jenis);
                $('[name="nama"]').val(data.nama);
                $('[name="kode"]').val(data.kode || '');
                $('[name="no"]').val(data.no || '');
                
                // Show/hide parent field based on jenis
                if (data.jenis != 'SOP') {
                    $('#parent_group').show();
                    // Pass parent_no sebagai selectedValue agar ter-select setelah options di-load
                    loadParentOptions(data.jenis, data.parent_no || '');
                } else {
                    $('#parent_group').hide();
                    $('[name="parent_no"]').val('');
                }

                if (data.file) {
                    $('#current_file').text('Current file: ' + data.file);
                } else {
                    $('#current_file').text('');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    };

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
                    url: "<?php echo site_url('pu_sop/delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        reload_table();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };
</script>