<!-- Delete Confirmation Modal Component -->
<div x-data="deleteConfirmationModal()" x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
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
                        <h3 class="text-lg font-semibold text-white" x-text="modalTitle"></h3>
                    </div>
                </div>
            </div>

            <!-- Modal body -->
            <div class="bg-slate-800 px-6 py-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <p class="text-slate-200 text-sm mb-4" x-text="modalMessage"></p>
                    
                    <!-- Item details if available -->
                    <div x-show="itemData && itemData.id" class="bg-slate-700 rounded-lg p-4 mb-4">
                        <div class="text-left space-y-2">
                            <div class="flex justify-between">
                                <span class="text-slate-400">ID:</span>
                                <span class="text-slate-200 font-medium" x-text="itemData.id"></span>
                            </div>
                            <div x-show="itemData.name" class="flex justify-between">
                                <span class="text-slate-400">Name:</span>
                                <span class="text-slate-200" x-text="itemData.name"></span>
                            </div>
                            <div x-show="itemData.customer_name" class="flex justify-between">
                                <span class="text-slate-400">Customer:</span>
                                <span class="text-slate-200" x-text="itemData.customer_name"></span>
                            </div>
                            <div x-show="itemData.status" class="flex justify-between">
                                <span class="text-slate-400">Status:</span>
                                <span class="text-slate-200" x-text="itemData.status"></span>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-slate-400 text-xs">
                        This action cannot be undone. Please make sure you want to proceed.
                    </p>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="bg-slate-700 px-6 py-4 flex justify-end space-x-3">
                <button @click="closeModal()" class="px-4 py-2 bg-slate-600 text-slate-200 rounded-lg hover:bg-slate-500 transition-colors">
                    <span x-text="cancelText"></span>
                </button>
                <button @click="confirmDelete()" :disabled="isDeleting" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 transition-colors">
                    <span x-show="!isDeleting" x-text="confirmText"></span>
                    <span x-show="isDeleting">Deleting...</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function deleteConfirmationModal() {
    return {
        isOpen: false,
        isDeleting: false,
        modalTitle: 'Confirm Delete',
        modalMessage: 'Are you sure you want to delete this item?',
        confirmText: 'Delete',
        cancelText: 'Cancel',
        itemData: {},
        onConfirm: null,

        openModal(item, options = {}) {
            this.itemData = item || {};
            this.modalTitle = options.title || 'Confirm Delete';
            this.modalMessage = options.message || 'Are you sure you want to delete this item?';
            this.confirmText = options.confirmText || 'Delete';
            this.cancelText = options.cancelText || 'Cancel';
            this.onConfirm = options.onConfirm || null;
            this.isDeleting = false;
            this.isOpen = true;
        },

        closeModal() {
            this.isOpen = false;
            this.itemData = {};
            this.onConfirm = null;
            this.isDeleting = false;
        },

        async confirmDelete() {
            this.isDeleting = true;
            
            try {
                if (this.onConfirm && typeof this.onConfirm === 'function') {
                    await this.onConfirm(this.itemData);
                } else {
                    // Default delete behavior
                    await this.performDelete();
                }
                
                this.closeModal();
                this.showNotification('Item deleted successfully', 'success');
                
                // Dispatch delete event
                window.dispatchEvent(new CustomEvent('item-deleted', {
                    detail: { item: this.itemData }
                }));
                
            } catch (error) {
                console.error('Error deleting item:', error);
                this.showNotification('Error deleting item', 'error');
            } finally {
                this.isDeleting = false;
            }
        },

        async performDelete() {
            if (!this.itemData || !this.itemData.id) {
                throw new Error('No item ID provided for deletion');
            }

            const response = await fetch(`/staff/schedules/${this.itemData.id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to delete item');
            }

            return await response.json();
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
</script><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/delete-confirmation-modal.blade.php ENDPATH**/ ?>