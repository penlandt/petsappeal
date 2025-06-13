<x-app-layout>
<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Returns
        </h2>
        @include('pos.partials.secondary-menu')
    </div>
</x-slot>

<div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Toggle -->
    <div class="mb-4 flex space-x-4">
        <button id="mode-client" class="px-4 py-2 bg-blue-600 text-white rounded" onclick="switchMode('client')">Client Sales</button>
        <button id="mode-anon" class="px-4 py-2 bg-gray-300 text-gray-800 rounded" onclick="switchMode('anon')">Anonymous Sales</button>
    </div>

    <!-- Client Selector -->
    <div id="client-section" class="mb-6">
        <label for="client-select" class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Select Client</label>
        <select id="client-select" class="tom-select w-full" placeholder="Choose a client..."></select>
    </div>

    <!-- Sales and Items -->
    <div class="mt-4">
        <h3 class="text-lg font-semibold dark:text-white mb-4">Sales and Items</h3>
        <div id="sales-list" class="space-y-6">
            <!-- Rendered via JS -->
        </div>
    </div>

    <!-- Refund Method and Submit -->
    <div class="mt-6">
        <label for="bulk-refund-method" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
            Refund Method
        </label>
        <select id="bulk-refund-method" class="w-full border border-gray-300 rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
            <option value="">-- Choose Method --</option>
            <option value="Cash">Cash</option>
            <option value="Credit">Credit</option>
            <option value="Store Credit">Store Credit</option>
            <option value="None">No Refund</option>
        </select>

        <button onclick="submitBulkReturn()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Submit All Selected Returns
        </button>
    </div>
</div>

<!-- Refund Confirmation Modal -->
<div id="refundConfirmationModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Refund Processed</h2>
        <p id="refundMessage" class="text-lg text-gray-800 dark:text-gray-200 mb-4"></p>
        <div class="flex justify-end">
            <button onclick="closeRefundModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                OK
            </button>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
let selectedSaleId = null;
let returnItems = [];
let currentMode = 'client';

function switchMode(mode) {
    currentMode = mode;
    document.getElementById('client-section').style.display = mode === 'client' ? 'block' : 'none';
    document.getElementById('sales-list').innerHTML = '';
    selectedSaleId = null;
    returnItems = [];

    document.getElementById('mode-client').className =
        mode === 'client' ? 'px-4 py-2 bg-blue-600 text-white rounded' : 'px-4 py-2 bg-gray-300 text-gray-800 rounded';
    document.getElementById('mode-anon').className =
        mode === 'anon' ? 'px-4 py-2 bg-blue-600 text-white rounded' : 'px-4 py-2 bg-gray-300 text-gray-800 rounded';

    if (mode === 'anon') {
        fetchAnonymousSales();
    }
}

function fetchAnonymousSales() {
    const list = document.getElementById('sales-list');
    list.innerHTML = '<p>Loading anonymous sales...</p>';

    fetch('/pos/test-anon-sales')
        .then(res => res.json())
        .then(sales => {
            if (!sales.length) {
                list.innerHTML = '<p>No anonymous sales found.</p>';
                return;
            }
            renderSales(sales);
        })
        .catch(error => {
            console.error('Error fetching anonymous sales:', error);
            list.innerHTML = '<p>Error loading sales data.</p>';
        });
}


function renderSales(sales) {
    const list = document.getElementById('sales-list');
    returnItems = [];

    list.innerHTML = sales.map(sale => `
        <div class="border rounded p-4 dark:border-gray-700">
            <h4 class="font-semibold dark:text-white">Sale #${sale.id} - ${sale.created_at} - $${parseFloat(sale.total).toFixed(2)}</h4>
            <table class="w-full mt-2 text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-2 py-1">Product</th>
                        <th class="px-2 py-1">Qty Purchased</th>
                        <th class="px-2 py-1">Qty Returned</th>
                        <th class="px-2 py-1">Qty Returnable</th>
                        <th class="px-2 py-1">Price</th>
                        <th class="px-2 py-1">Qty to Return</th>
                    </tr>
                </thead>
                <tbody>
                    ${sale.items.map(item => `
                        <tr>
                            <td class="px-2 py-1">${item.name}</td>
                            <td class="px-2 py-1">${item.quantity}</td>
                            <td class="px-2 py-1">${item.returned_quantity}</td>
                            <td class="px-2 py-1">${item.returnable_quantity}</td>
                            <td class="px-2 py-1">$${parseFloat(item.price).toFixed(2)}</td>
                            <td class="px-2 py-1">
                                ${item.returnable_quantity > 0 ? `
                                    <input type="number"
                                           min="0"
                                           max="${item.returnable_quantity}"
                                           value="0"
                                           class="w-16 px-2 py-1 border rounded dark:bg-gray-800 dark:text-white"
                                           data-sale-id="${sale.id}"
                                           data-sale-item-id="${item.sale_item_id}"
                                           data-product-id="${item.product_id}"
                                           data-price="${item.price}"
                                           onchange="updateReturnItems(this)" />
                                ` : 'N/A'}
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `).join('');
}

document.addEventListener('DOMContentLoaded', () => {
    const salesList = document.getElementById('sales-list');
    const clientSelect = new TomSelect('#client-select', {
        valueField: 'id',
        labelField: 'name',
        searchField: ['name'],
        placeholder: 'Select a client...',
        load: function(query, callback) {
            if (!query.length) return callback();
            fetch('/clients/json?search=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    callback(data.map(c => ({
                        id: c.id,
                        name: c.first_name + ' ' + c.last_name
                    })));
                }).catch(() => callback());
        }
    });

    clientSelect.on('change', async function(value) {
        salesList.innerHTML = '<p>Loading sales...</p>';
        if (!value) return;

        const res = await fetch(`/pos/returns/client/${value}/sales`);
        const sales = await res.json();
        if (!sales.length) {
            salesList.innerHTML = '<p>No sales found for this client.</p>';
            return;
        }
        renderSales(sales);
    });

    switchMode('client');
});

function updateReturnItems(input) {
    const quantity = parseInt(input.value, 10);
    const max = parseInt(input.getAttribute('max'), 10);

    if (isNaN(quantity) || quantity <= 0 || quantity > max) {
        returnItems = returnItems.filter(i => i.sale_item_id != input.dataset.saleItemId);
        input.value = 0;
        return;
    }

    const item = {
        sale_item_id: parseInt(input.dataset.saleItemId),
        product_id: parseInt(input.dataset.productId),
        quantity: quantity,
        price: parseFloat(input.dataset.price)
    };

    selectedSaleId = parseInt(input.dataset.saleId);

    const index = returnItems.findIndex(i => i.sale_item_id === item.sale_item_id);
    if (index !== -1) {
        returnItems[index] = item;
    } else {
        returnItems.push(item);
    }
}

function submitBulkReturn() {
    const refundMethod = document.getElementById('bulk-refund-method').value;
    const token = document.querySelector('meta[name="csrf-token"]').content;

    if (!selectedSaleId || !returnItems.length || !refundMethod) {
        alert('Please select return quantities and a refund method.');
        return;
    }

    fetch('/pos/returns/process', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            sale_id: selectedSaleId,
            refund_method: refundMethod,
            items: returnItems
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('refundMessage').textContent =
                `Amount to Return to Client: $${data.refund_amount.toFixed(2)} via ${data.refund_method}`;
            document.getElementById('refundConfirmationModal').classList.remove('hidden');
        } else {
            alert(data.error || 'An error occurred.');
        }
    })
    .catch(err => alert('Unexpected error: ' + err.message));
}

function closeRefundModal() {
    document.getElementById('refundConfirmationModal').classList.add('hidden');
    location.reload();
}
</script>
</x-app-layout>
