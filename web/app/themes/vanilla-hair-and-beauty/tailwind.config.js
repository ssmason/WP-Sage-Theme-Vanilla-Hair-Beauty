module.exports = {
  important: true,

  content: [
    './resources/views/**/*.blade.php',
    './resources/**/*.php',
    './resources/js/**/*.{js,jsx,ts,tsx}',
    './app/**/*.php',
  ],
  safelist: [
    'max-w-[1100px]',
    'px-[10px]',
    'py-2'
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
};

