const { test, expect } = require('@playwright/test');

const HOME_URL = 'http://localhost/proyecto-si784-2025-i-u2-syntax/web_ams/index.php';

test('Carga de página Home correcta', async ({ page }) => {
  await page.goto(HOME_URL);

  // Hero section
  await expect(page.locator('text=Sistema de Mentoría Académica UPT')).toBeVisible();
  await expect(page.locator('text=Explorar Servicios')).toBeVisible();
  await expect(page.locator('text=¿Cómo Funciona?')).toBeVisible();

  // Sección de servicios (6 cards)
  await expect(page.locator('.card.service-card')).toHaveCount(6);
  await expect(page.locator('text=Mentoría Personalizada')).toBeVisible();
  await expect(page.locator('text=Preparación de Exámenes')).toBeVisible();

  // Estadísticas
  await expect(page.locator('text=Estudiantes Atendidos')).toBeVisible();
  await expect(page.locator('text=Mentores Certificados')).toBeVisible();

  // Video explicativo
  await expect(page.locator('text=Conoce Nuestro Sistema')).toBeVisible();
  await expect(page.locator('a[href*="youtube.com"]')).toBeVisible();

  // Cómo solicitar una mentoría
  await expect(page.locator('text=¿Cómo Solicitar una Mentoría?')).toBeVisible();
  await expect(page.locator('text=Registro')).toBeVisible();
  await expect(page.locator('text=¡Aprende!')).toBeVisible();

  // Testimonios
  await expect(page.locator('text=Lo Que Dicen Nuestros Estudiantes')).toBeVisible();
  await expect(page.locator('.testimonial-card')).toHaveCount(3);

  // Call to Action final
  await expect(page.locator('text=¿Listo para Mejorar tu Rendimiento Académico?')).toBeVisible();
  await expect(page.locator('a:has-text("Registrarse Ahora")')).toBeVisible();
  await expect(page.locator('a:has-text("Más Información")')).toBeVisible();

  // Captura final
  await page.screenshot({ path: 'ui-tests/screenshots/home_carga_correcta.png' });
});
