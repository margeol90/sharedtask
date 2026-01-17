import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'



export default defineConfig({
  plugins: [
    vue(),
    tailwindcss(),
  ],

  test: {
    globals: true,       // allows `describe`, `it`, etc. globally
    environment: 'jsdom', // simulates a browser environment
    setupFiles: './tests/setup.js', // optional, for Pinia setup
  },
})
