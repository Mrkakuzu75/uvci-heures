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
                green: {
                    DEFAULT: '#00C07F',
                    dark:    '#009962',
                    light:   '#E6FBF3',
                },
                navy: {
                    DEFAULT: '#0D1B2A',
                    mid:     '#1A2E42',
                },
                orange: '#FF6B35',
            },
            fontFamily: {
                syne: ['"Syne"', ...defaultTheme.fontFamily.sans],
                dm:   ['"DM Sans"', ...defaultTheme.fontFamily.sans],
                sans: ['"DM Sans"', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [forms],
};
