<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('template/header'); ?>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            padding: 0;
            color: #333;
        }

        .form-container {
            max-width: 800px;
            margin: 15px auto;
            padding: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

 .header {
    text-align: center;
    margin-bottom: 30px;
}

.header h1 {
    margin: 0;
    font-size: 24px;
    text-transform: uppercase;
}

.header .d-flex {
    margin-top: 15px; /* Added margin-top for spacing */
    margin-bottom: 10px;
}

.header p {
    margin: 0;
    font-size: 14px;
}

.header h2 {
    margin-top: 20px;
    font-size: 20px;
    font-weight: normal;
}

.d-flex p {
    margin-bottom: 0;
}

        .form-section {
            margin-bottom: 25px;
        }

        .form-section label {
            font-weight: 600;
            color: #495057;
        }

        .table-rincian,
        .table-section {
            width: 100%;
            margin-top: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }

        .table-rincian th,
        .table-rincian td,
        .table-section th,
        .table-section td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .table-rincian th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .table-rincian td,
        .table-section td {
            height: 40px;
            font-size: 14px;
            color: #495057;
        }

        .table-section th {
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
        }

        .signature-text {
            margin-top: 10px;
            font-size: 14px;
            color: #495057;
        }

        .form-control {
            border-radius: 4px;
            padding: 10px;
            font-size: 14px;
        }

        .btn {
            border-radius: 5px;
            font-weight: 600;
        }

        .modal-content {
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #e0e0e0;
        }

        .modal-footer {
            border-top: 1px solid #e0e0e0;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 22px;
            }

            .header h2 {
                font-size: 18px;
            }

            .table-rincian th,
            .table-rincian td,
            .table-section th,
            .table-section td {
                font-size: 12px;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 20px;
            }

            .header h2 {
                font-size: 16px;
            }

            .table-rincian th,
            .table-rincian td,
            .table-section th,
            .table-section td {
                font-size: 10px;
                padding: 6px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <div class="d-flex justify-content-end mb-3">
                <a class="btn btn-danger btn-sm mr-2" href="<?= base_url('datadeklarasi') ?>"><i class="fas fa-file-pdf"></i>&nbsp;Print Out</a>
                <a class="btn btn-secondary btn-sm" href="<?= base_url('datadeklarasi') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
            </div>

            <!-- Header Section -->
            <div class="header">
    <h1>PT. MANDIRI CIPTA SEJAHTERA</h1>
    <div class="d-flex justify-content-center align-items-center">
        <p class="mb-1 mr-3"><strong>Divisi:</strong> <span id="divisi"></span></p>
        <p class="mb-1"><strong>Deklarasi:</strong> <span id="kodedeklarasi"></span></p>
    </div>
    <h2>FORM DEKLARASI</h2>
</div>

            <!-- Form Sections -->
            <div class="form-group row">
                <label for="tanggal" class="col-sm-3 col-form-label">Tanggal:</label>
                <div class="col-sm-9">
                    <span id="tanggal" class="form-control-plaintext"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="nama_pembayar" class="col-sm-3 col-form-label">Nama:</label>
                <div class="col-sm-9">
                    <span id="nama_pembayar" class="form-control-plaintext"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="jabatan" class="col-sm-3 col-form-label">Jabatan:</label>
                <div class="col-sm-9">
                    <span id="jabatan" class="form-control-plaintext"></span>
                </div>
            </div>

            <p>Telah/akan melakukan pembayaran kepada:</p>

            <div class="form-group row">
                <label for="nama_penerima" class="col-sm-3 col-form-label">Nama:</label>
                <div class="col-sm-9">
                    <span id="nama_penerima" class="form-control-plaintext"></span>
                    <input type="hidden" id="hidden_id" value="<?php echo $id ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="tujuan" class="col-sm-3 col-form-label">Tujuan:</label>
                <div class="col-sm-9">
                    <span id="tujuan" class="form-control-plaintext"></span>
                    <input type="hidden" id="hidden_id" value="<?php echo $id ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="sebesar" class="col-sm-3 col-form-label">Sebesar:</label>
                <div class="col-sm-9">
                    <span id="sebesar" class="form-control-plaintext"></span>
                    <input type="hidden" id="hidden_id" value="<?php echo $id ?>">
                </div>
            </div>

            <!-- Signature Table -->
            <table class="table table-bordered table-section">
                <thead>
                    <tr>
                        <th>Yang melakukan</th>
                        <th>Mengetahui</th>
                        <th>Menyetujui</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                           
                        </td>
                        <td>
                            <input type="text" id="signature_mengetahui" class="form-control text-center" placeholder="">
                        </td>
                        <td>
                            <input type="text" id="signature_menyetujui" class="form-control text-center" placeholder="">
                        </td>
                    </tr>
                    <tr>
                        <td id="melakukan">
                            <div class="signature-text text-center">Rakha</div>
                        </td>
                        <td id="mengetahui">
                            <div class="signature-text  text-center">Pak Deden</div>
                        </td>
                        <td id="menyetujui">
                            <div class="signature-text  text-center">Pak Heri</div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Comment Section -->
            <div class="form-group row">
                <label for="keterangan" class="col-sm-3 col-form-label">Keterangan:</label>
                <div class="col-sm-9">
                    <span id="keterangan" class="form-control-plaintext"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade
        id="appModal" tabindex="-1" role="dialog" aria-labelledby="appModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appModalLabel">Approval Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this prepayment request?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmApproval">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            // Handle the approval button click event
            $('#confirmApproval').click(function () {
                const id = $('#hidden_id').val();
                // Implement the logic to process the approval
                console.log('Approval confirmed for ID:', id);
                // Close the modal
                $('#appModal').modal('hide');
            });

            // Additional logic to dynamically load data into the form
            // Example: Load data into the form fields and tables
            $('#divisi').text('Finance');
            $('#kodedeklarasi').text('001234');
            $('#tanggal').text('29 August 2024');
            $('#nama_pembayar').text('Rakha Rizki');
            $('#jabatan').text('Software Developer');
            $('#nama_penerima').text('Aldo');
            $('#tujuan').text('Development');
            $('#sebesar').text('30.000');

            $('#keterangan').text('This prepayment is requested for the ongoing project development phase.');
        });
    </script>
</body>

</html>