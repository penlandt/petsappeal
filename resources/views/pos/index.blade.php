<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Point of Sale
                <span class="relative inline-block align-middle ml-2" x-data="{ show: false }">
                <svg @mouseenter="show = true" @mouseleave="show = false"
                    class="w-5 h-5 text-blue-500 cursor-pointer"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-9-1h2v5H9v-5zm0-4h2v2H9V5z" clip-rule="evenodd" />
                </svg>
                <div x-show="show" x-cloak
                    class="absolute z-50 bg-gray-700 text-white text-sm rounded py-2 px-3 bottom-full mb-2 left-0 w-64 whitespace-normal shadow-lg">
                    The beating heart of PETSAppeal, this unified register allows you to handle payments for grooming, boarding, and retail — all at the same time, in one central location.
                </div>
            </span>
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
                        <select id="client_id" name="client_id" class="tom-select w-full" autocomplete="off" required placeholder="Select a client (optional)">

                            <!-- Options will be populated dynamically -->
                        </select>
                        {{-- Loyalty Balance Display (hidden by default) --}}
                    <div id="loyalty-balance" class="hidden text-sm text-green-600 dark:text-green-400 ml-2">
                        Available Discount: <span id="loyalty-discount" class="font-semibold">$0.00</span>
                    </div>

                    {{-- Loyalty Apply Checkbox (hidden by default) --}}
                    <div id="apply-loyalty-wrapper" class="hidden flex items-center ml-2">
                        <input type="checkbox" id="apply-loyalty" class="mr-1" checked>
                        <label for="apply-loyalty" class="text-sm text-gray-800 dark:text-gray-200">Apply Loyalty Discount</label>
                    </div>
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

                    <!-- Loyalty Discount Line (hidden by default) -->
                    <div id="loyalty-discount-line" class="hidden flex justify-between mb-2 text-green-600 dark:text-green-400">
                        <span>Discount from Loyalty Points:</span>
                        <span id="loyalty-discount-amount" class="font-semibold">–$0.00</span>
                    </div>

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

        {{-- Stripe Card Element (only if location has connected Stripe account) --}}
        @if (!empty($location?->stripe_account_id))
            <div id="stripe-card-section" class="my-4 p-4 border rounded bg-white dark:bg-gray-700 dark:text-white">
                <label for="card-element" class="block text-sm font-medium mb-2">
                    Credit Card
                </label>
                <div id="card-element" class="p-2 border rounded bg-white dark:bg-gray-800">
                    <!-- Stripe Elements will be injected here -->
                </div>
                <div id="card-errors" class="mt-2 text-red-600 dark:text-red-400 text-sm"></div>
            </div>
        @endif

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
    const qtyInput = document.getElementById(`qty-${productId}`);
    if (!qtyInput) {
        console.error("Quantity input not found for product", productId);
        return;
    }

    const quantity = parseFloat(qtyInput.value) || 1;
    const isTaxable = arguments.length > 3 ? arguments[3] : false;


    const itemKey = `product-${productId}`;
    console.log("🛒 Adding to cart:", { itemKey, productId, name, price, quantity });

    const existingItem = cart.find(item => item.key === itemKey);

    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            key: itemKey,
            id: productId,
            name,
            price,
            quantity,
            taxable: isTaxable
        });
    }

    saveCartToLocalStorage();
    renderCart();

    const searchInput = document.getElementById('product-search');
    if (searchInput) {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
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
                ${item.name}
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
    });

    updateTotals();
}

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
        if (item.taxable === true) {
            taxableAmount += lineTotal;
        }
    });

    let discount = 0;

    // Loyalty logic
    const applyLoyalty = document.getElementById('apply-loyalty')?.checked;
    const clientId = clientSelect?.getValue?.() || document.getElementById('client_id')?.value || null;

    if (applyLoyalty && clientId && subtotal > 0) {
        fetch(`/pos/client/${clientId}/loyalty-points`)
            .then(res => res.json())
                        .then(data => {
                const points = parseFloat(data.points) || 0;
                const pointValue = parseFloat(data.point_value) || 0;
                const maxPercent = parseFloat(data.max_discount_percent) || 0;

                const maxAllowed = subtotal * (maxPercent / 100);
                const valueFromPoints = points * pointValue;

                discount = Math.min(valueFromPoints, maxAllowed);

                const discountedSubtotal = subtotal - discount;

                // Use the correct taxableAmount calculated earlier in the function
                const tax = taxableAmount * (productTaxRate / 100);
                const total = discountedSubtotal + tax;

                // Update the DOM
                document.getElementById('loyalty-discount-line').classList.remove('hidden');
                document.getElementById('loyalty-discount-amount').textContent = `–$${discount.toFixed(2)}`;

                document.getElementById('subtotal').textContent = `$${discountedSubtotal.toFixed(2)}`;
                document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
                document.getElementById('total').textContent = `$${total.toFixed(2)}`;
            })

            .catch(err => {
                console.error("Error applying loyalty discount:", err);
                // fallback without discount
                document.getElementById('loyalty-discount-line').classList.add('hidden');
                const tax = taxableAmount * (productTaxRate / 100);
                const total = subtotal + tax;
                document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
                document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
                document.getElementById('total').textContent = `$${total.toFixed(2)}`;
            });
    } else {
        // Loyalty not applied
        document.getElementById('loyalty-discount-line').classList.add('hidden');
        const tax = taxableAmount * (productTaxRate / 100);
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;
    }
}


function checkoutCart() {
    // Check if the cart is empty. If it is, alert the user and stop the process.
    if (cart.length === 0) {
        alert("Cart is empty.");
        return;
    }

    // If there are no payment entries, add a default one for the "Cash" method and $0.00 amount.
    if (paymentEntries.length === 0) {
        paymentEntries.push({ method: 'Cash', amount: 0.00, reference_number: '' });
        renderPaymentEntries(); // Re-render the payment entries to reflect the new entry.
    }

    // Get the total amount due from the #total element on the main page (pre-discount, tax, etc.)
    const totalDue = parseFloat(document.getElementById('total').textContent.replace('$', ''));

    // Get the amount paid so far and calculate the remaining balance
    const totalPaid = paymentEntries.reduce((sum, p) => sum + p.amount, 0);
    const remainingBalance = totalDue - totalPaid;

    // Update the display of the post-discount total and remaining balance in the Payment Modal
    renderPaymentEntries(totalDue, remainingBalance);

    // Open the Payment Modal
    document.getElementById('paymentModal').style.display = 'flex';
}

function renderPaymentEntries() {

const container = document.getElementById('payment-entries');
container.innerHTML = ''; // Clear the existing payment entries

const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
const taxableAmount = cart.reduce((sum, item) => item.taxable ? sum + (item.price * item.quantity) : sum, 0);
const tax = taxableAmount * (productTaxRate / 100);
const total = subtotal + tax;

const totalDue = parseFloat(document.getElementById('total').textContent.replace('$', ''));
const totalPaid = paymentEntries.reduce((sum, p) => sum + p.amount, 0);

const balance = totalPaid - totalDue;

// Create and insert amount due element
const postDiscountElement = document.createElement('div');
postDiscountElement.classList.add('mb-4');
postDiscountElement.id = 'amountDueDisplay';
postDiscountElement.textContent = `Amount Due: $${totalDue.toFixed(2)}`;
container.appendChild(postDiscountElement);

// Create and insert remaining balance/change owed element
const remainingBalanceElement = document.createElement('div');
remainingBalanceElement.classList.add('mt-4', 'font-semibold', 'text-gray-900', 'dark:text-white');
remainingBalanceElement.id = 'remainingBalanceDisplay';
container.appendChild(remainingBalanceElement);

// Add the payment entries to the modal using safe DOM manipulation
paymentEntries.forEach((entry, index) => {
    const entryRow = document.createElement('div');
    entryRow.className = 'mb-2 flex flex-wrap items-center gap-2';

    entryRow.innerHTML = `
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
    `;

    container.appendChild(entryRow);
});

// Update the remaining balance or change owed display
if (balance < 0) {
    remainingBalanceElement.textContent = `Remaining Balance: $${Math.abs(balance).toFixed(2)}`;
} else if (balance > 0) {
    remainingBalanceElement.textContent = `Change Owed: $${balance.toFixed(2)}`;
} else {
    remainingBalanceElement.textContent = `No Remaining Balance or Change Owed`;
}
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


async function submitPayments() {
    if (cart.length === 0) {
        alert("Cart is empty.");
        return;
    }

    const clientId = clientSelect?.getValue?.() || document.getElementById('client_id')?.value || null;

    if (!clientId) {
        alert("Please select a client.");
        return;
    }

    const totalDue = parseFloat(document.getElementById('total').textContent.replace('$', ''));
    const totalPaid = paymentEntries.reduce((sum, p) => sum + p.amount, 0);
    const epsilon = 0.01;

    if (totalPaid + epsilon < totalDue) {
        alert(`Total due is $${totalDue.toFixed(2)}. You have only entered $${totalPaid.toFixed(2)}.`);
        return;
    }

    // Check if any of the payments are Credit
    const creditPayment = paymentEntries.find(p => p.method === 'Credit');

    if (creditPayment && typeof stripe !== 'undefined' && typeof cardElement !== 'undefined') {
        try {
            const intentResponse = await fetch('/pos/create-payment-intent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    amount: creditPayment.amount
                })
            });

            const intentData = await intentResponse.json();

            if (!intentData.clientSecret) {
                alert("Failed to initialize Stripe PaymentIntent.");
                return;
            }

            const result = await stripe.confirmCardPayment(intentData.clientSecret, {
                payment_method: {
                    card: cardElement
                }
            });

            if (result.error) {
                console.error("Stripe confirmation error:", result.error);
                alert("Card payment failed: " + result.error.message);
                return;
            }

            if (result.paymentIntent?.status !== 'succeeded') {
                alert("Card payment did not succeed.");
                return;
            }

            // Store reference to Stripe intent ID
            creditPayment.reference_number = result.paymentIntent.id;

        } catch (error) {
            console.error("Stripe error:", error);
            alert("An error occurred processing the credit card.");
            return;
        }
    }

    // Submit the sale as usual
    fetch("/pos/checkout", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            items: cart.map(item => ({
                product_id: typeof item.id === 'number' ? item.id : null,
                name: item.name,
                price: item.price,
                quantity: item.quantity,
                taxable: item.taxable,
                invoice_id: item.invoice_id ?? null
            })),
            payments: paymentEntries,
            client_id: clientId,
            redeem_points: document.getElementById('apply-loyalty')?.checked || false,
            total_due: totalDue
        })
    })
    .then(async response => {
        const contentType = response.headers.get("content-type") || "";
        const raw = await response.text();
        console.log("🧾 Raw server response:", raw);

        if (!contentType.includes("application/json")) {
            throw new Error("Server did not return JSON");
        }

        const data = JSON.parse(raw);

        if (data.success) {
            alert(`Sale completed!\nChange owed: $${data.change_owed}`);
            cart = [];
            localStorage.removeItem('cart');
            renderCart();
            if (typeof clientSelect?.clear === 'function') {
                clientSelect.clear();
            }
            closePaymentModal();
        } else {
            alert("Checkout failed: " + (data.error || "Unknown error"));
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
        const container = document.createElement('div');
        container.className = 'flex items-center justify-between border-b border-gray-300 dark:border-gray-600 py-2';

        const left = document.createElement('div');

        const nameEl = document.createElement('div');
        nameEl.className = 'text-gray-900 dark:text-white font-semibold';
        nameEl.textContent = product.name;

        const priceEl = document.createElement('div');
        priceEl.className = 'text-sm text-gray-700 dark:text-gray-300';
        priceEl.textContent = `$${parseFloat(product.price).toFixed(2)}`;

        left.appendChild(nameEl);
        left.appendChild(priceEl);

        const right = document.createElement('div');
        right.className = 'flex items-center space-x-2';

        const input = document.createElement('input');
        input.type = 'number';
        input.id = `qty-${product.id}`;
        input.value = 1;
        input.min = 0.01;
        input.step = 0.01;
        input.className = 'w-20 px-2 py-1 rounded border dark:bg-gray-700 dark:text-white';

        const button = document.createElement('button');
        button.className = 'bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded';
        button.textContent = 'Add';

        // ✅ This is now fully safe
        button.addEventListener('click', () => {
            const cleanName = String(product.name).replace(/[\n\r\t]/g, ' ').trim();
            addToCart(product.id, cleanName, product.price, product.taxable);
        });

        right.appendChild(input);
        right.appendChild(button);

        container.appendChild(left);
        container.appendChild(right);

        productList.appendChild(container);
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

    // Recalculate totals when loyalty checkbox is changed
    document.getElementById('apply-loyalty')?.addEventListener('change', updateTotals);

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

            // Add a blank option
            const blankOption = document.createElement('option');
            blankOption.value = '';
            blankOption.text = 'Select a client (optional)';
            blankOption.disabled = true;
            blankOption.selected = true;
            selectElement.appendChild(blankOption);

            // Add client options
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
        updateTotals();
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

        if (clientSelect) {
            const option = {
                value: String(newClient.id),
                text: newClient.first_name + ' ' + newClient.last_name
            };

            clientSelect.addOption(option);
            clientSelect.addItem(option.value);
        
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

document.getElementById('client_id').addEventListener('change', async function () {
    const clientId = this.value;

    // Hide loyalty balance and apply checkbox by default
    document.getElementById('loyalty-balance').classList.add('hidden');
    document.getElementById('apply-loyalty-wrapper').classList.add('hidden');

    if (!clientId) return;  // If no client is selected, do nothing

    try {
        // Fetch the client's loyalty points
        const response = await fetch(`/pos/client/${clientId}/loyalty-points`);
        const data = await response.json();

        const points = parseFloat(data.points);

        // If the client has positive points, show the discount section
        if (points > 0) {
            const discount = points * parseFloat(data.point_value || 0.05);  // Assuming point_value is fetched properly

            // Display the available discount
            document.getElementById('loyalty-discount').textContent = `$${discount.toFixed(2)}`;
            document.getElementById('loyalty-balance').classList.remove('hidden');
            document.getElementById('apply-loyalty-wrapper').classList.remove('hidden');
        }

    } catch (error) {
        console.error("Error fetching loyalty points:", error);
        document.getElementById('loyalty-balance').classList.add('hidden');
        document.getElementById('apply-loyalty-wrapper').classList.add('hidden');
    }
});


</script>

@if (!empty($location?->stripe_account_id))
    <!-- Stripe.js and Elements initialization -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const stripe = Stripe("{{ config('services.stripe.key') }}");
            const elements = stripe.elements();

const card = elements.create('card', {
    style: {
        base: {
            color: '#ffffff',
            fontFamily: 'inherit',
            fontSize: '16px',
            '::placeholder': {
                color: '#cbd5e1' // Tailwind's slate-300
            },
            iconColor: '#ffffff',
            backgroundColor: '#1f2937' // Tailwind's gray-800
        },
        invalid: {
            color: '#f87171', // red-400
            iconColor: '#f87171'
        }
    }
});

card.mount('#card-element');

            // Optional: handle validation errors
            card.on('change', event => {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Save Stripe references for use in submitPayments()
            window.stripe = stripe;
            window.cardElement = card;
        });
    </script>
@endif

@include('partials.modals.new-client')

</x-app-layout>