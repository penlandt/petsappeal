<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Point of Sale
            </h2>
            @include('pos.partials.secondary-menu')

            
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
    
    
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="w-full lg:w-2/3">
                <div class="mb-4">
                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Select Client (optional)
                    </label>
                    <div class="flex items-center space-x-2">
                        <select id="client_id" name="client_id" class="tom-select w-full" autocomplete="off" required>
                            <!-- Options will be populated dynamically -->
                        </select>
                        <button id="addNewClientBtn" type="button"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold px-3 py-2 rounded"
                            title="Add New Client">
                            +
                        </button>
                    </div>
                </div>


                <div class="flex justify-between items-center mb-4">
                    <input type="text" id="product-search"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Search for a product...">

                    <button onclick="openAddProductModal()"
                        class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded whitespace-nowrap">
                        + Add Product
                    </button>
                </div>


                    <div id="product-list" class="mb-6 bg-white dark:bg-gray-800 rounded shadow p-4 max-h-96 overflow-auto">
                        <!-- Products will be dynamically loaded here -->
                    </div>

                    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Product</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Qty</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Price</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Total</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                <!-- Items will appear here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 rounded shadow p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Order Summary</h3>

                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700 dark:text-gray-300">Subtotal:</span>
                        <span id="subtotal" class="text-gray-900 dark:text-white">$0.00</span>
                    </div>

                    <div class="flex justify-between mb-4">
                        <span class="text-gray-700 dark:text-gray-300">
                            Tax <span class="text-sm text-gray-500 dark:text-gray-400">({{ $productTaxRate }}%)</span>:
                        </span>
                        <span id="tax" class="text-gray-900 dark:text-white">$0.00</span>
                    </div>

                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span class="text-gray-800 dark:text-white">Total:</span>
                        <span id="total" class="text-gray-900 dark:text-white">$0.00</span>
                    </div>

                    <button onclick="checkoutCart()" class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Checkout
                    </button>

                </div>
            </div>
        
    </div>

<!-- Payment Modal -->
<div id="paymentModal"
     class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-60 z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Enter Payment</h2>
        <div id="payment-entries"></div>
        <button type="button" id="add-payment-btn"
                class="mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Add Payment Method
        </button>
        <div class="flex justify-end space-x-2">
            <button onclick="closePaymentModal()"
                    type="button"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Cancel
            </button>

            <button type="button" onclick="submitPayments()"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
    Submit Payment
</button>
        </div>
    </div>
</div>

<!-- Return Modal -->
<div id="returnModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-60 z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Return Item</h2>
        <form id="returnForm">
            <input type="hidden" id="returnItemIndex">
            <div class="mb-4">
                <label for="returnQuantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity to Return</label>
                <input type="number" id="returnQuantity" min="1" step="1"
                    class="mt-1 w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-4">
                <label for="refundMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refund Method</label>
                <select id="refundMethod"
                    class="mt-1 w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white">
                    <option value="Cash">Cash</option>
                    <option value="Credit">Credit</option>
                    <option value="Store Credit">Store Credit</option>
                    <option value="None">None (No Refund)</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="returnPrice" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refund Price (per item)</label>
                <input type="number" id="returnPrice" min="0" step="0.01"
                    class="mt-1 w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeReturnModal()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Confirm Return</button>
            </div>
        </form>
    </div>
</div>


<!-- Add Product Modal -->
<div id="addProductModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-60 z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-xl">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add New Product</h2>
        <form id="addProductForm">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" id="newProductName" required
                        class="w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                    <input type="number" id="newProductPrice" required step="0.01"
                        class="w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cost</label>
                    <input type="number" id="newProductCost" required step="0.01"
                        class="w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                    <input type="number" id="newProductQuantity" required step="1" min="0"
                        class="w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">UPC</label>
                    <input type="text" id="newProductUPC"
                        class="w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU</label>
                    <input type="text" id="newProductSKU"
                        class="w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white" />
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea id="newProductDescription"
                    class="w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            <div class="mt-4 flex flex-wrap items-center gap-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="newProductInactive"
                        class="rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Inactive</span>
                </label>

                <label class="inline-flex items-center">
                    <input type="checkbox" id="newProductTaxable" checked
                        class="rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Taxable</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="closeAddProductModal()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>


<!-- TomSelect CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<!-- TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
const productTaxRate = @json($productTaxRate ?? 0);
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let paymentEntries = [];
const clientsJsonUrl = @json(route('clients.json'));


// Global functions (accessible from buttons, modals, etc.)
window.addToCart = function(productId, name, price) {
    console.log("addToCart called with:", productId, name, price);

    const qtyInput = document.getElementById(`qty-${productId}`);
    if (!qtyInput) {
        console.error("Quantity input not found for product", productId);
        return;
    }

    const quantity = parseFloat(qtyInput.value) || 1;

    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        const isTaxable = arguments.length > 3 ? arguments[3] : true; // default true
        cart.push({ id: productId, name, price, quantity, taxable: isTaxable });
    }

    saveCartToLocalStorage();
    renderCart();
    console.log("Cart after adding:", cart);

    // ✅ Clear the search box after adding
    const searchInput = document.getElementById('product-search');
    if (searchInput) {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input')); // Triggers the search to re-clear results
    }
};


window.removeFromCart = function(index) {
    cart.splice(index, 1);
    saveCartToLocalStorage();
    renderCart();
};

function saveCartToLocalStorage() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function renderCart() {
    console.log('renderCart was called');
    const cartItems = document.getElementById('cart-items');
    cartItems.innerHTML = '';

    cart.forEach((item, index) => {
        const total = item.price * item.quantity;
        const isInvoice = item.source === 'invoice';
        const viewLink = isInvoice
            ? `<br><a href="/invoices/${item.invoice_id}/print" target="_blank" class="text-blue-600 dark:text-blue-400 underline text-sm">view</a>`
            : '';

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="px-4 py-2 text-gray-900 dark:text-white">
                ${item.name}${viewLink}
                <br>
                <button onclick="startReturn(${index})"
                    class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 underline">
                    Return
                </button>
            </td>

            <td class="px-4 py-2 text-gray-900 dark:text-white">${item.quantity.toFixed(2)}</td>
            <td class="px-4 py-2 text-gray-900 dark:text-white">
                <input type="number" step="0.01" min="0" value="${item.price}"
                    onchange="updateCartPrice(${index}, this.value)"
                    class="w-20 px-2 py-1 border rounded dark:bg-gray-700 dark:text-white" />
            </td>
            <td class="px-4 py-2 text-gray-900 dark:text-white">$${total.toFixed(2)}</td>
            <td class="px-4 py-2 text-right">
                <button onclick="removeFromCart(${index})"
                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200">Remove</button>
            </td>
        `;
        cartItems.appendChild(tr);
        console.log(tr.innerHTML);
    });

    updateTotals();
}

function startReturn(index) {
    const item = cart[index];
    if (!item) return;

    document.getElementById('returnItemIndex').value = index;
    document.getElementById('returnQuantity').value = 1;
    document.getElementById('returnQuantity').max = item.quantity;
    document.getElementById('returnPrice').value = item.price ?? 0;

    document.getElementById('returnModal').classList.remove('hidden');
    document.getElementById('returnModal').classList.add('flex');
}

function closeReturnModal() {
    const modal = document.getElementById('returnModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('returnForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();

    const index = parseInt(document.getElementById('returnItemIndex').value);
    const quantity = parseInt(document.getElementById('returnQuantity').value);
    const method = document.getElementById('refundMethod').value;

    const item = cart[index];
    if (!item || quantity < 1 || quantity > item.quantity) {
        alert("Invalid quantity.");
        return;
    }

    const confirmed = confirm(`Return ${quantity} of ${item.name} via ${method}?`);
    if (!confirmed) return;

    // Update cart
    if (item.quantity > quantity) {
        item.quantity -= quantity;
    } else {
        cart.splice(index, 1);
    }
    saveCartToLocalStorage();
    renderCart();

    closeReturnModal();

    const price = parseFloat(document.getElementById('returnPrice').value) || 0;
    const taxRate = productTaxRate || 0;
    const taxAmount = (item.taxable ? price * quantity * (taxRate / 100) : 0).toFixed(2);

    // Send to server
    try {
        const response = await fetch('/pos/return', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            
            body: JSON.stringify({
                product_id: item.id,
                quantity: quantity,
                refund_method: method,
                client_id: clientSelect?.getValue?.() || document.getElementById('client_id')?.value || null,
                price: price,
                tax_amount: taxAmount
            })
        });

        const data = await response.json();
        if (data.success) {
            alert("Return processed.");
        } else {
            alert("Return saved locally, but not recorded on server.");
        }
    } catch (err) {
        console.error("Return failed:", err);
        alert("Return saved locally, but error contacting server.");
    }
});



function updateCartPrice(index, newPrice) {
    cart[index].price = parseFloat(newPrice) || 0;
    renderCart();
}

function updateTotals() {
    let subtotal = 0;
    let taxableAmount = 0;

    cart.forEach(item => {
        const lineTotal = item.price * item.quantity;
        subtotal += lineTotal;
        if (item.taxable) {
            taxableAmount += lineTotal;
        }
    });

    const tax = taxableAmount * (productTaxRate / 100);
    const total = subtotal + tax;

    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('total').textContent = `$${total.toFixed(2)}`;
}


function checkoutCart() {
    if (cart.length === 0) {
        alert("Cart is empty.");
        return;
    }

    if (paymentEntries.length === 0) {
        paymentEntries.push({ method: 'Cash', amount: 0.00, reference_number: '' });
        renderPaymentEntries();
    }

    document.getElementById('paymentModal').style.display = 'flex';
}

function closePaymentModal() {
    const modal = document.getElementById('paymentModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    const container = document.getElementById('paymentEntriesContainer');
    if (container) container.innerHTML = '';

    paymentEntries = [];
    const amountDueElem = document.getElementById('amountDueDisplay');
    if (amountDueElem) amountDueElem.textContent = '$0.00';
    const changeDueElem = document.getElementById('changeDueDisplay');
    if (changeDueElem) changeDueElem.textContent = '$0.00';
}

function renderPaymentEntries() {
    const container = document.getElementById('payment-entries');
    const active = document.activeElement;
    const activeId = active?.getAttribute('data-entry-index');
    container.innerHTML = '';

    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const taxableAmount = cart.reduce((sum, item) => item.taxable ? sum + (item.price * item.quantity) : sum, 0);
    const tax = taxableAmount * (productTaxRate / 100);
    const total = subtotal + tax;
    const totalPaid = paymentEntries.reduce((sum, p) => sum + p.amount, 0);
    const balance = totalPaid - total;

    container.innerHTML += `
        <div class="mb-4">
            <div class="text-gray-900 dark:text-white font-semibold">Amount Due: $${total.toFixed(2)}</div>
        </div>
    `;

    paymentEntries.forEach((entry, index) => {
        container.innerHTML += `
        <div class="mb-2 flex flex-wrap items-center gap-2">
            <select class="w-32 dark:bg-gray-700 dark:text-white"
                    onchange="updatePaymentMethod(${index}, this.value)">
                <option value="Cash"${entry.method === 'Cash' ? ' selected' : ''}>Cash</option>
                <option value="Credit"${entry.method === 'Credit' ? ' selected' : ''}>Credit</option>
                <option value="Check"${entry.method === 'Check' ? ' selected' : ''}>Check</option>
                <option value="Other"${entry.method === 'Other' ? ' selected' : ''}>Other</option>
            </select>
            <input type="number" placeholder="Amount"
                   class="w-24 px-2 py-1 rounded border dark:bg-gray-700 dark:text-white"
                   value="${entry.amount}" onblur="updatePaymentAmount(${index}, this.value)">
            <input type="text" placeholder="Reference"
                   class="flex-1 px-2 py-1 rounded border dark:bg-gray-700 dark:text-white"
                   value="${entry.reference_number || ''}" oninput="updatePaymentReference(${index}, this.value)">
            <button onclick="removePaymentEntry(${index})"
                    class="text-red-600 dark:text-red-400 text-sm">Remove</button>
        </div>`;
    });

    container.innerHTML += `
        <div class="mt-4 font-semibold ${balance < 0 ? 'text-gray-900 dark:text-white' : 'text-green-600 dark:text-green-400'}">
            ${balance < 0
                ? `Remaining Balance: $${Math.abs(balance).toFixed(2)}`
                : totalPaid === 0
                    ? `Change Owed: $0.00`
                    : `Change Owed: $${balance.toFixed(2)}`
            }
        </div>
    `;
}

function addPaymentEntry() {
    paymentEntries.push({ method: 'Cash', amount: 0.00, reference_number: '' });
    renderPaymentEntries();
}

function removePaymentEntry(index) {
    paymentEntries.splice(index, 1);
    renderPaymentEntries();
}

function updatePaymentMethod(index, value) {
    paymentEntries[index].method = value;
}

function updatePaymentAmount(index, value) {
    paymentEntries[index].amount = parseFloat(value) || 0;
    renderPaymentEntries();
}

function updatePaymentReference(index, value) {
    paymentEntries[index].reference_number = value;
}

function submitPayments() {
    if (cart.length === 0) {
        alert("Cart is empty.");
        return;
    }

    const totalPaid = paymentEntries.reduce((sum, p) => sum + p.amount, 0);
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const taxableAmount = cart.reduce((sum, item) => item.taxable ? sum + (item.price * item.quantity) : sum, 0);
    const tax = taxableAmount * (productTaxRate / 100);
    const total = subtotal + tax;

    if (totalPaid < total) {
        alert(`Total due is $${total.toFixed(2)}. You have only entered $${totalPaid.toFixed(2)}.`);
        return;
    }

    const clientId = clientSelect?.getValue?.() || document.getElementById('client_id')?.value || null;

    fetch("/pos/checkout", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({
            items: cart,
            payments: paymentEntries,
            client_id: clientId
        })
    })
    .then(async response => {
        const text = await response.text();
        try {
            const data = JSON.parse(text);
            if (data.success) {
                alert(`Sale completed!\nChange owed: $${data.change_owed}`);
                cart = [];
                localStorage.removeItem('cart');
                renderCart();
                if (typeof clientSelect?.clear === 'function') {
                    clientSelect.clear(); // ✅ Reset TomSelect to default state
                }
                closePaymentModal();
            } else {
                alert("Checkout failed: " + (data.error || "Unknown error"));
            }
        } catch (err) {
            console.error("Invalid JSON response:", err);
            alert("Server returned invalid response.");
        }
    })
    .catch(error => {
        console.error("Checkout error:", error);
        alert("An error occurred during checkout.");
    });
}

// DOM-specific code
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('product-search');
    const productList = document.getElementById('product-list');

    function renderProducts(products) {
        productList.innerHTML = '';
        products.forEach(product => {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between border-b border-gray-300 dark:border-gray-600 py-2';
            div.innerHTML = `
                <div>
                    <div class="text-gray-900 dark:text-white font-semibold">${product.name}</div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">$${parseFloat(product.price).toFixed(2)}</div>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="number" id="qty-${product.id}" value="1" min="0.01" step="0.01"
                        class="w-20 px-2 py-1 rounded border dark:bg-gray-700 dark:text-white" />
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded"
                        onclick="addToCart(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.price}, ${product.taxable})">
                        Add
                    </button>
                </div>
            `;
            productList.appendChild(div);
        });
    }

    async function searchProducts(query) {
        if (!query) {
            productList.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`/pos/api/products/search?q=${encodeURIComponent(query)}`);
            if (!response.ok) throw new Error('Network response was not ok');
            const products = await response.json();
            renderProducts(products);
        } catch (error) {
            productList.innerHTML = `<div class="text-red-600 dark:text-red-400 text-center">Failed to load products</div>`;
            console.error('Error loading products:', error);
        }
    }

    let debounceTimeout = null;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            searchProducts(searchInput.value.trim());
        }, 300);
    });

    document.getElementById('add-payment-btn')?.addEventListener('click', addPaymentEntry);

    renderCart();
});

function openAddProductModal() {
    document.getElementById('addProductModal').classList.remove('hidden');
    document.getElementById('addProductModal').classList.add('flex');
}

function closeAddProductModal() {
    document.getElementById('addProductModal').classList.add('hidden');
    document.getElementById('addProductModal').classList.remove('flex');
}

document.getElementById('addProductForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();

    const data = {
        name: document.getElementById('newProductName').value.trim(),
        price: parseFloat(document.getElementById('newProductPrice').value),
        cost: parseFloat(document.getElementById('newProductCost').value),
        quantity: parseInt(document.getElementById('newProductQuantity').value),
        upc: document.getElementById('newProductUPC').value.trim(),
        sku: document.getElementById('newProductSKU').value.trim(),
        description: document.getElementById('newProductDescription').value.trim(),
        inactive: document.getElementById('newProductInactive').checked ? 1 : 0,
    };

    if (!data.name || data.price < 0 || data.cost < 0 || data.quantity < 0) {
        alert("Please enter valid product information.");
        return;
    }

    try {
        const response = await fetch('/pos/api/products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (result.success) {
            alert('Product added successfully!');
            closeAddProductModal();
            document.getElementById('product-search').value = data.name;
            searchProducts(data.name);
        } else {
            alert('Failed to add product.');
        }
    } catch (err) {
        console.error(err);
        alert('An error occurred.');
    }
});

let clientSelect;

document.addEventListener("DOMContentLoaded", async () => {
    const selectElement = document.getElementById("client_id");

    if (selectElement) {
        try {
            // Fetch the full client list from the server
            const response = await fetch(clientsJsonUrl);
            const clients = await response.json();

            // Populate the <select> with options
            selectElement.innerHTML = ''; // clear existing options
            clients.forEach(client => {
                const option = document.createElement('option');
                option.value = String(client.id);
                option.text = `${client.first_name} ${client.last_name}`;
                selectElement.appendChild(option);
            });

            // Initialize TomSelect
            if (selectElement.tomselect) {
                selectElement.tomselect.destroy();
            }

            clientSelect = new TomSelect(selectElement, {
                allowEmptyOption: true,
                placeholder: 'Select a client (optional)',
                create: false,
                sortField: { field: "text", direction: "asc" }
            });

        } catch (err) {
            console.error('Failed to load clients:', err);
            alert('Error loading client list.');
        }
    }

    document.getElementById('add-payment-btn')?.addEventListener('click', addPaymentEntry);
    renderCart();
});



document.getElementById('client_id').addEventListener('change', async function () {
    const clientId = this.value;
    if (!clientId) return;

    try {
        const res = await fetch(`/pos/client/${clientId}/unpaid-invoices`);
        if (!res.ok) throw new Error("Failed to fetch unpaid invoices.");
        const text = await res.text();
        console.log('Raw unpaid invoice response:', text);
        const items = JSON.parse(text);

        items.forEach(item => {
            const existing = cart.find(c => c.id === `invoice-${item.invoice_id}`);

            if (!existing) {
                cart.push({
                    id: `invoice-${item.invoice_id}`,  // was invoice_item_id
                    name: item.name,
                    price: parseFloat(item.price),
                    quantity: item.quantity,
                    taxable: false,
                    source: 'invoice',
                    invoice_id: item.invoice_id,
                    invoice_item_id: item.invoice_item_id  // keep this if you plan to use it elsewhere
                });

            }
        });

        saveCartToLocalStorage();
        renderCart();
    } catch (err) {
        console.error("Error loading unpaid invoices:", err);
        alert("Could not load unpaid invoices.");
    }
});

</script>

<script>
let currentClientSelect = null;

// =======================
// Modal Form Handler
// =======================
function handleModalForm(modalId, formId, callback) {
    const modal = document.getElementById(modalId);
    const form = document.getElementById(formId);
    if (!modal || !form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: form.method,
            headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
},
            body: formData,
        });
        const data = await response.json();
        if (response.ok) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (typeof callback === 'function') {
                callback(data);
            }
        } else {
            alert('Error saving client.');
        }
    });
}

// =======================
// New Client Modal Logic
// =======================
document.getElementById('addNewClientBtn')?.addEventListener('click', function () {
    const modal = document.getElementById('newClientModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    currentClientSelect = clientSelect;
});

document.addEventListener('DOMContentLoaded', () => {
    handleModalForm('newClientModal', 'newClientForm', async function (data) {
    if (data && data.client) {
        const newClient = data.client;

        console.log('🟢 New client created:', newClient);
        console.log('⏳ Refreshing clientSelect...');

        if (clientSelect) {
            const option = {
                value: String(newClient.id),
                text: newClient.first_name + ' ' + newClient.last_name
            };

            clientSelect.addOption(option);
            clientSelect.addItem(option.value);
            console.log('✅ New client added and selected:', option);

        }
    }
});

document.querySelectorAll('#newClientModal .cancel-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('newClientModal').classList.add('hidden');
        document.getElementById('newClientModal').classList.remove('flex');
    });
});

});
</script>

@include('partials.modals.new-client')

</x-app-layout>