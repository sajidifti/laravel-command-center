import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
export default defineConfig({
    plugins: [tailwindcss()],
    // Disable Vite's publicDir feature because we build directly into the package `public/` folder.
    // When `outDir` and `publicDir` point to the same folder Vite warns that the feature
    // may not work correctly. We don't need the automatic public dir copy here.
    publicDir: false,
    root: __dirname,
    build: {
        outDir: path.resolve(__dirname, "public"),
        emptyOutDir: false,
        rollupOptions: {
            input: {
                app: path.resolve(__dirname, "resources/js/app.js"),
            },
            output: {
                entryFileNames: "js/[name].js",
                chunkFileNames: "js/[name].js",
                assetFileNames: (assetInfo) => {
                    // CSS files imported from JS - output as app.css directly
                    if (assetInfo.name && assetInfo.name.endsWith(".css")) {
                        return "css/app[extname]";
                    }
                    return "assets/[name][extname]";
                },
            },
        },
        target: "es2018",
    },
});
