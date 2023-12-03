import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                inter: ['"Inter"', "sans-serif"],
            },
            fontSize: {
                header: "2rem",
            },
            colors: {
                primary: "#2D349A",
                secondary: "#AB830F",
                accentOne: "#E1E6EF",
                active: "#2D6B9A",
                neutral: "#D6D7D8",
            },
            height: {
                128: "32rem",
                136: "36rem",
                160: "42.5rem",
            },
        },
    },

    plugins: [forms],
};
