import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./app/Livewire/**/*.php",
        "./app/View/**/*.php",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./vendor/robsontenorio/mary/src/View/Components/**/*.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        require("@tailwindcss/typography"),
        require("daisyui"),
        require("tailwind-bootstrap-grid")()
    ],
    daisyui: {
        themes: ["light", "dark"],
        darkMode: "class",
    },
};
