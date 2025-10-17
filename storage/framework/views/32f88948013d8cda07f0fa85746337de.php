<!-- Schedule Status Change Confirmation Modal -->
<div x-data="scheduleStatusConfirmationModal()" 
     x-show="isOpen" 
     x-transition:enter="transition ease-out duration-300" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     x-transition:leave="transition ease-in duration-200" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0" 
     class="fixed inset-0 z-[9999] overflow-y-auto" 
     style="display: none;"
     @keydown.escape.window="closeModal()">
    
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div x-show="isOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" 
             @click="closeModal()">
        </div>

        <!-- Modal panel -->
        <div x-show="isOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-700/50">
            
            <!-- Modal header -->
            <div class="px-6 py-5 border-b border-slate-700/50"
                 :class="{
                     'bg-gradient-to-r from-green-500/20 to-emerald-500/20': actionType === 'approve',
                     'bg-gradient-to-r from-red-500/20 to-pink-500/20': actionType === 'reject',
                     'bg-gradient-to-r from-blue-500/20 to-cyan-500/20': actionType === 'start_processing' || actionType === 'mark_ready' || actionType === 'mark_completed',
                     'bg-gradient-to-r from-amber-500/20 to-orange-500/20': actionType === 'add_price',
                     'bg-gradient-to-r from-slate-500/20 to-slate-600/20': actionType === 'cancel'
                 }">
                <div class="flex items-center gap-4">
                    <!-- Icon based on action type -->
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg"
                         :class="{
                             'bg-gradient-to-br from-green-500 to-emerald-600': actionType === 'approve',
                             'bg-gradient-to-br from-red-500 to-pink-600': actionType === 'reject',
                             'bg-gradient-to-br from-blue-500 to-cyan-600': actionType === 'start_processing' || actionType === 'mark_ready' || actionType === 'mark_completed',
                             'bg-gradient-to-br from-amber-500 to-orange-600': actionType === 'add_price',
                             'bg-gradient-to-br from-slate-500 to-slate-600': actionType === 'cancel'
                         }">
                        <i class="text-white text-xl" 
                           :class="{
                               'fas fa-check': actionType === 'approve',
                               'fas fa-times': actionType === 'reject',
                               'fas fa-play': actionType === 'start_processing',
                               'fas fa-box': actionType === 'mark_ready',
                               'fas fa-check-circle': actionType === 'mark_completed',
                               'fas fa-balance-scale': actionType === 'add_price',
                               'fas fa-ban': actionType === 'cancel'
                           }"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white" x-text="modalTitle"></h3>
                        <p class="text-sm text-slate-400" x-text="modalSubtitle"></p>
                    </div>
                </div>
            </div>

            <!-- Modal body -->
            <div class="px-6 py-6">
                <div class="space-y-4">
                    <!-- Schedule Details -->
                    <div class="bg-slate-700/50 rounded-xl p-4 border border-slate-600/50">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-400">Schedule ID:</span>
                                <span class="text-sm font-semibold text-white" x-text="'#' + (scheduleData.id || 'N/A')"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-400">Customer:</span>
                                <span class="text-sm font-medium text-white" x-text="scheduleData.customer_name || 'N/A'"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-400">Current Status:</span>
                                <span class="text-sm font-medium px-3 py-1 rounded-lg"
                                      :class="'status-' + (scheduleData.status || 'default').toLowerCase().replace(/\s+/g, '-')"
                                      x-text="scheduleData.status_display || scheduleData.status || 'N/A'"></span>
                            </div>
                            <div x-show="newStatus" class="flex justify-between items-center pt-2 border-t border-slate-600/50">
                                <span class="text-sm text-slate-400">New Status:</span>
                                <span class="text-sm font-semibold text-emerald-400" x-text="newStatus"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Warning message -->
                    <div class="bg-slate-700/30 border-l-4 rounded-lg p-4"
                         :class="{
                             'border-green-500': actionType === 'approve',
                             'border-red-500': actionType === 'reject',
                             'border-blue-500': actionType === 'start_processing' || actionType === 'mark_ready' || actionType === 'mark_completed',
                             'border-amber-500': actionType === 'add_price',
                             'border-slate-500': actionType === 'cancel'
                         }">
                        <p class="text-sm text-slate-300" x-text="modalMessage"></p>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="bg-slate-800/50 px-6 py-4 flex justify-end gap-3 border-t border-slate-700/50">
                <button @click="closeModal()" 
                        :disabled="isProcessing"
                        class="px-5 py-2.5 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-white rounded-xl transition-all font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                    Cancel
                </button>
                <button @click="confirmAction()" 
                        :disabled="isProcessing"
                        class="px-5 py-2.5 rounded-xl transition-all font-medium shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                        :class="{
                            'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white hover:shadow-green-500/25': actionType === 'approve',
                            'bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white hover:shadow-red-500/25': actionType === 'reject',
                            'bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white hover:shadow-blue-500/25': actionType === 'start_processing' || actionType === 'mark_ready' || actionType === 'mark_completed',
                            'bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white hover:shadow-amber-500/25': actionType === 'add_price',
                            'bg-gradient-to-r from-slate-500 to-slate-600 hover:from-slate-600 hover:to-slate-700 text-white hover:shadow-slate-500/25': actionType === 'cancel'
                        }">
                    <span x-show="!isProcessing" x-text="confirmButtonText"></span>
                    <span x-show="isProcessing" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function scheduleStatusConfirmationModal() {
    return {
        isOpen: false,
        isProcessing: false,
        actionType: '',
        modalTitle: 'Confirm Action',
        modalSubtitle: '',
        modalMessage: 'Are you sure you want to proceed with this action?',
        confirmButtonText: 'Confirm',
        newStatus: '',
        scheduleData: {},
        onConfirm: null,

        openModal(schedule, action, options = {}) {
            this.scheduleData = schedule || {};
            this.actionType = action;
            this.onConfirm = options.onConfirm || null;
            this.isProcessing = false;
            
            // Set modal content based on action type
            const actionConfigs = {
                'approve': {
                    title: 'Approve Schedule',
                    subtitle: 'Confirm schedule approval',
                    message: 'Are you sure you want to approve this schedule? The customer will be notified.',
                    confirmText: 'Approve Schedule',
                    newStatus: 'Confirmed'
                },
                'reject': {
                    title: 'Reject Schedule',
                    subtitle: 'Decline this schedule',
                    message: 'Are you sure you want to reject this schedule? Please provide a reason in the next step.',
                    confirmText: 'Reject Schedule',
                    newStatus: 'Cancelled'
                },
                'start_processing': {
                    title: 'Start Processing',
                    subtitle: 'Begin laundry processing',
                    message: 'Are you sure you want to start processing this schedule? Make sure weight and price have been set.',
                    confirmText: 'Start Processing',
                    newStatus: 'Processing'
                },
                'mark_ready': {
                    title: 'Mark Ready for Pickup',
                    subtitle: 'Schedule is ready',
                    message: 'Are you sure this schedule is ready for customer pickup?',
                    confirmText: 'Mark Ready',
                    newStatus: 'Ready for Pickup'
                },
                'mark_completed': {
                    title: 'Mark as Completed',
                    subtitle: 'Complete this schedule',
                    message: 'Are you sure you want to mark this schedule as completed? This indicates the customer has picked up their laundry.',
                    confirmText: 'Mark Completed',
                    newStatus: 'Completed'
                },
                'cancel': {
                    title: 'Cancel Schedule',
                    subtitle: 'Cancel this order',
                    message: 'Are you sure you want to cancel this schedule? This action cannot be undone.',
                    confirmText: 'Cancel Schedule',
                    newStatus: 'Cancelled'
                },
                'add_price': {
                    title: 'Set Price & Weight',
                    subtitle: 'Add pricing information',
                    message: 'You will be able to enter the weight and price in the next step.',
                    confirmText: 'Continue',
                    newStatus: ''
                }
            };
            
            const config = actionConfigs[action] || {
                title: 'Confirm Action',
                subtitle: '',
                message: 'Are you sure you want to proceed?',
                confirmText: 'Confirm',
                newStatus: ''
            };
            
            this.modalTitle = options.title || config.title;
            this.modalSubtitle = options.subtitle || config.subtitle;
            this.modalMessage = options.message || config.message;
            this.confirmButtonText = options.confirmText || config.confirmText;
            this.newStatus = config.newStatus;
            
            this.isOpen = true;
        },

        closeModal() {
            this.isOpen = false;
            this.scheduleData = {};
            this.actionType = '';
            this.onConfirm = null;
            this.isProcessing = false;
        },

        async confirmAction() {
            this.isProcessing = true;
            
            try {
                if (this.onConfirm && typeof this.onConfirm === 'function') {
                    await this.onConfirm(this.scheduleData, this.actionType);
                }
                
                this.closeModal();
                
            } catch (error) {
                console.error('Error performing action:', error);
                this.isProcessing = false;
            }
        }
    }
}

// Make it globally accessible
window.scheduleStatusConfirmationModal = scheduleStatusConfirmationModal;
</script>

<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/schedule-status-confirmation-modal.blade.php ENDPATH**/ ?>