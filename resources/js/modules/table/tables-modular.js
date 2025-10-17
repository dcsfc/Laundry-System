/**
 * Modular Tables JavaScript - Compiled from ES6 modules
 * Professional SaaS Data Management
 * 
 * This file is compiled from the modular table system for browser compatibility
 */

// ========== UTILITIES ==========

// Debounce utility for performance optimization
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

// Get nested value from object using dot notation
function getNestedValue(obj, path) {
    return path.split('.').reduce((current, key) => current?.[key], obj);
}

// Check if a value is a valid date
function isDate(value) {
    return value instanceof Date || (typeof value === 'string' && !isNaN(Date.parse(value)));
}

// Format date string for display
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    try {
        return new Date(dateString).toLocaleDateString();
    } catch {
        return 'Invalid Date';
    }
}

// Sort data by column with direction
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

// Generate page numbers for pagination
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

// Validate table data
function validateTableData(data) {
    if (!Array.isArray(data)) {
        console.error('Table data must be an array');
        return [];
    }
    return data;
}

// ========== ACTION MENU MANAGER ==========
// ActionMenuManager is now in a separate file: action-menu.js

// ========== BASE TABLE CLASS ==========

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
        // Portal system is now handled by ActionMenuManager
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
        // Event listeners are now handled by ActionMenuManager
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

// ========== USERS TABLE ==========

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

// ========== ORDERS TABLE ==========

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

// ========== GLOBAL EXPORTS ==========

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
                            this.viewDetails(rowData);
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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            this.originalData = result.schedules || [];
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing data:', error);
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
                }
    };
}

window.dataTable = dataTable;

// Note: ActionMenuManager is now in action-menu.js
