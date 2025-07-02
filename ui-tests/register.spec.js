const { test, expect } = require('@playwright/test');

const REGISTER_URL = 'http://localhost/proyecto-si784-2025-i-u2-syntax/web_ams/index.php?accion=registro';

test('El formulario de registro se carga correctamente', async ({ page }) => {
  await page.goto(REGISTER_URL);

  // Verifica que todos los campos del formulario estén visibles
  await expect(page.locator('input[name="dni"]')).toBeVisible();
  await expect(page.locator('input[name="nombre"]')).toBeVisible();
  await expect(page.locator('input[name="apellido"]')).toBeVisible();
  await expect(page.locator('input[name="email"]')).toBeVisible();
  await expect(page.locator('input[name="password"]')).toBeVisible();
  await expect(page.locator('input[name="confirmar"]')).toBeVisible();
  await expect(page.locator('button#btnRegistrar')).toBeVisible();

  // Captura de pantalla de evidencia
  await page.screenshot({ path: 'ui-tests/screenshots/register_carga.png' });
});
