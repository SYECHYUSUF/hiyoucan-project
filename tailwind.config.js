import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Palet warna berdasarkan referensi Pinterest (Hiyoucan Style)
                'hiyoucan': {
                    50: '#fbfcfb',  // Background sangat terang
                    100: '#f2f7f2',
                    200: '#e1ebe1',
                    300: '#c3dbc3',
                    400: '#9cc39c',
                    500: '#76a576', // Hijau sedang
                    600: '#578357',
                    700: '#456845', // Hijau tua (untuk tombol/header)
                    800: '#395339',
                    900: '#2f442f',
                },
                'earth': {
                    100: '#fdfbf7', // Krem background
                    200: '#faeecf', // Krem aksen
                    300: '#e8dcb9',
                    800: '#5c4d3c', // Coklat teks
                }
            }
        },
    },

    plugins: [forms],
};