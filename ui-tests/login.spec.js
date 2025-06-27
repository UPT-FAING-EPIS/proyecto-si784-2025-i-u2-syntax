const { test, expect } = require('@playwright/test');

test('Login carga correctamente', async ({ page }) => {
  await page.goto('http://localhost/proyecto-si784-2025-i-u2-syntax/web_ams/index.php?accion=login');

  // Esperar que el input de correo esté visible
  await expect(page.locator('input[type="email"]')).toBeVisible();

  // Tomar screenshot si pasa
  await page.screenshot({ path: 'ui-tests/screenshots/login.png' });
});
