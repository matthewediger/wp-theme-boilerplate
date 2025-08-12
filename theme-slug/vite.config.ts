import { defineConfig } from 'vite';
import path from 'path';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [ tailwindcss() ],
  base: '/dist/',
  build: {
    outDir: path.resolve(__dirname, 'dist'),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        admin: path.resolve(__dirname, 'src/admin/js/admin.ts'),
        'admin-style': path.resolve(__dirname, 'src/admin/css/admin-style.css'),
        main: path.resolve(__dirname, 'src/js/main.ts'),
        'main-style': path.resolve(__dirname, 'src/css/main-style.css')
      },
      output: {
        entryFileNames: (entryFileName) => {
          const fileName = entryFileName.name.replace(/\.js$/i, '').toLowerCase() || 'main';
          if (fileName.includes('admin')) {
            return `admin/js/${fileName}.js`;
          }
          return `js/${fileName}.js`;
        },
        assetFileNames: (entryFileName) => {
          const fileName = entryFileName.names[0].replace(/\.css$/i, '').toLowerCase() || 'style';
          if (fileName.includes('admin')) {
            return `admin/css/${fileName}.css`;
          }
          return `css/${fileName}.css`;
        }
      },
    },
  },
});