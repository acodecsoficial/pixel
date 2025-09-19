import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import autoImportComponents from 'unplugin-vue-components/vite'
import i18n from 'laravel-vue-i18n/vite'
import svgLoader from 'vite-svg-loader'
import path from 'path'

// https://vitejs.dev/config/
export default defineConfig({
  base: process.env.VITE_BASE || '/',    
  plugins: [
    laravel([
      'client/main.ts',
      'client/global.css',
    ]),
    vue(),
    i18n(),
    autoImportComponents({
      dirs: ['client/components'],
    }),
    svgLoader({
      defaultImport: 'url'
    })
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './client'),
    },
  },
  build: {
    rollupOptions: {
      external: /\.(svg|webp|png|jpg)$/i,
    }
  },
  optimizeDeps: {
    exclude: ['@headlessui/vue', 'primevue']
  },
  // ADICIONE ESTA SEÇÃO
  server: {
    hmr: {
      host: 'localhost',
    },
  },
})