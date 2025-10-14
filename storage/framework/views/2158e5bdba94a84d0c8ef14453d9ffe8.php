<!-- Order View Modal Component -->
<div x-data="orderViewModal()" x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>

        <!-- Modal panel -->
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <!-- Modal header -->
            <div class="bg-slate-700 px-6 py-4 border-b border-slate-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-100">Order Details</h3>
                        <p class="text-sm text-slate-400" x-text="'Order #' + (orderData.id || '')"></p>
                    </div>
                    <button @click="closeModal()" class="text-slate-400 hover:text-slate-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal body -->
            <div class="bg-slate-800 px-6 py-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Order Information -->
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-md font-semibold text-slate-200 mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-sky-400"></i>Order Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Order ID:</span>
                                    <span class="text-slate-200 font-medium" x-text="orderData.id || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                          :class="getStatusClass(orderData.status)" 
                                          x-text="getStatusText(orderData.status)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Service Type:</span>
                                    <span class="text-slate-200" x-text="getServiceTypeText(orderData.service_type)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Total Price:</span>
                                    <span class="text-slate-200 font-medium" x-text="'₱' + (orderData.total_price || '0.00')"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Payment Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                          :class="getPaymentStatusClass(orderData.payment_status)" 
                                          x-text="getPaymentStatusText(orderData.payment_status)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div>
                            <h4 class="text-md font-semibold text-slate-200 mb-4 flex items-center">
                                <i class="fas fa-user mr-2 text-sky-400"></i>Customer Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Name:</span>
                                    <span class="text-slate-200" x-text="orderData.customer_name || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Email:</span>
                                    <span class="text-slate-200" x-text="orderData.customer_email || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Phone:</span>
                                    <span class="text-slate-200" x-text="orderData.customer_phone || 'N/A'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-md font-semibold text-slate-200 mb-4 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-sky-400"></i>Schedule Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Drop-off Date:</span>
                                    <span class="text-slate-200" x-text="formatDate(orderData.dropoff_date)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Pickup Date:</span>
                                    <span class="text-slate-200" x-text="formatDate(orderData.pickup_date)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Created:</span>
                                    <span class="text-slate-200" x-text="formatDate(orderData.created_at)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Last Updated:</span>
                                    <span class="text-slate-200" x-text="formatDate(orderData.updated_at)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Staff Information -->
                        <div>
                            <h4 class="text-md font-semibold text-slate-200 mb-4 flex items-center">
                                <i class="fas fa-user-tie mr-2 text-sky-400"></i>Staff Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Assigned Staff:</span>
                                    <span class="text-slate-200" x-text="orderData.staff_name || 'Unassigned'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Created By:</span>
                                    <span class="text-slate-200" x-text="orderData.created_by_name || 'System'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div x-show="orderData.notes">
                            <h4 class="text-md font-semibold text-slate-200 mb-4 flex items-center">
                                <i class="fas fa-sticky-note mr-2 text-sky-400"></i>Notes
                            </h4>
                            <div class="bg-slate-700 rounded-lg p-4">
                                <p class="text-slate-200" x-text="orderData.notes"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-slate-600 mt-6">
                    <button @click="closeModal()" class="px-4 py-2 bg-slate-600 text-slate-200 rounded-lg hover:bg-slate-500 transition-colors">
                        Close
                    </button>
                    <button @click="editOrder()" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function orderViewModal() {
    return {
        isOpen: false,
        orderData: {},

        openModal(order) {
            this.orderData = order || {};
            this.isOpen = true;
        },

        closeModal() {
            this.isOpen = false;
            this.orderData = {};
        },

        editOrder() {
            this.closeModal();
            // Dispatch event to open edit modal
            window.dispatchEvent(new CustomEvent('edit-order', {
                detail: { order: this.orderData }
            }));
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                return new Date(dateString).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } catch {
                return 'Invalid Date';
            }
        },

        getStatusClass(status) {
            const statusClasses = {
                'scheduled': 'bg-yellow-500/20 text-yellow-400',
                'priced': 'bg-orange-500/20 text-orange-400',
                'in_progress': 'bg-blue-500/20 text-blue-400',
                'completed': 'bg-green-500/20 text-green-400',
                'cancelled': 'bg-red-500/20 text-red-400'
            };
            return statusClasses[status] || 'bg-slate-500/20 text-slate-400';
        },

        getStatusText(status) {
            const statusTexts = {
                'scheduled': 'Scheduled',
                'priced': 'Priced',
                'in_progress': 'In Progress',
                'completed': 'Completed',
                'cancelled': 'Cancelled'
            };
            return statusTexts[status] || 'Unknown';
        },

        getServiceTypeText(serviceType) {
            const serviceTexts = {
                'wash_fold': 'Wash & Fold',
                'dry_clean': 'Dry Clean',
                'ironing': 'Ironing',
                'express': 'Express Service'
            };
            return serviceTexts[serviceType] || 'General Laundry';
        },

        getPaymentStatusClass(status) {
            const statusClasses = {
                'paid': 'bg-green-500/20 text-green-400',
                'unpaid': 'bg-red-500/20 text-red-400'
            };
            return statusClasses[status] || 'bg-slate-500/20 text-slate-400';
        },

        getPaymentStatusText(status) {
            const statusTexts = {
                'paid': 'Paid',
                'unpaid': 'Unpaid'
            };
            return statusTexts[status] || 'Unknown';
        }
    }
}

// Listen for view-order events
document.addEventListener('view-order', function(event) {
    const modal = document.querySelector('[x-data*="orderViewModal"]');
    if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
        modal._x_dataStack[0].openModal(event.detail.order);
    }
});
</script>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/order-view-modal.blade.php ENDPATH**/ ?>