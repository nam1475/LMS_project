import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/frontend.css',
                'resources/css/admin.css',
                'resources/js/app.js',
                'resources/js/admin/login.js',
                'resources/js/admin/admin.js',
                'resources/js/admin/course.js',
                'resources/js/admin/notification.js',

                'resources/js/frontend/course.js',
                'resources/js/frontend/frontend.js',
                'resources/js/frontend/player.js',
                'resources/js/frontend/review.js',
                'resources/js/frontend/comment.js',
                'resources/js/frontend/chat.js',
                'resources/js/frontend/notification.js',

                'resources/js/global.js',
                'resources/js/upload.js',


            ],
            refresh: true,
        }),
    ],
});
