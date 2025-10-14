/**
 * Table Utilities - Reusable helper functions for data tables
 * @module table-utils
 */

/**
 * Debounce utility for performance optimization
 * @param {Function} func - Function to debounce
 * @param {number} wait - Delay in milliseconds
 * @returns {Function} Debounced function
 */
export function debounce(func, wait) {
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

/**
 * Get nested value from object using dot notation
 * @param {Object} obj - Source object
 * @param {string} path - Dot notation path (e.g., 'user.profile.name')
 * @returns {*} Value at the specified path or undefined
 */
export function getNestedValue(obj, path) {
    return path.split('.').reduce((current, key) => current?.[key], obj);
}

/**
 * Check if a value is a valid date
 * @param {*} value - Value to check
 * @returns {boolean} True if value is a date
 */
export function isDate(value) {
    return value instanceof Date || (typeof value === 'string' && !isNaN(Date.parse(value)));
}

/**
 * Format date string for display
 * @param {string} dateString - Date string to format
 * @returns {string} Formatted date or 'N/A'
 */
export function formatDate(dateString) {
    if (!dateString) return 'N/A';
    try {
        return new Date(dateString).toLocaleDateString();
    } catch {
        return 'Invalid Date';
    }
}

/**
 * Sort data by column with direction
 * @param {Array} data - Array of objects to sort
 * @param {string} column - Column to sort by
 * @param {string} direction - Sort direction ('asc' or 'desc')
 * @param {Function} getNestedValue - Function to get nested values
 * @param {Function} isDate - Function to check if value is date
 * @returns {Array} Sorted array
 */
export function sortData(data, column, direction, getNestedValue, isDate) {
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

/**
 * Generate page numbers for pagination
 * @param {number} currentPage - Current page number
 * @param {number} totalPages - Total number of pages
 * @param {number} maxVisible - Maximum visible page numbers
 * @returns {Array} Array of page numbers and ellipsis
 */
export function getPageNumbers(currentPage, totalPages, maxVisible = 7) {
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

/**
 * Validate table data
 * @param {*} data - Data to validate
 * @returns {Array} Validated data array
 */
export function validateTableData(data) {
    if (!Array.isArray(data)) {
        console.error('Table data must be an array');
        return [];
    }
    return data;
}
