<!-- Order Modal Component -->
<div x-data="orderModal()" x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>

        <!-- Modal panel -->
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <!-- Modal header -->
            <div class="bg-slate-700 px-6 py-4 border-b border-slate-600">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-100" x-text="modalTitle"></h3>
                    <button @click="closeModal()" class="text-slate-400 hover:text-slate-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal body -->
            <div class="bg-slate-800 px-6 py-4">
                <form @submit.prevent="submitForm()" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Selection -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                <i class="fas fa-user mr-2 text-sky-400"></i>Customer <span class="text-red-400">*</span>
                            </label>
                            <select x-model="formData.customer_id" required class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition-colors">
                                <option value="">Select customer</option>
                                <template x-for="customer in customers" :key="customer.id">
                                    <option :value="customer.id" x-text="customer.name"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Service Type -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                <i class="fas fa-concierge-bell mr-2 text-sky-400"></i>Service Type
                            </label>
                            <select x-model="formData.service_type" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition-colors">
                                <option value="wash_fold">Wash & Fold</option>
                                <option value="dry_clean">Dry Clean</option>
                                <option value="ironing">Ironing</option>
                                <option value="express">Express Service</option>
                            </select>
                        </div>

                        <!-- Drop-off Date -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-sky-400"></i>Drop-off Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" x-model="formData.dropoff_date" required class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition-colors">
                        </div>

                        <!-- Pickup Date -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                <i class="fas fa-calendar-check mr-2 text-sky-400"></i>Pickup Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" x-model="formData.pickup_date" required class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition-colors">
                        </div>

                        <!-- Estimated Price -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                <i class="fas fa-dollar-sign mr-2 text-sky-400"></i>Estimated Price
                            </label>
                            <input type="number" x-model="formData.total_price" step="0.01" min="0" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition-colors" placeholder="0.00">
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                <i class="fas fa-info-circle mr-2 text-sky-400"></i>Status
                            </label>
                            <select x-model="formData.status" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition-colors">
                                <option value="scheduled">🟡 Scheduled</option>
                                <option value="priced">🟠 Priced</option>
                                <option value="in_progress">🔵 In Progress</option>
                                <option value="completed">🟢 Completed</option>
                                <option value="cancelled">🔴 Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-slate-200 mb-2">
                            <i class="fas fa-sticky-note mr-2 text-sky-400"></i>Notes
                        </label>
                        <textarea x-model="formData.notes" rows="3" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition-colors" placeholder="Special instructions or notes..."></textarea>
                    </div>

                    <!-- Form actions -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-slate-600">
                        <button type="button" @click="closeModal()" class="px-4 py-2 bg-slate-600 text-slate-200 rounded-lg hover:bg-slate-500 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" :disabled="isSubmitting" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 disabled:opacity-50 transition-colors">
                            <span x-show="!isSubmitting" x-text="isEdit ? 'Update Order' : 'Create Order'"></span>
                            <span x-show="isSubmitting">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function orderModal() {
    return {
        isOpen: false,
        isEdit: false,
        isSubmitting: false,
        modalTitle: 'Add New Order',
        customers: [],
        formData: {
            customer_id: '',
            service_type: 'wash_fold',
            dropoff_date: '',
            pickup_date: '',
            total_price: '',
            status: 'scheduled',
            notes: ''
        },

        init() {
            this.loadCustomers();
        },

        openCreateModal() {
            this.isEdit = false;
            this.modalTitle = 'Add New Order';
            this.resetForm();
            this.isOpen = true;
        },

        openEditModal(order) {
            this.isEdit = true;
            this.modalTitle = 'Edit Order';
            this.formData = {
                id: order.id,
                customer_id: order.customer_id || '',
                service_type: order.service_type || 'wash_fold',
                dropoff_date: order.dropoff_date || '',
                pickup_date: order.pickup_date || '',
                total_price: order.total_price || '',
                status: order.status || 'scheduled',
                notes: order.notes || ''
            };
            this.isOpen = true;
        },

        closeModal() {
            this.isOpen = false;
            this.resetForm();
        },

        resetForm() {
            this.formData = {
                customer_id: '',
                service_type: 'wash_fold',
                dropoff_date: '',
                pickup_date: '',
                total_price: '',
                status: 'scheduled',
                notes: ''
            };
        },

        async loadCustomers() {
            try {
                const response = await fetch('/api/customers', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.customers = data.customers || [];
                }
            } catch (error) {
                console.error('Error loading customers:', error);
                this.customers = [];
            }
        },

        async submitForm() {
            this.isSubmitting = true;
            
            try {
                const url = this.isEdit ? `/staff/orders/${this.formData.id}` : '/staff/orders';
                const method = this.isEdit ? 'PUT' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.formData)
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    // Dispatch success event
                    window.dispatchEvent(new CustomEvent('order-saved', {
                        detail: { order: data.order, action: this.isEdit ? 'updated' : 'created' }
                    }));
                    
                    this.closeModal();
                    
                    // Show success notification
                    this.showNotification('Order ' + (this.isEdit ? 'updated' : 'created') + ' successfully!', 'success');
                } else {
                    const error = await response.json();
                    this.showNotification(error.message || 'An error occurred', 'error');
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                this.showNotification('An error occurred while saving the order', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },

        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    }
}
</script>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/order-modal.blade.php ENDPATH**/ ?>