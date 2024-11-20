document.addEventListener('DOMContentLoaded', function() {
    // Initialize the invoice management system
    initializeInvoiceManagement();
});

function initializeInvoiceManagement() {
    // Add event listeners for the add item button
    const addItemButton = document.getElementById('add-item');
    if (addItemButton) {
        addItemButton.addEventListener('click', addNewItem);
    }

    // Initialize existing items
    initializeExistingItems();

    // Add listener for tax rate changes
    const taxRateInput = document.getElementById('tax_rate');
    if (taxRateInput) {
        taxRateInput.addEventListener('input', calculateInvoiceTotal);
    }
}

function initializeExistingItems() {
    // Calculate totals for existing items
    document.querySelectorAll('.item-row').forEach((row, index) => {
        calculateLineTotal(index);
    });

    // Add remove button listeners
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            removeItem(this);
        });
    });

    // Add product select listeners
    document.querySelectorAll('.product-select').forEach(select => {
        select.addEventListener('change', function() {
            const row = this.closest('.item-row');
            const index = row.dataset.row;
            updateProductDetails(this, index);
        });
    });

    // Add quantity and price input listeners
    document.querySelectorAll('.quantity, .unit-price').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('.item-row');
            const index = row.dataset.row;
            calculateLineTotal(index);
        });
    });
}

function addNewItem() {
    const container = document.getElementById('items-container');
    const newIndex = container.children.length;
    
    // Clone the template
    const template = document.getElementById('item-template');
    const newRow = template.content.cloneNode(true);
    
    // Update the row index
    const rowElement = newRow.querySelector('.item-row');
    rowElement.dataset.row = newIndex;
    
    // Update all form element names and IDs
    updateElementAttributes(rowElement, newIndex);
    
    // Add the new row to the container
    container.appendChild(newRow);
    
    // Initialize the new row's event listeners
    initializeNewRow(rowElement);
    
    // Update totals
    calculateInvoiceTotal();
}

function updateElementAttributes(row, index) {
    // Update names
    row.querySelectorAll('[name^="items["]').forEach(element => {
        element.name = element.name.replace(/items\[\d+\]/, `items[${index}]`);
    });
    
    // Update IDs
    row.querySelectorAll('[id^="items_"]').forEach(element => {
        element.id = element.id.replace(/items_\d+/, `items_${index}`);
    });
    
    // Update labels
    row.querySelectorAll('label[for^="items_"]').forEach(element => {
        element.setAttribute('for', element.getAttribute('for').replace(/items_\d+/, `items_${index}`));
    });
}

function initializeNewRow(row) {
    // Add product select listener
    const productSelect = row.querySelector('.product-select');
    if (productSelect) {
        productSelect.addEventListener('change', function() {
            const index = row.dataset.row;
            updateProductDetails(this, index);
        });
    }

    // Add quantity and price input listeners
    row.querySelectorAll('.quantity, .unit-price').forEach(input => {
        input.addEventListener('input', function() {
            const index = row.dataset.row;
            calculateLineTotal(index);
        });
    });

    // Add remove button listener
    const removeButton = row.querySelector('.remove-item');
    if (removeButton) {
        removeButton.addEventListener('click', function() {
            removeItem(this);
        });
    }
}

function removeItem(button) {
    const row = button.closest('.item-row');
    
    // Animate removal
    row.style.transition = 'opacity 0.3s';
    row.style.opacity = '0';
    
    setTimeout(() => {
        row.remove();
        reindexRows();
        calculateInvoiceTotal();
    }, 300);
}

function reindexRows() {
    const container = document.getElementById('items-container');
    container.querySelectorAll('.item-row').forEach((row, index) => {
        row.dataset.row = index;
        updateElementAttributes(row, index);
    });
}

function updateProductDetails(select, index) {
    const option = select.options[select.selectedIndex];
    const row = select.closest('.item-row');
    
    if (option.value) {
        const price = option.dataset.price;
        const description = option.dataset.description;
        
        row.querySelector('.description-input').value = description;
        row.querySelector('.unit-price').value = price;
        
        calculateLineTotal(index);
    }
}

function calculateLineTotal(index) {
    const row = document.querySelector(`[data-row="${index}"]`);
    if (!row) return;

    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const total = quantity * unitPrice;
    
    row.querySelector('.line-total').value = total.toFixed(2);
    calculateInvoiceTotal();
}

function calculateInvoiceTotal() {
    let subtotal = 0;
    document.querySelectorAll('.line-total').forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });

    const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
    const taxAmount = subtotal * (taxRate / 100);
    const total = subtotal + taxAmount;

    // Update the totals
    const subtotalInput = document.getElementById('subtotal');
    const taxAmountInput = document.getElementById('tax_amount');
    const totalInput = document.getElementById('total_amount');

    if (subtotalInput) subtotalInput.value = subtotal.toFixed(2);
    if (taxAmountInput) taxAmountInput.value = taxAmount.toFixed(2);
    if (totalInput) totalInput.value = total.toFixed(2);

    // Update display values if they exist
    const subtotalDisplay = document.getElementById('subtotal-display');
    const taxAmountDisplay = document.getElementById('tax-amount-display');
    const totalDisplay = document.getElementById('total-amount-display');

    if (subtotalDisplay) subtotalDisplay.textContent = formatCurrency(subtotal);
    if (taxAmountDisplay) taxAmountDisplay.textContent = formatCurrency(taxAmount);
    if (totalDisplay) totalDisplay.textContent = formatCurrency(total);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}
