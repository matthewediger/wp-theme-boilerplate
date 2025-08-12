import { Config } from 'tailwindcss';

export default {
  content: [
    './inc/*.php',
    './inc/**/*.php',
    './src/**/*.{js,ts,css}',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
} as Config;