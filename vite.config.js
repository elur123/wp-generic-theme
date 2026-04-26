import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'
import { resolve } from 'path'

export default defineConfig({
  plugins: [tailwindcss()],
  build: {
    outDir: 'build',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        app: resolve(__dirname, 'src/js/app.js'),
        editor: resolve(__dirname, 'src/css/editor.css'),
      },
      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].js',
        assetFileNames: ({ name }) => {
          if (name && name.endsWith('.css')) return 'css/[name][extname]'
          return 'assets/[name][extname]'
        },
      },
    },
  },
})
