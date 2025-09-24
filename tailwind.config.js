import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    darkMode: 'class', // enables dark mode via "dark" class

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#ef4444',      // main red for buttons/highlights
                secondary: '#1f1f1f',    // dark background
                lightBg: '#f9f9f9',      // light background
                darkBg: '#1f1f1f',       // dark background
                textLight: '#1f1f1f',    // text in light mode
                textDark: '#f9f9f9',     // text in dark mode
            },
        },
    },

    plugins: [forms],
};
