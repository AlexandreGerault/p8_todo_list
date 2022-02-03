module.exports = {
  content: [
      'src/Component/**/*.php',
      'templates/**/*.html.twig',
  ],
  theme: {
    extend: {},
  },
  plugins: [
      require('@tailwindcss/forms'),
  ],
}
