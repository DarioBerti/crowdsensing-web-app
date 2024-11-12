const { defineConfig } = require('@vue/cli-service')

module.exports = defineConfig({
  transpileDependencies: true,
  publicPath: process.env.NODE_ENV === 'production'
    ? '/tirocinio/crowdsensing-web-app/bylights/' // Path di produzione
    : '/' // Path di sviluppo
})