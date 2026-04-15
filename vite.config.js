// vite.config.js

import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import Inertia from 'inertiajs/inertia';
import InertiaVue from '@inertiajs/inertia-vue3';
import { resolve } from 'path';
import { fileURLToPath } from 'url';
import { alias } from 'vite';

// Define the project root directory
const root = resolve(fileURLToPath(import.meta.url), '..');

// Define the alias for the project root directory
alias.set('@', root);

// Define the Vite configuration
export default defineConfig({
  // Enable Vue plugin
  plugins: [vue(), InertiaVue({ resolve: Inertia })],

  // Define the base directory for the project
  base: '/',

  // Define the public directory for the project
  publicDir: 'public',

  // Define the alias for the project root directory
  alias: {
    '@': root,
    '@assets': resolve(root, 'resources/assets'),
    '@components': resolve(root, 'resources/components'),
    '@js': resolve(root, 'resources/js'),
    '@store': resolve(root, 'resources/store'),
  },

  // Define the server configuration
  server: {
    // Enable hot module replacement
    hmr: {
      // Enable hot module replacement for Vue components
      client: {
        // Enable hot module replacement for Vue components
        vue: {
          // Enable hot module replacement for Vue components
          hmr: true,
        },
      },
    },
  },

  // Define the build configuration
  build: {
    // Enable sourcemaps
    sourcemap: true,

    // Define the output directory for the project
    outDir: 'public/dist',
  },
});