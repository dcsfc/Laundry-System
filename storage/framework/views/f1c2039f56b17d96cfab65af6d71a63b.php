<!-- Schedule Details Modal Component -->
<div x-data="scheduleDetailsModal()" x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>

        <!-- Modal panel -->
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <!-- Modal header -->
            <div class="bg-slate-700 px-6 py-4 border-b border-slate-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-100">Schedule Details</h3>
                        <p class="text-sm text-slate-400" x-text="'Schedule #' + (scheduleData.id || '')"></p>
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
                    <!-- Customer Information -->
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-md font-semibold text-slate-200 mb-4 flex items-center">
                                <i class="fas fa-user mr-2 text-sky-400"></i>Customer Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Name:</span>
                                    <span class="text-slate-200" x-text="scheduleData.customer_name || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Email:</span>
                                    <span class="text-slate-200" x-text="scheduleData.customer_email || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Phone:</span>
                                    <span class="text-slate-200" x-text="scheduleData.customer_phone || 'N/A'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Service Information -->
                        <div>
                            <h4 class="text-md font-semibold text-slate-200 mb-4 flex items-center">
                                <i class="fas fa-concierge-bell mr-2 text-sky-400"></i>Service Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Service Type:</span>
                                    <span class="text-slate-200" x-text="scheduleData.service_name || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                          :class="getStatusClass(scheduleData.status)" 
                                          x-text="scheduleData.status || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Approval Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                          :class="getApprovalStatusClass(scheduleData.approval_status)" 
                                          x-text="scheduleData.approval_status || 'N/A'"></span>
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
                                    <span class="text-slate-200" x-text="scheduleData.dropoff_date || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Drop-off Time:</span>
                                    <span class="text-slate-200" x-text="scheduleData.dropoff_time || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Pickup Date:</span>
                                    <span class="text-slate-200" x-text="scheduleData.pickup_date || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Pickup Time:</span>
                                    <span class="text-slate-200" x-text="scheduleData.pickup_time || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Requested:</span>
                                    <span class="text-slate-200" x-text="scheduleData.created_at || 'N/A'"></span>
                                </div>
                                <div x-show="scheduleData.approved_at" class="flex justify-between">
                                    <span class="text-slate-400">Processed:</span>
                                    <span class="text-slate-200" x-text="scheduleData.approved_at || 'N/A'"></span>
                                </div>
                                <div x-show="scheduleData.approved_by_name" class="flex justify-between">
                                    <span class="text-slate-400">Processed By:</span>
                                    <span class="text-slate-200" x-text="scheduleData.approved_by_name || 'N/A'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div x-show="scheduleData.notes">
                            <h4 class="text-md font-semibold text-slate-200 mb-4 flex items-center">
                                <i class="fas fa-sticky-note mr-2 text-sky-400"></i>Notes
                            </h4>
                            <div class="bg-slate-700 rounded-lg p-4">
                                <p class="text-slate-200" x-text="scheduleData.notes"></p>
                            </div>
                        </div>

                        <!-- Rejection Reason -->
                        <div x-show="scheduleData.rejection_reason">
                            <h4 class="text-md font-semibold text-slate-200 mb-4 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2 text-red-400"></i>Rejection Reason
                            </h4>
                            <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4">
                                <p class="text-red-200" x-text="scheduleData.rejection_reason"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-slate-600 mt-6">
                    <button @click="closeModal()" class="px-4 py-2 bg-slate-600 text-slate-200 rounded-lg hover:bg-slate-500 transition-colors">
                        Close
                    </button>
                    <template x-if="scheduleData.approval_status === 'Pending'">
                        <div class="flex space-x-3">
                            <button @click="approveSchedule()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                <i class="fas fa-check mr-2"></i>Approve
                            </button>
                            <button @click="rejectSchedule()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                <i class="fas fa-times mr-2"></i>Reject
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function scheduleDetailsModal() {
    return {
        isOpen: false,
        scheduleData: {},

        openModal(schedule) {
            this.scheduleData = schedule || {};
            this.isOpen = true;
        },

        closeModal() {
            this.isOpen = false;
            this.scheduleData = {};
        },

        async approveSchedule() {
            if (confirm(`Are you sure you want to approve schedule #${this.scheduleData.id}?`)) {
                try {
                    const response = await fetch(`/staff/schedules/${this.scheduleData.id}/approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
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
                    console.error('Error approving schedule:', error);
                    this.showNotification('Error approving schedule', 'error');
                }
            }
        },

        rejectSchedule() {
            this.closeModal();
            // Open rejection modal
            window.openRejectionModal(this.scheduleData.id, this.scheduleData);
        },

        getStatusClass(status) {
            const statusClasses = {
                'scheduled': 'bg-blue-500/20 text-blue-400',
                'priced': 'bg-orange-500/20 text-orange-400',
                'in_progress': 'bg-purple-500/20 text-purple-400',
                'completed': 'bg-green-500/20 text-green-400',
                'cancelled': 'bg-red-500/20 text-red-400'
            };
            return statusClasses[status?.toLowerCase()] || 'bg-slate-500/20 text-slate-400';
        },

        getApprovalStatusClass(status) {
            const statusClasses = {
                'pending': 'bg-amber-500/20 text-amber-400',
                'approved': 'bg-green-500/20 text-green-400',
                'rejected': 'bg-red-500/20 text-red-400'
            };
            return statusClasses[status?.toLowerCase()] || 'bg-slate-500/20 text-slate-400';
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

// Listen for view-schedule events
document.addEventListener('view-schedule', function(event) {
    const modal = document.querySelector('[x-data*="scheduleDetailsModal"]');
    if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
        modal._x_dataStack[0].openModal(event.detail.schedule);
    }
});
</script>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/schedule-details-modal.blade.php ENDPATH**/ ?>