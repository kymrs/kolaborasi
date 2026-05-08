<style>
    .category-card {
        background-color: #f8f9fa;
        border-left: 4px solid #28a745;
        margin-bottom: 20px;
    }

    .category-header {
        background-color: #28a745;
        color: white;
        padding: 15px;
        border-radius: 4px 4px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-body {
        padding: 15px;
        border: 1px solid #dee2e6;
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
        background-color: #fff3cd;
        border: 1px solid #ffc107;
        padding: 10px;
        margin-top: 10px;
        border-radius: 4px;
        font-weight: bold;
    }

    .calculation-section {
        background-color: #f0f7ff;
        border: 1px solid #0d6efd;
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
        background-color: #ff6b35;
        color: white;
        padding: 12px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 18px;
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
                                    <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" 
                                           placeholder="Company Name" required>
                                </div>

                                <!-- Event Type -->
                                <div class="form-group">
                                    <label for="event_type">Event Type <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="event_type" name="event_type" 
                                           placeholder="Event Type" required>
                                </div>

                                <!-- Number of Participants -->
                                <div class="form-group">
                                    <label for="number_of_participants">Number of Participants</label>
                                    <input type="number" class="form-control" id="number_of_participants" 
                                           name="number_of_participants" min="0" placeholder="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Selling Price (Auto calculated from margin) -->
                                <div class="form-group">
                                    <label for="selling_price">Selling Price</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" class="form-control" id="selling_price" 
                                               name="selling_price" readonly>
                                    </div>
                                    <small class="form-text text-muted">Auto-calculated based on margin</small>
                                </div>

                                <!-- Grand Total (Read-only) -->
                                <div class="form-group">
                                    <label for="grand_total">Grand Total</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" class="form-control" id="grand_total" 
                                               name="grand_total" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden ID -->
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">

                        <!-- ========== CATEGORIES SECTION ========== -->
                        <hr>

                        <div class="mb-3">
                            <button type="button" class="btn btn-success btn-sm" id="btn-add-category">
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
                                <span>Grand Total (All Items)</span>
                                <span id="display-grand-total">Rp 0</span>
                            </div>
                            <div class="calculation-row">
                                <span>Margin</span>
                                <span id="display-margin">0%</span>
                            </div>
                            <div class="calculation-row total-row">
                                <span>Selling Price</span>
                                <span id="display-selling-price">Rp 0</span>
                            </div>
                        </div>

                        <!-- ========== SUBMIT BUTTON ========== -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="btn-submit">
                                <i class="fas fa-save"></i> Save Cost Structure
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
    initializeCurrencyInputs();

    // ========== LOAD DATA FOR EDIT ==========
    if (IS_EDIT) {
        loadDataForEdit();
    } else {
        // Add default category for new form
        addCategoryRow();
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

    // ========== HELPER FUNCTIONS ==========
    function initializeDatepickers() {
        // Tambahkan jika ada date fields
    }

    /**
     * Initialize currency inputs
     */
    function initializeCurrencyInputs() {
        $('.currency-input').on('input', function() {
            let value = $(this).val();
            let cleanValue = value.replace(/[^0-9.]/g, '');
            let formattedValue = formatCurrency(cleanValue);
            $(this).val(formattedValue);
        });
    }

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
        return parseFloat(value.replace(/[^0-9.-]/g, '')) || 0;
    }

    /**
     * Add category row
     */
    function addCategoryRow() {
        const categoryIndex = $('#categories-container .category-card').length;
        const categoryHTML = `
            <div class="card category-card" data-category-index="${categoryIndex}">
                <div class="category-header">
                    <input type="text" class="form-control form-control-sm category-name" 
                           name="categories[${categoryIndex}][name]" placeholder="Category Name" 
                           style="background-color: white; color: black; flex-grow: 1;" required>
                    <button type="button" class="btn btn-danger btn-sm btn-remove-category" style="margin-left: 10px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="category-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary btn-sm btn-add-item">
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
        addItemRow.call($(`[data-category-index="${categoryIndex}"] .btn-add-item`)[0]);
    }

    /**
     * Remove category row
     */
    function removeCategoryRow() {
        $(this).closest('.category-card').remove();
        recalculateAll();
    }

    /**
     * Add item row ke category
     */
    function addItemRow() {
        const $categoryCard = $(this).closest('.category-card');
        const categoryIndex = $categoryCard.data('category-index');
        const $itemsContainer = $categoryCard.find('.items-container');
        const itemIndex = $itemsContainer.find('.item-row').length;

        const itemHTML = `
            <div class="item-row" data-item-index="${itemIndex}">
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
                <div style="width: 120px;">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size: 12px;">Rp</span>
                        </div>
                        <input type="text" class="form-control form-control-sm item-price currency-input" 
                               name="categories[${categoryIndex}][items][${itemIndex}][price]" 
                               placeholder="0" data-field="item-price">
                    </div>
                </div>
                <div style="width: 120px;">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size: 12px;">Rp</span>
                        </div>
                        <input type="text" class="form-control form-control-sm item-subtotal" 
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
    }

    /**
     * Remove item row
     */
    function removeItemRow() {
        $(this).closest('.item-row').remove();
        recalculateAll();
    }

    /**
     * Calculate subtotal untuk item
     */
    function calculateSubtotal() {
        const $itemRow = $(this).closest('.item-row');
        const qty = parseInt($itemRow.find('.item-qty').val()) || 0;
        const price = parseCurrency($itemRow.find('.item-price').val());
        const subtotal = qty * price;

        // Store numeric value in data attribute for form submission
        $itemRow.find('.item-subtotal').data('value', subtotal).val(subtotal.toLocaleString('id-ID'));

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

            $(this).find('.items-container .item-row').each(function() {
                const subtotal = parseCurrency($(this).find('.item-subtotal').val());
                categorySubtotal += subtotal;
                grandTotal += subtotal;
            });

            // Update category subtotal display
            $(this).find('.category-subtotal').text('Rp ' + categorySubtotal.toLocaleString('id-ID'));
        });

        // Update grand total
        $('#grand_total').val(grandTotal);
        $('#display-grand-total').text('Rp ' + grandTotal.toLocaleString('id-ID'));

        // Recalculate selling price
        calculateSellingPrice();
    }

    /**
     * Calculate selling price berdasarkan margin
     */
    function calculateSellingPrice() {
        const grandTotal = parseCurrency($('#grand_total').val());
        const margin = parseCurrency($('#margin').val()) || 0;

        const sellingPrice = grandTotal + (grandTotal * margin / 100);

        $('#selling_price').val(sellingPrice);
        $('#display-selling-price').text('Rp ' + Math.round(sellingPrice).toLocaleString('id-ID'));
        $('#display-margin').text(margin + '%');
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
                    alert('Failed to load data');
                }
            },
            error: function(error) {
                console.error("Error loading data:", error);
                alert('Error loading data');
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
        $('#margin').val(formatCurrency(data.margin.toString()));
        $('#grand_total').val(data.grand_total);

        // Clear categories container
        $('#categories-container').empty();

        // Populate categories
        if (data.categories && data.categories.length > 0) {
            data.categories.forEach(function(category, catIndex) {
                addCategoryRow();
                
                const $categoryCard = $(`[data-category-index="${catIndex}"]`);
                $categoryCard.find('.category-name').val(category.name);

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

        // Recalculate totals
        recalculateAll();
    }

    /**
     * Handle form submission
     */
    function handleFormSubmit(e) {
        e.preventDefault();

        // Validate
        if (!$('#company_name').val() || !$('#event_type').val()) {
            alert('Please fill in all required fields');
            return;
        }

        if ($('#categories-container .category-card').length === 0) {
            alert('Please add at least one category');
            return;
        }

        // Convert formatted values back to numeric before submission
        $('#categories-container .item-price, #categories-container .item-subtotal').each(function() {
            const $this = $(this);
            const numericValue = parseCurrency($this.val());
            $this.val(numericValue);
        });

        // Also convert margin if it's formatted
        const marginValue = parseCurrency($('#margin').val());
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
                    alert(response.message);
                    // Redirect ke list
                    setTimeout(function() {
                        window.location.href = "<?php echo base_url('sw_costructure') ?>";
                    }, 1000);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(error) {
                console.error("Error submitting form:", error);
                alert('An error occurred while saving data');
            },
            complete: function() {
                // Re-enable submit button
                $('#btn-submit').prop('disabled', false).html('<i class="fas fa-save"></i> Save Cost Structure');
            }
        });
    }
});
</script>