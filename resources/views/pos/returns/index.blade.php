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
    <div>
      <label for="client-select" class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Select Client</label>
      <select id="client-select" class="tom-select w-full" placeholder="Choose a client..."></select>
    </div>

    <div class="mt-6">
      <h3 class="text-lg font-semibold dark:text-white mb-4">Sales and Items</h3>
      <div id="sales-list" class="space-y-6">
        <!-- Sales and items will load here -->
      </div>
    </div>

    <!-- Return Modal -->
    <div id="returnModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-60 z-50">
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md">
        <h2 class="text-lg font-semibold dark:text-white mb-4">Return Item</h2>
        <form id="returnForm">
          <input type="hidden" id="returnSaleId" name="sale_id" />
          <input type="hidden" id="returnSaleItemId" name="sale_item_id" />
          <input type="hidden" id="returnProductId" name="product_id" />
          
          <div class="mb-4">
            <label for="returnQuantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity to Return</label>
            <input type="number" id="returnQuantity" name="quantity" min="1" step="1" class="mt-1 w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white" required />
          </div>
          <div class="mb-4">
            <label for="refundMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refund Method</label>
            <select id="refundMethod" name="refund_method" class="mt-1 w-full border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white" required>
              <option value="Cash">Cash</option>
              <option value="Credit">Credit</option>
              <option value="Store Credit">Store Credit</option>
              <option value="None">No Refund</option>
            </select>
          </div>
          <div class="flex justify-end gap-2">
            <button type="button" id="cancelReturnBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Confirm Return</button>
          </div>
        </form>
      </div>
    </div>

    <!-- TomSelect CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <!-- TomSelect JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
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
                const mapped = data.map(c => ({
                    id: c.id,
                    name: c.first_name + ' ' + c.last_name
                }));
                callback(mapped);
                })
                .catch(() => callback());
            }
      });

      const salesList = document.getElementById('sales-list');
      let currentClientId = null;

      clientSelect.on('change', async function(value) {
        currentClientId = value;
        salesList.innerHTML = '<p>Loading sales...</p>';
        if (!value) {
          salesList.innerHTML = '';
          return;
        }
        try {
          const res = await fetch(`/pos/returns/client/${value}/sales`);
          console.log('Response status:', res.status);
          const sales = await res.json();
          console.log('Sales data:', sales);
          if (!sales.length) {
            salesList.innerHTML = '<p>No sales found for this client.</p>';
            return;
          }
          // Render sales and items:
          salesList.innerHTML = sales.map(sale => `
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
                    <th class="px-2 py-1">Action</th>
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
                        ${item.returnable_quantity > 0 ? `<button class="return-btn text-red-600 hover:underline" data-sale-id="${sale.id}" data-sale-item-id="${item.sale_item_id}" data-product-id="${item.product_id}" data-max-return="${item.returnable_quantity}" data-product-name="${item.name}">Return</button>` : 'N/A'}
                      </td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            </div>
          `).join('');
        } catch (e) {
          console.error('Error fetching sales:', e);
          salesList.innerHTML = '<p class="text-red-600">Failed to load sales.</p>';
        }
      });

      // Return modal logic
      const returnModal = document.getElementById('returnModal');
      const returnForm = document.getElementById('returnForm');
      const cancelReturnBtn = document.getElementById('cancelReturnBtn');
      const returnQuantityInput = document.getElementById('returnQuantity');

      salesList.addEventListener('click', e => {
        if (e.target.classList.contains('return-btn')) {
            const btn = e.target;
            console.log('Return button clicked:', btn.dataset); // ✅ log all data attributes

            // Assign values to hidden inputs
            document.getElementById('returnSaleId').value = btn.dataset.saleId;
            document.getElementById('returnSaleItemId').value = btn.dataset.saleItemId;
            document.getElementById('returnProductId').value = btn.dataset.productId;

            // Also log what we just set
            console.log('Product ID set to:', btn.dataset.productId);

            returnQuantityInput.value = 1;
            returnQuantityInput.max = btn.dataset.maxReturn;

            returnModal.classList.remove('hidden');
            returnModal.classList.add('flex');
        }
    });


      cancelReturnBtn.addEventListener('click', () => {
        returnModal.classList.add('hidden');
        returnModal.classList.remove('flex');
      });

      returnForm.addEventListener('submit', async e => {
        e.preventDefault();

        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (!tokenMeta) {
            console.error('CSRF token meta tag is missing from DOM');
            alert('CSRF token not found in page.');
            return;
        }

        const csrfToken = tokenMeta.getAttribute('content');
        console.log('CSRF Token:', csrfToken);  // ✅ ADD THIS LINE

        const formData = new FormData(returnForm);

        try {
            const res = await fetch('/pos/returns/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', // 🔥 This is the fix
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                sale_id: parseInt(formData.get('sale_id')),
                sale_item_id: parseInt(formData.get('sale_item_id')),
                product_id: parseInt(formData.get('product_id')), // ✅ Fix is here
                quantity: parseInt(formData.get('quantity')),
                refund_method: formData.get('refund_method')
            })

            });

            const raw = await res.text();
            console.log('Raw server response:', raw);

            let data;
            try {
                data = JSON.parse(raw);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                alert('Unexpected response from server. Check console.');
                return;
            }

            console.log('Server response:', data);

            if (data.success) {
                alert('Return processed successfully.');
                returnModal.classList.add('hidden');
                returnModal.classList.remove('flex');
                clientSelect.refreshOptions();
                salesList.innerHTML = '';
            } else {
                alert('Failed to process return: ' + (data.error || 'Unknown error'));
            }
        } catch (err) {
            alert('Error submitting return.');
        }
    });
    });
    </script>

    </div>
</x-app-layout>
