import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import mkcert from'vite-plugin-mkcert'
import * as fs from "node:fs";
import * as path from "node:path";
export default defineConfig({
    server: {


        https: {
            key: fs.readFileSync(path.resolve(__dirname, '192.168.1.169-key.pem')),
            cert: fs.readFileSync(path.resolve(__dirname, '192.168.1.169.pem')),
        },
    },
    // plugins: [
    //
    //     laravel({
    //         input: ['resources/css/app.css', 'resources/js/app.js'],
    //         refresh: true,
    //     }),
    // ],
});
