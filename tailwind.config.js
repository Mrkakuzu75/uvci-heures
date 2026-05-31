/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'navy': '#5B2E8E',
                'green-dark': '#2E7D32',
            },
        },
    },
    plugins: [],
}