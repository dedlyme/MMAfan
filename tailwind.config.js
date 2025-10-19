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

    // 🌙 Dark mode toggled with "dark" class on <html> or <body>
    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#ef4444', // UFC Red (accents and buttons)
                secondary: '#1f1f1f', // dark gray for subtle backgrounds
                darkBg: '#0a0a0a',    // true black for dark mode
                lightBg: '#ffffff',   // pure white for light mode
                textLight: '#1f1f1f', // text in light mode
                textDark: '#f9f9f9',  // text in dark mode
            },
            boxShadow: {
                'xl-red': '0 10px 25px -3px rgba(239, 68, 68, 0.4)',
                'xl-soft': '0 10px 25px -3px rgba(0, 0, 0, 0.1)',
            },
            transitionDuration: {
                400: '400ms',
                600: '600ms',
            },
        },
    },

    plugins: [
        forms, // better default styles for form inputs
    ],
};