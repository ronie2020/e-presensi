import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', 
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
                elevate: {
                    dark: '#2c3f61',      // Biru Navy (Teks utama, Tombol Utama)
                    primary: '#0d52a1',   // Biru Pekat (Icon hover, aksen teks)
                    accent: '#56bbf1',    // Biru Muda/Cyan (Highlight, Shadow)
                    surface: '#ffffff',   // Background dasar card
                    soft: '#e5eff5',      // Background icon/badge
                    peach: {
                        light: '#f4d1c0', 
                        DEFAULT: '#f9a282', // Icon edit
                        dark: '#c86845'
                    }
                }
            },

            backgroundImage: {                
                'elevate-gradient-main': 'linear-gradient(120deg, #1cb5e0 0%, #e0f2fe 40%, #fff0e8 70%, #ffbca5 100%)',                
                'elevate-gradient-card': 'linear-gradient(135deg, #fffcf9 0%, #fff0e8 100%)',
            },
        },
    },

    plugins: [forms],
};