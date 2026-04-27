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
                // Tema Microsoft Elevate (Single Source of Truth)
                elevate: {
                    dark: '#032b5b',      // Biru Navy Gelap (Background section/Teks tebal)
                    primary: '#3b5889',   // Biru pekat (Tombol utama/Hover state)
                    accent: '#38bdf8',    // Cyan/Biru muda (Aksen, Ring, Icon)
                    surface: '#ffffff',   // Background dasar card
                    text: '#1e293b',      // Warna teks default
                    peach: {
                        light: '#fff0e8', // Card background gradient soft
                        DEFAULT: '#ffbca5', // Aksen gradient peach
                    }
                }
            },
            backgroundImage: {
                // Background utama seluruh halaman (Biru cyan ke peach)
                'elevate-gradient-main': 'linear-gradient(120deg, #1cb5e0 0%, #e0f2fe 40%, #fff0e8 70%, #ffbca5 100%)',
                // Background khusus untuk kartu/widget jika diperlukan
                'elevate-gradient-card': 'linear-gradient(135deg, #fffcf9 0%, #fff0e8 100%)',
            },
        },
    },

    plugins: [forms],
};