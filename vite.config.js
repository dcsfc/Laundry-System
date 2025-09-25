import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/datatable.css',
                'resources/js/app.js',
                'resources/js/datatable.js',
                'resources/js/datatable-renderer.js'
            ],
            refresh: true,
        }),
    ],
});

// Check for CSS plugin and correct config for Tailwind
