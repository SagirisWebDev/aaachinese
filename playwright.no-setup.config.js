// @ts-check
const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
    testDir: './tests/frontend/ui',
    workers: 1,
    timeout: 60000,
    use: {
        baseURL: process.env.WP_BASE_URL || 'http://localhost:10017',
        headless: true,
        storageState: './tests/frontend/ui/.auth-state.json',
    },
});
