import { test, expect } from '@playwright/test'

test.describe('Authentication', () => {
  test('should redirect unauthenticated user to login', async ({ page }) => {
    await page.goto('/')
    await expect(page).toHaveURL('/login')
  })

  test('should show validation errors on empty submit', async ({ page }) => {
    await page.goto('/login')
    await page.getByRole('button', { name: 'Entrar' }).click()
    await expect(page.getByText('E-mail inválido')).toBeVisible()
  })

  test('should show error on invalid credentials', async ({ page }) => {
    await page.goto('/login')
    await page.getByLabel('E-mail').fill('wrong@test.com')
    await page.getByLabel('Senha').fill('wrongpassword')
    await page.getByRole('button', { name: 'Entrar' }).click()
    await expect(page.getByText('E-mail ou senha inválidos')).toBeVisible({ timeout: 10000 })
  })

  test('should login and redirect to dashboard', async ({ page }) => {
    await page.goto('/login')
    await page.getByLabel('E-mail').fill('test@test.com')
    await page.getByLabel('Senha').fill('password')
    await page.getByRole('button', { name: 'Entrar' }).click()
    await expect(page).toHaveURL('/')
    await expect(page.getByRole('heading', { name: 'Dashboard' })).toBeVisible()
  })
})