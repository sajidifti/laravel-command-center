/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./resources/css/**/*.css",
        // include main app resources for local dev when running from repository root
        "../../resources/views/**/*.blade.php",
        "../../resources/js/**/*.js",
        "../../resources/css/**/*.css",
    ],
};
