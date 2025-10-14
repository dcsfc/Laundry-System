/**
 * Table Module - Central export file for all table functionality
 * @module table
 */

// Core table functionality
export { BaseTable } from './table-core.js';

// Action menu functionality
export { ActionMenuManager } from './table-action-menu.js';

// User-specific table implementation
export { usersTable } from './table-users.js';

// Utilities
export * from './table-utils.js';

// Global assignments for Blade template compatibility
import { BaseTable } from './table-core.js';
import { ActionMenuManager } from './table-action-menu.js';
import { usersTable } from './table-users.js';

// Make classes available globally for Blade templates
if (typeof window !== 'undefined') {
    window.BaseTable = BaseTable;
    window.ActionMenuManager = ActionMenuManager;
    window.usersTable = usersTable;
    
    // Create global ActionMenuManager instance
    if (!window.actionMenuManager) {
        window.actionMenuManager = new ActionMenuManager();
    }
}
