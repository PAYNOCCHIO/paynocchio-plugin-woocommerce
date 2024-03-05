/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./views/**/*.php'],
  prefix: 'cfps-',
  theme: {
    extend: {
      colors: {
        'c-brown': '#D9A464',
        'c-blue': '#3B82F6',
        'c-orange': '#FFBF72',
      },
    },
  },
  plugins: [
  ],
}

