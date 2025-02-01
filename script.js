document.addEventListener('DOMContentLoaded', function () {
    const inventory = [];
    let lastScrollTop = 0;
    const navbar = document.getElementById('navbar');

    const addItemForm = document.getElementById('add-item-form');
    const salesForm = document.getElementById('sales-form');
    const inventoryTableBody = document.querySelector('#inventory-table tbody');
    const saleItemSelect = document.getElementById('sale-item');

    // Hide/Show Navbar on Scroll
    window.addEventListener('scroll', function () {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop) {
            // Scroll Down
            navbar.style.top = '-80px';
        } else {
            // Scroll Up
            navbar.style.top = '0';
        }
        lastScrollTop = scrollTop;
    });

    // Update Inventory Table
    function updateInventoryTable() {
        inventoryTableBody.innerHTML = '';
        inventory.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>$${item.price.toFixed(2)}</td>
            `;
            inventoryTableBody.appendChild(row);
        });
    }

    // Update Sales Item Dropdown
    function updateSalesItemDropdown() {
        saleItemSelect.innerHTML = '';
        inventory.forEach(item => {
            const option = document.createElement('option');
            option.value = item.name;
            option.textContent = item.name;
            saleItemSelect.appendChild(option);
        });
    }

    // Add Item Form Submission
    addItemForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const itemName = document.getElementById('item-name').value;
        const itemQuantity = parseInt(document.getElementById('item-quantity').value);
        const itemPrice = parseFloat(document.getElementById('item-price').value);

        inventory.push({
            name: itemName,
            quantity: itemQuantity,
            price: itemPrice
        });

        updateInventoryTable();
        updateSalesItemDropdown();
        addItemForm.reset();
    });

    // Sales Form Submission
    salesForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const saleItemName = saleItemSelect.value;
        const saleQuantity = parseInt(document.getElementById('sale-quantity').value);

        const itemIndex = inventory.findIndex(item => item.name === saleItemName);
        if (itemIndex !== -1 && inventory[itemIndex].quantity >= saleQuantity) {
            inventory[itemIndex].quantity -= saleQuantity;
            if (inventory[itemIndex].quantity === 0) {
                inventory.splice(itemIndex, 1);
            }
            updateInventoryTable();
            updateSalesItemDropdown();
        } else {
            alert('Not enough stock available for this item.');
        }
        salesForm.reset();
    });
});