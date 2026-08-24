<style>
    .category-card {
        background-color: #f8f9fa;
        margin-bottom: 20px;
        border: 1px solid #242d4a79;
    }

    .category-header {
        background-color: #242d4a;
        color: white;
        padding: 15px;
        border-radius: 4px 4px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-body {
        padding: 15px;
        border-top: 0;
    }

    .item-row {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        align-items: flex-end;
        background-color: white;
        padding: 10px;
        border: 1px solid #e9ecef;
        border-radius: 4px;
    }

    .item-row input {
        border: 1px solid #ced4da;
    }

    .item-row .btn-remove-item {
        white-space: nowrap;
    }

    .subtotal-section {
        /* background-color: #198754; */
        /* color: white; */
        border: 1px solid #19875478;
        padding: 10px;
        margin-top: 10px;
        border-radius: 4px;
        font-weight: bold;
    }

    .calculation-section {
        background-color: #1987540f;
        border: 1px solid #198754;
        padding: 15px;
        margin-top: 20px;
        border-radius: 4px;
    }

    .calculation-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding: 8px 0;
    }

    .calculation-row.total-row {
        background-color: #198754;
        color: white;
        padding: 12px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 0;
    }

    .btn-action {
        white-space: nowrap;
    }

    .form-section-header {
        background-color: #242d4a;
        color: white;
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        font-weight: bold;
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
                    <a class="btn btn-primary btn-sm" href="<?= base_url('sw_costructure') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>

                <div class="card-body">
                    <form id="form-costructure">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Company Name -->
                                <div class="form-group">
                                    <label for="company_name">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" 
                                           placeholder="Company Name" required autofocus>
                                </div>

                                <!-- Event Type -->
                                <div class="form-group">
                                    <label for="event_type">Event Type</label>
                                    <input type="text" class="form-control" id="event_type" name="event_type" 
                                           placeholder="Event Type" required>
                                </div>

                                <!-- Number of Participants -->
                                <div class="form-group">
                                    <label for="number_of_participants">Number of Participants</label>
                                    <input type="number" class="form-control" id="number_of_participants" 
                                           name="number_of_participants" min="0" placeholder="0" required>
                                </div>

                                <!-- Margin -->
                                <div class="form-group">
                                    <label for="margin">Margin (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="margin" name="margin" 
                                               placeholder="0" min="0" step="0.01" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cashback -->
                                <div class="form-group">
                                    <label for="cashback">Cashback</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control currency-input" id="cashback" name="cashback" 
                                               placeholder="0" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <!-- Fee Mediator -->
                                <div class="form-group">
                                    <label for="fee_mediator">Fee Mediator</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control currency-input" id="fee_mediator" name="fee_mediator" 
                                               placeholder="0" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rounding Configuration -->
                                <div class="form-group">
                                    <label for="rounding">Price Per Person Rounding (Rp)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control currency-input" id="rounding" name="rounding" 
                                               placeholder="0" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Grand Total (Read-only) -->
                                <div class="form-group">
                                    <label for="grand_total">Grand Total</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="grand_total" 
                                               name="grand_total" readonly placeholder="Rp">
                                            <div class="input-group-append">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Received By EO (Auto calculated from margin) -->
                                <div class="form-group">
                                    <label for="received_by_eo">Received By EO</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="received_by_eo"
                                               name="received_by_eo" readonly placeholder="Rp">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Adjust Price (Auto calculated from margin) -->
                                <div class="form-group" id="adjustment_container">
                                    <label for="adjustment">Adjust Price</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control currency-input" id="adjustment"
                                               name="adjustment" placeholder="0">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden ID -->
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">

                        <!-- ========== CATEGORIES SECTION ========== -->
                        <hr>

                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-primary" id="btn-add-category">
                                <i class="fas fa-plus"></i> Add Category
                            </button>
                        </div>

                        <!-- Categories Container -->
                        <div id="categories-container">
                            <!-- Categories akan di-append di sini via JavaScript -->
                        </div>

                        <!-- ========== CALCULATION SECTION ========== -->
                        <div class="calculation-section">
                            <div class="calculation-row">
                                <span>Total Production Expenses (All Items)</span>
                                <span id="display-grand-total">Rp 0</span>
                            </div>
                            <div class="calculation-row">
                                <span>Margin (<span id="display-margin-percent"></span>)</span>
                                <span id="display-margin-value">0%</span>
                            </div>
                            <div class="calculation-row">
                                <span>Diterima Oleh EO</span>
                                <span id="display-received-by-eo">Rp 0</span>
                            </div>
                            <div class="calculation-row">
                                <span>Fee Mediator</span>
                                <span id="display-fee-mediator">Rp 0</span>
                            </div>
                            <div class="calculation-row">
                                <span>Cashback</span>
                                <span id="display-cashback">Rp 0</span>
                            </div>
                            <div class="calculation-row " id="adjustment_row">
                                <span>Perubahan harga</span>
                                <span id="display-adjustment">Rp 0</span>
                            </div>
                            <div class="calculation-row">
                                <span>Total Final</span>
                                <span id="display-total-final">Rp 0</span>
                            </div>
                            <div class="calculation-row">
                                <span>Harga Perorangan</span>
                                <span id="display-price-per-person">Rp 0</span>
                            </div>
                            <div class="calculation-row total-row">
                                <span>Harga Jual</span>
                                <span id="display-selling-price">Rp 0</span>
                            </div>
                        </div>

                        <!-- ========== SUBMIT BUTTON ========== -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="btn-submit" style="font-size: 14px;">
                                Save
                            </button>
                            <!-- <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </button> -->
                        </div>
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
    const ID = $('#id').val();
    const IS_EDIT = ID > 0;

    // ========== INITIALIZE DATEPICKER & LOCALE ==========
    initializeDatepickers();

    // ========== LOAD DATA FOR EDIT ==========
    if (IS_EDIT) {
        loadDataForEdit();
        $('#adjustment').show();
    } else {
        // Add default category for new form
        addCategoryRow();
        $('#adjustment_container').hide();
    }

    // ========== EVENT LISTENERS ==========
    $('#btn-add-category').on('click', addCategoryRow);
    $('#form-costructure').on('submit', handleFormSubmit);

    // Delegate event handlers untuk dynamic content
    $(document).on('click', '.btn-remove-category', removeCategoryRow);
    $(document).on('click', '.btn-add-item', addItemRow);
    $(document).on('click', '.btn-remove-item', removeItemRow);
    $(document).on('input', '.item-qty, .item-price', calculateSubtotal);
    $(document).on('input', '#margin', calculateSellingPrice);
    $(document).on('input', '#fee_mediator', calculateSellingPrice);
    $(document).on('input', '#cashback', calculateSellingPrice);
    $(document).on('input', '#rounding', calculateSellingPrice);
    $(document).on('input', '#adjustment', calculateSellingPrice);
    $(document).on('input', '#number_of_participants', calculateSellingPrice);
    $(document).on('input', '#rounding', calculateSellingPrice);

    // ========== HELPER FUNCTIONS ==========
    function initializeDatepickers() {
        // Tambahkan jika ada date fields
    }

    $('#margin').on('input', function() {
        if ($(this).val() > 100) {
            $(this).val(100);
        } else if ($(this).val() < 0) {
            $(this).val(0);
        }
    });

    // $('#fee_mediator').on('input', function() {
    //     if ($(this).val() > 100) {
    //         $(this).val(100);
    //     } else if ($(this).val() < 0) {
    //         $(this).val(0);
    //     }
    // });

    /**
     * Initialize currency inputs
     */
    $(document).on('input', '.currency-input', function() {
        let value = $(this).val();
        let cleanValue = value.replace(/[^0-9]/g, '');
        let formattedValue = formatCurrency(cleanValue);

        $(this).val(formattedValue);
    });
    
    /**
     * Format angka menjadi currency dengan separator ribuan
     */
    function formatCurrency(value) {
        if (!value) return '';
        let intValue = parseInt(value.replace(/\./g, ''));
        return intValue.toLocaleString('id-ID');
    }

    /**
     * Parse currency string ke number
     */
    function parseCurrency(value) {
        return parseFloat(value.replace(/\./g, '').replace(/[^0-9-]/g, '')) || 0;
    }

    // Add category row
    function addCategoryRow(addDefaultItem = true) {

        const defaultCategories = [
            "Acara & Man Pow",
            "Transportasi",
            "Akomodasi",
            "Konsumsi",
            "Perlengkapan",
            "Fee Tour Guide"
        ];

        const categoryIndex = $('#categories-container .category-card').length;

        // ambil default value berdasarkan urutan row
        const defaultValue = defaultCategories[categoryIndex] || '';

        const categoryHTML = `
            <div class="card category-card" data-category-index="${categoryIndex}">
                <div class="category-header">
                    <span style="margin-right: 10px;">${categoryIndex + 1}.</span> 
                    
                    <input 
                        type="text" 
                        class="form-control form-control-sm category-name" 
                        name="categories[${categoryIndex}][name]"
                        placeholder="Category Name"
                        list="category-suggestions-${categoryIndex}"
                        value="${defaultValue}"
                        style="background-color: white; color: black; flex-grow: 1;" 
                        required
                    >

                    <datalist id="category-suggestions-${categoryIndex}">
                        <option value="Acara & Man Pow">
                        <option value="Transportasi">
                        <option value="Akomodasi">
                        <option value="Konsumsi">
                        <option value="Perlengkapan">
                        <option value="Fee Tour Guide">
                    </datalist>

                    <button type="button" class="btn btn-danger btn-sm btn-remove-category" style="margin-left: 10px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="category-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-add-item" style="background-color: #198754; color: white;">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>

                    <div class="items-container" data-category-index="${categoryIndex}">
                        <!-- Items akan di-append di sini -->
                    </div>

                    <div class="subtotal-section">
                        Category Subtotal: <span class="category-subtotal">Rp 0</span>
                    </div>
                </div>
            </div>
        `;

        $('#categories-container').append(categoryHTML);

        // Add default item untuk category baru
        if (addDefaultItem) {
            addItemRow.call($(`[data-category-index="${categoryIndex}"] .btn-add-item`)[0]);
        }

        toggleCategoryDeleteButton();
    }

    // Remove category row
    function removeCategoryRow() {
        $(this).closest('.category-card').remove();
        reorderCategories();
        recalculateAll();
        toggleCategoryDeleteButton();
    }

    function toggleCategoryDeleteButton() {
        const totalCategory = $('#categories-container .category-card').length;

        $('.btn-remove-category').prop('disabled', totalCategory <= 1).css('cursor', totalCategory <= 1 ? 'not-allowed' : 'pointer');
    }

    // Reorder categories setelah ada perubahan (hapus)
    function reorderCategories() {
        $('#categories-container .category-card').each(function(categoryIndex) {

            // Update data-category-index
            $(this).attr('data-category-index', categoryIndex);

            // Update nomor urut category
            $(this).find('.category-header span:first').text((categoryIndex + 1) + '.');

            // Update category input name
            $(this).find('.category-name')
                .attr('name', `categories[${categoryIndex}][name]`);

            // Update items-container index
            $(this).find('.items-container')
                .attr('data-category-index', categoryIndex);

            // Reorder items dalam category ini
            $(this).find('.item-row').each(function(itemIndex) {

                $(this).attr('data-item-index', itemIndex);

                $(this).find('span:first').text((itemIndex + 1) + '.');

                $(this).find('.item-name')
                    .attr('name', `categories[${categoryIndex}][items][${itemIndex}][name]`);

                $(this).find('.item-qty')
                    .attr('name', `categories[${categoryIndex}][items][${itemIndex}][qty]`);

                $(this).find('.item-price')
                    .attr('name', `categories[${categoryIndex}][items][${itemIndex}][price]`);

                $(this).find('.item-subtotal')
                    .attr('name', `categories[${categoryIndex}][items][${itemIndex}][subtotal]`);
            });
        });
    }

    // add item row
    function addItemRow() {
        const $categoryCard = $(this).closest('.category-card');
        const categoryIndex = $categoryCard.data('category-index');
        const $itemsContainer = $categoryCard.find('.items-container');
        const itemIndex = $itemsContainer.find('.item-row').length;

        const itemHTML = `
            <div class="item-row" data-item-index="${itemIndex}">
                <span style="font-size: 12px; color: #6c757d; position: relative; bottom: 5px;" class="item-no">${itemIndex + 1}.</span>
                <div style="flex: 1;">
                    <input type="text" class="form-control form-control-sm item-name" 
                           name="categories[${categoryIndex}][items][${itemIndex}][name]" 
                           placeholder="Item Name" required>
                </div>
                <div style="width: 80px;">
                    <input type="number" class="form-control form-control-sm item-qty"
                           name="categories[${categoryIndex}][items][${itemIndex}][qty]" 
                           placeholder="Qty" min="1" value="1">
                </div>
                <div style="width: 140px;">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text item-number" style="font-size: 12px;">Rp</span>
                        </div>
                        <input type="text" class="form-control form-control-sm item-price currency-input" 
                               name="categories[${categoryIndex}][items][${itemIndex}][price]" 
                               placeholder="0" data-field="item-price">
                    </div>
                </div>  
                <div style="width: 140px;">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text item-number" style="font-size: 12px;">Rp</span>
                        </div>
                        <input type="text" class="form-control form-control-sm item-subtotal currency-input" 
                               name="categories[${categoryIndex}][items][${itemIndex}][subtotal]" 
                               placeholder="0" readonly>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm btn-remove-item btn-action">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        $itemsContainer.append(itemHTML);
        toggleItemDeleteButton($categoryCard);
    }

    // Remove item row
    function removeItemRow() {
        const $categoryCard = $(this).closest('.category-card');

        $(this).closest('.item-row').remove();

        reorderItems($categoryCard);

        recalculateAll();
        toggleItemDeleteButton($categoryCard);
    }

    function toggleItemDeleteButton($categoryCard) {
        const totalItems = $categoryCard.find('.item-row').length;

        $categoryCard.find('.btn-remove-item')
            .prop('disabled', totalItems <= 1).css('cursor', totalItems <= 1 ? 'not-allowed' : 'pointer');
    }
    
    // Calculate subtotal untuk item
    function calculateSubtotal() {
        const $itemRow = $(this).closest('.item-row');
        const qty = parseInt($itemRow.find('.item-qty').val()) || 0;
        const price = parseCurrency($itemRow.find('.item-price').val());
        const subtotal = qty * price;

        // Store numeric value in data attribute for form submission
        $itemRow.find('.item-subtotal').data('value', subtotal).val(formatCurrency(subtotal.toString()));

        // Recalculate totals
        recalculateAll();
    }

    /**
     * Recalculate semua totals (category subtotal dan grand total)
     */
    function recalculateAll() {
        let grandTotal = 0;

        // Recalculate setiap category
        $('#categories-container .category-card').each(function() {
            let categorySubtotal = 0;

            // Sum all items in this category
            $(this).find('.items-container .item-row').each(function() {
                const subtotal = parseCurrency($(this).find('.item-subtotal').val());
                categorySubtotal += subtotal;
            });

            // Update category subtotal display
            $(this).find('.category-subtotal').text('Rp ' + categorySubtotal.toLocaleString('id-ID'));
            
            // Add category subtotal to grand total
            grandTotal += categorySubtotal;
        });

        // Update grand total in hidden field
        $('#grand_total').val(formatCurrency(grandTotal.toString()));
        $('#display-grand-total').text('Rp ' + grandTotal.toLocaleString('id-ID'));

        // Recalculate selling price based on grand total
        calculateSellingPrice();
    }

    // Reorder items setelah ada perubahan (hapus)
    function reorderItems($categoryCard) {
        $categoryCard.find('.item-row').each(function(index) {

            // Update data index untuk item
            $(this).attr('data-item-index', index);

            // Update nomor urut item
            $(this).find('.item-no').text((index + 1) + '.');

            // Update nomor urut
            $(this).find('.item-number').text('Rp');

            // Update semua input name
            $(this).find('.item-name')
                .attr('name', `categories[${$categoryCard.data('category-index')}][items][${index}][name]`);

            $(this).find('.item-qty')
                .attr('name', `categories[${$categoryCard.data('category-index')}][items][${index}][qty]`);

            $(this).find('.item-price')
                .attr('name', `categories[${$categoryCard.data('category-index')}][items][${index}][price]`);

            $(this).find('.item-subtotal')
                .attr('name', `categories[${$categoryCard.data('category-index')}][items][${index}][subtotal]`);
        });
    }

    /**
     * Calculate selling price berdasarkan margin
     */
    function calculateSellingPrice() {
        const grandTotal = parseCurrency($('#grand_total').val()) || 0;
        const margin = parseFloat($('#margin').val()) || 0;
        const numberOfParticipants = parseInt($('#number_of_participants').val()) || 0;
        const roundingValue = parseCurrency($('#rounding').val()) || 50000;
        const feeMediator = parseCurrency($('#fee_mediator').val()) || 0;
        const cashback = parseCurrency($('#cashback').val()) || 0;
        const rounding = parseCurrency($('#rounding').val()) || 0;
        const adjustment = parseCurrency($('#adjustment').val()) || 0;

        // Update number of participants display
        $('#display-participants').text(numberOfParticipants);

        // Calculate total selling price (received by EO) = grand total + margin
        const totalSellingPrice = grandTotal + (grandTotal * margin / 100);
        $('#received_by_eo').val(formatCurrency(totalSellingPrice.toString()));
        $('#display-received-by-eo').text('Rp ' + parseCurrency($('#received_by_eo').val()).toLocaleString('id-ID'));

        // Calculate price per person (before rounding) = received_by_eo / numberOfParticipants
        const pricePerPerson = numberOfParticipants > 0 ? (totalSellingPrice + feeMediator + cashback - adjustment) / numberOfParticipants : 0;
        $('#display-price-per-person').text('Rp ' + pricePerPerson.toLocaleString('id-ID', { maximumFractionDigits: 0 }));

        $('#display-margin-percent').text(margin + '%');
        $('#display-margin-value').text('Rp ' + (grandTotal * margin / 100).toLocaleString('id-ID'));

        $('#display-fee-mediator').text('Rp ' + feeMediator.toLocaleString('id-ID'));
        $('#display-cashback').text('Rp ' + cashback.toLocaleString('id-ID'));

        if (adjustment == 0) {
            $('#adjustment_row').hide();
        } else {
            $('#adjustment_row').show();
        }

        $('#display-adjustment').text('Rp -' + adjustment.toLocaleString('id-ID'));

        const totalFinal = parseCurrency($('#received_by_eo').val()) + feeMediator + cashback - adjustment;
        $('#display-total-final').text('Rp ' + totalFinal.toLocaleString('id-ID'));


        // Calculate selling price per person after rounding
        let sellingPrice = Math.round(pricePerPerson + rounding);
        $('#display-selling-price').text('Rp ' + sellingPrice.toLocaleString('id-ID'));
    }

    /**
     * Load data untuk edit
     */
    function loadDataForEdit() {
        $.ajax({
            url: "<?php echo site_url('sw_costructure/get_data') ?>/" + ID,
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                if (response.status) {
                    populateFormData(response);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Failed to load data'
                    });
                }
            },
            error: function(error) {
                console.error("Error loading data:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Failed to load data'
                });
            }
        });
    }

    /**
     * Populate form dengan data yang di-load
     */
    function populateFormData(data) {
        $('#company_name').val(data.company_name);
        $('#event_type').val(data.event_type);
        $('#number_of_participants').val(data.number_of_participants);
        $('#margin').val(data.margin);
        $('#fee_mediator').val(formatCurrency(data.fee_mediator.toString()));
        $('#cashback').val(formatCurrency(data.cashback.toString()));
        $('#rounding').val(formatCurrency(data.rounding.toString()));
        $('#adjustment').val(formatCurrency(data.adjustment.toString()));
        $('#grand_total').val(data.grand_total);

        // Clear categories container
        $('#categories-container').empty();

        // Populate categories
        if (data.categories && data.categories.length > 0) {
            data.categories.forEach(function(category, catIndex) {
                addCategoryRow(false);
                
                const $categoryCard = $(`[data-category-index="${catIndex}"]`);
                const categoryName = category.name.replace(/^\d+\.\s*/, '');

                $categoryCard.find('.category-name').val(categoryName);

                // Populate items
                if (category.items && category.items.length > 0) {
                    category.items.forEach(function(item, itemIndex) {
                        // Add item row
                        $categoryCard.find('.btn-add-item').click();
                        
                        const $itemRow = $categoryCard.find('.items-container [data-item-index="' + itemIndex + '"]');
                        $itemRow.find('.item-name').val(item.name);
                        $itemRow.find('.item-qty').val(item.qty);
                        $itemRow.find('.item-price').val(formatCurrency(item.price.toString()));
                        $itemRow.find('.item-subtotal').val(formatCurrency(item.subtotal.toString())).data('value', item.subtotal);
                    });
                }
            });
        }

        // Recalculate totals and selling price
        recalculateAll();
    }

    /**
     * Handle form submission
     */
    function handleFormSubmit(e) {
        e.preventDefault();

        // Validate
        if (!$('#company_name').val() || !$('#event_type').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: 'Please fill in all required fields'
            });
            return;
        }

        if ($('#categories-container .category-card').length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: 'Please add at least one category'
            });
            return;
        }

        // Convert formatted values back to numeric before submission
        $('#categories-container .item-price, #categories-container .item-subtotal').each(function() {
            const $this = $(this);
            const numericValue = parseCurrency($this.val());
            $this.val(numericValue);
        });

        // Convert selling price and grand total to numeric
        $('#received_by_eo').val(parseCurrency($('#received_by_eo').val()));
        $('#grand_total').val(parseCurrency($('#grand_total').val()));

        // Convert rounding to numeric
        $('#rounding').val(parseCurrency($('#rounding').val()));

        // Margin is already numeric from input[type="number"]
        const marginValue = parseFloat($('#margin').val()) || 0;
        $('#margin').val(marginValue);

        // Disable submit button
        $('#btn-submit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        // Prepare form data
        const formData = new FormData(this);

        // Determine action (add or update)
        const url = IS_EDIT ? "<?php echo site_url('sw_costructure/update') ?>" : "<?php echo site_url('sw_costructure/add') ?>";

        // Submit via AJAX
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                    // Redirect ke list
                    setTimeout(function() {
                        window.location.href = "<?php echo base_url('sw_costructure') ?>";
                    }, 1500);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: response.message
                    });
                }
            },
            error: function(error) {
                console.error("Error submitting form:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'An error occurred while saving data'
                });
            },
            complete: function() {
                // Re-enable submit button
                $('#btn-submit').prop('disabled', false).html('Save');
            }
        });
    }
});
</script>