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

/* Smooth scrollbar */
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

/* User profile section */
.user-profile {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border: 1px solid #475569;
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}

.user-profile::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #6366f1, #8b5cf6, #06b6d4);
}

.user-avatar {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    margin: 0 auto 1.5rem;
    box-shadow: 0 8px 32px rgba(99, 102, 241, 0.3);
    border: 4px solid rgba(255, 255, 255, 0.1);
}

.user-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #f8fafc;
    text-align: center;
    margin-bottom: 0.5rem;
}

.user-email {
    color: #94a3b8;
    text-align: center;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

.status-role-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-active {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.05) 100%);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.3);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.1);
}

.status-inactive {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.05) 100%);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: capitalize;
}

.role-superadmin {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, rgba(139, 92, 246, 0.05) 100%);
    color: #a78bfa;
    border: 1px solid rgba(139, 92, 246, 0.3);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.1);
}

.role-administrator {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0.05) 100%);
    color: #60a5fa;
    border: 1px solid rgba(59, 130, 246, 0.3);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
}

.role-staff {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
}

.role-customer {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.05) 100%);
    color: #fbbf24;
    border: 1px solid rgba(245, 158, 11, 0.3);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.1);
}

/* Information grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.info-item {
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid rgba(71, 85, 105, 0.3);
    border-radius: 0.75rem;
    padding: 1.25rem;
    transition: all 0.2s ease;
}

.info-item:hover {
    background: rgba(30, 41, 59, 0.7);
    border-color: rgba(99, 102, 241, 0.3);
    transform: translateY(-2px);
}

.info-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-value {
    font-size: 1rem;
    color: #f8fafc;
    font-weight: 500;
    line-height: 1.5;
}

/* Account info section */
.account-info {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(51, 65, 85, 0.8) 100%);
    border: 1px solid rgba(71, 85, 105, 0.4);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-top: 1.5rem;
}

.account-info-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(71, 85, 105, 0.3);
}

.account-info-title {
    font-size: 1rem;
    font-weight: 600;
    color: #e2e8f0;
}

.account-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.account-info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.account-info-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.account-info-value {
    font-size: 0.875rem;
    color: #e2e8f0;
    font-weight: 500;
}

/* Button styles */
.modern-button {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.modern-button:hover {
    background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
}

.modern-button:active {
    transform: translateY(0);
}

.close-button {
    color: #94a3b8;
    transition: all 0.2s ease;
}

.close-button:hover {
    color: #f8fafc;
    background: rgba(71, 85, 105, 0.3);
}

/* Loading animation */
.loading-spinner {
    width: 2rem;
    height: 2rem;
    border: 3px solid rgba(99, 102, 241, 0.2);
    border-top: 3px solid #6366f1;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div 
    x-data="userViewModal()"
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
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">User Profile</h3>
                        <p class="text-sm text-slate-400">View detailed user information and account status</p>
                    </div>
                </div>
                <button 
                    @click="closeModal()"
                    class="w-9 h-9 flex items-center justify-center hover:bg-slate-700 rounded-lg transition-colors group"
                    aria-label="Close modal"
                >
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body-scroll flex-1 overflow-y-auto p-6">
                <div x-show="user" class="space-y-6">
                    <!-- User Profile Section -->
                    <div class="user-profile text-center">
                        <div class="user-avatar" x-text="getInitials(user?.name || '')"></div>
                        <h2 class="user-name" x-text="user?.name || 'N/A'"></h2>
                        <p class="user-email" x-text="user?.email || 'N/A'"></p>
                        <div class="status-role-container">
                            <span 
                                class="status-indicator"
                                :class="(user?.status || '').toLowerCase() === 'active' ? 'status-active' : 'status-inactive'"
                                x-text="(user?.status || 'inactive').charAt(0).toUpperCase() + (user?.status || 'inactive').slice(1)"
                            ></span>
                            <span 
                                class="role-badge"
                                :class="'role-' + (user?.role_name || user?.role || '').toLowerCase()"
                                x-text="user?.role_name || user?.role || 'N/A'"
                            ></span>
                        </div>
                    </div>

                    <!-- Detailed Information Grid -->
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Full Name
                            </span>
                            <span class="info-value" x-text="user?.name || 'N/A'"></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email Address
                            </span>
                            <span class="info-value" x-text="user?.email || 'N/A'"></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Phone Number
                            </span>
                            <span class="info-value" x-text="user?.phone_number || 'N/A'"></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                User Role
                            </span>
                            <span class="info-value" x-text="user?.role_name || user?.role || 'N/A'"></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Account Status
                            </span>
                            <span class="info-value" x-text="(user?.status || 'inactive').charAt(0).toUpperCase() + (user?.status || 'inactive').slice(1)"></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Account Created
                            </span>
                            <span class="info-value" x-text="formatDate(user?.created_at)"></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Created By
                            </span>
                            <span class="info-value" x-text="user?.created_by_name || 'System'"></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Account Age
                            </span>
                            <span class="info-value" x-text="user?.account_age || 'N/A'"></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                                User ID
                            </span>
                            <span class="info-value" x-text="user?.id || 'N/A'"></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Last Login
                            </span>
                            <span class="info-value" x-text="user?.last_login || 'Never'"></span>
                        </div>
                    </div>

                </div>

                <!-- Loading State -->
                <div x-show="!user" class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <div class="loading-spinner mx-auto mb-4"></div>
                        <p class="text-slate-400">Loading user details...</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 p-4 bg-slate-900 border-t border-indigo-500/20 rounded-b-xl">
                <button 
                    @click="closeModal()"
                    class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded-lg font-medium transition-colors"
                >
                    Close
                </button>
                <button 
                    @click="editUser()"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium flex items-center gap-2 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit User
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function userViewModal() {
    return {
        isModalOpen: false,
        user: null,

        init() {
            const self = this;
            
            // Listen for view user events
            document.addEventListener('view-user', (event) => {
                self.openModal(event.detail.user);
            });
            
            // Also listen on window for better compatibility
            window.addEventListener('view-user', (event) => {
                self.openModal(event.detail.user);
            });
        },

        openModal(userData) {
            this.user = userData;
            this.isModalOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.isModalOpen = false;
            this.user = null;
            document.body.style.overflow = '';
        },


        editUser() {
            if (this.user) {
                console.log('Edit user clicked:', this.user);
                
                // Store user data before closing modal
                const userData = { ...this.user };
                
                // Close view modal
                this.closeModal();
                
                // Small delay to ensure modal is closed before opening edit modal
                setTimeout(() => {
                    // Dispatch edit event with preserved user data
                    const event = new CustomEvent('open-user-modal', { 
                        detail: { action: 'edit', user: userData } 
                    });
                    
                    console.log('Dispatching edit event:', event.detail);
                    
                    // Try multiple ways to dispatch the event
                    window.dispatchEvent(event);
                    document.dispatchEvent(event);
                    
                    // Also try dispatching to document body
                    document.body.dispatchEvent(event);
                }, 100);
            } else {
                console.error('No user data available for editing');
            }
        },

        getInitials(name) {
            if (!name) return '?';
            return name.split(' ')
                .map(word => word.charAt(0))
                .join('')
                .toUpperCase()
                .substring(0, 2);
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                return new Date(dateString).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } catch {
                return 'Invalid Date';
            }
        },

    }
}
</script>
