<style>
    table #items-container tr td:nth-child(1),
    table #items-container tr td:nth-child(6) {
        text-align: center;
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
                    <a class="btn btn-primary btn-sm" href="<?= base_url('sw_invoice') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <!-- Row 1: Master Input Fields -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Letter Number -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="letter_number">Letter Number</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="letter_number" name="letter_number" readonly>
                                    </div>
                                </div>

                                <!-- Letter Date -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="letter_date">Letter Date</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="letter_date" name="letter_date" placeholder="dd-mm-yyyy" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>

                                <!-- Company Name -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="company_name">Company Name</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Event Type -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="event_type">Event Type</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="event_type" name="event_type" placeholder="Event Type">
                                    </div>
                                </div>

                                <!-- Final Date -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="final_date">Final Date</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="final_date" name="final_date" placeholder="dd-mm-yyyy" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>

                                <!-- Total Amount -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="total_amount">Total Amount</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="total_amount" name="total_amount" placeholder="0" data-hidden="total_amount_hidden" readonly>
                                            <input type="hidden" id="total_amount_hidden" name="total_amount_value">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Item Details Section -->
                        <hr>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-add-item">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                        </div>

                        <!-- Items Container Table -->
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="items-table">
                                <thead class="table-light" style="background-color: #242d4a;">
                                    <tr style="color: white; text-align: center;">
                                        <td width="5%" style="padding: 10px;">No</td>
                                        <td width="30%" style="padding: 10px;">Remarks</td>
                                        <td width="20%" style="padding: 10px;">Unit Price</td>
                                        <td width="15%" style="padding: 10px;">Qty</td>
                                        <td width="20%" style="padding: 10px;">Total</td>
                                        <td width="10%" style="padding: 10px;">Action</td>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    <!-- Item rows will be appended here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Loading indicator -->
                        <div id="loading" style="display: none;">
                            <p>Loading...</p>
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
                        <button type="submit" class="btn btn-primary btn-sm aksi"></button>

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
        var id = $('#id').val();

        // ========== GENERATE LETTER NUMBER ==========
        function generateLetterNumber() {
            $.ajax({
                url: "<?php echo site_url('sw_invoice/generate_letter_number') ?>",
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    $('#letter_number').val(data.letter_number);
                },
                error: function(error) {
                    console.error("Error generating letter number:", error);
                }
            });
        }

        // ========== DATEPICKER INITIALIZATION ==========
        var datePickerOptions = {
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true
        };

        $('#letter_date').datepicker(datePickerOptions);
        $('#final_date').datepicker(datePickerOptions);

        // ========== CURRENCY FORMAT FUNCTION ==========
        function formatCurrency(value) {
            // Remove non-numeric characters except for comma and dot
            let cleanValue = value.replace(/[^,\d]/g, '');
            let integerPart = cleanValue.replace(/\D/g, '');

            // Format with thousand separator
            if (integerPart) {
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            return integerPart;
        }

        // ========== CURRENCY INPUT HANDLERS ==========
        ['total_amount'].forEach(function(fieldId) {
            $('#' + fieldId).on('input', function() {
                let formatted = formatCurrency($(this).val());
                $(this).val(formatted);

                // Store clean value in hidden field
                let cleanValue = $(this).val().replace(/\./g, '');
                $('#' + fieldId + '_hidden').val(cleanValue);
            });

            // Set initial format if there's a value
            let currentVal = $('#' + fieldId).val();
            if (currentVal) {
                let formatted = formatCurrency(currentVal);
                $('#' + fieldId).val(formatted);
                $('#' + fieldId + '_hidden').val(formatted.replace(/\./g, ''));
            }
        });



        // ========== LOAD DATA FOR EDIT ==========
        if (id != 0) {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            
            $.ajax({
                url: "<?php echo site_url('sw_invoice/get_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id');
                    $('#id').val(data.id);
                    $('#letter_number').val(data.letter_number);
                    $('#letter_date').val(moment(data.letter_date).format('DD-MM-YYYY'));
                    $('#company_name').val(data.company_name);
                    $('#event_type').val(data.event_type);
                    $('#total_amount').val(formatCurrency(data.total_amount)).trigger('input');
                    $('#final_date').val(moment(data.final_date).format('DD-MM-YYYY'));

                    // Load Item Details
                    if (data.items && data.items.length > 0) {
                        $.each(data.items, function(index) {
                            itemCount++;
                            const item = data.items[index];
                            
                            const row = `
                                <tr id="item-row-${itemCount}">
                                    <td class="item-number">${itemCount}</td>
                                    <td>
                                        <textarea class="form-control form-control-sm remarks" name="remarks[${itemCount}]" id="remarks_${itemCount}" placeholder="Item remarks" rows="1">${item.remarks || ''}</textarea>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm unit-price" name="unit_price[${itemCount}]" id="unit_price_${itemCount}" placeholder="0" value="${formatCurrency(item.unit_price)}">
                                        <input type="hidden" id="hidden_unit_price_${itemCount}" name="unit_price_clean[${itemCount}]" value="${item.unit_price}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm qty" name="qty[${itemCount}]" id="qty_${itemCount}" placeholder="0" min="0" value="${item.qty}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm total-price" name="total_price[${itemCount}]" id="total_price_${itemCount}" placeholder="0" readonly value="${formatCurrency(item.total_price)}">
                                        <input type="hidden" id="hidden_total_price_${itemCount}" name="total_price_clean[${itemCount}]" value="${item.total_price}">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-item" data-id="${itemCount}" style="background-color: #dc0808; padding: 5px 15px; border: none; border-radius: 4px;">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            `;
                            $('#items-container').append(row);
                        });
                        
                        // Recalculate master total after loading all items
                        calculateMasterTotal();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error loading data: ' + textStatus
                    });
                }
            });
        } else {
            // Generate letter number for new record
            generateLetterNumber();
            $('.aksi').text('Save');
        }

        
        // ========== ITEM DETAILS MANAGEMENT ==========
        let itemCount = 0;

        // Add Item Button Click
        $(document).on('click', '#btn-add-item', function() {
            itemCount++;
            const row = `
                <tr id="item-row-${itemCount}">
                    <td class="item-number">${itemCount}</td>
                    <td>
                        <textarea class="form-control form-control-sm remarks" name="remarks[${itemCount}]" id="remarks_${itemCount}" placeholder="Item remarks" rows="1"></textarea>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm unit-price" name="unit_price[${itemCount}]" id="unit_price_${itemCount}" placeholder="0" required>
                        <input type="hidden" id="hidden_unit_price_${itemCount}" name="unit_price_clean[${itemCount}]" value="">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm qty" name="qty[${itemCount}]" id="qty_${itemCount}" placeholder="0" min="0" value="1" required>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm total-price" name="total_price[${itemCount}]" id="total_price_${itemCount}" placeholder="0" readonly>
                        <input type="hidden" id="hidden_total_price_${itemCount}" name="total_price_clean[${itemCount}]" value="">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-item" data-id="${itemCount}" style="background-color: #dc0808; padding: 5px 15px; border: none; border-radius: 4px;">
                            Delete
                        </button>
                    </td>
                </tr>
            `;
            $('#items-container').append(row);
            reorderItemRows();
            calculateMasterTotal();
        });

        // Calculate Total Price (unit_price * qty)
        $(document).on('input', '.qty', function() {
            calculateRowTotal($(this).closest('tr'));
            calculateMasterTotal();
        });

        // Handle unit price input changes
        $(document).on('input', '.unit-price', function() {
            let formatted = formatCurrency($(this).val());
            $(this).val(formatted);
            
            // Store clean value in hidden field
            let cleanValue = $(this).val().replace(/\./g, '');
            const fieldId = $(this).attr('id');
            $('#hidden_' + fieldId).val(cleanValue);
            
            // Recalculate total price for this row
            calculateRowTotal($(this).closest('tr'));
            calculateMasterTotal();
        });

        // Helper function to calculate row total
        function calculateRowTotal(row) {
            const unitPriceInput = row.find('.unit-price');
            const fieldId = unitPriceInput.attr('id');
            const unitPrice = parseFloat($('#hidden_' + fieldId).val()) || 0;
            const qty = parseFloat(row.find('.qty').val()) || 0;
            const totalPrice = unitPrice * qty;
            
            // Format and display total price
            const totalPriceFormatted = formatCurrency(totalPrice.toString());
            row.find('.total-price').val(totalPriceFormatted);
            
            // Store clean value in hidden field
            const totalPriceId = row.find('.total-price').attr('id');
            $('#hidden_' + totalPriceId).val(totalPrice.toFixed(0));
        }

        // Calculate Master Total (sum of all total_price)
        function calculateMasterTotal() {
            let masterTotal = 0;
            
            $('#items-container tr').each(function() {
                const totalPriceId = $(this).find('.total-price').attr('id');
                const totalPrice = parseFloat($('#hidden_' + totalPriceId).val()) || 0;
                masterTotal += totalPrice;
            });
            
            // Format and display total amount
            const totalAmountFormatted = formatCurrency(masterTotal.toString());
            $('#total_amount').val(totalAmountFormatted);
            
            // Store clean value in hidden field
            $('#total_amount_hidden').val(masterTotal.toFixed(0));
        }



        // Remove Item
        $(document).on('click', '.btn-remove-item', function() {
            $(this).closest('tr').remove();
            reorderItemRows();
            calculateMasterTotal();
        });

        // Reorder Item Rows
        function reorderItemRows() {
            $('#items-container tr').each(function(index) {
                const newRowNumber = index + 1;
                const remarksValue = $(this).find('.remarks').val();
                const unitPriceValue = $(this).find('.unit-price').val();
                const qtyValue = $(this).find('.qty').val();
                const totalPriceValue = $(this).find('.total-price').val();

                $(this).attr('id', `item-row-${newRowNumber}`);
                $(this).find('.item-number').text(newRowNumber);
                
                $(this).find('.remarks')
                    .attr('name', `remarks[${newRowNumber}]`)
                    .attr('id', `remarks_${newRowNumber}`)
                    .val(remarksValue);

                $(this).find('.unit-price')
                    .attr('name', `unit_price[${newRowNumber}]`)
                    .attr('id', `unit_price_${newRowNumber}`)
                    .val(unitPriceValue);
                
                // Update hidden field for clean value
                const hiddenUnitPrice = $(this).find('input[id^="hidden_unit_price"]');
                hiddenUnitPrice.attr('id', `hidden_unit_price_${newRowNumber}`)
                    .attr('name', `unit_price_clean[${newRowNumber}]`)
                    .val(unitPriceValue ? unitPriceValue.replace(/\./g, '') : '');

                $(this).find('.qty')
                    .attr('name', `qty[${newRowNumber}]`)
                    .attr('id', `qty_${newRowNumber}`)
                    .val(qtyValue);

                $(this).find('.total-price')
                    .attr('name', `total_price[${newRowNumber}]`)
                    .attr('id', `total_price_${newRowNumber}`)
                    .val(totalPriceValue);
                
                // Update hidden field for clean total price value
                const hiddenTotalPrice = $(this).find('input[id^="hidden_total_price"]');
                hiddenTotalPrice.attr('id', `hidden_total_price_${newRowNumber}`)
                    .attr('name', `total_price_clean[${newRowNumber}]`)
                    .val(totalPriceValue ? totalPriceValue.replace(/\./g, '') : '');

                $(this).find('.btn-remove-item').attr('data-id', newRowNumber);
            });
            itemCount = $('#items-container tr').length;
        }

        // ========== FORM SUBMIT ==========
        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('sw_invoice/add') ?>";
            } else {
                url = "<?php echo site_url('sw_invoice/update') ?>";
            }

            // Use FormData API for better handling of form data including arrays
            var formDataObj = new FormData($form[0]);
            
            // Override with clean currency values from hidden fields
            formDataObj.set('total_amount', $('#total_amount_hidden').val() || 0);

            // Show loading
            $('#loading').show();
            $('.aksi').prop('disabled', true);

            $.ajax({
                url: url,
                type: "POST",
                data: formDataObj,
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    $('#loading').hide();
                    $('.aksi').prop('disabled', false);

                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: data.message || 'Data saved successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('sw_invoice') ?>";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.error || 'Terjadi kesalahan saat menyimpan data'
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loading').hide();
                    $('.aksi').prop('disabled', false);
                    
                    console.log('Error:', textStatus, errorThrown);
                    console.log('Response:', jqXHR.responseText);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error adding / updating data: ' + textStatus
                    });
                }
            });
        });

        // ========== FORM VALIDATION ==========
        $("#form").validate({
            rules: {
                letter_number: {
                    required: true,
                },
                letter_date: {
                    required: true,
                },
                company_name: {
                    required: true,
                },
                event_type: {
                    required: true,
                },
                total_amount: {
                    required: true,
                },
                final_date: {
                    required: true,
                },
            },
            messages: {
                letter_number: {
                    required: "Letter number is required",
                },
                letter_date: {
                    required: "Letter date is required",
                },
                company_name: {
                    required: "Company name is required",
                },
                event_type: {
                    required: "Event type is required",
                },
                total_amount: {
                    required: "Total amount is required",
                },
                final_date: {
                    required: "Final date is required",
                },
            },
            errorPlacement: function(error, element) {
                // Find the row containing the input and append error there
                var row = element.closest('.row');
                error.appendTo(row);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            focusInvalid: false,
        });
    });
</script>