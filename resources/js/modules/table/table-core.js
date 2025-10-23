/**
 * Table Core - Base table functionality for all data tables
 * @module table-core
 */

import { 
    debounce, 
    getNestedValue, 
    isDate, 
    formatDate, 
    sortData, 
    getPageNumbers, 
    validateTableData 
} from './table-utils.js';

/**
 * Base Table Class - Handles common table functionality
 * @class BaseTable
 */
export class BaseTable {
    /**
     * Create a new BaseTable instance
     * @param {Array} data - Initial data array
     * @param {number} pageSize - Number of items per page
     * @param {Object} options - Configuration options
     */
    constructor(data, pageSize = 10, options = {}) {
        this.originalData = validateTableData(data);
        this.filteredData = [];
        this.paginatedData = [];
        
        // Pagination
        this.pageSize = Math.max(1, pageSize || 10);
        this.currentPage = 1;
        this.totalPages = 1;
        this.totalRecords = 0;
        this.startRecord = 0;
        this.endRecord = 0;
        
        // Search & Filter
        this.searchQuery = '';
        this.filters = {};
        
        // Sorting
        this.sortColumn = null;
        this.sortDirection = 'asc';
        
        // UI State
        this.isLoading = false;
        
        // Action Menu State
        this.activeMenuRow = null;
        this.activeMenuTrigger = null;
        
        // Action Menu Manager
        this.actionMenuManager = window.actionMenuManager || (typeof ActionMenuManager !== 'undefined' ? new ActionMenuManager() : null);
        
        // Options
        this.options = {
            searchable: true,
            sortable: true,
            pagination: true,
            bulkActions: false,
            ...options
        };
        
        this.init();
    }
    
    /**
     * Initialize the table
     */
    init() {
        this.validateData();
        this.applyFilters();
        this.setupPortalSystem();
        this.setupEventListeners();
    }
    
    /**
     * Validate table data
     */
    validateData() {
        this.originalData = validateTableData(this.originalData);
    }
    
    
    /**
     * Setup portal system for action menus
     */
    setupPortalSystem() {
        // Portal system is now handled by ActionMenuManager
    }
    
    /**
     * Open action menu for a specific row
     * @param {string|number} rowId - Unique identifier for the row
     * @param {HTMLElement} triggerElement - Element that triggered the menu
     * @param {Object} rowData - Data for the row
     */
    openActionMenu(rowId, triggerElement, rowData) {
        // Check if ActionMenuManager is available
        if (!this.actionMenuManager) {
            console.error('ActionMenuManager not available');
            return;
        }
        
        // Get actions from the table instance or use default actions
        const actions = this.getActionsForRow(rowData);
        this.actionMenuManager.openActionMenu(rowId, triggerElement, rowData, actions, (rowData, action) => {
            this.handleAction(rowData, action);
        });
    }
    
    /**
     * Get actions for a specific row
     * @param {Object} rowData - Row data
     * @returns {Array} Array of action objects
     */
    getActionsForRow(rowData) {
        // Default actions - can be overridden by specific table implementations
        return [
            { key: 'view', label: 'View', icon: 'view' },
            { key: 'edit', label: 'Edit', icon: 'edit' },
            { key: 'delete', label: 'Delete', icon: 'delete' }
        ];
    }
    
    /**
     * Close action menu
     */
    closeActionMenu() {
        if (this.actionMenuManager) {
            this.actionMenuManager.closeActionMenu();
        }
    }
    
    /**
     * Handle action selection
     * @param {Object} rowData - Row data
     * @param {Object} action - Action object
     */
    handleAction(rowData, action) {
        this.closeActionMenu();
        // This will be overridden by specific table implementations
        if (window[action.key]) {
            window[action.key](rowData);
        } else {
            console.log(`Action: ${action.key} for row:`, rowData);
        }
    }
    
    
    /**
     * Perform search
     */
    search() {
        this.currentPage = 1;
        this.applyFilters();
    }
    
    /**
     * Apply all filters and search logic
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
        
        // Custom filters
        Object.entries(this.filters).forEach(([key, value]) => {
            if (value && value !== '') {
                filtered = filtered.filter(row => {
                    const rowValue = getNestedValue(row, key);
                    return String(rowValue).toLowerCase() === String(value).toLowerCase();
                });
            }
        });
        
        // Apply sorting
        if (this.sortColumn) {
            filtered = sortData(filtered, this.sortColumn, this.sortDirection, getNestedValue, isDate);
        }
        
        this.filteredData = filtered;
        this.updatePagination(filtered);
    }
    
    /**
     * Sort data by column
     * @param {string} column - Column to sort by
     */
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
    
    /**
     * Update pagination based on filtered data
     * @param {Array} filteredData - Filtered data array
     */
    updatePagination(filteredData) {
        this.totalRecords = filteredData.length;
        this.totalPages = Math.max(1, Math.ceil(this.totalRecords / this.pageSize));
        
        // Ensure current page is within bounds
        if (this.currentPage > this.totalPages) {
            this.currentPage = Math.max(1, this.totalPages);
        }
        
        // Calculate record range
        if (this.totalRecords === 0) {
            this.startRecord = 0;
            this.endRecord = 0;
        } else {
            this.startRecord = (this.currentPage - 1) * this.pageSize + 1;
            this.endRecord = Math.min(this.currentPage * this.pageSize, this.totalRecords);
        }
        
        // Get paginated data
        const start = (this.currentPage - 1) * this.pageSize;
        const end = start + this.pageSize;
        this.paginatedData = filteredData.slice(start, end);
    }
    
    /**
     * Go to specific page
     * @param {number} page - Page number
     */
    goToPage(page) {
        const targetPage = parseInt(page);
        if (targetPage >= 1 && targetPage <= this.totalPages) {
            this.currentPage = targetPage;
            this.updatePagination(this.filteredData);
        }
    }
    
    /**
     * Go to next page
     */
    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.currentPage++;
            this.updatePagination(this.filteredData);
        }
    }
    
    /**
     * Go to previous page
     */
    previousPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.updatePagination(this.filteredData);
        }
    }
    
    /**
     * Change page size
     * @param {number} newPageSize - New page size
     */
    changePageSize(newPageSize) {
        this.pageSize = parseInt(newPageSize);
        this.currentPage = 1;
        this.updatePagination(this.filteredData);
    }
    
    /**
     * Get page numbers for pagination
     * @returns {Array} Array of page numbers
     */
    getPageNumbers() {
        return getPageNumbers(this.currentPage, this.totalPages);
    }
    
    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Event listeners are now handled by ActionMenuManager
    }
    
    /**
     * Clear all filters
     */
    clearAllFilters() {
        this.searchQuery = '';
        this.filters = {};
        this.currentPage = 1;
        this.applyFilters();
    }
    
    /**
     * Set filter value
     * @param {string} key - Filter key
     * @param {*} value - Filter value
     */
    setFilter(key, value) {
        this.filters[key] = value;
        this.currentPage = 1;
        this.applyFilters();
    }
    
    /**
     * Get nested value from object
     * @param {Object} obj - Source object
     * @param {string} path - Dot notation path
     * @returns {*} Value at path
     */
    getNestedValue(obj, path) {
        return getNestedValue(obj, path);
    }
    
    /**
     * Format date for display
     * @param {string} dateString - Date string
     * @returns {string} Formatted date
     */
    formatDate(dateString) {
        return formatDate(dateString);
    }
}

// Add debounced search method to BaseTable prototype
BaseTable.prototype.debouncedSearch = debounce(function() {
    this.currentPage = 1;
    this.applyFilters();
}, 300);
