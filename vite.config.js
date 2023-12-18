import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/scss/main.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    devServer: {
        proxy: ['http://localhost', '127.0.0.1'],
    },
    resolve: {
        mainFields: [
            'browser',
            'module',
            'main',
            'jsnext:main',
            'jsnext'
        ]
    },
    build: {
        minify: false
    }
});
