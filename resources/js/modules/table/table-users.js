/**
 * Table Users - User management specific table implementation
 * @module table-users
 */

import { BaseTable } from './table-core.js';

/**
 * Users Table - Specific implementation for user management
 * @param {Array} data - Initial user data
 * @param {number} pageSize - Number of users per page
 * @returns {Object} Users table instance
 */
export function usersTable(data, pageSize = 10) {
    const baseTable = new BaseTable(data, pageSize, {
        searchable: true,
        sortable: true,
        pagination: true,
        bulkActions: true
    });
    
    // Initialize data fetcher (check if it exists)
    let dataFetcher = null;
    if (typeof UsersDataFetcher !== 'undefined') {
        dataFetcher = new UsersDataFetcher();
    }
    
    return {
        ...baseTable,
        
        // User-specific filters
        statusFilter: '',
        roleFilter: '',
        
        // Data fetcher instance
        dataFetcher: dataFetcher,
        
        /**
         * Initialize the users table
         */
        init() {
            console.log('Users table initializing with data:', this.originalData);
            // Call validateData directly from baseTable
            baseTable.validateData.call(this);
            this.applyFilters();
            this.setupPortalSystem();
            this.setupEventListeners();
            console.log('Users table initialized. Paginated data:', this.paginatedData);
        },
        
        /**
         * Expose methods for Alpine.js
         */
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
            return baseTable.openActionMenu.call(this, rowId, triggerElement, rowData);
        },
        
        // Additional methods needed for templates
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
        
        /**
         * Apply filters with user-specific logic
         */
        applyFilters() {
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
            
            // User-specific filters
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
            
            // Apply sorting
            if (this.sortColumn) {
                filtered = this.sortData(filtered, this.sortColumn, this.sortDirection);
            }
            
            this.filteredData = filtered;
            this.updatePagination(filtered);
        },
        
        /**
         * Clear all filters including user-specific ones
         */
        clearAllFilters() {
            this.searchQuery = '';
            this.statusFilter = '';
            this.roleFilter = '';
            this.currentPage = 1;
            this.applyFilters();
        },
        
        /**
         * Override getActionsForRow for user-specific actions
         * @param {Object} rowData - User data
         * @returns {Array} Array of action objects
         */
        getActionsForRow(rowData) {
            return [
                { key: 'viewUser', label: 'View', icon: 'view' },
                { key: 'editUser', label: 'Edit', icon: 'edit' },
                { key: 'toggleUserStatus', label: 'Toggle Status', icon: 'toggle' },
                { key: 'deleteUser', label: 'Delete', icon: 'delete' }
            ];
        },
        
        // User-specific actions
        /**
         * View user details
         * @param {Object} user - User data
         */
        viewUser(user) {
            alert(`Viewing user: ${user.name || user.email}`);
        },
        
        /**
         * Edit user
         * @param {Object} user - User data
         */
        editUser(user) {
            console.log('editUser called with:', user);
            
            // Use Alpine.js $dispatch to communicate with the modal
            this.$dispatch('open-user-modal', { action: 'edit', user: user });
        },
        
        /**
         * Toggle user status
         * @param {Object} user - User data
         */
        async toggleUserStatus(user) {
            if (!this.dataFetcher) {
                // Fallback to local update if data fetcher is not available
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
        
        
        /**
         * Delete user
         * @param {Object} user - User data
         */
        async deleteUser(user) {
            if (confirm(`Are you sure you want to delete ${user.name || user.email}? This action cannot be undone.`)) {
                if (!this.dataFetcher) {
                    // Fallback to local delete if data fetcher is not available
                    const index = this.originalData.findIndex(item => item.id === user.id);
                    if (index !== -1) {
                        this.originalData.splice(index, 1);
                        this.applyFilters();
                        alert('User deleted successfully (local update)');
                    }
                    return;
                }
                
                try {
                    await this.dataFetcher.deleteUser(user.id);
                    const index = this.originalData.findIndex(item => item.id === user.id);
                    if (index !== -1) {
                        this.originalData.splice(index, 1);
                        this.applyFilters();
                        alert('User deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting user:', error);
                    alert('Error deleting user. Please try again.');
                }
            }
        },
        
        /**
         * Add new user
         */
        addNewUser() {
            // Redirect to the global function that works
            if (typeof openAddUserModal === 'function') {
                openAddUserModal();
            }
        },

        /**
         * Refresh table data
         */
        refresh() {
            // Refresh the table data
            if (this.dataFetcher) {
                this.dataFetcher.fetchData();
            } else {
                // Fallback: reload the page
                window.location.reload();
            }
        }
    };
}
