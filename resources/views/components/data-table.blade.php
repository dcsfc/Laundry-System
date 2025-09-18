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
    'colorScheme' => 'sky',
    'formType' => 'user',
    'formConfig' => []
])

@vite('resources/js/app.js')

<script>
const TableUtils = {
    formatCurrency(value) {
        const numValue = Number(value);
        if (isNaN(numValue)) return value;
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP'
        }).format(numValue);
    },
    
    formatDate(value) {
        try {
            const date = new Date(value);
            if (isNaN(date.getTime())) return value;
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'long', 
                day: 'numeric'
            }).format(date);
        } catch (e) {
            return value;
        }
    },

    formatDateTime(value) {
        try {
            const date = new Date(value);
            if (isNaN(date.getTime())) return value;
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(date);
        } catch (e) {
            return value;
        }
    },

    sanitizeSearchTerm(term) {
        if (!term) return '';
        return term.toLowerCase().replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
};

const FormConfigs = {
    user: {
        title: 'Add New User',
        description: 'Add a new user to your system',
        endpoint: '/superadmin/users/store-ajax',
        fields: {
            name: { type: 'text', required: true, label: 'Full Name' },
            email: { type: 'email', required: false, label: 'Email Address' },
            phone: { type: 'tel', required: false, label: 'Phone Number' },
            role: { type: 'select', required: true, label: 'User Role', 
                   options: [
                       { value: 'customer', label: '👤 Customer' },
                       { value: 'staff', label: '👷 Staff' },
                       { value: 'administrator', label: '🛠️ Administrator' },
                       { value: 'superadmin', label: '👑 Super Admin' }
                   ]},
            password: { type: 'password', required: true, label: 'Password' },
            status: { type: 'select', required: false, label: 'Status',
                     options: [
                         { value: 'active', label: '🟢 Active' },
                         { value: 'inactive', label: '🔴 Inactive' },
                         { value: 'pending', label: '🟡 Pending' }
                     ]}
        },
        defaultData: { name: '', email: '', phone: '', role: '', password: '', status: 'active' }
    },
    
    service: {
        title: 'Add New Service',
        description: 'Add a new service to your system',
        endpoint: '/superadmin/services',
        fields: {
            name: { type: 'text', required: true, label: 'Service Name' },
            description: { type: 'textarea', required: false, label: 'Description' },
            price: { type: 'number', required: true, label: 'Price', step: '0.01' },
            category: { type: 'text', required: false, label: 'Category' },
            duration: { type: 'number', required: false, label: 'Duration (minutes)' },
            status: { type: 'select', required: false, label: 'Status',
                     options: [
                         { value: 'active', label: '🟢 Active' },
                         { value: 'inactive', label: '🔴 Inactive' }
                     ]}
        },
        defaultData: { name: '', description: '', price: '', category: '', duration: '', status: 'active' }
    },

    inventory: {
        title: 'Add New Item',
        description: 'Add a new inventory item',
        endpoint: '/superadmin/inventory',
        fields: {
            item_name: { type: 'text', required: true, label: 'Item Name' },
            quantity: { type: 'number', required: true, label: 'Quantity' },
            unit: { type: 'text', required: true, label: 'Unit (e.g., pieces, kg, liters)' },
            threshold: { type: 'number', required: true, label: 'Low Stock Threshold' },
            cost_price: { type: 'number', required: false, label: 'Cost Price', step: '0.01' },
            selling_price: { type: 'number', required: false, label: 'Selling Price', step: '0.01' },
            status: { type: 'select', required: false, label: 'Status',
                     options: [
                         { value: 'available', label: '🟢 Available' },
                         { value: 'out_of_stock', label: '🔴 Out of Stock' },
                         { value: 'low_stock', label: '🟡 Low Stock' }
                     ]}
        },
        defaultData: { item_name: '', quantity: '', unit: '', threshold: '', cost_price: '', selling_price: '', status: 'available' }
    },

    order: {
        title: 'Add New Order',
        description: 'Create a new order',
        endpoint: '/superadmin/orders',
        fields: {
            customer_id: { type: 'select', required: true, label: 'Customer', options: [] },
            service_id: { type: 'select', required: true, label: 'Service', options: [] },
            drop_off_date: { type: 'date', required: true, label: 'Drop-off Date' },
            pickup_date: { type: 'date', required: true, label: 'Pickup Date' },
            notes: { type: 'textarea', required: false, label: 'Notes' },
            status: { type: 'select', required: false, label: 'Status',
                     options: [
                         { value: 'scheduled', label: '📅 Scheduled' },
                         { value: 'priced', label: '💰 Priced' },
                         { value: 'in_progress', label: '⚙️ In Progress' },
                         { value: 'completed', label: '✅ Completed' },
                         { value: 'cancelled', label: '❌ Cancelled' }
                     ]},
            payment_status: { type: 'select', required: false, label: 'Payment Status',
                            options: [
                                { value: 'unpaid', label: '❌ Unpaid' },
                                { value: 'paid', label: '✅ Paid' }
                            ]},
            payment_method: { type: 'select', required: false, label: 'Payment Method',
                            options: [
                                { value: 'cash', label: '💵 Cash' },
                                { value: 'gcash', label: '📱 GCash' },
                                { value: 'credit_card', label: '💳 Credit Card' },
                                { value: 'paypal', label: '🅿️ PayPal' }
                            ]}
        },
        defaultData: { customer_id: '', service_id: '', drop_off_date: '', pickup_date: '', notes: '', status: 'scheduled', payment_status: 'unpaid', payment_method: 'cash' }
    },

    payment: {
        title: 'Add New Payment',
        description: 'Record a new payment',
        endpoint: '/superadmin/payments',
        fields: {
            order_id: { type: 'select', required: true, label: 'Order', options: [] },
            amount: { type: 'number', required: true, label: 'Amount', step: '0.01' },
            payment_method: { type: 'select', required: true, label: 'Payment Method',
                            options: [
                                { value: 'cash', label: '💵 Cash' },
                                { value: 'gcash', label: '📱 GCash' },
                                { value: 'credit_card', label: '💳 Credit Card' },
                                { value: 'paypal', label: '🅿️ PayPal' }
                            ]},
            reference_number: { type: 'text', required: false, label: 'Reference Number' },
            payment_status: { type: 'select', required: false, label: 'Payment Status',
                            options: [
                                { value: 'pending', label: '🟡 Pending' },
                                { value: 'paid', label: '✅ Paid' },
                                { value: 'failed', label: '❌ Failed' }
                            ]},
            paid_at: { type: 'datetime-local', required: false, label: 'Paid At' }
        },
        defaultData: { order_id: '', amount: '', payment_method: 'cash', reference_number: '', payment_status: 'pending', paid_at: '' }
    },

    announcement: {
        title: 'Add New Announcement',
        description: 'Create a new announcement',
        endpoint: '/superadmin/announcements',
        fields: {
            title: { type: 'text', required: true, label: 'Title' },
            message: { type: 'textarea', required: true, label: 'Message', rows: 4 },
            status: { type: 'select', required: false, label: 'Status',
                     options: [
                         { value: 'draft', label: '📝 Draft' },
                         { value: 'published', label: '📢 Published' },
                         { value: 'archived', label: '📁 Archived' }
                     ]},
            priority: { type: 'select', required: false, label: 'Priority',
                       options: [
                           { value: 'low', label: '🟢 Low' },
                           { value: 'medium', label: '🟡 Medium' },
                           { value: 'high', label: '🔴 High' }
                       ]}
        },
        defaultData: { title: '', message: '', status: 'draft', priority: 'medium' }
    },

    role: {
        title: 'Add New Role',
        description: 'Create a new user role',
        endpoint: '/superadmin/roles',
        fields: {
            role_name: { type: 'text', required: true, label: 'Role Name' },
            description: { type: 'textarea', required: false, label: 'Description' },
            permissions: { type: 'checkbox', required: false, label: 'Permissions', 
                         options: [
                             { value: 'create_users', label: 'Create Users' },
                             { value: 'edit_users', label: 'Edit Users' },
                             { value: 'delete_users', label: 'Delete Users' },
                             { value: 'view_reports', label: 'View Reports' },
                             { value: 'manage_services', label: 'Manage Services' },
                             { value: 'manage_orders', label: 'Manage Orders' }
                         ]},
            status: { type: 'select', required: false, label: 'Status',
                     options: [
                         { value: 'active', label: '🟢 Active' },
                         { value: 'inactive', label: '🔴 Inactive' }
                     ]}
        },
        defaultData: { role_name: '', description: '', permissions: [], status: 'active' }
    },

    permission: {
        title: 'Add New Permission',
        description: 'Create a new permission',
        endpoint: '/superadmin/permissions',
        fields: {
            permission_name: { type: 'text', required: true, label: 'Permission Name' },
            description: { type: 'textarea', required: false, label: 'Description' },
            module: { type: 'select', required: true, label: 'Module',
                     options: [
                         { value: 'user', label: 'User Management' },
                         { value: 'service', label: 'Service Management' },
                         { value: 'order', label: 'Order Management' },
                         { value: 'payment', label: 'Payment Management' },
                         { value: 'inventory', label: 'Inventory Management' },
                         { value: 'report', label: 'Report Management' }
                     ]},
            action: { type: 'select', required: true, label: 'Action',
                     options: [
                         { value: 'create', label: 'Create' },
                         { value: 'read', label: 'Read' },
                         { value: 'update', label: 'Update' },
                         { value: 'delete', label: 'Delete' }
                     ]},
            status: { type: 'select', required: false, label: 'Status',
                     options: [
                         { value: 'active', label: '🟢 Active' },
                         { value: 'inactive', label: '🔴 Inactive' }
                     ]}
        },
        defaultData: { permission_name: '', description: '', module: '', action: '', status: 'active' }
    }
};

function dataTable(initialData, actions, pageSize, colorScheme, formType, formConfig) {
    return {
        data: initialData || [],
        actions: actions || [],
        displayedData: [],
        
        searchTerm: '',
        searchDebounce: null,
        statusFilter: 'all',
        roleFilter: 'all',
        
        sortKey: '',
        sortDirection: 'asc',
        
        currentPage: 1,
        pageSize: pageSize || 10,
        
        openMenuId: null,
        isMobile: false,
        menuPosition: { top: 0, left: 0, transform: '' },
        
        showAddModal: false,
        
        formData: {},
        formErrors: {},
        isSubmitting: false,
        showPassword: false,
        
        isLoading: false,
        loadingError: null,
        
        colorScheme: colorScheme || 'sky',
        
        formType: formType || 'user',
        formConfig: formConfig || {},

        init() {
            // Initialize displayedData immediately with all data
            this.displayedData = [...(this.data || [])];
            
            this.checkMobileSize();
            window.addEventListener('resize', () => this.checkMobileSize());
            window.addEventListener('keydown', (e) => this.handleGlobalKeydown(e));
            this.initFormData();
            
            // Apply filters after initialization
            this.$nextTick(() => {
                this.applyFilters();
            });
        },

        initFormData() {
            const config = this.getFormConfig();
            this.formData = { ...config.defaultData };
        },

        getFormConfig() {
            return this.formConfig && Object.keys(this.formConfig).length > 0 
                ? this.formConfig 
                : FormConfigs[this.formType] || FormConfigs.user;
        },

        get shouldShowSkeleton() {
            return this.isLoading && this.data.length === 0;
        },

        get shouldUseVirtualScrolling() {
            return this.displayedData.length > 100;
        },

        checkMobileSize() {
            this.isMobile = window.innerWidth < 768;
        },

        handleGlobalKeydown(event) {
            if (event.key === 'Escape') {
                this.closeAllMenus();
                if (this.showAddModal) this.closeAddModal();
            }
            
            if (event.key === 'Enter' && event.target.tagName === 'TH' && event.target.dataset.column) {
                const column = event.target.dataset.column;
                if (column) this.sort(column);
            }
        },

        search() {
            clearTimeout(this.searchDebounce);
            this.searchDebounce = setTimeout(() => {
                this.applyFilters();
            }, 300);
        },

        // FIXED: Enhanced filtering logic
        applyFilters() {
            try {
                // Ensure we have data to work with
                if (!Array.isArray(this.data) || this.data.length === 0) {
                    this.displayedData = [];
                    this.currentPage = 1;
                    return;
                }

                const searchLower = TableUtils.sanitizeSearchTerm(this.searchTerm);
                
                let filtered = this.data.filter(row => {
                    try {
                        // Search filter - allow all if no search term
                        const matchesSearch = !searchLower || 
                            Object.values(row).some(value => {
                                if (value === null || value === undefined) return false;
                                return String(value).toLowerCase().includes(searchLower);
                            });
                        
                        // Status filter - allow all if 'all' is selected
                        const matchesStatus = this.statusFilter === 'all' || 
                            !row.status || 
                            (row.status && row.status.toLowerCase() === this.statusFilter.toLowerCase());
                        
                        // Role filter - allow all if 'all' is selected  
                        const matchesRole = this.roleFilter === 'all' || 
                            (!row.role && !row.role_name) || 
                            (row.role && row.role.toLowerCase() === this.roleFilter.toLowerCase()) ||
                            (row.role_name && row.role_name.toLowerCase() === this.roleFilter.toLowerCase());
                        
                        return matchesSearch && matchesStatus && matchesRole;
                    } catch (error) {
                        this.handleError(error, 'filtering row');
                        return false;
                    }
                });

                // Sort if needed
                if (this.sortKey && filtered.length > 0) {
                    filtered.sort((a, b) => {
                        try {
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
                        } catch (error) {
                            this.handleError(error, 'sorting data');
                            return 0;
                        }
                    });
                }

                this.displayedData = filtered;
                this.currentPage = 1;
            } catch (error) {
                this.handleError(error, 'applying filters');
                this.displayedData = [...(this.data || [])];
            }
        },

        sort(columnKey) {
            if (!columnKey) return;
            
            try {
                if (this.sortKey === columnKey) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortKey = columnKey;
                    this.sortDirection = 'asc';
                }
                this.applyFilters();
            } catch (error) {
                this.handleError(error, 'sorting column');
            }
        },

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
            if (value === null || value === undefined) return '-';
            if (value === '') return '-';
            
            try {
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
                
                if (key.toLowerCase() === 'price' || key.toLowerCase() === 'amount' || key.toLowerCase() === 'total_price' || key.toLowerCase() === 'cost_price' || key.toLowerCase() === 'selling_price') {
                    return TableUtils.formatCurrency(value);
                }
                
                if (key.toLowerCase().includes('date') && !key.toLowerCase().includes('_at')) {
                    return TableUtils.formatDate(value);
                }

                if (key.toLowerCase().includes('_at') || key.toLowerCase() === 'paid_at') {
                    return TableUtils.formatDateTime(value);
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
            } catch (error) {
                this.handleError(error, 'formatting value');
                return String(value);
            }
        },

        getStatusConfig(status) {
            const statusLower = (status || '').toLowerCase();
            
            const statusConfigs = {
                'active': { class: 'emerald', dot: 'bg-emerald-400' },
                'completed': { class: 'emerald', dot: 'bg-emerald-400' },
                'confirmed': { class: 'emerald', dot: 'bg-emerald-400' },
                'paid': { class: 'emerald', dot: 'bg-emerald-400' },
                'available': { class: 'emerald', dot: 'bg-emerald-400' },
                'published': { class: 'emerald', dot: 'bg-emerald-400' },
                
                'pending': { class: 'yellow', dot: 'bg-yellow-400' },
                'waiting': { class: 'yellow', dot: 'bg-yellow-400' },
                'scheduled': { class: 'yellow', dot: 'bg-yellow-400' },
                'priced': { class: 'yellow', dot: 'bg-yellow-400' },
                'low_stock': { class: 'yellow', dot: 'bg-yellow-400' },
                'draft': { class: 'yellow', dot: 'bg-yellow-400' },
                'unpaid': { class: 'yellow', dot: 'bg-yellow-400' },
                
                'in_progress': { class: 'blue', dot: 'bg-blue-400' },
                'processing': { class: 'blue', dot: 'bg-blue-400' },
                'working': { class: 'blue', dot: 'bg-blue-400' },
                
                'cancelled': { class: 'rose', dot: 'bg-rose-400' },
                'inactive': { class: 'rose', dot: 'bg-rose-400' },
                'failed': { class: 'rose', dot: 'bg-rose-400' },
                'rejected': { class: 'rose', dot: 'bg-rose-400' },
                'out_of_stock': { class: 'rose', dot: 'bg-rose-400' },
                'archived': { class: 'rose', dot: 'bg-rose-400' }
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
            try {
                if (action === 'add') {
                    this.openAddModal();
                } else {
                    const actionConfig = this.actions.find(a => a.label.toLowerCase() === action.toLowerCase());
                    if (actionConfig && window[actionConfig.onclick]) {
                        window[actionConfig.onclick](row);
                    }
                }
            } catch (error) {
                this.handleError(error, 'handling action');
            }
        },

        positionMenu(rowId) {
            try {
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
            } catch (error) {
                this.handleError(error, 'positioning menu');
            }
        },

        toggleMenu(rowId) {
            try {
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
            } catch (error) {
                this.handleError(error, 'toggling menu');
            }
        },

        closeAllMenus() {
            this.openMenuId = null;
            document.body.classList.remove('mobile-menu-open');
        },

        openAddModal() {
            this.showAddModal = true;
            this.resetForm();
            document.body.classList.add('modal-open');
            
            this.$nextTick(() => {
                const firstInput = document.querySelector('#modal-form input[type="text"], #modal-form input[type="email"], #modal-form select');
                if (firstInput) firstInput.focus();
            });
        },

        closeAddModal() {
            this.showAddModal = false;
            this.resetForm();
            document.body.classList.remove('modal-open');
        },

        resetForm() {
            const config = this.getFormConfig();
            this.formData = { ...config.defaultData };
            this.formErrors = {};
            this.isSubmitting = false;
            this.showPassword = false;
        },

        validateForm() {
            const errors = {};
            const config = this.getFormConfig();
            
            Object.entries(config.fields).forEach(([fieldName, fieldConfig]) => {
                const value = this.formData[fieldName];
                
                if (fieldConfig.required && (!value || value.toString().trim() === '')) {
                    errors[fieldName] = `${fieldConfig.label} is required`;
                    return;
                }
                
                if (!value || value.toString().trim() === '') {
                    return;
                }
                
                switch (fieldConfig.type) {
                    case 'email':
                        const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
                        if (!emailRegex.test(value.trim())) {
                            errors[fieldName] = 'Please enter a valid email address';
                        }
                        break;
                        
                    case 'tel':
                        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
                        const cleanPhone = value.replace(/[\s\-\(\)]/g, '');
                        if (!phoneRegex.test(cleanPhone)) {
                            errors[fieldName] = 'Please enter a valid phone number';
                        }
                        break;
                        
                    case 'password':
                        if (value.length < 6) {
                            errors[fieldName] = 'Password must be at least 6 characters';
                        } else if (value.length > 128) {
                            errors[fieldName] = 'Password must be less than 128 characters';
                        }
                        break;
                        
                    case 'number':
                        if (isNaN(parseFloat(value)) || parseFloat(value) < 0) {
                            errors[fieldName] = `${fieldConfig.label} must be a valid positive number`;
                        }
                        break;
                        
                    case 'text':
                    case 'textarea':
                        if (value.length > 500) {
                            errors[fieldName] = `${fieldConfig.label} must be less than 500 characters`;
                        }
                        if (fieldName === 'name' && this.formType === 'user' && !/^[a-zA-Z\s'-]+$/.test(value.trim())) {
                            errors[fieldName] = 'Name contains invalid characters';
                        }
                        break;
                }
            });

            this.formErrors = errors;
            return Object.keys(errors).length === 0;
        },

        async submitForm() {
            if (!this.validateForm()) {
                const firstErrorField = Object.keys(this.formErrors)[0];
                const errorElement = document.querySelector(`[name="${firstErrorField}"]`);
                if (errorElement) errorElement.focus();
                return;
            }

            this.isSubmitting = true;
            const config = this.getFormConfig();

            try {
                const formData = new FormData();
                
                Object.entries(config.fields).forEach(([fieldName, fieldConfig]) => {
                    const value = this.formData[fieldName];
                    if (value !== null && value !== undefined && value !== '') {
                        if (fieldConfig.type === 'checkbox' && Array.isArray(value)) {
                            formData.append(fieldName, JSON.stringify(value));
                        } else {
                            formData.append(fieldName, value);
                        }
                    }
                });

                if (this.formType === 'user' && this.formData.role) {
                    const roleMapping = {
                        'customer': 4,
                        'staff': 3,
                        'administrator': 2,
                        'superadmin': 1
                    };
                    formData.append('role_id', roleMapping[this.formData.role] || 4);
                }

                let csrfToken = this.getCSRFToken();
                if (!csrfToken) {
                    this.showNotification('CSRF token not found. Please refresh the page and try again.', 'error');
                    return;
                }

                const response = await fetch(config.endpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success || response.ok) {
                    const newItem = this.buildNewItemFromResponse(result);
                    this.data.unshift(newItem);
                    this.applyFilters();

                    this.showNotification(`${config.title.replace('Add New ', '')} added successfully! 🎉`, 'success');
                    this.closeAddModal();
                } else {
                    if (result.errors) {
                        this.formErrors = result.errors;
                        this.showNotification('Please fix the validation errors.', 'error');
                    } else {
                        this.showNotification(result.message || 'Error occurred. Please try again.', 'error');
                    }
                }

            } catch (error) {
                this.handleError(error, 'submitting form');
                this.showNotification('Error occurred. Please try again.', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },

        getCSRFToken() {
            const hiddenToken = document.getElementById('csrf-token-input');
            if (hiddenToken) return hiddenToken.value;
            
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) return metaToken.getAttribute('content');
            
            const inputToken = document.querySelector('input[name="_token"]');
            if (inputToken) return inputToken.value;
            
            if (window.Laravel) return window.Laravel.csrfToken;
            
            return null;
        },

        buildNewItemFromResponse(result) {
            const config = this.getFormConfig();
            let newItem = { id: Date.now(), created_at: new Date().toISOString() };
            
            const responseData = result.data || result.user || result.service || result.item || result;
            
            if (responseData && typeof responseData === 'object') {
                newItem = { ...newItem, ...responseData };
            } else {
                newItem = { ...newItem, ...this.formData };
            }
            
            if (this.formType === 'user') {
                newItem.role_name = newItem.role || this.formData.role;
                newItem.created_by_name = 'Current User';
                newItem.account_age = '0 days';
            }
            
            return newItem;
        },

        getFieldIcon(fieldName, fieldConfig) {
            const iconMap = {
                'name': 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                'email': 'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207',
                'phone': 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z',
                'password': 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                'role': 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                'status': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'price': 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
                'description': 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'date': 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                'default': 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
            };
            
            return iconMap[fieldName] || iconMap[fieldConfig.type] || iconMap.default;
        },

        handleError(error, context = '') {
            console.error(`DataTable Error ${context}:`, error);
            this.showNotification(`Error: ${error.message || 'Something went wrong'}`, 'error');
            
            if (window.errorLogger) {
                window.errorLogger.log(error, { context, component: 'DataTable' });
            }
        },

        showNotification(message, type = 'success') {
            try {
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
            } catch (error) {
                console.error('Error showing notification:', error);
            }
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

    .skeleton {
        @apply bg-slate-700/50 animate-pulse rounded;
    }

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
        transition: background-color 0.2s ease;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
    
    .custom-scrollbar::-webkit-scrollbar-corner {
        background: #1e293b;
    }

    @media (max-width: 640px) {
        .data-table-container {
            padding: 1rem;
        }
        
        .mobile-card {
            @apply block bg-slate-800 rounded-lg p-4 mb-4 shadow-sm;
        }
        
        .mobile-card td {
            @apply block py-1;
        }
        
        .mobile-card td::before {
            content: attr(data-label) ": ";
            @apply font-semibold text-slate-300;
        }
    }

    .mobile-menu-open,
    .modal-open {
        overflow: hidden;
    }

    .modal-overlay-highest {
        z-index: 99997 !important;
    }

    .modal-backdrop-highest {
        z-index: 99998 !important;
    }

    .modal-content-highest {
        z-index: 99999 !important;
    }

    .data-table-modal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        z-index: 99997 !important;
    }

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

    .form-input-premium input:focus,
    .form-input-premium textarea:focus,
    .form-input-premium select:focus {
        @apply ring-2 ring-sky-400/50 border-sky-400 shadow-lg shadow-sky-400/10;
    }

    .focus-ring {
        @apply focus:outline-none focus:ring-2 focus:ring-sky-400/50 focus:border-sky-400;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
</style>

<!-- Reusable Data Table Component -->
<div 
    x-data="dataTable(@js($data), @js($actions), {{ $pageSize }}, '{{ $colorScheme }}', @js($formType), @js($formConfig))"
    x-init="init()"
    class="data-table-container w-full bg-slate-900 text-slate-50 p-6 rounded-xl shadow-xl border border-slate-800 {{ $customClass }}"
>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf-token-input">
    
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
            <div class="relative w-full md:w-64">
                <input 
                    type="text" 
                    placeholder="Search {{ strtolower($title) }}..." 
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-50 placeholder-slate-400 focus-ring text-sm transition-all"
                    x-model="searchTerm"
                    @input="search"
                    aria-label="Search table data"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 absolute left-3 top-2.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.386a1 1 0 01-1.414 1.415l-4.387-4.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd" />
                </svg>
            </div>
            @endif
            
            <button 
                @click="handleAction({}, 'add')" 
                class="px-4 py-2 bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} hover:from-{{ $colorScheme === 'indigo' ? 'indigo-600' : 'sky-600' }} hover:to-{{ $colorScheme === 'indigo' ? 'purple-600' : 'cyan-600' }} text-white rounded-lg text-sm font-medium shadow-lg transition-all duration-200 hover:shadow-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }}/25 flex items-center gap-2 focus-ring"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span x-text="getFormConfig().title || 'Add New Item'"></span>
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-4 flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2">
            <label class="text-sm text-slate-300 font-medium" for="status-filter">Status:</label>
            <select 
                id="status-filter"
                x-model="statusFilter" 
                @change="filterStatus"
                class="border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-50 bg-slate-800 focus-ring transition-all"
                aria-label="Filter by status"
            >
                <option value="all">All statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="pending">Pending</option>
                <option value="available">Available</option>
                <option value="out_of_stock">Out of Stock</option>
                <option value="low_stock">Low Stock</option>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="archived">Archived</option>
            </select>
        </div>
        
        @if($showRoleFilter)
        <div class="flex items-center gap-2">
            <label class="text-sm text-slate-300 font-medium" for="role-filter">Role:</label>
            <select 
                id="role-filter"
                x-model="roleFilter" 
                @change="filterRole"
                class="border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-50 bg-slate-800 focus-ring transition-all"
                aria-label="Filter by role"
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

    <!-- Loading State -->
    <div x-show="shouldShowSkeleton" class="space-y-4">
        <div class="skeleton h-12 w-full"></div>
        <div class="skeleton h-8 w-3/4"></div>
        <div class="skeleton h-8 w-1/2"></div>
        <div class="skeleton h-8 w-5/6"></div>
    </div>

    <!-- Table -->
    <div x-show="!shouldShowSkeleton" class="overflow-x-auto rounded-xl shadow-lg border border-slate-700/50 custom-scrollbar">
        <table class="min-w-full text-sm text-left text-slate-50 rounded-xl overflow-hidden bg-slate-900" role="table" aria-label="{{ $title }}">
            <caption class="sr-only">{{ $title }} with <span x-text="totalRecords"></span> items</caption>
            <thead class="bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 text-slate-100 uppercase text-xs font-semibold tracking-wider border-b border-slate-600/50 shadow-sm">
                <tr role="row">
                    @foreach($columns as $column)
                    <th 
                        class="px-4 py-3 select-none hover:text-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} transition-all duration-200 whitespace-nowrap {{ $sortable ? 'cursor-pointer' : 'cursor-default' }} {{ $column['key'] === 'id' ? 'text-center' : '' }}"
                        @if($sortable) 
                            @click="sort('{{ $column['key'] }}')" 
                            @keydown.enter="sort('{{ $column['key'] }}')"
                            tabindex="0"
                            :aria-sort="sortKey === '{{ $column['key'] }}' ? (sortDirection === 'asc' ? 'ascending' : 'descending') : 'none'"
                            data-column="{{ $column['key'] }}"
                            role="columnheader"
                        @endif
                    >
                        <div class="flex items-center gap-1 {{ $column['key'] === 'id' ? 'justify-center' : '' }}">
                            <span class="font-medium">{{ $column['label'] }}</span>
                            @if($sortable)
                            <span x-show="sortKey === '{{ $column['key'] }}'" aria-hidden="true">
                                <svg x-show="sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                <svg x-show="sortDirection === 'desc'" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                            @endif
                        </div>
                    </th>
                    @endforeach
                    
                    @if(count($actions) > 0)
                    <th class="px-4 py-3 text-center w-16" role="columnheader">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <template x-for="(row, rowIndex) in paginatedData" :key="row.id">
                    <tr 
                        class="border-b border-slate-700/30 transition-all duration-200 {{ $hoverEffects ? 'hover:bg-slate-800/60 hover:shadow-sm' : '' }}"
                        :class="{{ $alternatingRows ? 'rowIndex % 2 === 0 ? \'bg-slate-900/50\' : \'bg-slate-800/20\'' : 'bg-slate-900/50' }}"
                        role="row"
                    >
                        @foreach($columns as $column)
                        <td 
                            class="px-4 py-3 text-slate-100 font-normal whitespace-nowrap"
                            :class="{ 'text-center': '{{ $column['key'] }}' === 'id' }"
                            role="cell"
                            data-label="{{ $column['label'] }}"
                        >
                            <template x-if="'{{ $column['key'] }}' === 'status' || '{{ $column['key'] }}' === 'payment_status'">
                                <span :class="statusClass(row['{{ $column['key'] }}'])" role="status">
                                    <span class="w-2 h-2 rounded-full" :class="getStatusDotColor(row['{{ $column['key'] }}'])" aria-hidden="true"></span>
                                    <span x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                                </span>
                            </template>
                            
                            <template x-if="'{{ $column['key'] }}' === 'price' || '{{ $column['key'] }}' === 'amount' || '{{ $column['key'] }}' === 'total_price' || '{{ $column['key'] }}' === 'cost_price' || '{{ $column['key'] }}' === 'selling_price'">
                                <span x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                            </template>
                            
                            <template x-if="'{{ $column['key'] }}' === 'description' || '{{ $column['key'] }}' === 'message' || '{{ $column['key'] }}' === 'notes'">
                                <span class="truncate max-w-xs block" :title="row['{{ $column['key'] }}']" x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                            </template>
                            
                            <template x-if="'{{ $column['key'] }}' === 'created_by'">
                                <span x-text="formatValue('{{ $column['key'] }}', row.created_by_name || row.created_by_user_name || row.created_by || 'System')"></span>
                            </template>
                            
                            <template x-if="'{{ $column['key'] }}' !== 'status' && '{{ $column['key'] }}' !== 'payment_status' && '{{ $column['key'] }}' !== 'price' && '{{ $column['key'] }}' !== 'amount' && '{{ $column['key'] }}' !== 'total_price' && '{{ $column['key'] }}' !== 'cost_price' && '{{ $column['key'] }}' !== 'selling_price' && '{{ $column['key'] }}' !== 'description' && '{{ $column['key'] }}' !== 'message' && '{{ $column['key'] }}' !== 'notes' && '{{ $column['key'] }}' !== 'created_by'">
                                <span x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                            </template>
                        </td>
                        @endforeach
                        
                        @if(count($actions) > 0)
                        <td class="px-4 py-3 text-center relative" role="cell">
                            <button 
                                :id="'action-btn-' + row.id"
                                @click="toggleMenu(row.id)" 
                                class="p-2 rounded-full hover:bg-slate-800 focus-ring transition-all duration-200"
                                aria-haspopup="true"
                                :aria-expanded="openMenuId === row.id"
                                aria-label="Row actions"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M6 10a2 2 0 114 0 2 2 0 01-4 0zm-6 0a2 2 0 114 0 2 2 0 01-4 0zm12 0a2 2 0 114 0 2 2 0 01-4 0z"/>
                                </svg>
                            </button>

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
                                :aria-labelledby="'action-btn-' + row.id"
                                tabindex="-1"
                            >
                                @foreach($actions as $action)
                                <button 
                                    @click="handleAction(row, '{{ strtolower($action['label']) }}'); closeAllMenus()" 
                                    class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700/80 hover:text-white transition-all duration-200 focus:outline-none focus:bg-slate-700/80 focus:text-white"
                                    role="menuitem"
                                    tabindex="-1"
                                >
                                    <span x-html="getActionIcon('{{ $action['label'] }}')" aria-hidden="true"></span>
                                    <span class="font-medium">{{ $action['label'] }}</span>
                                </button>
                                @endforeach
                            </div>

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
                                :aria-labelledby="'action-btn-' + row.id"
                            >
                                <div class="w-12 h-1.5 bg-slate-700 rounded-full mx-auto mb-4" aria-hidden="true"></div>
                                
                                @foreach($actions as $action)
                                <button 
                                    @click="handleAction(row, '{{ strtolower($action['label']) }}'); closeAllMenus()" 
                                    class="flex items-center gap-4 w-full text-left px-4 py-4 text-base text-slate-200 hover:bg-slate-800/50 rounded-lg transition-all duration-200 focus:outline-none focus:bg-slate-800/50"
                                    role="menuitem"
                                >
                                    <span x-html="getActionIcon('{{ $action['label'] }}')" aria-hidden="true"></span>
                                    <span class="font-medium">{{ $action['label'] }}</span>
                                </button>
                                @endforeach
                                
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
                
                <tr x-show="displayedData.length === 0 && !shouldShowSkeleton">
                    <td :colspan="{{ count($columns) + (count($actions) > 0 ? 1 : 0) }}" class="px-4 py-16 text-center text-slate-400" role="cell">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center" aria-hidden="true">
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
    <div class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4 bg-gradient-to-r from-slate-800/80 to-slate-700/80 border border-slate-600/50 rounded-lg px-4 py-3 shadow-lg backdrop-blur-sm" x-show="!shouldShowSkeleton">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="text-slate-400 text-sm">
                Showing 
                <span x-text="totalRecords > 0 ? (currentPage - 1) * pageSize + 1 : 0"></span> - 
                <span x-text="Math.min(currentPage * pageSize, totalRecords)"></span> 
                of <span x-text="totalRecords"></span>
            </div>
            
            <div class="flex items-center gap-2">
                <label class="text-slate-400 text-sm" for="page-size-select">Rows per page:</label>
                <select 
                    id="page-size-select"
                    x-model="pageSize" 
                    @change="changePageSize"
                    class="border border-slate-600 rounded-lg px-2 py-1 text-sm text-slate-50 bg-slate-700 focus-ring transition-all"
                >
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
        <div class="flex items-center gap-2" role="navigation" aria-label="Pagination">
            <button 
                @click="goToPage(currentPage - 1)" 
                :disabled="currentPage === 1"
                class="px-3 py-1.5 bg-slate-700/60 border border-slate-600/50 rounded-full text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-600/80 hover:text-slate-100 hover:border-slate-500 transition-all duration-200 text-sm focus-ring"
                aria-label="Previous page"
            >
                Prev
            </button>
            <template x-for="page in totalPages" :key="page">
                <button 
                    @click="goToPage(page)" 
                    :class="page === currentPage ? 'bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} text-white border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} shadow-lg shadow-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }}/25' : 'bg-slate-700/60 text-slate-300 hover:bg-slate-600/80 hover:border-slate-500'"
                    class="px-3 py-1.5 rounded-full border border-slate-600/50 text-sm transition-all duration-200 focus-ring"
                    x-text="page"
                    :aria-label="'Page ' + page"
                    :aria-current="page === currentPage ? 'page' : null"
                ></button>
            </template>
            <button 
                @click="goToPage(currentPage + 1)" 
                :disabled="currentPage === totalPages"
                class="px-3 py-1.5 bg-slate-700/60 border border-slate-600/50 rounded-full text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-600/80 hover:text-slate-100 hover:border-slate-500 transition-all duration-200 text-sm focus-ring"
                aria-label="Next page"
            >
                Next
            </button>
        </div>
    </div>
    @endif

    <!-- Professional Form Modal -->
    <div 
        x-show="showAddModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-trap="showAddModal"
        class="fixed inset-0 overflow-y-auto z-[99997] data-table-modal"
        style="display: none;"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-title"
        aria-describedby="modal-description"
    >
        <div 
            @click="closeAddModal()"
            class="fixed inset-0 bg-black/70 backdrop-blur-xl z-[99998] modal-backdrop-premium modal-backdrop-highest"
        ></div>

        <div class="relative flex min-h-full items-center justify-center p-4 z-[99999] modal-content-highest">
            <div 
                @click.stop
                class="modal-content modal-content-premium relative w-full transform overflow-hidden rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700/50 shadow-2xl shadow-black/50"
                :class="{
                    'max-w-lg': Object.keys(getFormConfig().fields || {}).length <= 4,
                    'max-w-2xl': Object.keys(getFormConfig().fields || {}).length > 4 && Object.keys(getFormConfig().fields || {}).length <= 8,
                    'max-w-3xl': Object.keys(getFormConfig().fields || {}).length > 8
                }"
            >
                <div class="relative bg-gradient-to-r from-slate-800/90 via-slate-700/90 to-slate-800/90 px-6 py-4 border-b border-slate-600/50 backdrop-blur-sm">
                    <button 
                        @click="closeAddModal()"
                        class="absolute top-3 right-3 p-2 text-slate-400 hover:text-slate-200 hover:bg-slate-700/50 rounded-xl transition-all duration-200 group focus-ring"
                        aria-label="Close modal"
                    >
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} rounded-xl flex items-center justify-center shadow-lg shadow-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }}/25" aria-hidden="true">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 id="modal-title" class="text-xl font-bold text-white" x-text="getFormConfig().title"></h2>
                            <p id="modal-description" class="text-slate-400 text-sm" x-text="getFormConfig().description"></p>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submitForm()" id="modal-form" class="relative">
                    <div class="px-6 py-5">
                        <div class="space-y-4">
                            <template x-for="(chunk, chunkIndex) in Object.entries(getFormConfig().fields || {}).reduce((acc, [key, field], index) => { 
                                const fieldsPerRow = Object.keys(getFormConfig().fields || {}).length <= 4 ? 1 : 2;
                                const chunkIndex = Math.floor(index / fieldsPerRow); 
                                if (!acc[chunkIndex]) acc[chunkIndex] = []; 
                                acc[chunkIndex].push([key, field]); 
                                return acc; 
                            }, [])" :key="chunkIndex">
                                <div :class="{
                                    'grid grid-cols-1 gap-4': Object.keys(getFormConfig().fields || {}).length <= 4,
                                    'grid grid-cols-1 md:grid-cols-2 gap-4': Object.keys(getFormConfig().fields || {}).length > 4
                                }">
                                    <template x-for="[fieldName, fieldConfig] in chunk" :key="fieldName">
                                        <div class="form-input-premium">
                                            <label class="block text-sm font-medium text-slate-200 mb-2 flex items-center gap-2" :for="'form-' + fieldName">
                                                <svg class="w-4 h-4 text-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getFieldIcon(fieldName, fieldConfig)"/>
                                                </svg>
                                                <span x-text="fieldConfig.label"></span>
                                                <span x-show="fieldConfig.required" class="text-red-400" aria-label="required">*</span>
                                            </label>

                                            <!-- Text Input -->
                                            <template x-if="fieldConfig.type === 'text'">
                                                <input 
                                                    :type="fieldConfig.type"
                                                    :id="'form-' + fieldName"
                                                    :name="fieldName"
                                                    x-model="formData[fieldName]"
                                                    class="w-full px-3 py-2.5 bg-slate-700/50 border border-slate-600/50 rounded-lg text-slate-100 placeholder-slate-400 text-sm transition-all duration-200 hover:border-slate-500 focus-ring"
                                                    :placeholder="'Enter ' + fieldConfig.label.toLowerCase()"
                                                    :required="fieldConfig.required"
                                                    :aria-describedby="fieldName + '-error'"
                                                    :aria-invalid="formErrors[fieldName] ? 'true' : 'false'"
                                                >
                                            </template>

                                            <!-- Email Input -->
                                            <template x-if="fieldConfig.type === 'email'">
                                                <input 
                                                    type="email"
                                                    :id="'form-' + fieldName"
                                                    :name="fieldName"
                                                    x-model="formData[fieldName]"
                                                    class="w-full px-3 py-2.5 bg-slate-700/50 border border-slate-600/50 rounded-lg text-slate-100 placeholder-slate-400 text-sm transition-all duration-200 hover:border-slate-500 focus-ring"
                                                    :placeholder="'Enter ' + fieldConfig.label.toLowerCase()"
                                                    :required="fieldConfig.required"
                                                    :aria-describedby="fieldName + '-error'"
                                                    :aria-invalid="formErrors[fieldName] ? 'true' : 'false'"
                                                >
                                            </template>

                                            <!-- Phone Input -->
                                            <template x-if="fieldConfig.type === 'tel'">
                                                <input 
                                                    type="tel"
                                                    :id="'form-' + fieldName"
                                                    :name="fieldName"
                                                    x-model="formData[fieldName]"
                                                    class="w-full px-3 py-2.5 bg-slate-700/50 border border-slate-600/50 rounded-lg text-slate-100 placeholder-slate-400 text-sm transition-all duration-200 hover:border-slate-500 focus-ring"
                                                    :placeholder="'Enter ' + fieldConfig.label.toLowerCase()"
                                                    :required="fieldConfig.required"
                                                    :aria-describedby="fieldName + '-error'"
                                                    :aria-invalid="formErrors[fieldName] ? 'true' : 'false'"
                                                >
                                            </template>

                                            <!-- Password Input -->
                                            <template x-if="fieldConfig.type === 'password'">
                                                <div class="relative">
                                                    <input 
                                                        :type="showPassword ? 'text' : 'password'"
                                                        :id="'form-' + fieldName"
                                                        :name="fieldName"
                                                        x-model="formData[fieldName]"
                                                        class="w-full px-3 py-2.5 bg-slate-700/50 border border-slate-600/50 rounded-lg text-slate-100 placeholder-slate-400 text-sm transition-all duration-200 hover:border-slate-500 focus-ring pr-10"
                                                        :placeholder="'Enter ' + fieldConfig.label.toLowerCase()"
                                                        :required="fieldConfig.required"
                                                        :aria-describedby="fieldName + '-error'"
                                                        :aria-invalid="formErrors[fieldName] ? 'true' : 'false'"
                                                    >
                                                    <button type="button" 
                                                            @click="showPassword = !showPassword"
                                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-300 transition-colors duration-200 focus-ring rounded"
                                                            :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                                            :aria-pressed="showPassword">
                                                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>

                                            <!-- Number Input -->
                                            <template x-if="fieldConfig.type === 'number'">
                                                <input 
                                                    type="number"
                                                    :step="fieldConfig.step || '1'"
                                                    :id="'form-' + fieldName"
                                                    :name="fieldName"
                                                    x-model="formData[fieldName]"
                                                    class="w-full px-3 py-2.5 bg-slate-700/50 border border-slate-600/50 rounded-lg text-slate-100 placeholder-slate-400 text-sm transition-all duration-200 hover:border-slate-500 focus-ring"
                                                    :placeholder="'Enter ' + fieldConfig.label.toLowerCase()"
                                                    :required="fieldConfig.required"
                                                    :aria-describedby="fieldName + '-error'"
                                                    :aria-invalid="formErrors[fieldName] ? 'true' : 'false'"
                                                >
                                            </template>

                                            <!-- Date Input -->
                                            <template x-if="fieldConfig.type === 'date'">
                                                <input 
                                                    type="date"
                                                    :id="'form-' + fieldName"
                                                    :name="fieldName"
                                                    x-model="formData[fieldName]"
                                                    class="w-full px-3 py-2.5 bg-slate-700/50 border border-slate-600/50 rounded-lg text-slate-100 text-sm transition-all duration-200 hover:border-slate-500 focus-ring"
                                                    :required="fieldConfig.required"
                                                    :aria-describedby="fieldName + '-error'"
                                                    :aria-invalid="formErrors[fieldName] ? 'true' : 'false'"
                                                >
                                            </template>

                                            <!-- DateTime Input -->
                                            <template x-if="fieldConfig.type === 'datetime-local'">
                                                <input 
                                                    type="datetime-local"
                                                    :id="'form-' + fieldName"
                                                    :name="fieldName"
                                                    x-model="formData[fieldName]"
                                                    class="w-full px-3 py-2.5 bg-slate-700/50 border border-slate-600/50 rounded-lg text-slate-100 text-sm transition-all duration-200 hover:border-slate-500 focus-ring"
                                                    :required="fieldConfig.required"
                                                    :aria-describedby="fieldName + '-error'"
                                                    :aria-invalid="formErrors[fieldName] ? 'true' : 'false'"
                                                >
                                            </template>

                                            <!-- Select Input -->
                                            <template x-if="fieldConfig.type === 'select'">
                                                <select 
                                                    :id="'form-' + fieldName"
                                                    :name="fieldName"
                                                    x-model="formData[fieldName]"
                                                    class="w-full px-3 py-2.5 bg-slate-700/50 border border-slate-600/50 rounded-lg text-slate-100 text-sm transition-all duration-200 hover:border-slate-500 focus-ring"
                                                    :required="fieldConfig.required"
                                                    :aria-describedby="fieldName + '-error'"
                                                    :aria-invalid="formErrors[fieldName] ? 'true' : 'false'"
                                                >
                                                    <option value="">Select an option</option>
                                                    <template x-for="option in fieldConfig.options || []" :key="option.value">
                                                        <option :value="option.value" x-text="option.label"></option>
                                                    </template>
                                                </select>
                                            </template>

                                            <!-- Textarea Input -->
                                            <template x-if="fieldConfig.type === 'textarea'">
                                                <textarea 
                                                    :id="'form-' + fieldName"
                                                    :name="fieldName"
                                                    x-model="formData[fieldName]"
                                                    class="w-full px-3 py-2.5 bg-slate-700/50 border border-slate-600/50 rounded-lg text-slate-100 placeholder-slate-400 text-sm transition-all duration-200 hover:border-slate-500 focus-ring"
                                                    :placeholder="'Enter ' + fieldConfig.label.toLowerCase()"
                                                    :rows="fieldConfig.rows || 3"
                                                    :required="fieldConfig.required"
                                                    :aria-describedby="fieldName + '-error'"
                                                    :aria-invalid="formErrors[fieldName] ? 'true' : 'false'"
                                                ></textarea>
                                            </template>

                                            <!-- Error Display -->
                                            <div x-show="formErrors[fieldName]" :id="fieldName + '-error'" class="mt-1.5 text-sm text-red-400 flex items-center gap-1.5" role="alert">
                                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span x-text="formErrors[fieldName]"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-slate-800/50 to-slate-700/50 px-6 py-4 border-t border-slate-600/50 backdrop-blur-sm">
                        <div class="flex justify-end items-center gap-3">
                            <button 
                                type="button"
                                @click="closeAddModal()"
                                class="px-4 py-2 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-slate-100 border border-slate-600/50 hover:border-slate-500 rounded-lg text-sm transition-all duration-200 font-medium focus-ring"
                                :disabled="isSubmitting"
                            >
                                Cancel
                            </button>

                            <button 
                                type="submit"
                                class="btn-premium flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-lg text-sm transition-all duration-200 font-semibold shadow-lg hover:shadow-emerald-500/25 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none focus-ring"
                                :disabled="isSubmitting"
                            >
                                <template x-if="isSubmitting">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <template x-if="!isSubmitting">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </template>
                                <span x-text="isSubmitting ? (getFormConfig().title.replace('Add New ', 'Adding ') + '...') : getFormConfig().title"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
