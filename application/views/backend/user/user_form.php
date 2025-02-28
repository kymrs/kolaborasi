<?php
$sub_bisnis = $this->db->select('id_menu, nama_menu, sub_image')
    ->where('sub_image !=', null)
    ->where('sub_color !=', null)
    ->get('tbl_menu')
    ->result_array();

$user = $this->db->select('core')
    ->where('id_user', $this->session->userdata('id_user'))
    ->get('tbl_user')
    ->row_array();

$core = $user['core'] ?? ''; // Default kosong kalau core tidak ada
$core_array = explode(',', $core);
?>
<style>
    /* Membuat checkbox lebih besar dan cantik */
    input[type="checkbox"] {
        display: none;
    }

    /* Styling untuk label checkbox */
    input[type="checkbox"]+label {
        position: relative;
        padding-left: 30px;
        /* Memberikan ruang untuk kotak checkbox */
        cursor: pointer;
        font-size: 16px;
        color: #333;
        display: inline-block;
        line-height: 22px;
    }

    /* Kotak checkbox custom */
    input[type="checkbox"]+label:before {
        content: "";
        position: absolute;
        left: 0;
        top: 2px;
        width: 18px;
        height: 18px;
        border: 2px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
        transition: all 0.3s ease;
    }

    /* Style untuk checkbox yang terpilih (checked) */
    input[type="checkbox"]:checked+label:before {
        border-color: #1B2136;
        background-color: #1B2136;
    }

    /* Memberikan efek centang (checkmark) */
    input[type="checkbox"]:checked+label:after {
        content: "\2713";
        /* Unicode untuk tanda centang */
        position: absolute;
        left: 4px;
        top: 0px;
        font-size: 14px;
        color: #fff;
    }

    /* Hover efek saat mouse berada di atas label */
    input[type="checkbox"]+label:hover {
        color: #1B2136;
    }

    /* modal */
    .modal-input {
        background-color: rgb(36, 44, 73);
        color: white;
        border: none;
        padding: 5px 15px;
        font-size: 14px;
        border-radius: 5px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.15), -4px 4px 6px rgba(0, 0, 0, 0.15), 4px 4px 6px rgba(0, 0, 0, 0.15);
        /* Bayangan bawah dan kiri-kanan */
        cursor: pointer;
        transition: all 0.055s ease;
    }

    .modal-input:hover {
        scale: 1.020;
    }

    .modal-input:active {
        transform: translateY(2px);
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1), -2px 2px 6px rgba(0, 0, 0, 0.1), 2px 2px 6px rgba(0, 0, 0, 0.1);
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
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('user') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">

                                <!-- FIELD USER -->
                                <!-- First Set of Fields -->
                                <input type="hidden" id="hidden_id" name="hidden_id" />
                                <div class="form-group row">
                                    <label for="username" class="col-sm-4 col-form-label">Username</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fullname" class="col-sm-4 col-form-label">Fullname</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Fullname">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fullname" class="col-sm-4 col-form-label">Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group row password-field">
                                    <!-- FIELD NEW PASSWORD -->
                                </div>
                                <div class="form-group row">
                                    <input type="hidden" id="id_user" name="id_user" value="">

                                    <label class="col-sm-4" for="divisi">Divisi</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="divisi" name="divisi">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <option value="-">-</option>
                                            <option value="Operational">OPERATIONAL</option>
                                            <option value="Finance">FINANCE</option>
                                            <option value="HC & GA">HC & GA</option>
                                            <option value="IT">IT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="jabatan">Jabatan</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="-" class="form-control" id="jabatan" name="jabatan">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="core" class="col-sm-4 col-form-label">Core</label>
                                    <div class="col-sm-8">
                                        <button type="button" class="modal-input" data-toggle="modal" data-target="#coreModal">
                                            Piih Core
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="image" class="col-sm-4 col-form-label">Image</label>
                                    <div class="col-sm-8">
                                        <input type="file" class="" id="image" name="image">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="level" class="col-sm-4 col-form-label">User Level</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="level" name="level">
                                            <option value="" selected disabled>No Selected</option>
                                            <?php foreach ($userlevels as $userlevel) { ?>
                                                <option value="<?= $userlevel->id_level ?>"><?= $userlevel->nama_level ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="aktif" class="col-sm-4 col-form-label">Active</label>
                                    <div class="col-sm-8 form-inline row ml-1">
                                        <div class="custom-control custom-radio col-sm-2">
                                            <input class="custom-control-input" type="radio" id="customRadio1" name="aktif" value="Y">
                                            <label for="customRadio1" class="custom-control-label">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio2" name="aktif" value="N" checked>
                                            <label for="customRadio2" class="custom-control-label">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Layanan Modal -->
                        <div class="modal fade" id="coreModal" tabindex="-1" aria-labelledby="coreModal" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="coreModal">Pilih Core</h5>
                                        <button type="button" data-dismiss="modal" aria-label="Close" style="background-color: #fff; border: none">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <button id="checkAll" type="button" style="margin-bottom: 10px; cursor: pointer; border-radius: 5px; background-color: #242d4a; outline: none; color: white">All</button>
                                        <?php foreach ($sub_bisnis as $data) : ?>
                                            <div>
                                                <input type="checkbox" name="core[]" id="<?= $data['id_menu'] ?>" style="cursor: pointer" value="<?= $data['id_menu'] ?>"><label for="<?= $data['id_menu'] ?>" style="margin-left: 3px; margin-right: 10px; cursor: pointer; font-size: 1rem"><?= $data['nama_menu'] ?></label>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="">
                        <?php } ?>
                        <button type="submit" class="btn btn-primary btn-sm aksi">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $(document).ready(function() {
        let isChecked = false; // Flag buat toggle

        $('#checkAll').on('click', function() {
            isChecked = !isChecked; // Balik status
            $('input[name="core[]"]').prop('checked', isChecked);
        });

        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('.password-field').remove();
            $('#kode_notifikasi').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('user/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    moment.locale('id');
                    $('#hidden_id').val(data.user.id_user);
                    $('#password').prop('readonly', true);
                    $('#username').val(data.user.username);
                    $('#fullname').val(data.user.fullname);
                    $('#password').val(data.user.password);
                    $('#level').val(data.user.id_level);
                    $('#no_rek').val(data.user.no_rek);
                    $('#divisi').val(data.detail.divisi);
                    $('#jabatan').val(data.detail.jabatan);

                    var coreData = data.user.core; // Misal data ini didapat dari API atau sumber lainnya

                    // Pisahkan data core menjadi array
                    var coreArray = coreData.split(',');

                    // Tandai checkbox yang sesuai dengan ID menu
                    coreArray.forEach(function(coreId) {
                        // Tandai checkbox yang sesuai dengan ID-nya
                        $('#' + coreId).prop('checked', true);
                    });

                    //APPEND NEW PASSWORD
                    $('.password-field').append(`
                        <label for="fullname" class="col-sm-4 col-form-label">New Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">
                                    </div>
                    `);

                    // RADIO BUTTON
                    if (data.user.is_active == 'Y') {
                        $('#customRadio1').prop('checked', true);
                        $('#customRadio2').prop('checked', false);
                    } else {
                        $('#customRadio1').prop('checked', false);
                        $('#customRadio2').prop('checked', true);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error getting data from ajax');
                }
            });
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);

            // Validasi jika core tidak ada yang dicentang
            if ($('input[name="core[]"]:checked').length === 0) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Silakan pilih setidaknya 1 core data!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return false; // Hentikan submit jika tidak ada yang dicentang
            }

            if (!$form.valid()) return false;

            var url = (id == 0) ? "<?php echo site_url('user/add') ?>" : "<?php echo site_url('user/update') ?>";

            $.ajax({
                url: url,
                type: "POST",
                data: new FormData(this),
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('user') ?>";
                        });
                    } else {
                        if (data.error === "user") {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Failed to save user!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else if (data.error === "data_user") {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Failed to save user!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else if (data.error === "size") {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'File upload size is more than 3MB!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else if (data.error === "type") {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Format files is not supported!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / updating data');
                    console.error("Error Response:", jqXHR.responseText);
                    console.error("Text Status:", textStatus);
                    console.error("Error Thrown:", errorThrown);
                }
            });
        });


        $("#form").validate({
            rules: {
                username: {
                    required: true
                },
                fullname: {
                    required: true
                },
                password: {
                    required: true
                },
                core: {
                    required: true
                },
                level: {
                    required: true
                },
                aktif: {
                    required: true
                },
                name: {
                    required: true
                },
                divisi: {
                    required: true
                },
                jabatan: {
                    required: true
                },
                app_id: {
                    required: true
                },
                app2_id: {
                    required: true
                },
                no_rek: { // Tambahkan aturan untuk no_rek
                    required: true,
                    minlength: 10,
                    digits: true // Memastikan input hanya angka
                }
            },
            messages: {
                username: {
                    required: "Username is required"
                },
                fullname: {
                    required: "Fullname is required"
                },
                password: {
                    required: "Password is required"
                },
                level: {
                    required: "User Level is required"
                },
                aktif: {
                    required: "Active is required"
                },
                divisi: {
                    required: "Divisi is required"
                },
                jabatan: {
                    required: "Jabatan is required"
                },
                app_id: {
                    required: "Approval 1 is required"
                },
                app2_id: {
                    required: "Approval 2 is required"
                },
                no_rek: { // Tambahkan pesan untuk no_rek
                    required: "Nomor rekening harus diisi",
                    minlength: "Nomor rekening harus minimal 10 digit",
                    digits: "Nomor rekening hanya boleh berisi angka"
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

    });
</script>