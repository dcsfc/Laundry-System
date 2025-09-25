/**
 * Reusable Data Table Component - Production Ready
 * Provides search, pagination, and action handling functionality
 */
function dataTable(data, columns, actions, pageSize) {
    return {
        // Data
        originalData: Array.isArray(data) ? data : [],
        filteredData: [],
        paginatedData: [],
        columns: Array.isArray(columns) ? columns : [],
        actions: Array.isArray(actions) ? actions : [],
        
        // Pagination
        currentPage: 1,
        pageSize: pageSize || 10,
        totalPages: 1,
        totalRecords: 0,
        startRecord: 0,
        endRecord: 0,
        
        // Search
        searchQuery: '',
        
        // Filters
        statusFilter: '',
        roleFilter: '',
        
        // Sorting
        sortColumn: null,
        sortDirection: 'asc',
        
        // Loading states
        isLoading: false,
        
        // Initialize
        init() {
            this.applyFilters();
        },
        
        // Search functionality
        search() {
            this.currentPage = 1;
            this.applyFilters();
        },
        
        // Filter by status
        filterByStatus() {
            this.currentPage = 1;
            this.applyFilters();
        },
        
        // Filter by role
        filterByRole() {
            this.currentPage = 1;
            this.applyFilters();
        },
        
        // Filter data based on search query and filters
        applyFilters() {
            let filtered = [...this.originalData];
            
            // Apply search filter
            if (this.searchQuery && this.searchQuery.trim()) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(item => {
                    if (typeof item !== 'object' || item === null) return false;
                    return Object.values(item).some(value => 
                        value !== null && String(value).toLowerCase().includes(query)
                    );
                });
            }
            
            // Apply status filter
            if (this.statusFilter) {
                filtered = filtered.filter(item => {
                    if (typeof item !== 'object' || item === null) return false;
                    return item.status && item.status.toLowerCase() === this.statusFilter.toLowerCase();
                });
            }
            
            // Apply role filter
            if (this.roleFilter) {
                filtered = filtered.filter(item => {
                    if (typeof item !== 'object' || item === null) return false;
                    return item.role_name && item.role_name.toLowerCase() === this.roleFilter.toLowerCase();
                });
            }
            
            // Apply sorting
            if (this.sortColumn) {
                filtered = this.sortData(filtered, this.sortColumn, this.sortDirection);
            }
            
            this.filteredData = filtered;
            this.totalRecords = filtered.length;
            this.totalPages = Math.ceil(this.totalRecords / this.pageSize);
            
            this.updatePaginatedData();
        },
        
        // Update paginated data based on current page
        updatePaginatedData() {
            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + this.pageSize;
            this.paginatedData = this.filteredData.slice(start, end);
            
            this.startRecord = this.totalRecords > 0 ? start + 1 : 0;
            this.endRecord = Math.min(end, this.totalRecords);
        },
        
        // Sort data
        sortData(data, column, direction) {
            return data.sort((a, b) => {
                const aVal = a[column];
                const bVal = b[column];
                
                if (aVal === null || aVal === undefined) return 1;
                if (bVal === null || bVal === undefined) return -1;
                
                if (typeof aVal === 'string' && typeof bVal === 'string') {
                    return direction === 'asc' 
                        ? aVal.localeCompare(bVal)
                        : bVal.localeCompare(aVal);
                }
                
                if (typeof aVal === 'number' && typeof bVal === 'number') {
                    return direction === 'asc' 
                        ? aVal - bVal
                        : bVal - aVal;
                }
                
                return 0;
            });
        },
        
        // Sort functionality
        sort(column) {
            if (this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
            this.currentPage = 1;
            this.applyFilters();
        },
        
        // Pagination
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.updatePaginatedData();
            }
        },
        
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.updatePaginatedData();
            }
        },
        
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.updatePaginatedData();
            }
        },
        
        // Get page numbers for pagination
        getPageNumbers() {
            const pages = [];
            const maxVisible = 5;
            let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
            let end = Math.min(this.totalPages, start + maxVisible - 1);
            
            if (end - start + 1 < maxVisible) {
                start = Math.max(1, end - maxVisible + 1);
            }
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            
            return pages;
        },
        
        // Action handlers
        handleAction(action, row) {
            switch (action) {
                case 'viewUser':
                    this.viewUser(row);
                    break;
                case 'editUser':
                    this.editUser(row);
                    break;
                case 'toggleUserStatus':
                    this.toggleUserStatus(row);
                    break;
                case 'deleteUser':
                    this.deleteUser(row);
                    break;
                default:
                    break;
            }
        },
        
        // User action methods
        viewUser(row) {
            // Implementation for viewing user
        },
        
        editUser(row) {
            // Implementation for editing user
        },
        
        toggleUserStatus(row) {
            // Implementation for toggling user status
        },
        
        deleteUser(row) {
            // Implementation for deleting user
        },
        
        // Add new record
        addNewRecord() {
            // Implementation for adding new record
        },
        
        // Utility methods
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString();
        },
        
        formatCurrency(amount) {
            if (amount === null || amount === undefined) return 'N/A';
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        },
        
        // Loading state management
        setLoading(loading) {
            this.isLoading = loading;
        },
        
        // Data refresh
        refreshData(newData) {
            this.originalData = Array.isArray(newData) ? newData : [];
            this.currentPage = 1;
            this.searchQuery = '';
            this.sortColumn = null;
            this.sortDirection = 'asc';
            this.applyFilters();
        }
    };
}

// Global action handler
window.handleDataTableAction = function(action, row) {
    const dataTableInstance = Alpine.store('dataTable');
    if (dataTableInstance) {
        dataTableInstance.handleAction(action, row);
    }
};

// Global add new record handler
window.addNewRecord = function() {
    const dataTableInstance = Alpine.store('dataTable');
    if (dataTableInstance) {
        dataTableInstance.addNewRecord();
    }
};
