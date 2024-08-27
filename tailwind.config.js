/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    'resources/views/**/*.{php, blade.php}',
    './node_modules/flowbite/**/*.js',
  ],
  theme: {
    extend: {
      fontFamily: {
        century: ['Century', 'sans-serif'],
        Montserrat: ['Montserrat', 'sans-serif'],
      },
      colors: {
        verde: {
          50: '#F5F9F5',
          100: '#EBF2EC',
          200: '#CEE0CF',
          300: '#B0CDB2',
          400: '#74A779',
          500: '#39813F',
          600: '#337439',
          700: '#224D26',
          800: '#1A3A1C',
          900: '#112713',
          950: '#0B1A0D',
          DEFAULT: '#39813F',
        },
        'background-general': { DEFAULT: '#F1F2F6' },
        'verde-claro': { DEFAULT: '#579D44' },
        'verde-oscuro': { DEFAULT: '#2A6439' },
        'verde-manzana': { DEFAULT: '#93B540' },
        'verde-menta': { DEFAULT: '#5FCE90' },
        'verde-azul': { DEFAULT: '#429591' },
        negro: { DEFAULT: '#1D1D27' },
        secondary: { DEFAULT: '#E9F0D9' },
        primary: { DEFAULT: '#3A8340' },
        'azul-gris': { DEFAULT: '#F7F7F9' },
        'gris-oscuro': { DEFAULT: '#626262' },
        'gris-azul': { DEFAULT: '#5D5A88' },
        'rojo-claro': { DEFAULT: '#FF6464' },
        'color-borde': { DEFAULT: '#D9D9D9' },
        'amarillo-claro': { DEFAULT: '#D99938' },
        'azul-claro': { DEFAULT: '#6A9BFF' },
        'verde-firma': { DEFAULT: '#579E44' },

        // colores rgba
        'verde-transparent': { DEFAULT: 'rgba(95, 206, 144, 0.2)' },
        'verde-claro-transparent': { DEFAULT: 'rgba(115, 183, 0, 0.2)' },
        'rojo-transparent': { DEFAULT: 'rgba(255,100,100,0.2)' },
        'verde-tooltip': { DEFAULT: ' rgba(229, 246, 225, 1)' },
        'amarillo-transparent': { DEFAULT: ' rgba(217, 153, 56, 0.2)' },
        'azul-transparent': { DEFAULT: ' rgba(106, 155, 255, 0.2)' },

        //Cards color
        'secondary-green': { DEFAULT: '#A1E3CB' },
        'secondary-mint': { DEFAULT: '#BAEDBD' },

        //color notification card
        'borde-verde': { DEFAULT: '#44A82D' },
        'borde-gris': { DEFAULT: '#D9D9D9' },
        'icon-gris': { DEFAULT: '#ACACAC' },
        'title-popup': { DEFAULT: '#0A1B48' },
        'text-popup': { DEFAULT: '#7F7F7F' },
        'bg-readmore': { DEFAULT: '#EDF3FF' },

        //Color campos input
        'input-bg': { DEFAULT: 'rgba(255, 255, 255, 0.3)' },

        //Line horizontal
        line: { DEFAULT: '#CDD1DE' },
      },
      backgroundImage: {
        'bg-gris': "url('../img/bg-figuras.png')",
        'bg-login': "url('../img/bg-login.jpg')",
      },
      borderRadius: {
        'custom-br1': '0px 60px 60px 0px',
        'custom-br2': '60px',
      },
      height: {
        432: '432px',
      },
    },
  },
  plugins: [require('flowbite/plugin')],
};
