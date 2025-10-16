import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/landing.css',
                'resources/css/admin-login.css',
                'resources/css/customer-login.css',
                'resources/css/customer-schedules.css',
                'resources/css/announcements.css',
                'resources/css/usermanagement.css',
                'resources/css/tables.css',
                'resources/css/table-headers.css',
                'resources/css/status-badges.css',
                'resources/css/search-filters.css',
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                'resources/js/landing.js',
                'resources/js/modules/schedules/customer-schedules.js',
                'resources/js/modules/announcements/index.js',
                'resources/js/modules/settings/settings.js',
                'resources/js/modules/table/action-menu.js',
                'resources/js/modules/notifications/modern-notifications.js',
                'resources/js/modules/notifications/notification-demo.js',
                'resources/js/modules/table/table-data-fetcher.js',
                'resources/js/modules/table/tables-modular.js'
            ],
            refresh: true,
        }),
    ],
});
