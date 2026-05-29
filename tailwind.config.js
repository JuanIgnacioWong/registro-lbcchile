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
            colors: {
                primary: '#0c2340',
                secondary: '#1f4e8c',
                accent: '#35bdfe',
                success: '#16a34a',
                warning: '#f59e0b',
                danger: '#dc2626',
                muted: '#eef2f7',
            },
            borderRadius: {
                xl: '1rem',
                '2xl': '1.25rem',
            },
            boxShadow: {
                soft: '0 12px 35px -18px rgba(15, 23, 42, 0.35)',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['Archivo', 'Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
