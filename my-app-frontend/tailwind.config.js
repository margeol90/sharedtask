/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
        screens: {
        'sm': '640px',   // small phones
        'md': '768px',   // tablets
        'lg': '1024px',  // small laptops
        'xl': '1280px',  // desktops
        '2xl': '1536px', // large desktops
      },
      maxWidth: {
        'card-sm': '20rem',   // mobile cards
        'card-md': '28rem',   // tablet cards
        'card-lg': '36rem',   // desktop cards
        'card-xl': '48rem',   // wide desktop cards
      },
    },
  },
  plugins: [],
}
