/**
 * Table Action Menu - Handles action menu positioning and interactions
 * @module table-action-menu
 */

/**
 * Action Menu Manager Class
 * @class ActionMenuManager
 */
export class ActionMenuManager {
    /**
     * Create a new ActionMenuManager instance
     */
    constructor() {
        this.activeMenuRow = null;
        this.activeMenuTrigger = null;
        this.portalId = 'table-action-menu-portal';
        this.setupPortalSystem();
        this.setupEventListeners();
    }

    /**
     * Setup portal system for action menus
     */
    setupPortalSystem() {
        if (!document.getElementById(this.portalId)) {
            const portal = document.createElement('div');
            portal.id = this.portalId;
            portal.style.cssText = `
                position: fixed;
                top: 0; left: 0;
                z-index: 99999;
                pointer-events: none;
            `;
            document.body.appendChild(portal);
        }
    }

    /**
     * Open action menu for a specific row
     * @param {string|number} rowId - Unique identifier for the row
     * @param {HTMLElement} triggerElement - Element that triggered the menu
     * @param {Object} rowData - Data for the row
     * @param {Array} actions - Array of action objects
     * @param {Function} onAction - Callback function when action is selected
     */
    openActionMenu(rowId, triggerElement, rowData, actions, onAction) {
        // If same row's menu is already open → close only
        if (this.activeMenuRow === rowId) {
            this.closeActionMenu();
            return;
        }
        
        // Otherwise open fresh
        this.closeActionMenu();
        this.activeMenuRow = rowId;
        this.activeMenuTrigger = triggerElement;
        this.renderPortalMenu(rowData, actions, onAction);
    }

    /**
     * Render action menu in portal
     * @param {Object} rowData - Row data
     * @param {Array} actions - Array of action objects
     * @param {Function} onAction - Callback function when action is selected
     */
    renderPortalMenu(rowData, actions, onAction) {
        if (!this.activeMenuTrigger || !actions) return;
        
        const portal = document.getElementById(this.portalId);
        portal.innerHTML = '';
        
        const triggerRect = this.activeMenuTrigger.getBoundingClientRect();
        
        const menuWidth = 180;
        const menuHeight = actions.length * 40 + 20; // Approximate height
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
            border-radius: 0.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.9);
            pointer-events: auto;
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.15s ease;
            z-index: 50;
        `;
        
        actions.forEach(action => {
            const item = document.createElement('button');
            item.className = 'portal-menu-item';
            item.style.cssText = `
                display: flex;
                align-items: center;
                gap: 0.5rem;
                width: 100%;
                padding: 0.5rem 0.75rem;
                background: transparent;
                border: none;
                color: #e2e8f0;
                font-size: 0.875rem;
                cursor: pointer;
                transition: background 0.1s;
                text-align: left;
            `;
            item.onmouseenter = () => item.style.background = '#334155';
            item.onmouseleave = () => item.style.background = 'transparent';
            item.innerHTML = `
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${this.getActionIcon(action.icon)}
                </svg>
                <span>${action.label}</span>
            `;
            item.onclick = (e) => {
                e.stopPropagation();
                if (onAction) {
                    onAction(rowData, action);
                }
                this.closeActionMenu();
            };
            menu.appendChild(item);
        });
        
        portal.appendChild(menu);
        
        requestAnimationFrame(() => {
            menu.style.opacity = '1';
            menu.style.transform = 'scale(1)';
        });
    }

    /**
     * Close action menu
     */
    closeActionMenu() {
        const portal = document.getElementById(this.portalId);
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
    }

    /**
     * Get action icon SVG path
     * @param {string} icon - Icon type
     * @returns {string} SVG path string
     */
    getActionIcon(icon) {
        const icons = {
            view: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>',
            edit: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
            toggle: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
            key: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>',
            delete: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
            copy: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>',
            download: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
            settings: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>'
        };
        return icons[icon] || '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>';
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.actions-dropdown') && !e.target.closest('.portal-menu')) {
                this.closeActionMenu();
            }
        });
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeActionMenu();
            }
        });
        
        window.addEventListener('scroll', () => this.closeActionMenu());
        window.addEventListener('resize', () => this.closeActionMenu());
    }

    /**
     * Check if menu is open for a specific row
     * @param {string|number} rowId - Row identifier
     * @returns {boolean} True if menu is open for the row
     */
    isMenuOpen(rowId) {
        return this.activeMenuRow === rowId;
    }

    /**
     * Get currently active menu row
     * @returns {string|number|null} Active menu row ID
     */
    getActiveMenuRow() {
        return this.activeMenuRow;
    }
}
