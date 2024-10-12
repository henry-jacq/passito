import { defineConfig } from 'vite';
import path from 'path';
import { liveReload } from 'vite-plugin-live-reload';

export default defineConfig({
  root: path.resolve(__dirname, 'resources'), // Set the root to the resources folder
  build: {
    outDir: path.resolve(__dirname, 'public/build'), // Output directory for built files
    emptyOutDir: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'resources/js/main.js'), // Main JS entry point
        // Add more entries here if needed
      },
    },
  },
  plugins: [
    liveReload(['views/**/*.*']), // Use liveReload as a Vite plugin
  ],
  css: {
    postcss: {
      plugins: [
        require('tailwindcss'),
        require('autoprefixer'),
      ],
    },
  },
  server: {
    host: '0.0.0.0',  // Allows access to the Vite server externally
    port: 5173,       // Vite port
    watch: {
      // Watch the PHP views folder for changes
      usePolling: true, // Necessary for Docker environments
      interval: 1000,   // Polling interval
    },
  },
});