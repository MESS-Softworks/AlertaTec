import { test, expect } from '@playwright/test';

test('has title', async ({ page }) => {
  await page.goto('file:///C:/Users/roces/OneDrive/Desktop/Organizar/VS%20PROYECTO%20MESS/AlertaTec/index.html');

   //Expect a title "to contain" a substring.
  await expect(page).toHaveTitle(/Alerta/);
  //await page.('link', {name: 'IR' }).click();
});

test('get started link', async ({ page }) => {
  await page.goto('https://playwright.dev/');

  // Click the get started link.
  await page.getByRole('link', { name: 'Get started' }).click();
  // Expects page to have a heading with the name of Installation.
  await expect(page.getByRole('heading', { name: 'Installation' })).toBeVisible();
});
