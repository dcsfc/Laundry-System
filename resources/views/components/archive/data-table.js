/**
 * Professional Data Table Component - CLEAN & BULLETPROOF PORTAL SYSTEM
 * FIXES: Action menu positioning tied exactly to row triggers
 * CLEANED: Debugging removed, with viewport clamping added
 */

function dataTable(data, columns, actions, pageSize = 10, fetchUrl = null) {
    return {
        // Core Data
        originalData: Array.isArray(data) ? [...data] : [],
        filteredData: [],
        paginatedData: [],
        columns: Array.isArray(columns) ? columns : [],
        actions: Array.isArray(actions) ? actions : [],
        fetchUrl: fetchUrl,

        // Pagination
        currentPage: 1,
        pageSize: Math.max(1, pageSize || 10),
        totalPages: 1,
        totalRecords: 0,
        startRecord: 0,
        endRecord: 0,

        // Search & Filter
        searchQuery: '',
        statusFilter: '',
        roleFilter: '',

        // Sorting
        sortColumn: null,
        sortDirection: 'asc',

        // UI State
        isLoading: false,
        bulkSelectedItems: new Set(),
        selectAll: false,

        // PORTAL MENU STATE
        activeMenuRow: null,
        activeMenuTrigger: null,

        // Initialize
        init() {
            this.validateData();
            if (this.fetchUrl) {
                this.loadFromServer();
            } else {
                this.applyFilters();
            }
            this.setupPortalSystem();
            this.setupEventListeners();
        },

        async loadFromServer() {
            try {
                this.isLoading = true;
                const response = await fetch(this.fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!response.ok) throw new Error('Network response was not ok');
                const json = await response.json();
                const serverData = Array.isArray(json?.data) ? json.data : [];
                this.originalData = serverData;
                this.currentPage = 1;
                this.applyFilters();
            } catch (e) {
                console.error('Failed to load data:', e);
                this.originalData = [];
                this.filteredData = [];
                this.totalRecords = 0;
                this.totalPages = 1;
                this.paginatedData = [];
            } finally {
                this.isLoading = false;
            }
        },

        validateData() {
            if (!Array.isArray(this.originalData)) {
                throw new Error('Data must be an array');
            }
            if (!Array.isArray(this.columns) || this.columns.length === 0) {
                throw new Error('Columns must be a non-empty array');
            }
        },

        // ========== PORTAL SYSTEM ==========

        setupPortalSystem() {
            if (!document.getElementById('action-menu-portal')) {
                const portal = document.createElement('div');
                portal.id = 'action-menu-portal';
                portal.style.cssText = `
                    position: fixed;
                    top: 0; left: 0;
                    z-index: 99999;
                    pointer-events: none;
                `;
                document.body.appendChild(portal);
            }
        },

        openActionMenu(rowId, triggerElement, rowData) {
            // If same rowâ€™s menu is already open â†’ close only
            if (this.activeMenuRow === rowId) {
                this.closeActionMenu();
                return;
            }
        
            // Otherwise open fresh
            this.closeActionMenu();
            this.activeMenuRow = rowId;
            this.activeMenuTrigger = triggerElement;
            this.renderPortalMenu(rowData);
        },

        renderPortalMenu(rowData) {
            if (!this.activeMenuTrigger) return;

            const portal = document.getElementById('action-menu-portal');
            portal.innerHTML = '';

            const triggerRect = this.activeMenuTrigger.getBoundingClientRect();

            const menuWidth = 180;
            const menuHeight = this.actions.length * 40 + 16;
            const viewport = { width: window.innerWidth, height: window.innerHeight };

            let left, top;

            // Horizontal: prefer right side
            if (triggerRect.right + menuWidth <= viewport.width - 10) {
                left = triggerRect.right + 8;
            } else {
                left = triggerRect.left - menuWidth - 8;
            }

            // Vertical: prefer below
            if (triggerRect.bottom + menuHeight <= viewport.height - 10) {
                top = triggerRect.bottom + 4;
            } else {
                top = triggerRect.top - menuHeight - 4;
            }

            // Clamp so menu never goes off-screen
            left = Math.max(10, Math.min(left, viewport.width - menuWidth - 10));
            top = Math.max(10, Math.min(top, viewport.height - menuHeight - 10));

            const menu = document.createElement('div');
            menu.className = 'portal-menu';
            menu.style.cssText = `
                position: absolute;
                left: ${left}px;
                top: ${top}px;
                min-width: ${menuWidth}px;
                background: #1e293b;
                border: 1px solid #475569;
                border-radius: 8px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.9);
                pointer-events: auto;
                opacity: 0;
                transform: scale(0.95);
                transition: all 0.15s ease;
            `;

            this.actions.forEach(action => {
                const item = document.createElement('button');
                item.className = 'portal-menu-item';
                item.style.cssText = `
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    width: 100%;
                    padding: 10px 12px;
                    background: transparent;
                    border: none;
                    color: #e2e8f0;
                    font-size: 14px;
                    cursor: pointer;
                    transition: background 0.1s;
                `;
                item.onmouseenter = () => item.style.background = '#334155';
                item.onmouseleave = () => item.style.background = 'transparent';
                item.innerHTML = `
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${this.getActionIcon(action.key)}
                    </svg>
                    <span>${action.label}</span>
                `;
                item.onclick = (e) => {
                    e.stopPropagation();
                    this.handleAction(rowData, action.key);
                };
                menu.appendChild(item);
            });

            portal.appendChild(menu);

            requestAnimationFrame(() => {
                menu.style.opacity = '1';
                menu.style.transform = 'scale(1)';
            });
        },

        closeActionMenu() {
            const portal = document.getElementById('action-menu-portal');
            if (portal) {
                const menu = portal.querySelector('.portal-menu');
                if (menu) {
                    menu.style.opacity = '0';
                    menu.style.transform = 'scale(0.95)';
                    setTimeout(() => portal.innerHTML = '', 150);
                }
            }
            this.activeMenuRow = null;
            this.activeMenuTrigger = null;
        },

        getActionIcon(action) {
            const icons = {
                view: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>',
                edit: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
                delete: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
                toggle: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>'
            };
            return icons[action] || '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>';
        },

        // ===== CORE FUNCTIONALITY =====

        search: debounce(function() {
            this.currentPage = 1;
            this.applyFilters();
        }, 300),

        applyFilters() {
            let filtered = [...this.originalData];

            if (this.searchQuery?.trim()) {
                const query = this.searchQuery.toLowerCase().trim();
                filtered = filtered.filter(item =>
                    Object.values(item || {}).some(value =>
                        String(value || '').toLowerCase().includes(query)
                    )
                );
            }
            if (this.statusFilter) {
                filtered = filtered.filter(item =>
                    (item?.status || '').toString().toLowerCase() === this.statusFilter.toLowerCase()
                );
            }
            if (this.roleFilter) {
                filtered = filtered.filter(item =>
                    ((item?.role_name || item?.role) || '').toString().toLowerCase() === this.roleFilter.toLowerCase()
                );
            }
            if (this.sortColumn) {
                filtered = this.sortData(filtered, this.sortColumn, this.sortDirection);
            }

            this.filteredData = filtered;
            this.totalRecords = filtered.length;
            this.totalPages = Math.max(1, Math.ceil(this.totalRecords / this.pageSize));
            if (this.currentPage > this.totalPages) this.currentPage = Math.max(1, this.totalPages);

            this.updatePaginatedData();
            this.updateSelectAll();
        },

        sortData(data, column, direction) {
            return [...data].sort((a, b) => {
                let aVal = this.getNestedValue(a, column);
                let bVal = this.getNestedValue(b, column);

                if (aVal === null || aVal === undefined) return direction === 'asc' ? 1 : -1;
                if (bVal === null || bVal === undefined) return direction === 'asc' ? -1 : 1;

                if (this.isDate(aVal) && this.isDate(bVal)) {
                    return direction === 'asc' ? new Date(aVal) - new Date(bVal) : new Date(bVal) - new Date(aVal);
                }

                if (typeof aVal === 'number' && typeof bVal === 'number') {
                    return direction === 'asc' ? aVal - bVal : bVal - aVal;
                }

                const strA = String(aVal).toLowerCase();
                const strB = String(bVal).toLowerCase();
                return direction === 'asc' ? strA.localeCompare(strB) : strB.localeCompare(strA);
            });
        },

        getNestedValue(obj, path) {
            return path.split('.').reduce((current, key) => current?.[key], obj);
        },

        isDate(value) {
            return value instanceof Date || (typeof value === 'string' && !isNaN(Date.parse(value)));
        },

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

        updatePaginatedData() {
            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + this.pageSize;
            this.paginatedData = this.filteredData.slice(start, end);
            this.startRecord = this.totalRecords > 0 ? start + 1 : 0;
            this.endRecord = Math.min(end, this.totalRecords);
        },

        goToPage(page) {
            const targetPage = parseInt(page);
            if (targetPage >= 1 && targetPage <= this.totalPages) {
                this.currentPage = targetPage;
                this.updatePaginatedData();
            }
        },

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

        getPageNumbers() {
            const pages = [];
            const maxVisible = 7;
            const totalPages = this.totalPages;
            const current = this.currentPage;

            if (totalPages <= maxVisible) {
                for (let i = 1; i <= totalPages; i++) pages.push(i);
            } else {
                pages.push(1);
                if (current > 4) pages.push('...');
                const start = Math.max(2, current - 1);
                const end = Math.min(totalPages - 1, current + 1);
                for (let i = start; i <= end; i++) pages.push(i);
                if (current < totalPages - 3) pages.push('...');
                if (totalPages > 1) pages.push(totalPages);
            }
            return pages;
        },

        handleAction(row, action) {
            this.closeActionMenu();
            switch (action) {
                case 'view':
                    alert(`Viewing: ${row.name || row.id}`);
                    break;
                case 'edit':
                    alert(`Editing: ${row.name || row.id}`);
                    break;
                case 'toggle':
                    row.status = row.status === 'active' ? 'inactive' : 'active';
                    alert(`Status: ${row.status}`);
                    break;
                case 'delete':
                    if (confirm(`Delete ${row.name || row.id}?`)) {
                        const index = this.originalData.findIndex(item => item.id === row.id);
                        if (index !== -1) {
                            this.originalData.splice(index, 1);
                            this.applyFilters();
                            alert('Deleted');
                        }
                    }
                    break;
                default:
                    alert(`Action: ${action}`);
            }
        },

        toggleSelectAll() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                this.paginatedData.forEach(item => this.bulkSelectedItems.add(item.id));
            } else {
                this.bulkSelectedItems.clear();
            }
        },

        toggleItemSelection(itemId) {
            if (this.bulkSelectedItems.has(itemId)) {
                this.bulkSelectedItems.delete(itemId);
            } else {
                this.bulkSelectedItems.add(itemId);
            }
            this.updateSelectAll();
        },

        updateSelectAll() {
            const visibleIds = this.paginatedData.map(item => item.id);
            this.selectAll = visibleIds.length > 0 && visibleIds.every(id => this.bulkSelectedItems.has(id));
        },

        setupEventListeners() {
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.actions-dropdown') && !e.target.closest('.portal-menu')) {
                    this.closeActionMenu();
                }
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.closeActionMenu();
            });
            window.addEventListener('scroll', () => this.closeActionMenu());
            window.addEventListener('resize', () => this.closeActionMenu());
        },

        addNew() {
            alert('Add new record');
        },

        clearAllFilters() {
            this.searchQuery = '';
            this.statusFilter = '';
            this.roleFilter = '';
            this.currentPage = 1;
            this.applyFilters();
        },

        exportData(format = 'csv') {
            const data = this.filteredData;
            const filename = `export-${new Date().toISOString().split('T')[0]}`;

            if (format === 'csv') {
                const headers = this.columns.map(col => col.label || col.key);
                const rows = data.map(item =>
                    this.columns.map(col => this.getNestedValue(item, col.key) || '')
                );
                const csv = [headers, ...rows]
                    .map(row => row.map(field => `"${String(field).replace(/"/g, '""')}"`).join(','))
                    .join('\n');
                this.downloadFile(csv, `${filename}.csv`, 'text/csv');
            }
        },

        downloadFile(content, filename, mimeType) {
            const blob = new Blob([content], { type: mimeType });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                return new Date(dateString).toLocaleDateString();
            } catch {
                return 'Invalid Date';
            }
        }
    };
}

// Debounce utility
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