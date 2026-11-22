import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/js/admin.js'],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Bootstrap 5.3 still uses @import and global functions.
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'mixed-decls', 'if-function'],
                quietDeps: true,
            },
        },
    },
    server: {
        port: 5176,
        strictPort: true,
        watch: { ignored: ['**/storage/framework/views/**'] },
    },
});
