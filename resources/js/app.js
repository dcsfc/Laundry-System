import './bootstrap';
import Alpine from 'alpinejs';

// Import table modules before Alpine starts
import './modules/table/tables-modular.js';
import './modules/table/action-menu.js';

window.Alpine = Alpine;
Alpine.start();






