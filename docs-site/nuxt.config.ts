// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  modules: [
    '@nuxt/content',
    '@nuxtjs/tailwindcss',
    '@nuxtjs/color-mode'
  ],
  content: {
    highlight: {
      theme: 'github-dark'
    }
  },
  colorMode: {
    classSuffix: ''
  }
})
