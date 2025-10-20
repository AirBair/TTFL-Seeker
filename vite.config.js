import { defineConfig } from 'vite'
import symfonyPlugin from 'vite-plugin-symfony'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'

export default defineConfig({
    plugins: [
        vue(),
        vuetify(),
        symfonyPlugin(),
    ],
    build: {
        outDir: 'public/build/',
        rollupOptions: {
            input: {
                app: './assets/vue/app.ts',
            },
        },
    },
})
