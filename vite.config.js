import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from 'tailwindcss'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    css: {
        postcss: {
          plugins: [tailwindcss()],
        },
    },
    server: {
        host: '0.0.0.0', // Para acessar de fora do contêiner
        port: 5173, // Porta padrão do Vite
    },
});
