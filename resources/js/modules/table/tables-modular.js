/**
 * Modular Tables JavaScript - Compiled from ES6 modules
 * Professional SaaS Data Management
 * 
 * This file is compiled from the modular table system for browser compatibility
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func.apply(this, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function getNestedValue(obj, path) {
    return path.split('.').reduce((current, key) => current?.[key], obj);
}

function isDate(value) {
    return value instanceof Date || (typeof value === 'string' && !isNaN(Date.parse(value)));
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    try {
        return new Date(dateString).toLocaleDateString();
    } catch {
        return 'Invalid Date';
    }
}

function sortData(data, column, direction, getNestedValue, isDate) {
    return [...data].sort((a, b) => {
        let aVal = getNestedValue(a, column);
        let bVal = getNestedValue(b, column);
        
        if (aVal === null || aVal === undefined) return direction === 'asc' ? 1 : -1;
        if (bVal === null || bVal === undefined) return direction === 'asc' ? -1 : 1;
        
        if (isDate(aVal) && isDate(bVal)) {
            return direction === 'asc' ? new Date(aVal) - new Date(bVal) : new Date(bVal) - new Date(aVal);
        }
        
        if (typeof aVal === 'number' && typeof bVal === 'number') {
            return direction === 'asc' ? aVal - bVal : bVal - aVal;
        }
        
        const strA = String(aVal).toLowerCase();
        const strB = String(bVal).toLowerCase();
        return direction === 'asc' ? strA.localeCompare(strB) : strB.localeCompare(strA);
    });
}

function getPageNumbers(currentPage, totalPages, maxVisible = 7) {
    const pages = [];
    
    if (totalPages <= maxVisible) {
        for (let i = 1; i <= totalPages; i++) {
            pages.push(i);
        }
    } else {
        pages.push(1);
        if (currentPage > 4) pages.push('...');
        const start = Math.max(2, currentPage - 1);
        const end = Math.min(totalPages - 1, currentPage + 1);
        for (let i = start; i <= end; i++) pages.push(i);
        if (currentPage < totalPages - 3) pages.push('...');
        if (totalPages > 1) pages.push(totalPages);
    }
    
    return pages;
}

function validateTableData(data) {
    if (!Array.isArray(data)) {
        console.error('Table data must be an array');
        return [];
    }
    return data;
}


class BaseTable {
    constructor(data, pageSize = 10, options = {}) {
        this.originalData = validateTableData(data);
        this.filteredData = [];
        this.paginatedData = [];
        
        this.pageSize = Math.max(1, pageSize || 10);
        this.currentPage = 1;
        this.totalPages = 1;
        this.totalRecords = 0;
        this.startRecord = 0;
        this.endRecord = 0;
        
        this.searchQuery = '';
        this.filters = {};
        
        this.sortColumn = null;
        this.sortDirection = 'asc';
        
        this.isLoading = false;
        
        this.activeMenuRow = null;
        this.activeMenuTrigger = null;
        
        this.actionMenuManager = window.actionMenuManager || (typeof ActionMenuManager !== 'undefined' ? new ActionMenuManager() : null);
        
        this.options = {
            searchable: true,
            sortable: true,
            pagination: true,
            bulkActions: false,
            ...options
        };
        
        this.init();
    }
    
    init() {
        this.validateData();
        this.applyFilters();
        this.setupPortalSystem();
        this.setupEventListeners();
    }
    
    validateData() {
        this.originalData = validateTableData(this.originalData);
    }
    
    setupPortalSystem() {
    }
    
    openActionMenu(rowId, triggerElement, rowData) {
        if (!this.actionMenuManager) {
            console.error('ActionMenuManager not available');
            return;
        }
        
        const actions = this.getActionsForRow(rowData);
        this.actionMenuManager.openActionMenu(rowId, triggerElement, rowData, actions, (rowData, action) => {
            this.handleAction(rowData, action);
        });
    }
    
    getActionsForRow(rowData) {
        return [
            { key: 'view', label: 'View', icon: 'view' },
            { key: 'edit', label: 'Edit', icon: 'edit' },
            { key: 'delete', label: 'Delete', icon: 'delete' }
        ];
    }
    
    closeActionMenu() {
        if (this.actionMenuManager) {
            this.actionMenuManager.closeActionMenu();
        }
    }
    
    handleAction(rowData, action) {
        this.closeActionMenu();
        if (window[action.key]) {
            window[action.key](rowData);
        } else {
            console.log(`Action: ${action.key} for row:`, rowData);
        }
    }
    
    search() {
        this.currentPage = 1;
        this.applyFilters();
    }
    
    applyFilters() {
        let filtered = [...this.originalData];
        
        if (this.searchQuery?.trim()) {
            const query = this.searchQuery.toLowerCase().trim();
            filtered = filtered.filter(row => {
                return Object.values(row).some(value => 
                    String(value).toLowerCase().includes(query)
                );
            });
        }
        
        Object.entries(this.filters).forEach(([key, value]) => {
            if (value && value !== '') {
                filtered = filtered.filter(row => {
                    const rowValue = getNestedValue(row, key);
                    return String(rowValue).toLowerCase() === String(value).toLowerCase();
                });
            }
        });
        
        if (this.sortColumn) {
            filtered = sortData(filtered, this.sortColumn, this.sortDirection, getNestedValue, isDate);
        }
        
        this.filteredData = filtered;
        this.updatePagination(filtered);
    }
    
    sort(column) {
        if (this.sortColumn === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortColumn = column;
            this.sortDirection = 'asc';
        }
        this.currentPage = 1;
        this.applyFilters();
    }
    
    updatePagination(filteredData) {
        this.totalRecords = filteredData.length;
        this.totalPages = Math.max(1, Math.ceil(this.totalRecords / this.pageSize));
        
        if (this.currentPage > this.totalPages) {
            this.currentPage = Math.max(1, this.totalPages);
        }
        
        if (this.totalRecords === 0) {
            this.startRecord = 0;
            this.endRecord = 0;
        } else {
            this.startRecord = (this.currentPage - 1) * this.pageSize + 1;
            this.endRecord = Math.min(this.currentPage * this.pageSize, this.totalRecords);
        }
        
        const start = (this.currentPage - 1) * this.pageSize;
        const end = start + this.pageSize;
        this.paginatedData = filteredData.slice(start, end);
    }
    
    goToPage(page) {
        const targetPage = parseInt(page);
        if (targetPage >= 1 && targetPage <= this.totalPages) {
            this.currentPage = targetPage;
            this.updatePagination(this.filteredData);
        }
    }
    
    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.currentPage++;
            this.updatePagination(this.filteredData);
        }
    }
    
    previousPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.updatePagination(this.filteredData);
        }
    }
    
    changePageSize(newPageSize) {
        this.pageSize = parseInt(newPageSize);
        this.currentPage = 1;
        this.updatePagination(this.filteredData);
    }
    
    getPageNumbers() {
        return getPageNumbers(this.currentPage, this.totalPages);
    }
    
    setupEventListeners() {
    }
    
    clearAllFilters() {
        this.searchQuery = '';
        this.filters = {};
        this.currentPage = 1;
        this.applyFilters();
    }
    
    setFilter(key, value) {
        this.filters[key] = value;
        this.currentPage = 1;
        this.applyFilters();
    }
    
    getNestedValue(obj, path) {
        return getNestedValue(obj, path);
    }
    
    formatDate(dateString) {
        return formatDate(dateString);
    }
}

// Add debounced search method to BaseTable prototype
BaseTable.prototype.debouncedSearch = debounce(function() {
    this.currentPage = 1;
    this.applyFilters();
}, 300);


function usersTable(data, pageSize = 10) {
    const baseTable = new BaseTable(data, pageSize, {
        searchable: true,
        sortable: true,
        pagination: true,
        bulkActions: true
    });
    
    let dataFetcher = null;
    if (typeof UsersDataFetcher !== 'undefined') {
        dataFetcher = new UsersDataFetcher();
    }
    
    return {
        ...baseTable,
        
        statusFilter: '',
        roleFilter: '',
        dataFetcher: dataFetcher,
        
        init() {
            console.log('Users table initializing with data:', this.originalData);
            baseTable.validateData.call(this);
            this.applyFilters();
            this.setupPortalSystem();
            this.setupEventListeners();
            console.log('Users table initialized. Paginated data:', this.paginatedData);
        },
        
        validateData() {
            return baseTable.validateData.call(this);
        },
        
        formatDate(dateString) {
            return baseTable.formatDate.call(this, dateString);
        },
        
        getPageNumbers() {
            return baseTable.getPageNumbers.call(this);
        },
        
        openActionMenu(rowId, triggerElement, rowData) {
            if (!this.actionMenuManager) {
                console.error('ActionMenuManager not available');
                return;
            }
            
            const actions = this.getActionsForRow(rowData);
            this.actionMenuManager.openActionMenu(rowId, triggerElement, rowData, actions, (rowData, action) => {
                this.handleAction(rowData, action);
            });
        },
        
        sort(column) {
            return baseTable.sort.call(this, column);
        },
        
        search() {
            return baseTable.search.call(this);
        },
        
        debouncedSearch() {
            return baseTable.debouncedSearch.call(this);
        },
        
        clearAllFilters() {
            return baseTable.clearAllFilters.call(this);
        },
        
        setFilter(key, value) {
            return baseTable.setFilter.call(this, key, value);
        },
        
        goToPage(page) {
            return baseTable.goToPage.call(this, page);
        },
        
        nextPage() {
            return baseTable.nextPage.call(this);
        },
        
        previousPage() {
            return baseTable.previousPage.call(this);
        },
        
        changePageSize(newPageSize) {
            return baseTable.changePageSize.call(this, newPageSize);
        },
        
        getNestedValue(obj, path) {
            return baseTable.getNestedValue.call(this, obj, path);
        },
        
        updatePagination(filteredData) {
            return baseTable.updatePagination.call(this, filteredData);
        },
        
        setupPortalSystem() {
            return baseTable.setupPortalSystem.call(this);
        },
        
        setupEventListeners() {
            baseTable.setupEventListeners.call(this);
            
            // Listen for user-saved events to refresh the table
            document.addEventListener('user-saved', (event) => {
                console.log('User saved event received:', event.detail);
                this.refreshTable();
            });
        },
        
        async refreshTable() {
            console.log('Refreshing users table...');
            try {
                if (this.dataFetcher) {
                    // Use data fetcher if available
                    const newData = await this.dataFetcher.fetchUsers();
                    this.originalData = newData;
                } else {
                    // Fallback: reload the page data
                    const response = await fetch('/superadmin/users/fetch', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        if (data.success && data.users) {
                            this.originalData = data.users;
                        }
                    }
                }
                
                // Reapply filters and pagination
                this.applyFilters();
                console.log('Table refreshed successfully');
            } catch (error) {
                console.error('Error refreshing table:', error);
                // Fallback: reload the page
                window.location.reload();
            }
        },
        
        applyFilters() {
            let filtered = [...this.originalData];
            
            if (this.searchQuery?.trim()) {
                const query = this.searchQuery.toLowerCase().trim();
                filtered = filtered.filter(row => {
                    return Object.values(row).some(value => 
                        String(value).toLowerCase().includes(query)
                    );
                });
            }
            
            if (this.statusFilter && this.statusFilter !== '') {
                filtered = filtered.filter(row => {
                    return String(row.status).toLowerCase() === this.statusFilter.toLowerCase();
                });
            }
            
            if (this.roleFilter && this.roleFilter !== '') {
                filtered = filtered.filter(row => {
                    return String(row.role_name || row.role).toLowerCase() === this.roleFilter.toLowerCase();
                });
            }
            
            if (this.sortColumn) {
                filtered = sortData(filtered, this.sortColumn, this.sortDirection, getNestedValue, isDate);
            }
            
            this.filteredData = filtered;
            this.updatePagination(filtered);
        },
        
        clearAllFilters() {
            this.searchQuery = '';
            this.statusFilter = '';
            this.roleFilter = '';
            this.currentPage = 1;
            this.applyFilters();
        },
        
        getActionsForRow(rowData) {
            return [
                { key: 'viewUser', label: 'View', icon: 'view' },
                { key: 'editUser', label: 'Edit', icon: 'edit' },
                { key: 'toggleUserStatus', label: 'Toggle Status', icon: 'toggle' },
                { key: 'deleteUser', label: 'Delete', icon: 'delete' }
            ];
        },
        
        viewUser(user) {
            window.dispatchEvent(new CustomEvent('view-user', { 
                detail: { user: user } 
            }));
        },
        
        editUser(user) {
            console.log('editUser called with:', user);
            window.dispatchEvent(new CustomEvent('open-user-modal', { 
                detail: { action: 'edit', user: user } 
            }));
        },
        
        async toggleUserStatus(user) {
            if (!this.dataFetcher) {
                const newStatus = user.status === 'Active' ? 'Inactive' : 'Active';
                user.status = newStatus;
                this.showStatusChangeNotification(user.name, newStatus, 'success');
                this.applyFilters();
                return;
            }
            
            try {
                const response = await this.dataFetcher.toggleUserStatus(user.id);
                if (response.success) {
                    // Update the user status in the data
                    const newStatus = response.new_status === 'active' ? 'Active' : 'Inactive';
                    user.status = newStatus;
                    this.showStatusChangeNotification(user.name, newStatus, 'success');
                    this.applyFilters();
                } else {
                    this.showStatusChangeNotification(user.name, user.status, 'error', 'Failed to update user status');
                }
            } catch (error) {
                console.error('Error toggling user status:', error);
                this.showStatusChangeNotification(user.name, user.status, 'error', 'Network error occurred');
            }
        },
        
        showStatusChangeNotification(userName, status, type, customMessage = null) {
            // Use the modern notification system
            if (typeof window.showUserStatusNotification === 'function') {
                window.showUserStatusNotification(userName, status, type, customMessage);
            } else if (typeof window.showNotification === 'function') {
                // Fallback to modern notification system
                const isActive = status === 'Active';
                
                let title, message;
                
                if (type === 'success') {
                    title = 'Status Updated';
                    message = customMessage || `${userName}'s account has been ${isActive ? 'activated' : 'deactivated'}`;
                } else {
                    title = 'Update Failed';
                    message = customMessage || `Failed to update ${userName}'s account status`;
                }
                
                window.showNotification(type, message, {
                    title: title,
                    duration: type === 'success' ? 3000 : 5000
                });
            } else {
                // Final fallback to alert
                alert(customMessage || `${userName}'s status has been updated to ${status}`);
            }
        },
        
        
        async deleteUser(user) {
            // Open the delete confirmation modal using global function
            if (typeof window.openDeleteConfirmation === 'function') {
                window.openDeleteConfirmation(user, {
                    title: 'Delete User',
                    message: `Are you sure you want to delete ${user.name || user.email}?`,
                    additionalInfo: 'This action cannot be undone. All user data and associated records will be permanently removed.',
                    requireConfirmation: true,
                    confirmationText: 'DELETE',
                    onConfirm: async (userData) => {
                        return await this.performUserDeletion(userData);
                    }
                });
            } else {
                console.error('openDeleteConfirmation function not available');
            }
        },

        async performUserDeletion(user) {
            if (!this.dataFetcher) {
                const index = this.originalData.findIndex(item => item.id === user.id);
                if (index !== -1) {
                    this.originalData.splice(index, 1);
                    this.applyFilters();
                    this.showNotification('User deleted successfully (local update)', 'success');
                }
                return;
            }
            
            try {
                await this.dataFetcher.deleteUser(user.id);
                const index = this.originalData.findIndex(item => item.id === user.id);
                if (index !== -1) {
                    this.originalData.splice(index, 1);
                    this.applyFilters();
                    this.showNotification('User deleted successfully', 'success');
                }
            } catch (error) {
                console.error('Error deleting user:', error);
                this.showNotification('Error deleting user. Please try again.', 'error');
                throw error; // Re-throw to let the modal handle the error state
            }
        },
        
        addNewUser() {
            if (typeof openAddUserModal === 'function') {
                openAddUserModal();
            }
        },

        refresh() {
            if (this.dataFetcher) {
                this.dataFetcher.fetchData();
            } else {
                window.location.reload();
            }
        },
        
        handleSearch() {
            this.debouncedSearch();
        },
        
        handleFilterChange(filterKey, value) {
            this.setFilter(filterKey, value);
        },
        
        clearSearch() {
            this.searchQuery = '';
            this.search();
        },
        
        handleAction(rowData, action) {
            this.closeActionMenu();
            if (this[action.key]) {
                this[action.key](rowData);
            } else {
                console.log(`Action method not found: ${action.key} for row:`, rowData);
            }
        },
        
        closeActionMenu() {
            if (this.actionMenuManager) {
                this.actionMenuManager.closeActionMenu();
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
    };
}


function ordersTable(data, pageSize = 10) {
    const baseTable = new BaseTable(data, pageSize, {
        searchable: true,
        sortable: true,
        pagination: true,
        bulkActions: false
    });
    
    return {
        ...baseTable,
        
        statusFilter: '',
        paymentFilter: '',
        
        init() {
            console.log('Orders table initializing with data:', this.originalData);
            baseTable.validateData.call(this);
            this.applyFilters();
            this.setupPortalSystem();
            this.setupEventListeners();
            console.log('Orders table initialized. Paginated data:', this.paginatedData);
        },
        
        validateData() {
            return baseTable.validateData.call(this);
        },
        
        formatDate(dateString) {
            return baseTable.formatDate.call(this, dateString);
        },
        
        getPageNumbers() {
            return baseTable.getPageNumbers.call(this);
        },
        
        openActionMenu(rowId, triggerElement, rowData) {
            if (!this.actionMenuManager) {
                console.error('ActionMenuManager not available');
                return;
            }
            
            const actions = this.getActionsForRow(rowData);
            this.actionMenuManager.openActionMenu(rowId, triggerElement, rowData, actions, (rowData, action) => {
                this.handleAction(rowData, action);
            });
        },
        
        sort(column) {
            return baseTable.sort.call(this, column);
        },
        
        search() {
            return baseTable.search.call(this);
        },
        
        debouncedSearch() {
            return baseTable.debouncedSearch.call(this);
        },
        
        clearAllFilters() {
            this.searchQuery = '';
            this.statusFilter = '';
            this.paymentFilter = '';
            this.currentPage = 1;
            this.applyFilters();
        },
        
        setFilter(key, value) {
            return baseTable.setFilter.call(this, key, value);
        },
        
        goToPage(page) {
            return baseTable.goToPage.call(this, page);
        },
        
        nextPage() {
            return baseTable.nextPage.call(this);
        },
        
        previousPage() {
            return baseTable.previousPage.call(this);
        },
        
        changePageSize(newPageSize) {
            return baseTable.changePageSize.call(this, newPageSize);
        },
        
        getNestedValue(obj, path) {
            return baseTable.getNestedValue.call(this, obj, path);
        },
        
        updatePagination(filteredData) {
            return baseTable.updatePagination.call(this, filteredData);
        },
        
        setupPortalSystem() {
            return baseTable.setupPortalSystem.call(this);
        },
        
        setupEventListeners() {
            baseTable.setupEventListeners.call(this);
            
            // Listen for order-saved events to refresh the table
            document.addEventListener('order-saved', (event) => {
                console.log('Order saved event received:', event.detail);
                this.refreshTable();
            });
        },
        
        async refreshTable() {
            console.log('Refreshing orders table...');
            try {
                // Reload the page data
                const response = await fetch('/staff/orders/fetch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.orders) {
                        this.originalData = data.orders;
                    }
                }
                
                // Reapply filters and pagination
                this.applyFilters();
                console.log('Table refreshed successfully');
            } catch (error) {
                console.error('Error refreshing table:', error);
                // Fallback: reload the page
                window.location.reload();
            }
        },
        
        applyFilters() {
            let filtered = [...this.originalData];
            
            if (this.searchQuery?.trim()) {
                const query = this.searchQuery.toLowerCase().trim();
                filtered = filtered.filter(row => {
                    return Object.values(row).some(value => 
                        String(value).toLowerCase().includes(query)
                    );
                });
            }
            
            if (this.statusFilter && this.statusFilter !== '') {
                filtered = filtered.filter(row => {
                    return String(row.status).toLowerCase() === this.statusFilter.toLowerCase();
                });
            }
            
            if (this.paymentFilter && this.paymentFilter !== '') {
                filtered = filtered.filter(row => {
                    return String(row.payment_status).toLowerCase() === this.paymentFilter.toLowerCase();
                });
            }
            
            if (this.sortColumn) {
                filtered = sortData(filtered, this.sortColumn, this.sortDirection, getNestedValue, isDate);
            }
            
            this.filteredData = filtered;
            this.updatePagination(filtered);
        },
        
        getActionsForRow(rowData) {
            return [
                { key: 'viewOrder', label: 'View', icon: 'view' },
                { key: 'editOrder', label: 'Edit', icon: 'edit' },
                { key: 'updateStatus', label: 'Update Status', icon: 'update' },
                { key: 'deleteOrder', label: 'Delete', icon: 'delete' }
            ];
        },
        
        viewOrder(order) {
            window.dispatchEvent(new CustomEvent('view-order', { 
                detail: { order: order } 
            }));
        },
        
        editOrder(order) {
            window.dispatchEvent(new CustomEvent('edit-order', { 
                detail: { order: order } 
            }));
        },
        
        updateStatus(order) {
            window.dispatchEvent(new CustomEvent('update-order-status', { 
                detail: { order: order } 
            }));
        },
        
        deleteOrder(order) {
            window.openDeleteConfirmation(order, {
                title: 'Delete Order',
                message: `Are you sure you want to delete order #${order.id}? This action cannot be undone.`,
                confirmText: 'Delete Order',
                cancelText: 'Cancel',
                onConfirm: () => {
                    console.log('Deleting order:', order);
                    // Handle delete logic here
                }
            });
        },
        
        handleAction(rowData, action) {
            switch(action.key) {
                case 'viewOrder':
                    this.viewOrder(rowData);
                    break;
                case 'editOrder':
                    this.editOrder(rowData);
                    break;
                case 'updateStatus':
                    this.updateStatus(rowData);
                    break;
                case 'deleteOrder':
                    this.deleteOrder(rowData);
                    break;
                default:
                    console.log('Unknown action:', action.key);
            }
        }
    };
}


window.BaseTable = BaseTable;
window.usersTable = usersTable;
window.ordersTable = ordersTable;

// Generic dataTable function for reusable data table component
function dataTable(data, columns, actions, pageSize = 10) {
    return {
        // Core data
        originalData: data || [],
        columns: columns || [],
        actions: actions || [],
        pageSize: pageSize,
        
        // State
        searchQuery: '',
        statusFilter: '',
        paymentFilter: '',
        serviceFilter: '',
        roleFilter: '',
        sortColumn: '',
        sortDirection: 'asc',
        currentPage: 1,
        isLoading: false,  // Only true during full table refresh, not per-row actions
        rowLoadingId: null,  // Track which specific row is being updated
        activeMenuRow: null,
        
        // Computed data
        get filteredData() {
            let filtered = [...this.originalData];
            
            // Search filter
            if (this.searchQuery?.trim()) {
                const query = this.searchQuery.toLowerCase().trim();
                filtered = filtered.filter(row => {
                    return Object.values(row).some(value => 
                        String(value).toLowerCase().includes(query)
                    );
                });
            }
            
            // Status filter
            if (this.statusFilter && this.statusFilter !== '') {
                filtered = filtered.filter(row => {
                    const s = String(row.status ?? '').trim().toLowerCase();
                    return s === this.statusFilter.toLowerCase();
                });
            }
            
            // Payment filter
            if (this.paymentFilter && this.paymentFilter !== '') {
                filtered = filtered.filter(row => {
                    const s = String(row.payment_status ?? '').trim().toLowerCase();
                    return s === this.paymentFilter.toLowerCase();
                });
            }
            
            // Service filter
            if (this.serviceFilter && this.serviceFilter !== '') {
                filtered = filtered.filter(row => {
                    const s = String(row.service_name ?? '').trim().toLowerCase();
                    return s === this.serviceFilter.toLowerCase();
                });
            }
            
            // Role filter
            if (this.roleFilter && this.roleFilter !== '') {
                filtered = filtered.filter(row => {
                    const s = String(row.role ?? '').trim().toLowerCase();
                    return s === this.roleFilter.toLowerCase();
                });
            }
            
            // Sorting
            if (this.sortColumn) {
                filtered = sortData(filtered, this.sortColumn, this.sortDirection, getNestedValue, isDate);
            }
            
            return filtered;
        },
        
        get totalItems() {
            return this.filteredData.length;
        },
        
        get totalRecords() {
            return this.filteredData.length;
        },
        
        get totalPages() {
            return Math.max(1, Math.ceil(this.totalItems / this.pageSize));
        },
        
        get paginatedData() {
            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + this.pageSize;
            return this.filteredData.slice(start, end);
        },
        
        get startRecord() {
            return this.totalItems === 0 ? 0 : (this.currentPage - 1) * this.pageSize + 1;
        },
        
        get endRecord() {
            return Math.min(this.currentPage * this.pageSize, this.totalItems);
        },
        
        // Methods
        init() {
            this.validateData();
        },
        
        validateData() {
            if (!Array.isArray(this.originalData)) {
                console.warn('DataTable: originalData is not an array, converting...');
                this.originalData = [];
            }
        },
        
        sort(columnKey) {
            if (this.sortColumn === columnKey) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = columnKey;
                this.sortDirection = 'asc';
            }
        },
        
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
        
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        
        getPageNumbers() {
            return getPageNumbers(this.currentPage, this.totalPages);
        },
        
        handleFilterChange(name, value) {
            this[name] = value;
            this.currentPage = 1;
        },
        
        handleSearch() {
            this.currentPage = 1;
        },
        
        clearSearch() {
            this.searchQuery = '';
            this.currentPage = 1;
        },
        
        clearAllFilters() {
            this.searchQuery = '';
            this.statusFilter = '';
            this.paymentFilter = '';
            this.serviceFilter = '';
            this.roleFilter = '';
            this.currentPage = 1;
        },
        
        openActionMenu(rowId, triggerElement, rowData) {
            this.activeMenuRow = rowId;
            // Handle action menu opening logic here
            console.log('Opening action menu for row:', rowId, rowData);
        },
        
                closeActionMenu() {
                    this.activeMenuRow = null;
                },
                
                handleAction(actionKey, rowData) {
                    this.closeActionMenu();
                    
                    // Handle different action types
                    switch (actionKey) {
                        case 'view':
                        case 'viewUser':
                            this.viewDetails(rowData);
                            break;
                        case 'edit':
                        case 'editUser':
                            this.editUser(rowData);
                            break;
                        case 'delete':
                        case 'deleteUser':
                            this.deleteUser(rowData);
                            break;
                        case 'toggle':
                        case 'toggleUserStatus':
                        case 'deactivateUser':
                            this.toggleUserStatus(rowData);
                            break;
                        case 'viewUserActivity':
                            this.viewUserActivity(rowData);
                            break;
                        case 'resetUserPassword':
                            this.resetUserPassword(rowData);
                            break;
                        case 'approve':
                            this.approveSchedule(rowData);
                            break;
                        case 'reject':
                            this.rejectSchedule(rowData);
                            break;
                        case 'add_price':
                            this.addPricing(rowData);
                            break;
                        case 'start_processing':
                            this.startProcessing(rowData);
                            break;
                        case 'cancel':
                            this.cancelSchedule(rowData);
                            break;
                        case 'mark_ready':
                            this.markReadyForPickup(rowData);
                            break;
                        case 'mark_completed':
                            this.markCompleted(rowData);
                            break;
                        default:
                            console.log('Unknown action:', actionKey, rowData);
                    }
                },
                
                getActionIcon(icon) {
                    const icons = {
                        // Status Icons
                        'clock': 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
                        'check-circle': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'refresh-cw': 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                        'package': 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                        'check-circle-2': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'x-circle': 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                        
                        // Action Icons
                        view: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
                        eye: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
                        edit: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                        toggle: 'M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                        check: 'M5 13l4 4L19 7',
                        times: 'M6 18L18 6M6 6l12 12',
                        key: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
                        delete: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
                        copy: 'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z',
                        download: 'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        settings: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                        refresh: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                        archive: 'M5 8l6 6 6-6M5 8h14M5 8l6-6 6 6',
                        restore: 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6'
                    };
                    return icons[icon] || 'M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z';
                },
                
                getActionIconSvg(icon) {
                    const pathD = this.getActionIcon(icon);
                    return `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="action-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${pathD}"></path></svg>`;
                },
                
                viewDetails(rowData) {
                    // Open view modal or navigate to details page
                    console.log('View details for:', rowData);
                    // You can implement modal or navigation here
                },
                
                approveSchedule(rowData) {
                    if (confirm('Are you sure you want to approve this schedule?')) {
                        this.makeRequest(`/staff/schedules/${rowData.id}/approve`, 'POST', {}, 
                            'Schedule approved successfully!', rowData.id);
                    }
                },
                
                rejectSchedule(rowData) {
                    const reason = prompt('Please provide a reason for rejection:');
                    if (reason !== null) {
                        this.makeRequest(`/staff/schedules/${rowData.id}/reject`, 'POST', { reason }, 
                            'Schedule rejected successfully!', rowData.id);
                    }
                },
                
                addPricing(rowData) {
                    // Open pricing modal
                    const weight = prompt('Enter weight (kg):');
                    const price = prompt('Enter price:');
                    
                    if (weight !== null && price !== null && weight && price) {
                        this.makeRequest(`/staff/schedules/${rowData.id}/pricing`, 'PUT', { 
                            weight: parseFloat(weight), 
                            price: parseFloat(price) 
                        }, 'Pricing updated successfully!', rowData.id);
                    }
                },
                
                startProcessing(rowData) {
                    if (confirm('Start processing this schedule?')) {
                        this.makeRequest(`/staff/schedules/${rowData.id}/start-processing`, 'POST', {}, 
                            'Processing started successfully!', rowData.id);
                    }
                },
                
                cancelSchedule(rowData) {
                    const reason = prompt('Cancellation reason (optional):');
                    this.makeRequest(`/staff/schedules/${rowData.id}/cancel`, 'POST', { 
                        cancellation_reason: reason 
                    }, 'Schedule cancelled successfully!', rowData.id);
                },
                
                markReadyForPickup(rowData) {
                    if (confirm('Mark as ready for pickup?')) {
                        this.makeRequest(`/staff/schedules/${rowData.id}/mark-ready`, 'POST', {}, 
                            'Marked as ready for pickup!', rowData.id);
                    }
                },
                
                markCompleted(rowData) {
                    if (confirm('Mark as completed?')) {
                        this.makeRequest(`/staff/schedules/${rowData.id}/mark-completed`, 'POST', {}, 
                            'Schedule completed successfully!', rowData.id);
                    }
                },
                
                makeRequest(url, method, data, successMessage, rowId) {
                    // Use row-level loading instead of global loading for per-row actions
                    this.rowLoadingId = rowId;
                    
                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: method !== 'GET' ? JSON.stringify(data) : null
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            // Show success message
                            this.showNotification(successMessage, 'success');
                            
                            // Refresh the data without showing full table loading
                            this.refreshData();
                            
                            // Refresh the statistics
                            if (typeof loadScheduleStats === 'function') {
                                loadScheduleStats();
                            }
                        } else {
                            this.showNotification(result.message || 'An error occurred', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showNotification('An error occurred', 'error');
                    })
                    .finally(() => {
                        this.rowLoadingId = null;
                    });
                },
                
                refreshData() {
                    // Check if we need to fetch all schedules (including completed/cancelled)
                    const needsAllData = this.statusFilter && ['completed', 'cancelled'].includes(this.statusFilter);
                    const endpoint = needsAllData ? '/staff/schedules/all' : '/staff/schedules/fetch';
                    const method = needsAllData ? 'GET' : 'POST';
                    
                    // Refresh the table data
                    fetch(endpoint, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        console.log('Refresh response status:', response.status);
                        console.log('Refresh response headers:', response.headers.get('content-type'));
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Response is not JSON. Content-Type: ' + contentType);
                        }
                        
                        return response.json();
                    })
                    .then(result => {
                        console.log('Refresh result:', result);
                        if (result.success) {
                            this.originalData = result.data || [];
                            console.log('Refreshed table data:', this.originalData);
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing data:', error);
                        console.error('Error details:', {
                            message: error.message,
                            stack: error.stack
                        });
                    });
                },
                
                showNotification(message, type = 'info') {
                    // Simple notification - you can enhance this with a proper notification system
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                        type === 'success' ? 'bg-green-500 text-white' :
                        type === 'error' ? 'bg-red-500 text-white' :
                        'bg-blue-500 text-white'
                    }`;
                    notification.textContent = message;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                },
                
                handleFilterChange(filterType, value) {
                    // Handle status filter changes
                    if (filterType === 'statusFilter') {
                        this.statusFilter = value;
                        
                        // If switching to completed/cancelled, fetch all data
                        if (['completed', 'cancelled'].includes(value)) {
                            this.fetchAllSchedules();
                        } else {
                            // If switching away from completed/cancelled, fetch active data
                            this.fetchActiveSchedules();
                        }
                    } else {
                        // Handle other filters normally
                        this[filterType] = value;
                        this.applyFilters();
                    }
                },
                
                fetchAllSchedules() {
                    fetch('/staff/schedules/all', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            this.originalData = result.schedules || [];
                            this.applyFilters();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching all schedules:', error);
                    });
                },
                
                fetchActiveSchedules() {
                    fetch('/staff/schedules/fetch', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            this.originalData = result.schedules || [];
                            this.applyFilters();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching active schedules:', error);
                    });
                },
                
                // User Management Methods
                editUser(rowData) {
                    console.log('Edit user:', rowData);
                    window.location.href = `/admin/users/${rowData.id}/edit`;
                },
                
                deleteUser(rowData) {
                    if (confirm(`Are you sure you want to delete ${rowData.name}?`)) {
                        console.log('Delete user:', rowData);
                        // Implement delete logic here
                    }
                },
                
                toggleUserStatus(rowData) {
                    const action = rowData.status === 'Active' ? 'deactivate' : 'activate';
                    if (confirm(`Are you sure you want to ${action} ${rowData.name}?`)) {
                        console.log(`${action} user:`, rowData);
                        // Implement toggle logic here
                    }
                },
                
                viewUserActivity(rowData) {
                    console.log('View user activity:', rowData);
                    // Implement view activity logic here
                },
                
                resetUserPassword(rowData) {
                    if (confirm(`Are you sure you want to reset password for ${rowData.name}?`)) {
                        console.log('Reset password for user:', rowData);
                        // Implement reset password logic here
                    }
                }
    };
}

window.dataTable = dataTable;

