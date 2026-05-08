<style>
    table #items-container tr td:nth-child(1),
    table #items-container tr td:nth-child(8) {
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
                    <a class="btn btn-primary btn-sm" href="<?= base_url('sw_confirm_letter') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <!-- Row 1: Letter Details (Left Column) -->
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

                                <!-- Event Type -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="event_type">Event Type</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="event_type" name="event_type" placeholder="Event Type">
                                    </div>
                                </div>

                                <!-- Venue -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="venue">Venue</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="venue" name="venue" placeholder="Venue Location">
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="total_amount">Total Amount</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="total_amount" name="total_amount" placeholder="0" data-hidden="total_amount_hidden" readonly>
                                            <input type="hidden" id="total_amount_hidden" name="total_amount_value">
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="dp_date">DP Date</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="dp_date" name="dp_date" placeholder="dd-mm-yyyy" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>
                            </div>

                            <!-- Row 1: Event Schedule (Right Column) -->
                            <div class="col-md-6">
                                <!-- Setup -->
                                <div class="row align-items-start mb-3">
                                    <label class="col-lg-4" for="setup">Setup</label>
                                    <div class="col-lg-8">
                                        <textarea class="form-control" id="setup" name="setup" rows="3" placeholder="Setup Details"></textarea>
                                    </div>
                                </div>

                                <!-- Start Date -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="start_date">Start Date</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="start_date" name="start_date" placeholder="dd-mm-yyyy" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>

                                <!-- End Date -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="end_date">End Date</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="end_date" name="end_date" placeholder="dd-mm-yyyy" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>

                                <!-- Start Time -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="start_time">Start Time</label>
                                    <div class="col-lg-8">
                                        <input type="time" class="form-control" id="start_time" name="start_time" onfocus="this.showPicker && this.showPicker()" style="cursor: pointer;">
                                    </div>
                                </div>

                                <!-- End Time -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="end_time">End Time</label>
                                    <div class="col-lg-8">
                                        <input type="time" class="form-control" id="end_time" name="end_time" onfocus="this.showPicker && this.showPicker()" style="cursor: pointer;">
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="final_date">Final Date</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="final_date" name="final_date" placeholder="dd-mm-yyyy" autocomplete="off" style="cursor: pointer;">
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
                                        <td width="15%" style="padding: 10px;">Item Type</td>
                                        <td width="20%" style="padding: 10px;">Package Name</td>
                                        <td width="20%" style="padding: 10px;">Item Name</td>
                                        <td width="12%" style="padding: 10px;">Unit Price</td>
                                        <td width="8%" style="padding: 10px;">Qty</td>
                                        <td width="12%" style="padding: 10px;">Total Price</td>
                                        <td width="7%" style="padding: 10px;">Action</td>
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
                url: "<?php echo site_url('sw_confirm_letter/generate_letter_number') ?>",
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
        $('#start_date').datepicker(datePickerOptions);
        $('#end_date').datepicker(datePickerOptions);
        $('#dp_date').datepicker(datePickerOptions);
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
                url: "<?php echo site_url('sw_confirm_letter/get_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id');
                    $('#id').val(data.id);
                    $('#letter_number').val(data.letter_number);
                    $('#letter_date').val(moment(data.letter_date).format('DD-MM-YYYY'));
                    $('#company_name').val(data.company_name);
                    $('#event_type').val(data.event_type);
                    $('#venue').val(data.venue);
                    $('#setup').val(data.setup);
                    $('#start_date').val(moment(data.start_date).format('DD-MM-YYYY'));
                    $('#end_date').val(moment(data.end_date).format('DD-MM-YYYY'));
                    $('#start_time').val(data.start_time);
                    $('#end_time').val(data.end_time);
                    
                    // Set currency fields
                    $('#total_amount').val(formatCurrency(data.total_amount)).trigger('input');
                    
                    $('#dp_date').val(moment(data.dp_date).format('DD-MM-YYYY'));
                    $('#final_date').val(moment(data.final_date).format('DD-MM-YYYY'));

                    // Load Item Details
                    if (data.items && data.items.length > 0) {
                        $.each(data.items, function(index) {
                            itemCount++;
                            const item = data.items[index];
                            const isPackageReadonly = item.item_type !== 'package';
                            const readonlyAttr = isPackageReadonly ? 'readonly' : '';
                            const bgColor = isPackageReadonly ? '#e9ecef' : '#fff';
                            
                            const row = `
                                <tr id="item-row-${itemCount}">
                                    <td class="item-number">${itemCount}</td>
                                    <td>
                                        <select class="form-control form-control-sm item-type" name="item_type[${itemCount}]" id="item_type_${itemCount}">
                                            <option value="">-- Select --</option>
                                            <option value="additional" ${item.item_type === 'additional' ? 'selected' : ''}>Additional</option>
                                            <option value="package" ${item.item_type === 'package' ? 'selected' : ''}>Package</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm package-name" name="package_name[${itemCount}]" id="package_name_${itemCount}" placeholder="Package name" value="${item.package_name || ''}" ${readonlyAttr} style="background-color: ${bgColor};">
                                    </td>
                                    <td>    
                                        <input type="text" class="form-control form-control-sm item-name" name="item_name[${itemCount}]" id="item_name_${itemCount}" placeholder="Item name" value="${item.item_name}">
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
                        
                        // Calculate total amount after loading items
                        calculateTotalAmount();
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

            // Item Type Change - Set Package Name Readonly/Editable
            $(document).on('change', '.item-type', function() {
                const row = $(this).closest('tr');
                const itemType = $(this).val();
                const packageNameInput = row.find('.package-name');
                
                if (itemType === 'package') {
                    packageNameInput.prop('readonly', false).css('background-color', '#fff');
                } else {
                    packageNameInput.prop('readonly', true).css('background-color', '#e9ecef');
                    packageNameInput.val('');
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
                        <select class="form-control form-control-sm item-type" name="item_type[${itemCount}]" id="item_type_${itemCount}" required>
                            <option value="">-- Select --</option>
                            <option value="additional">Additional</option>
                            <option value="package">Package</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm package-name" name="package_name[${itemCount}]" id="package_name_${itemCount}" placeholder="Package name" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm item-name" name="item_name[${itemCount}]" id="item_name_${itemCount}" placeholder="Item name" required>
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
        });

        // Item Type Change - Set Package Name Readonly/Editable
        $(document).on('change', '.item-type', function() {
            const row = $(this).closest('tr');
            const itemType = $(this).val();
            const packageNameInput = row.find('.package-name');
            
            if (itemType === 'package') {
                packageNameInput.prop('readonly', false).css('background-color', '#fff');
            } else {
                packageNameInput.prop('readonly', true).css('background-color', '#e9ecef');
            }
        });

        // Format Unit Price as Currency
        $(document).on('input', '.unit-price', function() {
            let formatted = formatCurrency($(this).val());
            $(this).val(formatted);
            
            // Store clean value in hidden field
            let cleanValue = $(this).val().replace(/\./g, '');
            const fieldId = $(this).attr('id');
            $('#hidden_' + fieldId).val(cleanValue);
            
            // Recalculate total
            calculateRowTotal($(this).closest('tr'));
        });

        // Calculate Total Price (unit_price * qty)
        $(document).on('input', '.qty', function() {
            calculateRowTotal($(this).closest('tr'));
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
            
            // Recalculate total amount
            calculateTotalAmount();
        }

        // Function to calculate total amount from all total_price items
        function calculateTotalAmount() {
            let totalAmount = 0;
            
            // Sum all total_price values from items
            $('#items-container .total-price').each(function() {
                const cleanValue = $(this).attr('id').replace('total_price_', '');
                const hiddenField = $('#hidden_total_price_' + cleanValue);
                const itemTotal = parseFloat(hiddenField.val()) || 0;
                totalAmount += itemTotal;
            });
            
            // Format and display total amount
            const totalAmountFormatted = formatCurrency(totalAmount.toString());
            $('#total_amount').val(totalAmountFormatted);
            
            // Store clean value in hidden field
            $('#total_amount_hidden').val(totalAmount.toFixed(0));
        }

        // Remove Item
        $(document).on('click', '.btn-remove-item', function() {
            $(this).closest('tr').remove();
            reorderItemRows();
        });

        // Reorder Item Rows
        function reorderItemRows() {
            $('#items-container tr').each(function(index) {
                const newRowNumber = index + 1;
                const itemTypeValue = $(this).find('.item-type').val();
                const packageNameValue = $(this).find('.package-name').val();
                const itemNameValue = $(this).find('.item-name').val();
                const unitPriceValue = $(this).find('.unit-price').val();
                const qtyValue = $(this).find('.qty').val();
                const totalPriceValue = $(this).find('.total-price').val();

                $(this).attr('id', `item-row-${newRowNumber}`);
                $(this).find('.item-number').text(newRowNumber);
                
                $(this).find('.item-type')
                    .attr('name', `item_type[${newRowNumber}]`)
                    .attr('id', `item_type_${newRowNumber}`)
                    .val(itemTypeValue);

                $(this).find('.package-name')
                    .attr('name', `package_name[${newRowNumber}]`)
                    .attr('id', `package_name_${newRowNumber}`)
                    .val(packageNameValue);
                
                // Set readonly based on item type
                if (itemTypeValue === 'package') {
                    $(this).find('.package-name').prop('readonly', false).css('background-color', '#fff');
                } else {
                    $(this).find('.package-name').prop('readonly', true).css('background-color', '#e9ecef');
                }

                $(this).find('.item-name')
                    .attr('name', `item_name[${newRowNumber}]`)
                    .attr('id', `item_name_${newRowNumber}`)
                    .val(itemNameValue);

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
            
            // Recalculate total amount after reordering
            calculateTotalAmount();
        }

        // ========== FORM SUBMIT ==========
        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('sw_confirm_letter/add') ?>";
            } else {
                url = "<?php echo site_url('sw_confirm_letter/update') ?>";
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
                            location.href = "<?= base_url('sw_confirm_letter') ?>";
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
                venue: {
                    required: true,
                },
                setup: {
                    required: true,
                },
                start_date: {
                    required: true,
                },
                end_date: {
                    required: true,
                },
                start_time: {
                    required: true,
                },
                end_time: {
                    required: true,
                },
                total_amount: {
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
                venue: {
                    required: "Venue is required",
                },
                setup: {
                    required: "Setup is required",
                },
                start_date: {
                    required: "Start date is required",
                },
                end_date: {
                    required: "End date is required",
                },
                start_time: {
                    required: "Start time is required",
                },
                end_time: {
                    required: "End time is required",
                },
                total_amount: {
                    required: "Total amount is required",
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