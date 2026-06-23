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
                sans: ['Poppins', 'Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'limu': {
                    'blue': '#3B1E5E',
                    'blue-light': '#7B3FE4',
                    'blue-dark': '#2A1544',
                    'light': '#F5F5F7',
                    'accent': '#A78BFA',
                },
            },
        },
    },

    plugins: [forms],
};
