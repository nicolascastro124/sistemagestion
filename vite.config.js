import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { readdirSync } from 'fs';
import { resolve } from 'path';


// Función para leer los archivos de carpeta 
function getFilesFromDirectory(directory, extension = '.js') {
    return readdirSync(directory)
        .filter(file => file.endsWith(extension))
        .map(file => resolve(directory, file));
}

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js', // Archivo principal
                ...getFilesFromDirectory('resources/js/assets/categoria'), // carpeta ventas
                ...getFilesFromDirectory('resources/js/assets/ventas'), // carpeta ventas
                ...getFilesFromDirectory('resources/js/functions/ventas'), // carpeta ventas
                'resources/css/app.css' // CSS
            ],
            refresh: true,
        }),
    ],
});
