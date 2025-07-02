const { test, expect } = require('@playwright/test');

const URL = 'http://localhost/proyecto-si784-2025-i-u2-syntax/web_ams/index.php?accion=login';

test('Login carga correctamente', async ({ page }) => {
  await page.goto(URL);

  await expect(page.locator('input[type="email"]')).toBeVisible();
  await expect(page.locator('input[type="password"]')).toBeVisible();

  await page.screenshot({ path: 'ui-tests/screenshots/login_carga.png' });
});

