/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './client/**/*.vue',
    './resources/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        'primary': 'rgb(var(--primary-color) / <alpha-value>)',
        'primary-contrast': 'rgb(var(--primary-color-contrast) / <alpha-value>)',
        'surface': 'rgb(var(--surface-color) / <alpha-value>)',
        'background': 'rgb(var(--bg-color) / <alpha-value>)',
      },
      fontSize: {
        // Extra extra small
        xxs: '0.625rem',
      },
      borderRadius: {
        'custom': 'var(--border-radius)',
        'custom-max': 'clamp(0px, var(--border-radius), 0.72rem)',
      },
      screens: {
        'xs': '400px',
        // Ultra wide
        'uw': '1800px',
      }
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
