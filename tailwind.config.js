/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        mint: 'var(--color-mint)',
        red: {
          600: '#dc2626',
        }
      },
    },
  },
  plugins: [],
} 