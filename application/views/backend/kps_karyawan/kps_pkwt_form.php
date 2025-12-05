<style>
    /* Styling box input Select2 */
    .select2-container .select2-selection--single {
        height: 38px;
        /* Sama seperti form-control Bootstrap */
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        transition: 300ms;
    }

    /* Saat select2 dalam keadaan fokus */
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--single:hover {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Styling dropdown Select2 */
    .select2-container--default .select2-results__option {
        padding: 10px;
        font-size: 1rem;
    }

    /* Hover pada dropdown */
    .select2-container--default .select2-results__option--highlighted {
        background-color: #007bff;
        color: white;
    }

    /* Tag styles */
    .tag {
        display: inline-block;
        background: #1a2035;
        color: #fff;
        border-radius: 15px;
        padding: 3px 12px 3px 10px;
        margin: 2px 2px 2px 0;
        font-size: 0.95em;
        position: relative;
    }

    .tag .remove-tag {
        margin-left: 8px;
        cursor: pointer;
        color: #fff;
        font-weight: bold;
    }

    .tag .remove-tag:hover {
        color: red;
    }

    /* Styling placeholder */
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }

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

    /* Stepper styles for Total Bulan */
    .input-step {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .input-step .step-btn {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        border: 1px solid #ced4da;
        background: #ffffff;
        color: #212529;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        user-select: none;
    }
    .input-step .step-btn:active {
        transform: translateY(1px);
    }
    .input-step input[type="number"] {
        text-align: center;
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 6px 10px;
        width: 110px;
        font-weight: 600;
    }
    .input-step .step-btn[aria-disabled="true"] {
        opacity: 0.45;
        cursor: not-allowed;
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
                    <a style="background-color: rgb(36, 44, 73);" class="btn btn-secondary btn-sm" onclick="window.history.back()">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="no_perjanjian">No Perjanjian</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="no_perjanjian" id="no_perjanjian" placeholder="No Perjanjian" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="npk">Nama Karyawan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="npk" id="npk">
                                            <option value="" hidden>Pilih Karyawan</option>
                                            <?php foreach ($karyawan as $data) : ?>
                                                <option value="<?= $data['npk'] ?>-<?= $data['id_user'] ?>"><?= $data['nama_lengkap'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- NEW: Total Bulan with stepper -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="total_bulan">Total Bulan</label>
                                    <div class="col-sm-7">
                                        <div class="input-step">
                                            <button type="button" class="step-btn" id="btn_decrease" aria-label="Kurangi bulan" aria-disabled="true">−</button>
                                            <input type="number" class="form-control" name="total_bulan" id="total_bulan" min="0" max="120" value="0" />
                                            <button type="button" class="step-btn" id="btn_increase" aria-label="Tambah bulan">+</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5" for="jk_awal">Kontrak Awal</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="jk_awal" id="jk_awal" placeholder="Pilih Tanggal Kontrak Awal" style="cursor: pointer" autocomplete="off" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="jk_akhir">Kontrak Akhir</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="jk_akhir" id="jk_akhir" placeholder="Tanggal Kontrak Akhir" style="cursor: pointer" autocomplete="off" readonly>
                                    </div>
                                </div>
                                <div class="form-group row" style="display: none;">
                                    <label class="col-sm-5" for="hari">Hari</label>
                                    <div class="col-sm-7">
                                        <select name="hari" id="hari" class="form-control">
                                            <option value="" hidden>Pilih Hari</option>
                                            <option value="Senin">Senin</option>
                                            <option value="Selasa">Selasa</option>
                                            <option value="Rabu">Rabu</option>
                                            <option value="Kamis">Kamis</option>
                                            <option value="Jumat">Jumat</option>
                                            <option value="Sabtu">Sabtu</option>
                                            <option value="Minggu">Minggu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" style="display: none;">
                                    <label class="col-sm-5" for="tanggal">Tanggal</label>
                                    <div class="col-sm-7">
                                        <select name="tanggal" id="tanggal" class="form-control">
                                            <option value="" hidden>Pilih Tanggal</option>
                                            <option value="Satu">Satu</option>
                                            <option value="Dua">Dua</option>
                                            <option value="Tiga">Tiga</option>
                                            <option value="Empat">Empat</option>
                                            <option value="Lima">Lima</option>
                                            <option value="Enam">Enam</option>
                                            <option value="Tujuh">Tujuh</option>
                                            <option value="Delapan">Delapan</option>
                                            <option value="Sembilan">Sembilan</option>
                                            <option value="Sepuluh">Sepuluh</option>
                                            <option value="Sebelas">Sebelas</option>
                                            <option value="Dua Belas">Dua Belas</option>
                                            <option value="Tiga Belas">Tiga Belas</option>
                                            <option value="Empat Belas">Empat Belas</option>
                                            <option value="Lima Belas">Lima Belas</option>
                                            <option value="Enam Belas">Enam Belas</option>
                                            <option value="Tujuh Belas">Tujuh Belas</option>
                                            <option value="Delapan Belas">Delapan Belas</option>
                                            <option value="Sembilan Belas">Sembilan Belas</option>
                                            <option value="Dua Puluh">Dua Puluh</option>
                                            <option value="Dua Puluh Satu">Dua Puluh Satu</option>
                                            <option value="Dua Puluh Dua">Dua Puluh Dua</option>
                                            <option value="Dua Puluh Tiga">Dua Puluh Tiga</option>
                                            <option value="Dua Puluh Empat">Dua Puluh Empat</option>
                                            <option value="Dua Puluh Lima">Dua Puluh Lima</option>
                                            <option value="Dua Puluh Enam">Dua Puluh Enam</option>
                                            <option value="Dua Puluh Tujuh">Dua Puluh Tujuh</option>
                                            <option value="Dua Puluh Delapan">Dua Puluh Delapan</option>
                                            <option value="Dua Puluh Sembilan">Dua Puluh Sembilan</option>
                                            <option value="Tiga Puluh">Tiga Puluh</option>
                                            <option value="Tiga Puluh Satu">Tiga Puluh Satu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" style="display: none;">
                                    <label class="col-sm-5" for="bulan">Bulan</label>
                                    <div class="col-sm-7">
                                        <select name="bulan" id="bulan" class="form-control">
                                            <option value="" hidden>Pilih Bulan</option>
                                            <option value="Januari">Januari</option>
                                            <option value="Februari">Februari</option>
                                            <option value="Maret">Maret</option>
                                            <option value="April">April</option>
                                            <option value="Mei">Mei</option>
                                            <option value="Juni">Juni</option>
                                            <option value="Juli">Juli</option>
                                            <option value="Agustus">Agustus</option>
                                            <option value="September">September</option>
                                            <option value="Oktober">Oktober</option>
                                            <option value="November">November</option>
                                            <option value="Desember">Desember</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="gaji">Gaji</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control rupiah" name="gaji" id="gaji" placeholder="Gaji" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row" style="display: none;">
                                    <label class="col-sm-5" for="tahun">Tahun</label>
                                    <div class="col-sm-7">
                                        <select name="tahun" id="tahun" class="form-control">
                                            <option value="" hidden>Pilih Tahun</option>
                                            <option value="Dua Ribu Dua Puluh Lima">2025</option>
                                            <option value="Dua Ribu Dua Puluh Enam">2026</option>
                                            <option value="Dua Ribu Dua Puluh Tujuh">2027</option>
                                            <option value="Dua Ribu Dua Puluh Delapan">2028</option>
                                            <option value="Dua Ribu Dua Puluh Sembilan">2029</option>
                                            <option value="Dua Ribu Tiga Puluh">2030</option>
                                            <option value="Dua Ribu Tiga Puluh Satu">2031</option>
                                            <option value="Dua Ribu Tiga Puluh Dua">2032</option>
                                            <option value="Dua Ribu Tiga Puluh Tiga">2033</option>
                                            <option value="Dua Ribu Tiga Puluh Empat">2034</option>
                                            <option value="Dua Ribu Tiga Puluh Lima">2035</option>
                                            <option value="Dua Ribu Tiga Puluh Enam">2036</option>
                                            <option value="Dua Ribu Tiga Puluh Tujuh">2037</option>
                                            <option value="Dua Ribu Tiga Puluh Delapan">2038</option>
                                            <option value="Dua Ribu Tiga Puluh Sembilan">2039</option>
                                            <option value="Dua Ribu Empat Puluh">2040</option>
                                            <option value="Dua Ribu Empat Puluh Satu">2041</option>
                                            <option value="Dua Ribu Empat Puluh Dua">2042</option>
                                            <option value="Dua Ribu Empat Puluh Tiga">2043</option>
                                            <option value="Dua Ribu Empat Puluh Empat">2044</option>
                                            <option value="Dua Ribu Empat Puluh Lima">2045</option>
                                            <option value="Dua Ribu Empat Puluh Enam">2046</option>
                                            <option value="Dua Ribu Empat Puluh Tujuh">2047</option>
                                            <option value="Dua Ribu Empat Puluh Delapan">2048</option>
                                            <option value="Dua Ribu Empat Puluh Sembilan">2049</option>
                                            <option value="Dua Ribu Lima Puluh">2050</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" style="display: none;">
                                    <label class="col-sm-5" for="jangka_waktu">Jangka Waktu</label>
                                    <div class="col-sm-7">
                                        <select name="jangka_waktu" id="jangka_waktu" class="form-control">
                                            <option value="" hidden>Pilih Jangka Waktu</option>
                                            <option value="1 (Satu) bulan">1 bulan</option>
                                            <option value="2 (Dua) bulan">2 bulan</option>
                                            <option value="3 (Tiga) bulan">3 bulan</option>
                                            <option value="4 (Empat) bulan">4 bulan</option>
                                            <option value="5 (Lima) bulan">5 bulan</option>
                                            <option value="6 (Enam) bulan">6 bulan</option>
                                            <option value="7 (Tujuh) bulan">7 bulan</option>
                                            <option value="8 (Delapan) bulan">8 bulan</option>
                                            <option value="9 (Sembilan) bulan">9 bulan</option>
                                            <option value="10 (Sepuluh) bulan">10 bulan</option>
                                            <option value="11 (Sebelas) bulan">11 bulan</option>
                                            <option value="12 (Dua Belas) bulan">12 bulan</option>
                                            <option value="13 (Tiga Belas) bulan">13 bulan</option>
                                            <option value="14 (Empat Belas) bulan">14 bulan</option>
                                            <option value="15 (Lima Belas) bulan">15 bulan</option>
                                            <option value="16 (Enam Belas) bulan">16 bulan</option>
                                            <option value="17 (Tujuh Belas) bulan">17 bulan</option>
                                            <option value="18 (Delapan Belas) bulan">18 bulan</option>
                                            <option value="19 (Sembilan Belas) bulan">19 bulan</option>
                                            <option value="20 (Dua Puluh) bulan">20 bulan</option>
                                            <option value="21 (Dua Puluh Satu) bulan">21 bulan</option>
                                            <option value="22 (Dua Puluh Dua) bulan">22 bulan</option>
                                            <option value="23 (Dua Puluh Tiga) bulan">23 bulan</option>
                                            <option value="24 (Dua Puluh Empat) bulan">24 bulan</option>
                                            <option value="25 (Dua Puluh Lima) bulan">25 bulan</option>
                                            <option value="26 (Dua Puluh Enam) bulan">26 bulan</option>
                                            <option value="27 (Dua Puluh Tujuh) bulan">27 bulan</option>
                                            <option value="28 (Dua Puluh Delapan) bulan">28 bulan</option>
                                            <option value="29 (Dua Puluh Sembilan) bulan">29 bulan</option>
                                            <option value="30 (Tiga Puluh) bulan">30 bulan</option>
                                            <option value="31 (Tiga Puluh Satu) bulan">31 bulan</option>
                                            <option value="32 (Tiga Puluh Dua) bulan">32 bulan</option>
                                            <option value="33 (Tiga Puluh Tiga) bulan">33 bulan</option>
                                            <option value="34 (Tiga Puluh Empat) bulan">34 bulan</option>
                                            <option value="35 (Tiga Puluh Lima) bulan">35 bulan</option>
                                            <option value="36 (Tiga Puluh Enam) bulan">36 bulan</option>
                                            <option value="37 (Tiga Puluh Tujuh) bulan">37 bulan</option>
                                            <option value="38 (Tiga Puluh Delapan) bulan">38 bulan</option>
                                            <option value="39 (Tiga Puluh Sembilan) bulan">39 bulan</option>
                                            <option value="40 (Empat Puluh) bulan">40 bulan</option>
                                            <option value="41 (Empat Puluh Satu) bulan">41 bulan</option>
                                            <option value="42 (Empat Puluh Dua) bulan">42 bulan</option>
                                            <option value="43 (Empat Puluh Tiga) bulan">43 bulan</option>
                                            <option value="44 (Empat Puluh Empat) bulan">44 bulan</option>
                                            <option value="45 (Empat Puluh Lima) bulan">45 bulan</option>
                                            <option value="46 (Empat Puluh Enam) bulan">46 bulan</option>
                                            <option value="47 (Empat Puluh Tujuh) bulan">47 bulan</option>
                                            <option value="48 (Empat Puluh Delapan) bulan">48 bulan</option>
                                            <option value="49 (Empat Puluh Sembilan) bulan">49 bulan</option>
                                            <option value="50 (Lima Puluh) bulan">50 bulan</option>
                                            <option value="51 (Lima Puluh Satu) bulan">51 bulan</option>
                                            <option value="52 (Lima Puluh Dua) bulan">52 bulan</option>
                                            <option value="53 (Lima Puluh Tiga) bulan">53 bulan</option>
                                            <option value="54 (Lima Puluh Empat) bulan">54 bulan</option>
                                            <option value="55 (Lima Puluh Lima) bulan">55 bulan</option>
                                            <option value="56 (Lima Puluh Enam) bulan">56 bulan</option>
                                            <option value="57 (Lima Puluh Tujuh) bulan">57 bulan</option>
                                            <option value="58 (Lima Puluh Delapan) bulan">58 bulan</option>
                                            <option value="59 (Lima Puluh Sembilan) bulan">59 bulan</option>
                                            <option value="60 (Enam Puluh) bulan">60 bulan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tj_pulsa">Tunjangan Pulsa</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control rupiah" name="tj_pulsa" id="tj_pulsa" placeholder="Tunjangan Pulsa" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tj_ops">Tunjangan Operasional</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control rupiah" name="tj_ops" id="tj_ops" placeholder="Tunjangan Operasional" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="thr">THR</label>
                                    <div class="col-sm-7">
                                        <select name="thr" id="thr" class="form-control">
                                            <option value="" hidden>Pilih THR</option>
                                            <option value="Ada">Ada</option>
                                            <option value="Tidak Ada">Tidak Ada</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tj_kehadiran">Tunjangan Kehadiran</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control rupiah" name="tj_kehadiran" id="tj_kehadiran" placeholder="Tunjangan Kehadiran" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="insentif">Insentif</label>
                                    <div class="col-sm-7">
                                        <select name="insentif" id="insentif" class="form-control">
                                            <option value="" hidden>Pilih Insentif</option>
                                            <option value="Ada">Ada</option>
                                            <option value="Tidak Ada">Tidak Ada</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Hidden inputs -->
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="">
                        <?php } ?>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-sm aksi mt-3"></button>
                    </form>

                    <!-- Modal -->
                    <div id="imageModal" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <img id="modalImage" src="" alt="Gambar" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $(document).ready(function() {
        $('#npk').select2({
            placeholder: 'Pilih Karyawan',
            allowClear: true,
            width: '100%'
        });

        $(document).on('input', '.only-number', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        $('#tanggal').select2({
            placeholder: "Pilih Tanggal",
            allowClear: true
        });
        $('#tahun').select2({
            placeholder: "Pilih Tahun",
            allowClear: true
        });
        $('#id_user').select2({
            placeholder: "Pilih User",
            allowClear: true
        });
    });

    // Helpers to toggle form state and show message
    function disableForm(message) {
        $('#form input, #form select, #form textarea, #form button, .aksi').prop('disabled', true).css('pointer-events', 'none');
        $('.btn-secondary').prop('disabled', false); // keep Back button enabled
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            html: message,
            confirmButtonColor: "#0e131d"
        });
    }

    function enableForm() {
        $('#form input, #form select, #form textarea, #form button, .aksi').prop('disabled', false).css('pointer-events', 'auto');
    }

    // Hitung max bulan yang bisa ditambah berdasarkan total_bulan existing
    function getMaxAllowedBulan() {
        var summary = window._contractSummary;
        if (!summary || !summary.status) return 120; // default max jika tidak ada summary
        
        var existingBulan = parseInt(summary.total_bulan, 10) || 0;
        var maxTotalBulan = 60; // 5 tahun
        var maxNewBulan = maxTotalBulan - existingBulan; // sisa bulan yang bisa ditambah
        
        return Math.max(0, maxNewBulan); // jangan negative
    }

    // Update stepper state based on existing kontrak
    function updateTotalBulanConstraint() {
        var maxAllowed = getMaxAllowedBulan();
        var $total = $('#total_bulan');
        var $inc = $('#btn_increase');
        var $dec = $('#btn_decrease');

        $total.attr('max', maxAllowed);

        // jika nilai sekarang melebihi max -> clamp
        var currentVal = parseInt($total.val(), 10) || 0;
        if (currentVal > maxAllowed) {
            $total.val(maxAllowed);
        }

        // update button state
        var val = parseInt($total.val(), 10) || 0;
        $dec.attr('aria-disabled', val <= 0).prop('disabled', val <= 0);
        $inc.attr('aria-disabled', val >= maxAllowed).prop('disabled', val >= maxAllowed);
    }

    // Stepper behavior for Total Bulan
    (function(){
        var $total = $('#total_bulan');
        var $inc = $('#btn_increase');
        var $dec = $('#btn_decrease');

        const MIN_MONTHS = 0;

        function clamp(v, min, max){ return Math.max(min, Math.min(max, v)); }
        
        function updateButtons(){
            var maxAllowed = getMaxAllowedBulan();
            var val = parseInt($total.val(),10) || 0;
            $dec.attr('aria-disabled', val <= MIN_MONTHS).prop('disabled', val <= MIN_MONTHS);
            $inc.attr('aria-disabled', val >= maxAllowed).prop('disabled', val >= maxAllowed);
        }

        function showMaxReachedAlert() {
            var maxAllowed = getMaxAllowedBulan();
            var summary = window._contractSummary;
            var existingBulan = (summary && summary.status) ? parseInt(summary.total_bulan, 10) || 0 : 0;
            
            Swal.fire({
                icon: 'warning',
                title: 'Maksimal 5 tahun (60 bulan)',
                html: 'Total kontrak karyawan sudah ' + existingBulan + ' bulan. <br>Hanya bisa menambah ' + maxAllowed + ' bulan lagi untuk mencapai 60 bulan.',
                confirmButtonColor: "#0e131d"
            });
        }

        // increase: +1 (BUKAN +2)
        $inc.on('click', function(){
            var maxAllowed = getMaxAllowedBulan();
            var val = parseInt($total.val(),10) || 0;
            if (val < maxAllowed) {
                $total.val(val + 1).trigger('change');
                if (val + 1 === maxAllowed) {
                    showMaxReachedAlert();
                }
            }
            updateButtons();
            if (typeof computeJkAkhirFromStart === 'function') computeJkAkhirFromStart();
        });

        // decrease: -1
        $dec.on('click', function(){
            var val = parseInt($total.val(),10) || 0;
            if (val > MIN_MONTHS) {
                $total.val(val - 1).trigger('change');
            }
            updateButtons();
            if (typeof computeJkAkhirFromStart === 'function') computeJkAkhirFromStart();
        });

        // keyboard support: arrow up/down
        $total.on('keydown', function(e){
            if (e.key === 'ArrowUp') { e.preventDefault(); $inc.trigger('click'); }
            if (e.key === 'ArrowDown') { e.preventDefault(); $dec.trigger('click'); }
        });

        // ensure value within range and update UI on manual input
        $total.on('input change', function(){
            var maxAllowed = getMaxAllowedBulan();
            var val = parseInt($total.val(),10);
            if (isNaN(val)) val = MIN_MONTHS;
            if (val > maxAllowed) {
                $total.val(maxAllowed);
                showMaxReachedAlert();
                val = maxAllowed;
            } else {
                val = clamp(val, MIN_MONTHS, maxAllowed);
                $total.val(val);
            }
            updateButtons();

            // update start state dan recompute akhir kontrak
            if (typeof updateStartState === 'function') updateStartState();
            if (typeof computeJkAkhirFromStart === 'function') computeJkAkhirFromStart();
        });

        // init
        $(function(){ updateButtons(); });
    })();

    (function(){
        // pastikan state awal dan fungsi compute tersedia
        function updateStartState() {
            var months = parseInt($('#total_bulan').val(), 10);
            var isEdit = ($('#id').length && $('#id').val() && $('#id').val() != '0');
            if (isNaN(months) || months <= 0) {
                if (!isEdit) {
                    $('#jk_awal').prop('readonly', true).css('pointer-events', 'none').val('');
                } else {
                    $('#jk_awal').prop('readonly', true).css('pointer-events', 'none');
                }
                $('#jk_akhir').val('');
            } else {
                $('#jk_awal').prop('readonly', false).css('pointer-events', 'auto');
            }
        }

        function computeJkAkhirFromStart() {
            var months = parseInt($('#total_bulan').val(), 10);
            var startVal = $('#jk_awal').val();
            if (!startVal || isNaN(months) || months <= 0) {
                $('#jk_akhir').val('');
                return;
            }

            var start = null;
            var parts = startVal.split('-');
            if (parts.length === 3) {
                start = new Date(parseInt(parts[0],10), parseInt(parts[1],10)-1, parseInt(parts[2],10));
            } else {
                start = new Date(startVal);
            }
            if (!start || isNaN(start.getTime())) {
                $('#jk_akhir').val('');
                return;
            }

            var dt = new Date(start.getTime());
            dt.setMonth(dt.getMonth() + months);
            dt.setDate(dt.getDate() - 1);

            var y = dt.getFullYear();
            var m = ('0' + (dt.getMonth() + 1)).slice(-2);
            var d = ('0' + dt.getDate()).slice(-2);
            var iso = y + '-' + m + '-' + d;

            $('#jk_akhir').val(iso).trigger('change');
        }

        $(function(){
            updateStartState();
            if ($('#jk_awal').val()) computeJkAkhirFromStart();
        });

        $(document).off('input change', '#total_bulan').on('input change', '#total_bulan', function(){
            var isEdit = ($('#id').length && $('#id').val() && $('#id').val() != '0');
            
            updateStartState();
            
            // Jika edit mode: jangan ubah jk_awal, hanya recompute jk_akhir
            if (isEdit && typeof computeJkAkhirFromStart === 'function') {
                computeJkAkhirFromStart();
            } else if (!isEdit && typeof computeJkAkhirFromStart === 'function') {
                // Create mode: compute normally
                computeJkAkhirFromStart();
            }
        });

        $(document).off('change', '#jk_awal').on('change', '#jk_awal', function(){
            computeJkAkhirFromStart();
        });
    })();
</script>
<script>
    (function(){
        // pastikan state awal dan fungsi compute tersedia
        function updateStartState() {
            var months = parseInt($('#total_bulan').val(), 10);
            var isEdit = ($('#id').length && $('#id').val() && $('#id').val() != '0');
            if (isNaN(months) || months <= 0) {
                if (!isEdit) {
                    $('#jk_awal').prop('readonly', true).css('pointer-events', 'none').val('');
                } else {
                    $('#jk_awal').prop('readonly', true).css('pointer-events', 'none');
                }
                $('#jk_akhir').val('');
            } else {
                $('#jk_awal').prop('readonly', false).css('pointer-events', 'auto');
            }
        }

        function computeJkAkhirFromStart() {
            var months = parseInt($('#total_bulan').val(), 10);
            var startVal = $('#jk_awal').val();
            if (!startVal || isNaN(months) || months <= 0) {
                $('#jk_akhir').val('');
                return;
            }

            var start = null;
            var parts = startVal.split('-');
            if (parts.length === 3) {
                start = new Date(parseInt(parts[0],10), parseInt(parts[1],10)-1, parseInt(parts[2],10));
            } else {
                start = new Date(startVal);
            }
            if (!start || isNaN(start.getTime())) {
                $('#jk_akhir').val('');
                return;
            }

            var dt = new Date(start.getTime());
            dt.setMonth(dt.getMonth() + months);
            dt.setDate(dt.getDate() - 1);

            var y = dt.getFullYear();
            var m = ('0' + (dt.getMonth() + 1)).slice(-2);
            var d = ('0' + dt.getDate()).slice(-2);
            var iso = y + '-' + m + '-' + d;

            $('#jk_akhir').val(iso).trigger('change');
        }

        // inisialisasi saat load
        $(function(){
            updateStartState();
            // jika ada nilai awal (edit), hitung ulang
            if ($('#jk_awal').val()) computeJkAkhirFromStart();
        });

        // sambungkan dengan event yang sudah ada
        $(document).off('input change', '#total_bulan').on('input change', '#total_bulan', function(){
            var isEdit = ($('#id').length && $('#id').val() && $('#id').val() != '0');
            
            updateStartState();
            
            // Jika edit mode: jangan ubah jk_awal, hanya recompute jk_akhir
            if (isEdit && typeof computeJkAkhirFromStart === 'function') {
                computeJkAkhirFromStart();
            } else if (!isEdit && typeof computeJkAkhirFromStart === 'function') {
                // Create mode: compute normally
                computeJkAkhirFromStart();
            }
        });

        // saat pengguna memilih tanggal awal
        $(document).off('change', '#jk_awal').on('change', '#jk_awal', function(){
            computeJkAkhirFromStart();
        });
    })();
</script>
<script>
    $('#npk').off('change').on('change', function() {
        var val = $(this).val() || '';
        var npk = val.split('-')[0];
        var isEdit = ($('#id').length && $('#id').val() && $('#id').val() != '0');

        // jika mode edit, jangan reset atau ubah tanggal -> keluar langsung
        if (isEdit) {
            console.log('Edit mode: skip npk change handler');
            return;
        }

        window._contractSummary = null;
        enableForm();
        updateTotalBulanConstraint(); // reset constraint

        if (!npk) {
            $('#jk_awal').val('');
            $('#jk_akhir').val('');
            $('#tgl_masuk_iso').val('');
            $('#tgl_akhir_kontrak_iso').val('');
            updateTotalBulanConstraint();
            return;
        }

        $.ajax({
            url: '<?= site_url("kps_karyawan/get_karyawan_dates") ?>',
            method: 'POST',
            dataType: 'json',
            data: { npk: npk },
            success: function(res) {
                if (res && res.status) {
                    if (res.tgl_masuk) {
                        if ($('#tgl_masuk_iso').length) $('#tgl_masuk_iso').val(res.tgl_masuk);
                    }
                    if (res.tgl_akhir_kontrak) {
                        if ($('#tgl_akhir_kontrak_iso').length) $('#tgl_akhir_kontrak_iso').val(res.tgl_akhir_kontrak);
                    }
                }

                $.ajax({
                    url: '<?= site_url("kps_karyawan/get_contract_summary") ?>',
                    method: 'POST',
                    dataType: 'json',
                    data: { npk: npk },
                    success: function(summary) {
                        window._contractSummary = summary && summary.status ? summary : null;
                        updateTotalBulanConstraint(); // update max bulan based on existing kontrak

                        if (summary && summary.status && summary.last_end) {
                            var last = new Date(summary.last_end);
                            var nextStart = new Date(last);
                            nextStart.setDate(nextStart.getDate() + 1);

                            if (parseInt(summary.total_bulan, 10) >= 60) {
                                var gapDate = new Date(summary.last_end);
                                gapDate.setDate(gapDate.getDate() + 31);
                                var today = new Date();
                                if (today < gapDate) {
                                    var y = gapDate.getFullYear();
                                    var m = ('0' + (gapDate.getMonth() + 1)).slice(-2);
                                    var d = ('0' + gapDate.getDate()).slice(-2);
                                    var allowedIso = y + '-' + m + '-' + d;
                                    $('#jk_awal').val(allowedIso).trigger('change');
                                    disableForm('Karyawan sudah mencapai 5 tahun kontrak. Kontrak baru hanya boleh dimulai setelah ' + allowedIso + '.');
                                    return;
                                } else {
                                    nextStart = gapDate > nextStart ? gapDate : nextStart;
                                }
                            }

                            var y2 = nextStart.getFullYear();
                            var m2 = ('0' + (nextStart.getMonth() + 1)).slice(-2);
                            var d2 = ('0' + nextStart.getDate()).slice(-2);
                            var nextDayIso = y2 + '-' + m2 + '-' + d2;
                            $('#jk_awal').val(nextDayIso).trigger('change');
                            if ($('#tgl_masuk_iso').length) $('#tgl_masuk_iso').val(nextDayIso);
                            if (typeof computeJkAkhirFromStart === 'function') computeJkAkhirFromStart();
                        } else {
                            if (res && res.status && res.tgl_masuk) {
                                $('#jk_awal').val(res.tgl_masuk).trigger('change');
                                if ($('#tgl_masuk_iso').length) $('#tgl_masuk_iso').val(res.tgl_masuk);
                                if (typeof computeJkAkhirFromStart === 'function') computeJkAkhirFromStart();
                            } else {
                                $('#jk_awal').val('').trigger('change');
                                $('#jk_akhir').val('').trigger('change');
                            }
                        }
                    },
                    error: function() {
                        window._contractSummary = null;
                        updateTotalBulanConstraint();
                    }
                });
            },
            error: function() {
                console.warn('Gagal ambil data karyawan');
                updateTotalBulanConstraint();
            }
        });
    });

    // Re-check on submit
    $("#form").on('submit', function(e) {
        var summary = window._contractSummary;
        if (summary && summary.status && parseInt(summary.total_bulan, 10) >= 60 && summary.last_end) {
            var allowedDate = new Date(summary.last_end);
            allowedDate.setDate(allowedDate.getDate() + 31);
            var jk_awal_val = $('#jk_awal').val();
            var cand = jk_awal_val ? new Date(jk_awal_val) : null;
            if (!cand || cand < allowedDate) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Dilarang',
                    text: 'Karyawan telah mencapai 5 tahun kontrak. Kontrak baru hanya boleh dimulai minimal 1 bulan setelah kontrak terakhir (' + allowedDate.toISOString().slice(0,10) + ').'
                });
                return false;
            }
        }

        // Cek total_bulan tidak melebihi sisa yang diperbolehkan
        var totalBulanVal = parseInt($('#total_bulan').val(), 10) || 0;
        var maxAllowed = getMaxAllowedBulan();
        if (totalBulanVal > maxAllowed) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Total Bulan Melebihi Batas',
                text: 'Total bulan tidak boleh melebihi ' + maxAllowed + ' bulan.'
            });
            return false;
        }
    });
    $('#jk_awal, #jk_akhir').on('change', function() {
        var jk_awal = $('#jk_awal').val();
        var jk_akhir = $('#jk_akhir').val();

        if (jk_awal && jk_akhir) {
            var awal = new Date(jk_awal);
            var akhir = new Date(jk_akhir);

            var tahunAwal = awal.getFullYear();
            var tahunAkhir = akhir.getFullYear();
            var bulanAwal = awal.getMonth();
            var bulanAkhir = akhir.getMonth();

            // Hitung total bulan (inklusif)
            var totalBulan = (tahunAkhir - tahunAwal) * 12 + (bulanAkhir - bulanAwal) + 1;
            if (totalBulan < 1) totalBulan = 1;

            // Pilih jangka waktu sesuai hasil
            $('#jangka_waktu').val(totalBulan + ' (' + getBulanTerbilang(totalBulan) + ') bulan').trigger('change');
        }
    });

    // Fungsi untuk mengubah angka ke terbilang Indonesia
    function getBulanTerbilang(num) {
        var arr = [
        "", "Satu","Dua","Tiga","Empat","Lima","Enam","Tujuh","Delapan","Sembilan",
        "Sepuluh","Sebelas","Dua Belas","Tiga Belas","Empat Belas","Lima Belas","Enam Belas","Tujuh Belas","Delapan Belas","Sembilan Belas",
        "Dua Puluh","Dua Puluh Satu","Dua Puluh Dua","Dua Puluh Tiga","Dua Puluh Empat","Dua Puluh Lima","Dua Puluh Enam","Dua Puluh Tujuh","Dua Puluh Delapan","Dua Puluh Sembilan",
        "Tiga Puluh","Tiga Puluh Satu","Tiga Puluh Dua","Tiga Puluh Tiga","Tiga Puluh Empat","Tiga Puluh Lima","Tiga Puluh Enam","Tiga Puluh Tujuh","Tiga Puluh Delapan","Tiga Puluh Sembilan",
        "Empat Puluh","Empat Puluh Satu","Empat Puluh Dua","Empat Puluh Tiga","Empat Puluh Empat","Empat Puluh Lima","Empat Puluh Enam","Empat Puluh Tujuh","Empat Puluh Delapan","Empat Puluh Sembilan",
        "Lima Puluh","Lima Puluh Satu","Lima Puluh Dua","Lima Puluh Tiga","Lima Puluh Empat","Lima Puluh Lima","Lima Puluh Enam","Lima Puluh Tujuh","Lima Puluh Delapan","Lima Puluh Sembilan",
        "Enam Puluh"
        ];

        return arr[num] || num;
    }

    function getTahunTerbilang(year) {
        var map = {
            2025: "Dua Ribu Dua Puluh Lima",
            2026: "Dua Ribu Dua Puluh Enam",
            2027: "Dua Ribu Dua Puluh Tujuh",
            2028: "Dua Ribu Dua Puluh Delapan",
            2029: "Dua Ribu Dua Puluh Sembilan",
            2030: "Dua Ribu Tiga Puluh",
            2031: "Dua Ribu Tiga Puluh Satu",
            2032: "Dua Ribu Tiga Puluh Dua",
            2033: "Dua Ribu Tiga Puluh Tiga",
            2034: "Dua Ribu Tiga Puluh Empat",
            2035: "Dua Ribu Tiga Puluh Lima",
            2036: "Dua Ribu Tiga Puluh Enam",
            2037: "Dua Ribu Tiga Puluh Tujuh",
            2038: "Dua Ribu Tiga Puluh Delapan",
            2039: "Dua Ribu Tiga Puluh Sembilan",
            2040: "Dua Ribu Empat Puluh",
            2041: "Dua Ribu Empat Puluh Satu",
            2042: "Dua Ribu Empat Puluh Dua",
            2043: "Dua Ribu Empat Puluh Tiga",
            2044: "Dua Ribu Empat Puluh Empat",
            2045: "Dua Ribu Empat Puluh Lima",
            2046: "Dua Ribu Empat Puluh Enam",
            2047: "Dua Ribu Empat Puluh Tujuh",
            2048: "Dua Ribu Empat Puluh Delapan",
            2049: "Dua Ribu Empat Puluh Sembilan",
            2050: "Dua Ribu Lima Puluh"
        };
        return map[year] || year;
    }

    $('#jk_awal').on('change', function() {
        var jk_awal = $(this).val();
        if (jk_awal) {
            var tanggalObj = new Date(jk_awal);

            // Hari (Senin, Selasa, dst)
            var hariArr = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var hari = hariArr[tanggalObj.getDay()];
            $('#hari').val(hari).trigger('change');

            // Tanggal (Satu, Dua, dst)
            var tanggalNum = tanggalObj.getDate();
            var tanggalArr = [
                '', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh',
                'Sebelas', 'Dua Belas', 'Tiga Belas', 'Empat Belas', 'Lima Belas', 'Enam Belas', 'Tujuh Belas',
                'Delapan Belas', 'Sembilan Belas', 'Dua Puluh', 'Dua Puluh Satu', 'Dua Puluh Dua', 'Dua Puluh Tiga',
                'Dua Puluh Empat', 'Dua Puluh Lima', 'Dua Puluh Enam', 'Dua Puluh Tujuh', 'Dua Puluh Delapan',
                'Dua Puluh Sembilan', 'Tiga Puluh', 'Tiga Puluh Satu'
            ];
            $('#tanggal').val(tanggalArr[tanggalNum]).trigger('change');

            // Bulan (Januari, dst)
            var bulanArr = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            var bulan = bulanArr[tanggalObj.getMonth()];
            $('#bulan').val(bulan).trigger('change');

            // Tahun (2025, dst)
            var tahunNum = tanggalObj.getFullYear();
            var tahunTerbilang = getTahunTerbilang(tahunNum);
            $('#tahun').val(tahunTerbilang).trigger('change');
        }
    });

    function getRoman(num) {
        // Fungsi untuk mengubah angka ke romawi
        const romans = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
        return romans[parseInt(num)] || num;
    }

    function generateNoPerjanjian() {
        let npk = $('#npk').val();
        let jk_awal = $('#jk_awal').val();
        let jk_akhir = $('#jk_akhir').val();

        if (npk && jk_awal && jk_akhir) {
            $.ajax({
                url: '<?= site_url("kps_karyawan/generate_no_perjanjian") ?>',
                type: 'POST',
                data: {
                    npk: npk,
                    jk_awal: jk_awal,
                    jk_akhir: jk_akhir
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        $('#no_perjanjian').val(res.no_perjanjian);
                    } else {
                        $('#no_perjanjian').val('');
                    }
                }
            });
        }
    }

    // Trigger generate saat ketiga input terisi/berubah
    $('#npk, #jk_awal, #jk_akhir').on('change', generateNoPerjanjian);

    // Validasi tanggal kontrak awal dan akhir
    $('#jk_awal, #jk_akhir').on('change', function() {
        let jk_awal = $('#jk_awal').val();
        let jk_akhir = $('#jk_akhir').val();

        if (jk_awal && jk_akhir) {
            let awal = new Date(jk_awal);
            let akhir = new Date(jk_akhir);

            // jika tanggal akhir sebelum tanggal awal -> error
            if (akhir < awal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Tanggal akhir harus sama atau setelah tanggal awal.',
                    confirmButtonColor: "#0e131d"
                });
                $('#jk_akhir').val('');
                // update jangka waktu juga bersih jika perlu
                $('#jangka_waktu').val('').trigger('change');
                return;
            }

            // hitung total bulan inklusif
            var tahunAwal = awal.getFullYear();
            var tahunAkhir = akhir.getFullYear();
            var bulanAwal = awal.getMonth();
            var bulanAkhir = akhir.getMonth();

            // Hitung total bulan
            var totalBulan = (tahunAkhir - tahunAwal) * 12 + (bulanAkhir - bulanAwal) + 1;
            if (totalBulan < 1) totalBulan = 1;

            $('#jangka_waktu').val(totalBulan + ' (' + getBulanTerbilang(totalBulan) + ') bulan').trigger('change');
        }
    });

    $(document).ready(function() {
        $('.rupiah').on('input', function() {
            let input = $(this).val().replace(/[^,\d]/g, '').toString();
            let split = input.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            $(this).val('Rp ' + rupiah);
        });
    });

    $(function() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd', // atau format lain sesuai kebutuhan
            changeMonth: true,
            yearRange: "1900:2099", // bisa disesuaikan
            changeYear: true,
            autoclose: true
        });
    });

    $('#status_kerja').on('change', function() {
        if ($(this).val() == 'nonaktif') {
            $('#tgl_phk_container').show();
        } else {
            $('#tgl_phk_container').hide();
        }
    });

    $('#foto').on('change', function() {
        const file = this.files[0];

        if (file) {
            const maxSize = 3 * 1024 * 1024; // 3 MB dalam byte

            if (file.size > maxSize) {
                swal.fire({
                    icon: 'warning',
                    text: "Ukuran file tidak boleh melebihi dari 3MB!",
                    confirmButtonColor: "#0e131d"
                });
                $(this).val(''); // reset input file
            }
        }
    });

    $(document).ready(function() {
        $('#nama_bank').select2({
            placeholder: "Pilih Nama Bank",
            allowClear: true
        });
        $('#jabatan').select2({
            placeholder: "Pilih Jabatan",
            allowClear: true
        });
        $('#grade').select2({
            placeholder: "Pilih Grade",
            allowClear: true
        });
    });

    $(document).on('change', '#tgl_lahir', function() {
        var tgl = $(this).val();
        if (tgl) {
            var birthYear = new Date(tgl).getFullYear();
            var nowYear = new Date().getFullYear();
            var umur = nowYear - birthYear;
            if (umur < 0) umur = 0;
            $('#umur').val(umur);
        } else {
            $('#umur').val('');
        }
    });

    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('#customer_id').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='group']").hide();
            $('#npk').attr('disabled', true);
            $('#jk_awal').attr('readonly', true).css('pointer-events', 'none');
            $('#jk_akhir').attr('readonly', true).css('pointer-events', 'none');
            $.ajax({
                url: "<?php echo site_url('kps_karyawan/edit_data2') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#id').val(data['transaksi'].id);
                    // // ISI FIELD PRODUK AGEN
                    $('#id_user').val(data['transaksi'].id_user).change();
                    var npkVal = data['transaksi'].npk + '-' + data['transaksi'].id_user;
                    var npkText = data['transaksi'].nama_lengkap || npkVal;
                    if ($('#npk option[value="' + npkVal + '"]').length === 0) {
                        // buat option baru (selected)
                        var newOption = new Option(npkText, npkVal, true, true);
                        $('#npk').append(newOption).trigger('change');
                    } else {
                        $('#npk').val(npkVal).trigger('change');
                    }
                    // jika ingin men-disable select tetap tampil selection-nya
                    $('#npk').prop('disabled', true).trigger('change.select2');
                    $('#no_perjanjian').val(data['transaksi'].no_perjanjian);
                    $('#jk_awal').val(data['transaksi'].jk_awal);
                    $('#jk_akhir').val(data['transaksi'].jk_akhir);
                    $('#hari').val(data['transaksi'].hari);
                    $('#tanggal').val(data['transaksi'].tanggal).change();
                    $('#bulan').val(data['transaksi'].bulan);
                    $('#tahun').val(data['transaksi'].tahun).change();
                    $('#jangka_waktu').val(data['transaksi'].jangka_waktu);
                    $('#gaji').val(data['transaksi']['gaji'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#tj_pulsa').val(data['transaksi']['tj_pulsa'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#tj_ops').val(data['transaksi']['tj_ops'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#thr').val(data['transaksi']['thr'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#tj_kehadiran').val(data['transaksi']['tj_kehadiran'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#insentif').val(data['transaksi'].insentif);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

        }

        // Script delete data file gambar

        function deleteFile(field) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "File ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo base_url("kps_karyawan/delete_file"); ?>', // URL controller untuk menghapus file
                        type: 'POST',
                        data: {
                            id: id, // Kirim ID customer untuk diupdate
                            field: field // Kirim nama field yang akan dihapus
                        },
                        success: function(response) {
                            Swal.fire(
                                'Terhapus!',
                                'File berhasil dihapus.',
                                'success'
                            );
                            // Update tampilan untuk field yang dihapus
                            $('#' + field + '_foto').hide();
                            $('#' + field + '_image').hide();
                            $('#' + field + '_tidak_ada').text('Tidak ada').show();
                            $('#delete_' + field).hide();
                        },
                        error: function() {
                            Swal.fire('Error', 'Tidak dapat menghapus file.', 'error');
                        }
                    });
                }
            });
        }

        if (aksi == "read") {
            $('#form input, #form select, #form textarea').prop('disabled', true).css('cursor', 'not-allowed');
            $('.aksi').hide(); // Sembunyikan tombol aksi jika mode read
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('kps_karyawan/add_kontrak_karyawan') ?>";
            } else {
                url = "<?php echo site_url('kps_karyawan/update_kontrak_karyawan/') ?>" + id;
            }

            // Gunakan FormData untuk menangani file upload
            var formData = new FormData($form[0]); // Ambil semua data form termasuk file

            // Tampilkan loading Swal sebelum request dikirim
            Swal.fire({
                title: 'Memproses...',
                html: 'Mohon tunggu, data sedang disimpan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false, // Agar tidak mengubah tipe konten FormData
                processData: false, // Agar data FormData tidak diproses menjadi string
                dataType: "JSON",
                success: function(data) {
                    Swal.close(); // Tutup loading swal setelah request selesai

                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.href = "<?= base_url('kps_karyawan/e_pkwt') ?>";
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.close(); // Tutup loading swal jika terjadi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error adding / updating data!',
                    });
                }
            });
        });

        $("#form").validate({
            rules: {
                status_kerja: {
                    required: true,
                }
            },
            messages: {},
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