<style>
/* Professional SaaS Modal Styles - Dark Theme */
.modal-backdrop {
    backdrop-filter: blur(4px);
    background: rgba(0, 0, 0, 0.5);
}

.modal-content {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(99, 102, 241, 0.2);
}

/* Smooth scrollbar for modal body - Dark theme */
.modal-body-scroll {
    scrollbar-width: thin;
    scrollbar-color: #475569 #1e293b;
}

.modal-body-scroll::-webkit-scrollbar {
    width: 6px;
}

.modal-body-scroll::-webkit-scrollbar-track {
    background: #1e293b;
    border-radius: 3px;
}

.modal-body-scroll::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 3px;
}

.modal-body-scroll::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
</style>

<div 
    x-data="userModal()"
    x-show="isModalOpen" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] overflow-hidden"
    style="display: none;"
    x-cloak
    @keydown.escape.window="closeModal()"
    @click.self="closeModal()"
>
    <!-- Backdrop -->
    <div class="fixed inset-0 modal-backdrop"></div>
    
    <!-- Modal Container -->
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6">
        <div 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="relative w-[90%] sm:w-full max-w-lg h-[90vh] sm:h-auto max-h-[90vh] bg-slate-900 rounded-xl modal-content flex flex-col"
            @click.stop
        >
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-indigo-500/20">
            <div>
                <h3 class="text-lg font-semibold text-white" x-text="isEdit ? 'Edit User' : 'Add User'"></h3>
                <p class="text-sm text-slate-400 mt-1">Create and configure user account settings</p>
            </div>
            <button 
                @click="closeModal()"
                class="w-9 h-9 flex items-center justify-center hover:bg-slate-700 rounded-lg transition-colors group"
                aria-label="Close modal"
            >
                <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Scrollable Body -->
        <div class="flex-1 overflow-y-auto modal-body-scroll">
            <form id="user-form" @submit.prevent="submitForm()" class="p-6 space-y-6">
                <!-- Name and Email Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Full Name <span class="text-red-400">*</span>
                        </label>
                        <input 
                            type="text" 
                            x-model="form.name"
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Enter full name"
                            required
                        >
                        <div x-show="errors.name" class="mt-1 text-sm text-red-400" x-text="errors.name"></div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Email Address <span class="text-red-400">*</span>
                        </label>
                        <input 
                            type="email" 
                            x-model="form.email"
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Enter email address"
                            required
                        >
                        <div x-show="errors.email" class="mt-1 text-sm text-red-400" x-text="errors.email"></div>
                    </div>
                </div>

                <!-- Phone and Role Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Phone Number -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Phone Number
                        </label>
                        <input 
                            type="tel" 
                            x-model="form.phone_number"
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Enter phone number"
                        >
                        <div x-show="errors.phone_number" class="mt-1 text-sm text-red-400" x-text="errors.phone_number"></div>
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Role <span class="text-red-400">*</span>
                        </label>
                        <select 
                            x-model="form.role_id"
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            required
                        >
                            <option value="">Select a role</option>
                            <template x-for="role in roles" :key="role.id">
                                <option :value="role.id" x-text="role.name.charAt(0).toUpperCase() + role.name.slice(1)"></option>
                            </template>
                        </select>
                        <div x-show="errors.role_id" class="mt-1 text-sm text-red-400" x-text="errors.role_id"></div>
                    </div>
                </div>

                <!-- Password and Status Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Password <span class="text-red-400" x-text="isEdit ? '' : '*'"></span>
                        </label>
                        <input 
                            type="password" 
                            x-model="form.password"
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            :placeholder="isEdit ? 'Leave blank to keep current password' : 'Enter password'"
                            :required="!isEdit"
                        >
                        <div x-show="errors.password" class="mt-1 text-sm text-red-400" x-text="errors.password"></div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Status
                        </label>
                        <select 
                            x-model="form.status"
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div x-show="errors.status" class="mt-1 text-sm text-red-400" x-text="errors.status"></div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 p-4 bg-slate-900 border-t border-indigo-500/20 rounded-b-xl">
            <button 
                type="button"
                @click="closeModal()"
                class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded-lg font-medium transition-colors"
            >
                Cancel
            </button>
            <button 
                type="submit"
                form="user-form"
                :disabled="isLoading"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium flex items-center gap-2 transition-colors"
            >
                <svg x-show="isLoading" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span x-text="isLoading ? 'Saving...' : (isEdit ? 'Update User' : 'Create User')"></span>
            </button>
        </div>
        </div>
    </div>
</div>

<script>
function userModal() {
    return {
        isModalOpen: false,
        isEdit: false,
        isLoading: false,
        form: {
            id: null,
            name: '',
            email: '',
            phone_number: '',
            role_id: '',
            password: '',
            status: 'active'
        },
        errors: {},
        roles: [],

        init() {
            // Use roles passed from controller or load via AJAX as fallback
            if (typeof window.passedRoles !== 'undefined' && window.passedRoles.length > 0) {
                this.roles = window.passedRoles;
            } else {
                this.loadRoles();
            }
            
            // Listen for Alpine.js events from anywhere in the document
            document.addEventListener('open-user-modal', (event) => {
                const { action, user } = event.detail;
                
                if (action === 'create') {
                    this.openCreateModal();
                } else if (action === 'edit' && user) {
                    this.openEditModal(user);
                }
            });
            
            // Also listen for custom events
            window.addEventListener('open-user-modal', (event) => {
                const { action, user } = event.detail;
                
                if (action === 'create') {
                    this.openCreateModal();
                } else if (action === 'edit' && user) {
                    this.openEditModal(user);
                }
            });
            
            // Listen for modal open events
            this.$watch('isModalOpen', (value) => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        },

        async loadRoles() {
            try {
                const response = await fetch('/superadmin/users/roles');
                const data = await response.json();
                if (data.success) {
                    this.roles = data.roles;
                }
            } catch (error) {
                console.error('Error loading roles:', error);
            }
        },

        openCreateModal() {
            this.isEdit = false;
            this.resetForm();
            this.isModalOpen = true;
            this.$nextTick(() => {
                this.$refs.nameInput?.focus();
            });
        },

        openEditModal(user) {
            this.isEdit = true;
            this.form = {
                id: user.id,
                name: user.name,
                email: user.email,
                phone_number: user.phone_number || '',
                role_id: user.role_id,
                password: '',
                status: user.status
            };
            this.errors = {};
            this.isModalOpen = true;
            this.$nextTick(() => {
                this.$refs.nameInput?.focus();
            });
        },

        closeModal() {
            this.isModalOpen = false;
            this.resetForm();
        },

        resetForm() {
            this.form = {
                id: null,
                name: '',
                email: '',
                phone_number: '',
                role_id: '',
                password: '',
                status: 'active'
            };
            this.errors = {};
        },

        async submitForm() {
            this.isLoading = true;
            this.errors = {};

            try {
                const url = '/superadmin/users/store-ajax';
                const method = 'POST';
                
                const formData = { ...this.form };
                
                // Remove password field if it's empty in edit mode
                if (this.isEdit && !formData.password) {
                    delete formData.password;
                }
                
                // Ensure status is lowercase
                if (formData.status) {
                    formData.status = formData.status.toLowerCase();
                }
                
                // Add operation type
                formData.operation = this.isEdit ? 'update' : 'create';
                
                
                console.log('Form data being sent:', formData);
                console.log('URL:', url);
                console.log('Method:', method);

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                const data = await response.json();

                if (response.ok && data.success) {
                    this.closeModal();
                    // Trigger table refresh
                    this.$dispatch('user-saved', data.user);
                    // Show success message
                    this.showNotification('User saved successfully!', 'success');
                } else {
                    console.log('Error response:', data);
                    if (data.errors) {
                        this.errors = data.errors;
                        this.showNotification('Please fix the validation errors', 'error');
                    } else {
                        this.showNotification(data.message || 'An error occurred', 'error');
                    }
                }
            } catch (error) {
                console.error('Error saving user:', error);
                this.showNotification('An error occurred while saving the user', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        showNotification(message, type = 'info') {
            // Use the modern notification system if available
            if (typeof window.showNotification === 'function') {
                const titles = {
                    success: 'Success',
                    error: 'Error',
                    warning: 'Warning',
                    info: 'Info'
                };
                
                window.showNotification(type, message, {
                    title: titles[type] || 'Notification',
                    duration: type === 'success' ? 3000 : 5000
                });
            } else {
                // Fallback to simple notification
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-[10000] px-6 py-3 rounded-lg text-white font-medium ${
                    type === 'success' ? 'bg-green-600' : 
                    type === 'error' ? 'bg-red-600' : 'bg-blue-600'
                }`;
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        }
    }
}
</script>