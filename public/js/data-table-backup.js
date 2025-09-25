/**
 * Reusable Data Table Component
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
        
        // Filter data based on search query
        applyFilters() {
            console.log('=== Apply Filters ===');
            console.log('Original Data Length:', this.originalData.length);
            console.log('Original Data:', this.originalData);
            
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
            
            // Apply sorting
            if (this.sortColumn) {
                filtered = this.sortData(filtered, this.sortColumn, this.sortDirection);
            }
            
            this.filteredData = filtered;
            this.totalRecords = filtered.length;
            this.totalPages = Math.ceil(this.totalRecords / this.pageSize);
            
            console.log('Filtered Data Length:', this.filteredData.length);
            console.log('Total Records:', this.totalRecords);
            console.log('Total Pages:', this.totalPages);
            console.log('===================');
            
            this.updatePaginatedData();
        },
        
        // Update paginated data based on current page
        updatePaginatedData() {
            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + this.pageSize;
            this.paginatedData = this.filteredData.slice(start, end);
            
            this.startRecord = this.totalRecords > 0 ? start + 1 : 0;
            this.endRecord = Math.min(end, this.totalRecords);
            
            console.log('=== Update Paginated Data ===');
            console.log('Start:', start, 'End:', end);
            console.log('Paginated Data Length:', this.paginatedData.length);
            console.log('Paginated Data:', this.paginatedData);
            console.log('Start Record:', this.startRecord);
            console.log('End Record:', this.endRecord);
            console.log('============================');
        },
        
        // Pagination controls
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.updatePaginatedData();
            }
        },
        
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.updatePaginatedData();
            }
        },
        
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.updatePaginatedData();
            }
        },
        
        // Generate page numbers for pagination
        getPageNumbers() {
            const pages = [];
            const totalPages = this.totalPages;
            const currentPage = this.currentPage;
            const maxVisible = 5;
            
            if (totalPages <= maxVisible) {
                for (let i = 1; i <= totalPages; i++) {
                    pages.push(i);
                }
            } else {
                if (currentPage <= 3) {
                    for (let i = 1; i <= 4; i++) {
                        pages.push(i);
                    }
                    pages.push('...');
                    pages.push(totalPages);
                } else if (currentPage >= totalPages - 2) {
                    pages.push(1);
                    pages.push('...');
                    for (let i = totalPages - 3; i <= totalPages; i++) {
                        pages.push(i);
                    }
                } else {
                    pages.push(1);
                    pages.push('...');
                    for (let i = currentPage - 1; i <= currentPage + 1; i++) {
                        pages.push(i);
                    }
                    pages.push('...');
                    pages.push(totalPages);
                }
            }
            
            return pages;
        },
        
        // Sorting functionality
        sort(columnKey) {
            if (this.sortColumn === columnKey) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = columnKey;
                this.sortDirection = 'asc';
            }
            this.currentPage = 1;
            this.applyFilters();
        },
        
        sortData(data, columnKey, direction) {
            return data.sort((a, b) => {
                let aVal = a[columnKey];
                let bVal = b[columnKey];
                
                // Handle null/undefined values
                if (aVal === null || aVal === undefined) aVal = '';
                if (bVal === null || bVal === undefined) bVal = '';
                
                // Convert to strings for comparison
                aVal = String(aVal).toLowerCase();
                bVal = String(bVal).toLowerCase();
                
                // Handle numeric values
                if (!isNaN(aVal) && !isNaN(bVal)) {
                    aVal = parseFloat(aVal);
                    bVal = parseFloat(bVal);
                }
                
                // Handle dates
                if (columnKey.includes('date') || columnKey.includes('created') || columnKey.includes('updated')) {
                    aVal = new Date(a[columnKey]);
                    bVal = new Date(b[columnKey]);
                }
                
                let result = 0;
                if (aVal < bVal) result = -1;
                if (aVal > bVal) result = 1;
                
                return direction === 'desc' ? -result : result;
            });
        },
        
        // Removed getSortIcon - now using inline SVG with Alpine.js bindings
        
        // Action handling
        handleAction(row, actionKey) {
            // Emit custom event for action handling
            const event = new CustomEvent('datatable:action', {
                detail: { row, action: actionKey }
            });
            document.dispatchEvent(event);
        },
        
        // Add new record
        addNew() {
            // Emit custom event for adding new records
            const event = new CustomEvent('datatable:add');
            document.dispatchEvent(event);
        },
        
        // Loading states
        showLoading() {
            this.isLoading = true;
        },
        
        hideLoading() {
            this.isLoading = false;
        },
        
        // Simulate loading for demo purposes
        simulateLoading(duration = 1000) {
            this.showLoading();
            setTimeout(() => {
                this.hideLoading();
            }, duration);
        },
        
        // Utility methods
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString();
        },
        
        formatStatus(status) {
            const statusMap = {
                'active': 'Active',
                'inactive': 'Inactive',
                'pending': 'Pending',
                'completed': 'Completed',
                'cancelled': 'Cancelled'
            };
            return statusMap[status?.toLowerCase()] || status;
        },
        
        formatRole(role) {
            const roleMap = {
                'superadmin': 'Super Admin',
                'administrator': 'Administrator',
                'admin': 'Administrator',
                'staff': 'Staff',
                'customer': 'Customer'
            };
            return roleMap[role?.toLowerCase()] || role;
        }
    };
}

// Global event listeners for data table actions
document.addEventListener('DOMContentLoaded', function() {
    // Handle data table action events
    document.addEventListener('datatable:action', function(event) {
        const { row, action } = event.detail;
        console.log('Data table action triggered:', { action, row });
        
        // You can add specific action handling here
        // For example, opening modals, making API calls, etc.
        switch(action) {
            case 'viewUser':
                console.log('View user:', row);
                // Add your view logic here
                break;
            case 'editUser':
                console.log('Edit user:', row);
                // Add your edit logic here
                break;
            case 'toggleUserStatus':
                console.log('Toggle user status:', row);
                // Add your toggle logic here
                break;
            case 'deleteUser':
                console.log('Delete user:', row);
                // Add your delete logic here
                break;
            default:
                console.log('Unknown action:', action);
        }
    });
    
    // Handle add new record events
    document.addEventListener('datatable:add', function(event) {
        console.log('Add new record requested');
        // Add your add new logic here
    });
});
