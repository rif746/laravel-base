import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path'

export default defineConfig({
    build: {},
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',

                'resources/js/alpinejs.js',
                'resources/js/bootstrap.js',
                'resources/js/sidebar.js',
                'resources/js/plugin/jquery.js',
                'resources/js/plugin/datatables.js',
                'resources/js/plugin/apexchart.js',
                'resources/js/plugin/filepond.js',
                'resources/js/plugin/select2.js',
                'resources/js/plugin/sweetalert2.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            'jquery': path.resolve(__dirname, 'node_modules/jquery/dist-module/jquery.module.js'),
        }
    },
    optimizeDeps: {
        include: ['jquery', 'datatables.net-dt', 'datatables.net-bs5'],
    },
});
