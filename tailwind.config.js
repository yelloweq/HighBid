import withMT from "@material-tailwind/html/utils/withMT";
import forms from '@tailwindcss/forms';
import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default withMT({
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'bg-primary': '#231F20',
                'gbg-primary': '#0F0F0F',
                'primary-new': '#EFE6DD',
                'blue-accent': '#7EBDC2',
                'vanilla-accent': '#F3DFA2',
                'red-accent': '#BB4430',
            }
        },
    },
    plugins: [forms],
});
