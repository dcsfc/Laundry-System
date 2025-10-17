<!-- Pricing Modal -->
<div x-data="pricingModal()" 
     x-show="isOpen" 
     x-cloak
     @keydown.escape.window="close()"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <!-- Backdrop -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/70 backdrop-blur-sm"
         @click="close()">
    </div>

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 max-w-lg w-full"
             @click.stop>
            
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-700">
                <div>
                    <h3 class="text-xl font-bold text-white">Set Pricing</h3>
                    <p class="text-sm text-slate-400 mt-1">Order #<span x-text="orderData.id"></span></p>
                </div>
                <button @click="close()" 
                        class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-6">
                <!-- Customer Info -->
                <div class="bg-slate-700/50 rounded-lg p-4">
                    <p class="text-sm text-slate-400">Customer</p>
                    <p class="text-white font-medium" x-text="orderData.customer_name"></p>
                </div>

                <!-- Weight Input -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Weight (kg) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" 
                           x-model="weight"
                           step="0.1"
                           min="0.1"
                           placeholder="Enter weight in kg"
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all">
                    <p class="mt-1 text-xs text-slate-400">Enter the total weight of the laundry</p>
                </div>

                <!-- Price Input -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Total Price (₱) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" 
                           x-model="price"
                           step="0.01"
                           min="0.01"
                           placeholder="Enter total price"
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all">
                    <p class="mt-1 text-xs text-slate-400">Enter the total price for this laundry service</p>
                </div>

                <!-- Price Calculation Helper (Optional) -->
                <div x-show="weight > 0 && price > 0" class="bg-sky-500/10 border border-sky-500/30 rounded-lg p-4">
                    <p class="text-sm text-sky-400">
                        Price per kg: <span class="font-bold" x-text="'₱' + (price / weight).toFixed(2)"></span>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-700 bg-slate-700/30">
                <button @click="close()"
                        class="px-6 py-2.5 text-slate-300 hover:text-white font-medium rounded-lg hover:bg-slate-700 transition-all">
                    Cancel
                </button>
                <button @click="save()"
                        :disabled="!weight || !price || weight <= 0 || price <= 0"
                        :class="!weight || !price || weight <= 0 || price <= 0 ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90'"
                        class="px-6 py-2.5 bg-gradient-to-r from-sky-600 to-cyan-600 text-white font-medium rounded-lg transition-all shadow-lg shadow-sky-500/30">
                    <span x-show="!isSaving">Save Pricing</span>
                    <span x-show="isSaving" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function pricingModal() {
    return {
        isOpen: false,
        isSaving: false,
        orderData: {},
        weight: '',
        price: '',
        
        open(row) {
            this.orderData = row;
            this.weight = row.weight || '';
            this.price = row.total_price || '';
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },
        
        close() {
            this.isOpen = false;
            this.orderData = {};
            this.weight = '';
            this.price = '';
            this.isSaving = false;
            document.body.style.overflow = '';
        },
        
        save() {
            if (!this.weight || !this.price || this.weight <= 0 || this.price <= 0) {
                alert('Please enter valid weight and price values');
                return;
            }
            
            this.isSaving = true;
            
            fetch(`/staff/schedules/${this.orderData.id}/pricing`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    weight: parseFloat(this.weight),
                    price: parseFloat(this.price)
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update the table row without page reload
                    this.updateTableRow(this.orderData.id, {
                        weight: this.weight + ' kg',
                        total_price: '₱' + parseFloat(this.price).toFixed(2)
                    });
                    
                    alert(data.message);
                    this.close();
                    
                    // Trigger stats refresh
                    if (window.loadScheduleStats) {
                        window.loadScheduleStats();
                    }
                } else {
                    alert(data.message || 'Failed to save pricing');
                    this.isSaving = false;
                }
            })
            .catch(error => {
                console.error('Error saving pricing:', error);
                alert('Failed to save pricing: ' + error.message);
                this.isSaving = false;
            });
        },
        
        updateTableRow(orderId, updates) {
            // Get the data table instance
            const dataTableEl = document.querySelector('[data-datatable]');
            if (dataTableEl && dataTableEl._x_dataStack && dataTableEl._x_dataStack[0]) {
                const dt = dataTableEl._x_dataStack[0];
                
                // Find and update the row in originalData
                const rowIndex = dt.originalData.findIndex(row => row.id == orderId);
                if (rowIndex !== -1) {
                    // First apply the immediate updates
                    Object.assign(dt.originalData[rowIndex], updates);
                    
                    // Force immediate reactivity for visible changes
                    dt.originalData = [...dt.originalData];
                    
                    // Always refresh row actions to get updated button states
                    this.refreshRowActions(orderId, dt);
                }
            }
        },
        
        refreshRowActions(orderId, dt) {
            // Fetch updated row data with new actions
            fetch(`/staff/schedules/${orderId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Response is not JSON. Content-Type: ' + contentType);
                    }
                    
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.order) {
                        const rowIndex = dt.originalData.findIndex(row => row.id == orderId);
                        if (rowIndex !== -1) {
                            // Update the entire row with fresh data including actions
                            Object.assign(dt.originalData[rowIndex], data.order);
                            console.log('Refreshed row actions:', dt.originalData[rowIndex]);
                            
                            // Force Alpine.js reactivity by triggering a small change
                            dt.originalData = [...dt.originalData];
                        }
                    }
                })
                .catch(error => {
                    console.error('Error refreshing actions:', error);
                    console.error('Error details:', {
                        message: error.message,
                        stack: error.stack
                    });
                });
        }
    }
}
</script>

<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/schedule-pricing-modal.blade.php ENDPATH**/ ?>