<!-- Schedule Rejection Modal Component -->
<div x-data="rejectionModal()" x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>

        <!-- Modal panel -->
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <!-- Modal header -->
            <div class="bg-red-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-white">Reject Schedule Request</h3>
                        <p class="text-sm text-red-100" x-text="'Schedule #' + (orderId || '')"></p>
                    </div>
                </div>
            </div>

            <!-- Modal body -->
            <div class="bg-slate-800 px-6 py-6">
                <div class="text-center mb-6">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <p class="text-slate-200 text-sm mb-4">Please provide a reason for rejecting this schedule request.</p>
                    
                    <!-- Schedule details -->
                    <div x-show="orderData" class="bg-slate-700 rounded-lg p-4 mb-4 text-left">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Customer:</span>
                                <span class="text-slate-200" x-text="orderData.customer_name || 'N/A'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Service:</span>
                                <span class="text-slate-200" x-text="orderData.service_name || 'N/A'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Drop-off:</span>
                                <span class="text-slate-200" x-text="(orderData.dropoff_date || 'N/A') + ' ' + (orderData.dropoff_time || '')"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Pickup:</span>
                                <span class="text-slate-200" x-text="(orderData.pickup_date || 'N/A') + ' ' + (orderData.pickup_time || '')"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rejection reason form -->
                <form @submit.prevent="submitRejection()">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                Rejection Reason <span class="text-red-400">*</span>
                            </label>
                            <textarea 
                                x-model="rejectionReason" 
                                required 
                                rows="4" 
                                class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-red-400 focus:border-red-400 transition-colors" 
                                placeholder="Please explain why this schedule request is being rejected..."
                            ></textarea>
                        </div>

                        <!-- Quick rejection reasons -->
                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-2">Quick Reasons</label>
                            <div class="grid grid-cols-1 gap-2">
                                <button type="button" @click="setQuickReason('Schedule conflict - no available staff')" 
                                        class="text-left px-3 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 text-sm transition-colors">
                                    Schedule conflict - no available staff
                                </button>
                                <button type="button" @click="setQuickReason('Requested date is not available')" 
                                        class="text-left px-3 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 text-sm transition-colors">
                                    Requested date is not available
                                </button>
                                <button type="button" @click="setQuickReason('Service not available for requested dates')" 
                                        class="text-left px-3 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 text-sm transition-colors">
                                    Service not available for requested dates
                                </button>
                                <button type="button" @click="setQuickReason('Please contact us to reschedule')" 
                                        class="text-left px-3 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 text-sm transition-colors">
                                    Please contact us to reschedule
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Form actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-slate-600 mt-6">
                        <button type="button" @click="closeModal()" class="px-4 py-2 bg-slate-600 text-slate-200 rounded-lg hover:bg-slate-500 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" :disabled="isSubmitting || !rejectionReason.trim()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 transition-colors">
                            <span x-show="!isSubmitting">Reject Schedule</span>
                            <span x-show="isSubmitting">Rejecting...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function rejectionModal() {
    return {
        isOpen: false,
        isSubmitting: false,
        orderId: null,
        orderData: {},
        rejectionReason: '',

        openModal(orderId, orderData) {
            this.orderId = orderId;
            this.orderData = orderData || {};
            this.rejectionReason = '';
            this.isSubmitting = false;
            this.isOpen = true;
        },

        closeModal() {
            this.isOpen = false;
            this.orderId = null;
            this.orderData = {};
            this.rejectionReason = '';
            this.isSubmitting = false;
        },

        setQuickReason(reason) {
            this.rejectionReason = reason;
        },

        async submitRejection() {
            if (!this.rejectionReason.trim()) {
                this.showNotification('Please provide a rejection reason', 'error');
                return;
            }

            this.isSubmitting = true;

            try {
                const response = await fetch(`/staff/schedules/${this.orderId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        rejection_reason: this.rejectionReason
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    this.showNotification(data.message, 'success');
                    this.closeModal();
                    // Refresh the table
                    window.dispatchEvent(new CustomEvent('schedule-processed'));
                } else {
                    const error = await response.json();
                    this.showNotification(error.message, 'error');
                }
            } catch (error) {
                console.error('Error rejecting schedule:', error);
                this.showNotification('Error rejecting schedule', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },

        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    }
}
</script>
