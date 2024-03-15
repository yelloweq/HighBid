import withMT from "@material-tailwind/html/utils/withMT";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default withMT({
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js"
    ],

    theme: {
        extend: {
            fontFamily: {
                inter: ["Inter", "sans-serif"],
            },
            colors: {
                "blue-primary": "#100F23",
                "blue-accent": "#3417FF",
                "blue-secondary": "#0D255B",
                "blue-thirtiary": "#123177",
            },
            spacing: {
                '8xl': '88rem',
                '9xl': '96rem',
                '10xl': '104rem',
                '11xl': '112rem',
            },
            height: {
                '600': '37.5rem',
            },
            width: {
                '600': '37.5rem',
            }
        },
    },
    plugins: [
        forms,
        require('flowbite/plugin'),
    ],
});
