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
                'elevate-gradient-main': 'linear-gradient(135deg, #031d3d 0%, #0d52a1 50%, #021124 100%)',                
                'elevate-gradient-card': 'linear-gradient(135deg, rgba(3, 29, 61, 0.9) 0%, rgba(2, 17, 36, 0.8) 100%)',
            },
        },
    },

    plugins: [forms],
};