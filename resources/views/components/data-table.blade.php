@props([
    'columns' => [],
    'data' => [],
    'actions' => [],
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10,
    'emptyMessage' => 'No data found',
    'hoverEffects' => true,
    'alternatingRows' => true,
    'customClass' => 'bg-slate-800 text-slate-200',
    'title' => 'Data Table',
    'description' => 'Manage your data records',
    'showRoleFilter' => false,
    'availableRoles' => [],
    'colorScheme' => 'sky'
])

@vite('resources/js/app.js')

<script>
function dataTable(initialData, actions, pageSize, colorScheme) {
    return {
        data: initialData,
        actions: actions,
        searchTerm: '',
        statusFilter: 'all',
        roleFilter: 'all',
        sortKey: '',
        sortDirection: 'asc',
        displayedData: [],
        currentPage: 1,
        pageSize: pageSize,
        openMenuId: null,
        isMobile: false,
        menuPosition: { top: 0, left: 0, transform: '' },
        showAddModal: false,
        modalStep: 1,
        modalSteps: [
            { step: 1, title: '', subtitle: '' }
        ],
        formData: {
            name: '',
            email: '',
            phone: '',
            role: '',
            password: '',
            status: 'active'
        },
        formErrors: {},
        isSubmitting: false,
        showPassword: false,
        colorScheme: colorScheme,

        init() {
            console.log('DataTable init - Initial data:', this.data);
            console.log('DataTable init - Data length:', this.data ? this.data.length : 0);
            
            // Initialize displayedData with the actual data
            this.displayedData = [...(this.data || [])];
            
            console.log('DataTable init - Displayed data:', this.displayedData);
            console.log('DataTable init - Displayed data length:', this.displayedData.length);
            
            this.checkMobileSize();
            window.addEventListener('resize', () => this.checkMobileSize());
            this.$nextTick(() => {
                this.applyFilters();
            });
        },

        checkMobileSize() {
            this.isMobile = window.innerWidth < 768;
        },

        applyFilters() {
            console.log('applyFilters called - this.data:', this.data);
            console.log('applyFilters called - this.data length:', this.data ? this.data.length : 0);
            
            // Ensure we have data to work with
            if (!Array.isArray(this.data) || this.data.length === 0) {
                console.log('No data available, setting displayedData to empty array');
                this.displayedData = [];
                this.currentPage = 1;
                return;
            }
            
            const searchLower = this.searchTerm ? this.searchTerm.toLowerCase() : '';
            const statusLower = this.statusFilter === 'all' ? null : this.statusFilter.toLowerCase();
            const roleLower = this.roleFilter === 'all' ? null : this.roleFilter.toLowerCase();
            
            let filtered = this.data.filter(row => {
                const matchesSearch = !searchLower || 
                    Object.values(row).some(value => 
                        String(value).toLowerCase().includes(searchLower)
                    );
                const matchesStatus = !statusLower || row.status?.toLowerCase() === statusLower;
                const matchesRole = !roleLower || 
                    row.role?.toLowerCase() === roleLower ||
                    row.role_name?.toLowerCase() === roleLower;
                
                return matchesSearch && matchesStatus && matchesRole;
            });

            if (this.sortKey) {
                filtered.sort((a, b) => {
                    let aVal = a[this.sortKey] ?? '';
                    let bVal = b[this.sortKey] ?? '';

                    if (!isNaN(aVal) && !isNaN(bVal)) {
                        return this.sortDirection === 'asc' ? aVal - bVal : bVal - aVal;
                    }

                    aVal = aVal.toString().toLowerCase();
                    bVal = bVal.toString().toLowerCase();

                    if (aVal < bVal) return this.sortDirection === 'asc' ? -1 : 1;
                    if (aVal > bVal) return this.sortDirection === 'asc' ? 1 : -1;
                    return 0;
                });
            }

            this.displayedData = filtered;
            this.currentPage = 1;
            
            console.log('applyFilters completed - displayedData length:', this.displayedData.length);
        },

        sort(columnKey) {
            if (!columnKey) return;
            if (this.sortKey === columnKey) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortKey = columnKey;
                this.sortDirection = 'asc';
            }
            this.applyFilters();
        },

        search() { this.applyFilters() },
        filterStatus() { this.applyFilters() },
        filterRole() { this.applyFilters() },

        goToPage(page) {
            if (page < 1) page = 1;
            if (page > this.totalPages) page = this.totalPages;
            this.currentPage = page;
        },

        changePageSize() {
            this.currentPage = 1;
            this.applyFilters();
        },

        get paginatedData() {
            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + this.pageSize;
            return this.displayedData.slice(start, end);
        },

        get totalPages() {
            return Math.ceil(this.displayedData.length / this.pageSize);
        },

        get totalRecords() {
            return this.displayedData.length;
        },

        formatValue(key, value) {
            if (value === null || value === undefined) {
                return '-';
            }
            
            if (value === '') {
                return '-';
            }
            
            const stringValue = String(value);
            if (stringValue.includes('function') || 
                stringValue.includes('=>') || 
                stringValue.includes('const ') || 
                stringValue.includes('let ') || 
                stringValue.includes('var ') ||
                stringValue.includes('await ') ||
                stringValue.includes('async ') ||
                stringValue.includes('setTimeout') ||
                stringValue.includes('document.') ||
                stringValue.includes('x-data=') ||
                stringValue.includes('x-init=') ||
                stringValue.includes('x-show=') ||
                stringValue.includes('x-text=') ||
                stringValue.includes('@click') ||
                stringValue.includes('@keydown') ||
                stringValue.includes('class=') ||
                stringValue.includes('id=') ||
                stringValue.includes('<div') ||
                stringValue.includes('<span') ||
                stringValue.includes('<button') ||
                stringValue.includes('<svg') ||
                stringValue.includes('</div>') ||
                stringValue.includes('</span>') ||
                stringValue.includes('</button>') ||
                stringValue.includes('</svg>') ||
                (stringValue.length > 200 && stringValue.includes(';'))) {
                return '[Code/HTML Content]';
            }
            
            if (key.toLowerCase() === 'price' || key.toLowerCase() === 'amount' || key.toLowerCase() === 'total_price') {
                const numValue = Number(value);
                if (isNaN(numValue)) return value;
                return `â‚±${numValue.toLocaleString()}`;
            }
            
            if (key.toLowerCase().includes('date') || key.toLowerCase().includes('_at')) {
                try {
                    const date = new Date(value);
                    if (isNaN(date.getTime())) return value;
                    return date.toLocaleDateString('en-US', { 
                        year: 'numeric', month: 'long', day: 'numeric' 
                    });
                } catch (e) {
                    return value;
                }
            }
            
            if (typeof value === 'boolean') {
                return value ? 'Yes' : 'No';
            }
            
            if (typeof value === 'number') {
                return value.toLocaleString();
            }
            
            if (stringValue.length > 100) {
                return stringValue.substring(0, 100) + '...';
            }
            
            return stringValue;
        },

        getStatusConfig(status) {
            const statusLower = status.toLowerCase();
            
            const statusConfigs = {
                'active': { class: 'emerald', dot: 'bg-emerald-400' },
                'completed': { class: 'emerald', dot: 'bg-emerald-400' },
                'confirmed': { class: 'emerald', dot: 'bg-emerald-400' },
                'paid': { class: 'emerald', dot: 'bg-emerald-400' },
                'in stock': { class: 'emerald', dot: 'bg-emerald-400' },
                
                'pending': { class: 'yellow', dot: 'bg-yellow-400' },
                'waiting': { class: 'yellow', dot: 'bg-yellow-400' },
                'scheduled': { class: 'yellow', dot: 'bg-yellow-400' },
                'priced': { class: 'yellow', dot: 'bg-yellow-400' },
                'low stock': { class: 'yellow', dot: 'bg-yellow-400' },
                
                'in progress': { class: 'blue', dot: 'bg-blue-400' },
                'processing': { class: 'blue', dot: 'bg-blue-400' },
                'working': { class: 'blue', dot: 'bg-blue-400' },
                
                'cancelled': { class: 'rose', dot: 'bg-rose-400' },
                'inactive': { class: 'rose', dot: 'bg-rose-400' },
                'failed': { class: 'rose', dot: 'bg-rose-400' },
                'rejected': { class: 'rose', dot: 'bg-rose-400' },
                'out of stock': { class: 'rose', dot: 'bg-rose-400' }
            };
            
            return statusConfigs[statusLower] || { class: 'gray', dot: 'bg-gray-400' };
        },

        statusClass(status) {
            const config = this.getStatusConfig(status);
            return `flex items-center gap-1 bg-${config.class}-500/20 text-${config.class}-400 border border-${config.class}-500/30 px-3 py-0.5 rounded-full text-xs font-medium`;
        },

        getStatusDotColor(status) {
            const config = this.getStatusConfig(status);
            return config.dot;
        },

        handleAction(row, action) {
            if (action === 'add') {
                this.openAddModal();
            } else {
                const actionConfig = this.actions.find(a => a.label.toLowerCase() === action.toLowerCase());
                if (actionConfig && window[actionConfig.onclick]) {
                    window[actionConfig.onclick](row);
                }
            }
        },

        positionMenu(rowId) {
            const button = document.getElementById('action-btn-' + rowId);
            if (!button) return;

            const buttonRect = button.getBoundingClientRect();
            const menuWidth = 176;
            const menuHeight = 200;
            const padding = 8;

            const spaceBelow = window.innerHeight - buttonRect.bottom;
            const spaceAbove = buttonRect.top;
            const spaceRight = window.innerWidth - buttonRect.right;
            const spaceLeft = buttonRect.left;

            let top, left;

            if (spaceBelow >= menuHeight + padding || spaceBelow >= spaceAbove) {
                top = buttonRect.bottom + padding;
            } else {
                top = buttonRect.top - menuHeight - padding;
            }

            if (spaceRight >= menuWidth || spaceRight >= spaceLeft) {
                left = buttonRect.left;
            } else {
                left = buttonRect.right - menuWidth;
            }

            top = Math.max(padding, Math.min(top, window.innerHeight - menuHeight - padding));
            left = Math.max(padding, Math.min(left, window.innerWidth - menuWidth - padding));

            this.menuPosition = {
                top: top + 'px',
                left: left + 'px',
                transform: 'none'
            };
        },

        toggleMenu(rowId) {
            if (this.openMenuId !== null && this.openMenuId !== rowId) {
                this.closeAllMenus();
            }
            
            if (this.openMenuId === rowId) {
                this.closeAllMenus();
                return;
            }

            this.openMenuId = rowId;

            if (this.isMobile) {
                document.body.classList.add('mobile-menu-open');
            } else {
                this.$nextTick(() => {
                    this.positionMenu(rowId);
                });
            }

            this.$nextTick(() => {
                if (!this.isMobile) {
                    const menu = document.getElementById('action-menu-' + rowId);
                    if (menu) {
                        const firstButton = menu.querySelector('button');
                        if (firstButton) {
                            firstButton.focus();
                        }
                    }
                }
            });
        },

        closeAllMenus() {
            this.openMenuId = null;
            document.body.classList.remove('mobile-menu-open');
        },

        openAddModal() {
            this.showAddModal = true;
            this.modalStep = 1;
            this.formData = {
                name: '',
                email: '',
                phone: '',
                role: '',
                password: '',
                status: 'active'
            };
            this.formErrors = {};
            this.isSubmitting = false;
            this.showPassword = false;
            document.body.classList.add('modal-open');
            
            this.$nextTick(() => {
                const firstInput = document.querySelector('#modal-form input[type="text"]');
                if (firstInput) firstInput.focus();
            });
        },

        closeAddModal() {
            this.showAddModal = false;
            this.modalStep = 1;
            this.formData = {
                name: '',
                email: '',
                phone: '',
                role: '',
                password: '',
                status: 'active'
            };
            this.formErrors = {};
            this.isSubmitting = false;
            this.showPassword = false;
            document.body.classList.remove('modal-open');
        },

        nextStep() {
            // Since we only have one step now, this function is not needed
            // But keeping it for compatibility
        },

        prevStep() {
            // Since we only have one step now, this function is not needed
            // But keeping it for compatibility
        },

        validateCurrentStep() {
            this.formErrors = {};
            let isValid = true;

            if (this.modalStep === 1) {
                if (!this.formData.name || this.formData.name.trim() === '') {
                    this.formErrors.name = 'Name is required';
                    isValid = false;
                }

                if (this.formData.email && !this.isValidEmail(this.formData.email)) {
                    this.formErrors.email = 'Please enter a valid email address';
                    isValid = false;
                }

                if (this.formData.phone && !this.isValidPhone(this.formData.phone)) {
                    this.formErrors.phone = 'Please enter a valid phone number';
                    isValid = false;
                }
            }

            return isValid;
        },

        validateForm() {
            this.formErrors = {};
            let isValid = true;

            if (!this.formData.name || this.formData.name.trim() === '') {
                this.formErrors.name = 'Name is required';
                isValid = false;
            }

            if (!this.formData.role || this.formData.role.trim() === '') {
                this.formErrors.role = 'User role is required';
                isValid = false;
            }

            if (!this.formData.password || this.formData.password.trim() === '') {
                this.formErrors.password = 'Password is required';
                isValid = false;
            } else if (this.formData.password.length < 6) {
                this.formErrors.password = 'Password must be at least 6 characters';
                isValid = false;
            }

            if (this.formData.email && !this.isValidEmail(this.formData.email)) {
                this.formErrors.email = 'Please enter a valid email address';
                isValid = false;
            }

            if (this.formData.phone && !this.isValidPhone(this.formData.phone)) {
                this.formErrors.phone = 'Please enter a valid phone number';
                isValid = false;
            }

            return isValid;
        },

        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        isValidPhone(phone) {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            return phoneRegex.test(phone.replace(/\s/g, ''));
        },

        async submitForm() {
            if (!this.validateForm()) {
                return;
            }

            this.isSubmitting = true;

            try {
                await new Promise(resolve => setTimeout(resolve, 2000));

                const newItem = {
                    id: Date.now(),
                    ...this.formData,
                    created_at: new Date().toISOString(),
                    status: this.formData.status
                };

                this.data.unshift(newItem);
                this.applyFilters();

                this.showNotification('Item added successfully! ðŸŽ‰', 'success');
                this.closeAddModal();

            } catch (error) {
                console.error('Error adding item:', error);
                this.showNotification('Error adding item. Please try again.', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },

        showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-[99999] px-6 py-4 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full ${
                type === 'success' 
                    ? 'bg-gradient-to-r from-emerald-500 to-green-600 text-white border border-emerald-400/20' 
                    : 'bg-gradient-to-r from-red-500 to-rose-600 text-white border border-red-400/20'
            }`;
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full ${type === 'success' ? 'bg-white/20' : 'bg-white/20'} flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${type === 'success' 
                                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                                }
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-sm">${type === 'success' ? 'Success!' : 'Error!'}</p>
                        <p class="text-xs opacity-90">${message}</p>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 4000);
        },

        getActionIcon(actionLabel) {
            const iconClass = 'h-4 w-4 text-slate-400';
            const actionLower = actionLabel.toLowerCase();
            
            const icons = {
                view: `<svg xmlns="http://www.w3.org/2000/svg" class="${iconClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`,
                edit: `<svg xmlns="http://www.w3.org/2000/svg" class="${iconClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>`,
                delete: `<svg xmlns="http://www.w3.org/2000/svg" class="${iconClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>`,
                add: `<svg xmlns="http://www.w3.org/2000/svg" class="${iconClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>`,
                print: `<svg xmlns="http://www.w3.org/2000/svg" class="${iconClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>`,
                download: `<svg xmlns="http://www.w3.org/2000/svg" class="${iconClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`,
                copy: `<svg xmlns="http://www.w3.org/2000/svg" class="${iconClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>`,
                status: `<svg xmlns="http://www.w3.org/2000/svg" class="${iconClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>`,
                approve: `<svg xmlns="http://www.w3.org/2000/svg" class="${iconClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`,
                reject: `<svg xmlns="http://www.w3.org/2000/svg" class="${iconClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`,
                default: `<svg xmlns="http://www.w3.org/2000/svg" class="${iconClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`
            };

            for (const [key, icon] of Object.entries(icons)) {
                if (actionLower.includes(key)) {
                    return icon;
                }
            }

            return icons.default;
        }
    }
}
</script>

<style>
    /* Premium SaaS modal animations */
    @keyframes slideInFromTop {
        from {
            transform: translateY(-100px) scale(0.9);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    @keyframes fadeInBackdrop {
        from {
            opacity: 0;
            backdrop-filter: blur(0px);
        }
        to {
            opacity: 1;
            backdrop-filter: blur(8px);
        }
    }

    .modal-content-premium {
        animation: slideInFromTop 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .modal-backdrop-premium {
        animation: fadeInBackdrop 0.3s ease-out;
    }

    /* Progress bar */
    .progress-bar {
        @apply relative bg-slate-700 rounded-full h-1 overflow-hidden;
    }

    .progress-fill {
        @apply h-full bg-gradient-to-r transition-all duration-500 ease-out;
    }

    /* Scrollbar styling */
    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #1e293b;
        border-radius: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 4px;
        border: 1px solid #334155;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
    
    .custom-scrollbar::-webkit-scrollbar-corner {
        background: #1e293b;
    }

    /* Mobile menu body scroll prevention */
    .mobile-menu-open {
        overflow: hidden;
    }

    /* Modal body scroll prevention */
    .modal-open {
        overflow: hidden;
    }

    /* High z-index modal classes - FIXED */
    .modal-overlay-highest {
        z-index: 99997 !important;
    }

    .modal-backdrop-highest {
        z-index: 99998 !important;
    }

    .modal-content-highest {
        z-index: 99999 !important;
    }

    /* Ensure modal is always on top */
    .data-table-modal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        z-index: 99997 !important;
    }

    /* Premium button hover effects */
    .btn-premium {
        @apply relative overflow-hidden;
    }

    .btn-premium::before {
        content: '';
        @apply absolute top-0 left-0 w-full h-full bg-white/10 transform scale-x-0 transition-transform duration-300 origin-left;
    }

    .btn-premium:hover::before {
        @apply scale-x-100;
    }

    /* Premium step indicator */
    .step-indicator {
        @apply relative w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm font-semibold transition-all duration-300;
    }

    .step-indicator.active {
        @apply border-sky-400 bg-sky-400 text-white shadow-lg shadow-sky-400/25;
    }

    .step-indicator.completed {
        @apply border-emerald-400 bg-emerald-400 text-white;
    }

    .step-indicator.inactive {
        @apply border-slate-600 bg-slate-800 text-slate-400;
    }

    /* Premium form field focus states */
    .form-input-premium {
        @apply relative;
    }

    .form-input-premium input:focus,
    .form-input-premium textarea:focus,
    .form-input-premium select:focus {
        @apply ring-2 ring-sky-400/50 border-sky-400 shadow-lg shadow-sky-400/10;
    }

    /* Pulse animation for loading states */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .5;
        }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Enhanced focus states */
    .focus-ring {
        @apply focus:outline-none focus:ring-2 focus:ring-sky-400/50 focus:border-sky-400;
    }
</style>

<!-- Reusable Data Table Component -->
<div 
    x-data="dataTable(@js($data), @js($actions), {{ $pageSize }}, '{{ $colorScheme }}')"
    x-init="init()"
    @keydown.escape.window="closeAllMenus(); if (showAddModal) closeAddModal();"
    class="data-table-container w-full bg-slate-900 text-slate-50 p-6 rounded-xl shadow-xl border border-slate-800 {{ $customClass }}"
>
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} rounded-full"></div>
                <h2 class="text-2xl font-bold text-slate-50 tracking-wide">{{ $title }}</h2>
            </div>
            <p class="text-slate-400 text-sm ml-4">{{ $description }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            @if($searchable)
            <!-- Search -->
            <div class="relative w-full md:w-64">
                <input 
                    type="text" 
                    placeholder="Search {{ strtolower($title) }}..." 
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-50 placeholder-slate-400 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} text-sm transition-all"
                    x-model="searchTerm"
                    @input="search"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 absolute left-3 top-2.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.386a1 1 0 01-1.414 1.415l-4.387-4.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd" />
                </svg>
            </div>
            @endif
            
            <!-- Add Button -->
            <button 
                @click="handleAction({}, 'add')" 
                class="px-4 py-2 bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} hover:from-{{ $colorScheme === 'indigo' ? 'indigo-600' : 'sky-600' }} hover:to-{{ $colorScheme === 'indigo' ? 'purple-600' : 'cyan-600' }} text-white rounded-lg text-sm font-medium shadow-lg transition-all duration-200 hover:shadow-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }}/25 flex items-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-4 flex flex-wrap items-center gap-3">
        <!-- Status Filter -->
        <div class="flex items-center gap-2">
            <label class="text-sm text-slate-300 font-medium">Status:</label>
            <select 
                x-model="statusFilter" 
                @change="filterStatus"
                class="border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-50 bg-slate-800 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} transition-all"
            >
                <option value="all">All statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="pending">Pending</option>
            </select>
        </div>
        
        @if($showRoleFilter)
        <!-- Role Filter -->
        <div class="flex items-center gap-2">
            <label class="text-sm text-slate-300 font-medium">Role:</label>
            <select 
                x-model="roleFilter" 
                @change="filterRole"
                class="border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-50 bg-slate-800 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} transition-all"
            >
                <option value="all">All roles</option>
                @if(!empty($availableRoles))
                    @foreach($availableRoles as $role)
                        <option value="{{ strtolower($role->name) }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                @else
                    <option value="superadmin">Super Admin</option>
                    <option value="administrator">Administrator</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                @endif
            </select>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-xl shadow-lg border border-slate-700/50 custom-scrollbar" style="scrollbar-width: thin; scrollbar-color: #475569 #1e293b;">
        <table class="min-w-full text-sm text-left text-slate-50 rounded-xl overflow-hidden bg-slate-900">
            <thead class="bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 text-slate-100 uppercase text-xs font-semibold tracking-wider border-b border-slate-600/50 shadow-sm">
                <tr>
                    @foreach($columns as $column)
                    <th 
                        class="px-4 py-3 cursor-pointer select-none hover:text-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} transition-all duration-200 whitespace-nowrap {{ $sortable ? '' : 'cursor-default' }}"
                        @if($sortable) @click="sort('{{ $column['key'] }}')" @endif
                    >
                        <div class="flex items-center gap-1 {{ $column['key'] === 'id' ? 'justify-center' : '' }}">
                            <span class="font-medium">{{ $column['label'] }}</span>
                            @if($sortable)
                            <span x-show="sortKey === '{{ $column['key'] }}'">
                                <svg x-show="sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                <svg x-show="sortDirection === 'desc'" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                            @endif
                        </div>
                    </th>
                    @endforeach
                    
                    @if(count($actions) > 0)
                    <th class="px-4 py-3 text-center w-16">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <template x-for="(row, rowIndex) in paginatedData" :key="row.id">
                    <tr 
                        class="border-b border-slate-700/30 transition-all duration-200 {{ $hoverEffects ? 'hover:bg-slate-800/60 hover:shadow-sm' : '' }}"
                        :class="{{ $alternatingRows ? 'rowIndex % 2 === 0 ? \'bg-slate-900/50\' : \'bg-slate-800/20\'' : 'bg-slate-900/50' }}"
                    >
                        @foreach($columns as $column)
                        <td 
                            class="px-4 py-3 text-slate-100 font-normal whitespace-nowrap"
                            :class="{ 'text-center': '{{ $column['key'] }}' === 'id' }"
                        >
                            <!-- Status -->
                            <template x-if="'{{ $column['key'] }}' === 'status' || '{{ $column['key'] }}' === 'payment_status'">
                                <span :class="statusClass(row['{{ $column['key'] }}'])">
                                    <span class="w-2 h-2 rounded-full" :class="getStatusDotColor(row['{{ $column['key'] }}'])"></span>
                                    <span x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                                </span>
                            </template>
                            
                            <!-- Price/Amount -->
                            <template x-if="'{{ $column['key'] }}' === 'price' || '{{ $column['key'] }}' === 'amount' || '{{ $column['key'] }}' === 'total_price'">
                                <span x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                            </template>
                            
                            <!-- Description (truncate) -->
                            <template x-if="'{{ $column['key'] }}' === 'description' || '{{ $column['key'] }}' === 'message'">
                                <span class="truncate max-w-xs block" :title="row['{{ $column['key'] }}']" x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                            </template>
                            
                            <!-- Created By -->
                            <template x-if="'{{ $column['key'] }}' === 'created_by'">
                                <span x-text="formatValue('{{ $column['key'] }}', row.created_by_name || row.created_by_user_name || 'System')"></span>
                            </template>
                            
                            <!-- Default -->
                            <template x-if="'{{ $column['key'] }}' !== 'status' && '{{ $column['key'] }}' !== 'payment_status' && '{{ $column['key'] }}' !== 'price' && '{{ $column['key'] }}' !== 'amount' && '{{ $column['key'] }}' !== 'total_price' && '{{ $column['key'] }}' !== 'description' && '{{ $column['key'] }}' !== 'message' && '{{ $column['key'] }}' !== 'created_by'">
                                <span x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                            </template>
                        </td>
                        @endforeach
                        
                        @if(count($actions) > 0)
                        <!-- Action menu cell -->
                        <td class="px-4 py-3 text-center relative">
                            <button 
                                :id="'action-btn-' + row.id"
                                @click="toggleMenu(row.id)" 
                                class="p-2 rounded-full hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} transition-all duration-200"
                                aria-haspopup="true"
                                :aria-expanded="openMenuId === row.id"
                                aria-label="Row actions"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6 10a2 2 0 114 0 2 2 0 01-4 0zm-6 0a2 2 0 114 0 2 2 0 01-4 0zm12 0a2 2 0 114 0 2 2 0 01-4 0z"/>
                                </svg>
                            </button>

                            <!-- Desktop Dropdown Menu -->
                            <div 
                                x-show="!isMobile && openMenuId === row.id"
                                :id="'action-menu-' + row.id"
                                :style="menuPosition"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                @click.away="closeAllMenus()"
                                class="fixed z-[99999] w-44 bg-slate-800/95 backdrop-blur-sm border border-slate-700/50 rounded-lg shadow-xl py-1"
                                role="menu" 
                                aria-orientation="vertical"
                                aria-labelledby="'action-btn-' + row.id"
                                tabindex="-1"
                            >
                                @foreach($actions as $action)
                                <button 
                                    @click="handleAction(row, '{{ strtolower($action['label']) }}'); closeAllMenus()" 
                                    class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700/80 hover:text-white transition-all duration-200 focus:outline-none focus:bg-slate-700/80 focus:text-white"
                                    role="menuitem"
                                    tabindex="-1"
                                >
                                    <span x-html="getActionIcon('{{ $action['label'] }}')"></span>
                                    <span class="font-medium">{{ $action['label'] }}</span>
                                </button>
                                @endforeach
                            </div>

                            <!-- Mobile Bottom Sheet -->
                            <div 
                                x-show="isMobile && openMenuId === row.id"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="transform translate-y-full"
                                x-transition:enter-end="transform translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="transform translate-y-0"
                                x-transition:leave-end="transform translate-y-full"
                                @click.away="closeAllMenus()"
                                class="fixed inset-x-0 bottom-0 z-[99999] bg-slate-900 rounded-t-2xl shadow-2xl p-4"
                                role="menu" 
                                aria-orientation="vertical"
                                aria-labelledby="'action-btn-' + row.id"
                            >
                                <!-- Drag Handle -->
                                <div class="w-12 h-1.5 bg-slate-700 rounded-full mx-auto mb-4"></div>
                                
                                <!-- Menu Items -->
                                @foreach($actions as $action)
                                <button 
                                    @click="handleAction(row, '{{ strtolower($action['label']) }}'); closeAllMenus()" 
                                    class="flex items-center gap-4 w-full text-left px-4 py-4 text-base text-slate-200 hover:bg-slate-800/50 rounded-lg transition-all duration-200 focus:outline-none focus:bg-slate-800/50"
                                    role="menuitem"
                                >
                                    <span x-html="getActionIcon('{{ $action['label'] }}')"></span>
                                    <span class="font-medium">{{ $action['label'] }}</span>
                                </button>
                                @endforeach
                                
                                <!-- Cancel Button -->
                                <button 
                                    @click="closeAllMenus()" 
                                    class="w-full mt-4 py-3 text-center text-slate-400 hover:text-slate-300 transition-colors duration-200"
                                >
                                    Cancel
                                </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                </template>
                
                <!-- Empty State -->
                <tr x-show="displayedData.length === 0">
                    <td :colspan="{{ count($columns) + (count($actions) > 0 ? 1 : 0) }}" class="px-4 py-16 text-center text-slate-400">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-medium text-slate-300 mb-1">No data found</h3>
                                <p class="text-sm text-slate-500">{{ $emptyMessage }}</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($pagination)
    <!-- Pagination -->
    <div class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4 bg-gradient-to-r from-slate-800/80 to-slate-700/80 border border-slate-600/50 rounded-lg px-4 py-3 shadow-lg backdrop-blur-sm">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="text-slate-400 text-sm">
                Showing 
                <span x-text="totalRecords > 0 ? (currentPage - 1) * pageSize + 1 : 0"></span> - 
                <span x-text="Math.min(currentPage * pageSize, totalRecords)"></span> 
                of <span x-text="totalRecords"></span>
            </div>
            
            <!-- Rows per page -->
            <div class="flex items-center gap-2">
                <label class="text-slate-400 text-sm">Rows per page:</label>
                <select 
                    x-model="pageSize" 
                    @change="changePageSize"
                    class="border border-slate-600 rounded-lg px-2 py-1 text-sm text-slate-50 bg-slate-700 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} transition-all"
                >
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button 
                @click="goToPage(currentPage - 1)" 
                :disabled="currentPage === 1"
                class="px-3 py-1.5 bg-slate-700/60 border border-slate-600/50 rounded-full text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-600/80 hover:text-slate-100 hover:border-slate-500 transition-all duration-200 text-sm"
            >
                Prev
            </button>
            <template x-for="page in totalPages" :key="page">
                <button 
                    @click="goToPage(page)" 
                    :class="page === currentPage ? 'bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} text-white border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} shadow-lg shadow-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }}/25' : 'bg-slate-700/60 text-slate-300 hover:bg-slate-600/80 hover:border-slate-500'"
                    class="px-3 py-1.5 rounded-full border border-slate-600/50 text-sm transition-all duration-200"
                    x-text="page"
                ></button>
            </template>
            <button 
                @click="goToPage(currentPage + 1)" 
                :disabled="currentPage === totalPages"
                class="px-3 py-1.5 bg-slate-700/60 border border-slate-600/50 rounded-full text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-600/80 hover:text-slate-100 hover:border-slate-500 transition-all duration-200 text-sm"
            >
                Next
            </button>
        </div>
    </div>
    @endif

    <!-- Premium SaaS Modal - PROFESSIONAL UPGRADE -->
    <div 
        x-show="showAddModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 overflow-y-auto z-[99997] data-table-modal"
        style="display: none;"
    >
        <!-- Premium Backdrop with Enhanced Blur -->
        <div 
            @click="closeAddModal()"
            class="fixed inset-0 bg-black/70 backdrop-blur-xl z-[99998] modal-backdrop-premium modal-backdrop-highest"
        ></div>

        <!-- Premium Modal Container -->
        <div class="relative flex min-h-full items-center justify-center p-4 z-[99999] modal-content-highest">
            <div 
                @click.stop
                class="modal-content modal-content-premium relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700/50 shadow-2xl shadow-black/50"
            >
                <!-- Premium Header with Progress Indicator -->
                <div class="relative bg-gradient-to-r from-slate-800/90 via-slate-700/90 to-slate-800/90 px-8 py-6 border-b border-slate-600/50 backdrop-blur-sm">
                    <!-- Close Button -->
                    <button 
                        @click="closeAddModal()"
                        class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-200 hover:bg-slate-700/50 rounded-xl transition-all duration-200 group"
                    >
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <!-- Header Content -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} rounded-2xl flex items-center justify-center shadow-lg shadow-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }}/25">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-1">Create New Item</h2>
                            <p class="text-slate-400 text-sm">Add a new item to your {{ strtolower($title) }}</p>
                        </div>
                    </div>

                    <!-- Modern Step Indicator -->
                    <div class="relative">
                        <!-- Progress Bar Background -->
                        <div class="w-full h-2 bg-slate-700/50 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} rounded-full transition-all duration-500 ease-out"
                                 :style="`width: ${((modalStep - 1) / (modalSteps.length - 1)) * 100}%`">
                            </div>
                        </div>
                        
                        <!-- Step Dots -->
                        <div class="flex justify-between mt-4">
                            <template x-for="(stepData, index) in modalSteps" :key="index">
                                <div class="flex flex-col items-center">
                                    <!-- Step Circle -->
                                    <div class="relative">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300"
                                             :class="{
                                                 'bg-gradient-to-r from-{{ $colorScheme === "indigo" ? "indigo-500" : "sky-500" }} to-{{ $colorScheme === "indigo" ? "purple-500" : "cyan-500" }} text-white shadow-lg shadow-{{ $colorScheme === "indigo" ? "indigo-500" : "sky-500" }}/25': stepData.step === modalStep,
                                                 'bg-slate-600 text-slate-300': stepData.step < modalStep,
                                                 'bg-slate-700 text-slate-500': stepData.step > modalStep
                                             }">
                                            <template x-if="stepData.step < modalStep">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </template>
                                            <template x-if="stepData.step >= modalStep">
                                                <span x-text="stepData.step"></span>
                                            </template>
                                        </div>
                                        
                                        <!-- Pulse Animation for Current Step -->
                                        <div x-show="stepData.step === modalStep" 
                                             class="absolute inset-0 w-10 h-10 rounded-full bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} animate-ping opacity-20">
                                        </div>
                                    </div>
                                    
                                    <!-- Step Label -->
                                    <div class="mt-2 text-center">
                                        <div class="text-xs font-medium transition-colors duration-300"
                                             :class="{
                                                 'text-white': stepData.step === modalStep,
                                                 'text-slate-300': stepData.step < modalStep,
                                                 'text-slate-500': stepData.step > modalStep
                                             }"
                                             x-text="stepData.title">
                                        </div>
                                        <div class="text-xs text-slate-400 mt-1" 
                                             x-show="stepData.step === modalStep"
                                             x-text="stepData.subtitle">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Premium Form Content -->
                <form @submit.prevent="submitForm()" id="modal-form" class="relative">
                    <div class="px-8 py-6">
                        <!-- Step 1: Basic Information -->
                        <div x-show="modalStep === 1" id="step-1" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-8"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name Field -->
                                <div class="form-input-premium">
                                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Full Name <span class="text-red-400">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        x-model="formData.name"
                                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}/50 focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}"
                                        placeholder="Enter full name"
                                        required
                                    >
                                    <div x-show="formErrors.name" class="mt-2 text-sm text-red-400 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="formErrors.name"></span>
                                    </div>
                                </div>

                                <!-- Email Field -->
                                <div class="form-input-premium">
                                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                        </svg>
                                        Email Address
                                    </label>
                                    <input 
                                        type="email" 
                                        x-model="formData.email"
                                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}/50 focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}"
                                        placeholder="Enter email address"
                                    >
                                    <div x-show="formErrors.email" class="mt-2 text-sm text-red-400 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="formErrors.email"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Phone Field -->
                                <div class="form-input-premium">
                                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        Phone Number
                                    </label>
                                    <input 
                                        type="tel" 
                                        x-model="formData.phone"
                                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}/50 focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}"
                                        placeholder="Enter phone number"
                                    >
                                    <div x-show="formErrors.phone" class="mt-2 text-sm text-red-400 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="formErrors.phone"></span>
                                    </div>
                                </div>

                                <!-- Role Field -->
                                <div class="form-input-premium">
                                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        User Role <span class="text-red-400">*</span>
                                    </label>
                                    <select 
                                        x-model="formData.role"
                                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}/50 focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}"
                                        required
                                    >
                                        <option value="">Select a role</option>
                                        <option value="customer">ðŸ‘¤ Customer</option>
                                        <option value="staff">ðŸ‘· Staff</option>
                                        <option value="administrator">ðŸ› ï¸ Administrator</option>
                                        <option value="superadmin">ðŸ‘‘ Super Admin</option>
                                    </select>
                                    <div x-show="formErrors.role" class="mt-2 text-sm text-red-400 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="formErrors.role"></span>
                                    </div>
                                </div>

                                <!-- Status Field -->
                                <div class="form-input-premium">
                                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Status
                                    </label>
                                    <select 
                                        x-model="formData.status"
                                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}/50 focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}"
                                    >
                                        <option value="active">ðŸŸ¢ Active</option>
                                        <option value="inactive">ðŸ”´ Inactive</option>
                                        <option value="pending">ðŸŸ¡ Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Password Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password Field -->
                            <div class="form-input-premium">
                                <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Password <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        :type="showPassword ? 'text' : 'password'"
                                        x-model="formData.password"
                                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}/50 focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}"
                                        placeholder="Enter password"
                                        required
                                    >
                                    <button type="button" 
                                            @click="showPassword = !showPassword"
                                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-300 transition-colors duration-200">
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                                <div x-show="formErrors.password" class="mt-2 text-sm text-red-400 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="formErrors.password"></span>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- Premium Action Buttons -->
                    <div class="bg-gradient-to-r from-slate-800/50 to-slate-700/50 px-8 py-6 border-t border-slate-600/50 backdrop-blur-sm">
                        <div class="flex justify-between items-center">
                            <!-- Back Button -->
                            <button 
                                x-show="modalStep > 1"
                                type="button"
                                @click="prevStep()"
                                class="flex items-center gap-2 px-4 py-2.5 text-slate-300 hover:text-slate-100 bg-slate-700/50 hover:bg-slate-600/50 border border-slate-600/50 hover:border-slate-500 rounded-xl transition-all duration-200 font-medium"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Back
                            </button>

                            <div class="flex gap-3">
                                <!-- Cancel Button -->
                                <button 
                                    type="button"
                                    @click="closeAddModal()"
                                    class="px-6 py-2.5 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-slate-100 border border-slate-600/50 hover:border-slate-500 rounded-xl transition-all duration-200 font-medium"
                                    :disabled="isSubmitting"
                                >
                                    Cancel
                                </button>

                                <!-- Next Button -->
                                <button 
                                    x-show="modalStep < modalSteps.length"
                                    type="button"
                                    @click="nextStep()"
                                    class="btn-premium flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} hover:from-{{ $colorScheme === 'indigo' ? 'indigo-600' : 'sky-600' }} hover:to-{{ $colorScheme === 'indigo' ? 'purple-600' : 'cyan-600' }} text-white rounded-xl transition-all duration-200 font-semibold shadow-lg hover:shadow-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }}/25 transform hover:scale-[1.02]"
                                >
                                    Continue
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>

                                <!-- Submit Button -->
                                <button 
                                    x-show="modalStep === modalSteps.length"
                                    type="submit"
                                    class="btn-premium flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-xl transition-all duration-200 font-semibold shadow-lg hover:shadow-emerald-500/25 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                    :disabled="isSubmitting"
                                >
                                    <template x-if="isSubmitting">
                                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </template>
                                    <template x-if="!isSubmitting">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </template>
                                    <span x-text="isSubmitting ? 'Creating Item...' : 'Create Item'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>