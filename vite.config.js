import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/public.js',
                'resources/js/choose-seat.js',
                'resources/js/book-ticket.js',
            ],
            refresh: true,
        }),
    ],
});
