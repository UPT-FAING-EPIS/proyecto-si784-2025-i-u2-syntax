// playwright.config.js
const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
  testDir: './ui-tests',
  timeout: 30000,
  retries: 0,
  use: {
    headless: true,
    baseURL: 'http://localhost:8000', // ⚠️ CAMBIADO para usar el servidor PHP embebido
    screenshot: 'only-on-failure',
    video: 'on',
  },
  reporter: [['html', { outputFolder: 'docs/ui-report', open: 'never' }]],
});
