const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Noto Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                current: 'currentColor',
                'primary': {
                    DEFAULT: '#474a8a',
                    50: '#f3f5fb',
                    100: '#e5e8f4',
                    200: '#d0d7ed',
                    300: '#b0bee0',
                    400: '#8a9cd0',
                    500: '#6e7ec3',
                    600: '#5b66b5',
                    700: '#5056a5',
                    800: '#474a8a',
                    900: '#3c3f6c',
                },
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
