// Data Table JavaScript functionality
// This file provides additional functionality for the data table component

// Global data table functions
window.dataTableFunctions = {
    // Initialize data table with proper data handling
    initDataTable: function(data, actions, columns, pageSize = 10) {
        console.log('Initializing data table with:', { data, actions, columns, pageSize });
        
        // Ensure data is an array
        if (!Array.isArray(data)) {
            console.warn('Data is not an array, converting...', data);
            data = [];
        }
        
        return {
            data: data,
            actions: actions || [],
            columns: columns || [],
            searchTerm: '',
            statusFilter: 'all',
            roleFilter: 'all',
            sortKey: '',
            sortDirection: 'asc',
            displayedData: [...data],
            currentPage: 1,
            pageSize: pageSize,
            openMenuId: null,
            isMobile: false,
            menuPosition: { top: 0, left: 0, transform: '' },
            showAddModal: false,
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
                    return `₱${numValue.toLocaleString()}`;
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

            toggleMenu(rowId) {
                if (this.openMenuId !== null && this.openMenuId !== rowId) {
                    this.closeAllMenus();
                }
                
                if (this.openMenuId === rowId) {
                    this.closeAllMenus();
                    return;
                }

                this.openMenuId = rowId;
            },

            closeAllMenus() {
                this.openMenuId = null;
            },

            openAddModal() {
                this.showAddModal = true;
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
            },

            closeAddModal() {
                this.showAddModal = false;
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

                    this.showNotification('Item added successfully! 🎉', 'success');
                    this.closeAddModal();

                } catch (error) {
                    console.error('Error adding item:', error);
                    this.showNotification('Error adding item. Please try again.', 'error');
                } finally {
                    this.isSubmitting = false;
                }
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
        };
    }
};

// Make the function available globally
window.dataTable = window.dataTableFunctions.initDataTable;