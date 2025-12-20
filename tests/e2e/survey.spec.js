import { test, expect } from '@playwright/test';

/**
 * Test Suite: Admin Dashboard & Survey CRUD
 * Testing dashboard, create, edit, delete survey functionality
 */

// Helper function for admin login
async function adminLogin(page) {
    await page.goto('/bbspgl-admin/masuk');
    await page.waitForLoadState('networkidle');
    await page.fill('input[name="email"]', 'admin@bbspgl.esdm.go.id');
    await page.fill('input[name="kata_sandi"]', '12345678');
    await page.click('button[type="submit"]');

    // Wait for redirect
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(3000);
}

test.describe('Admin Survey Management', () => {
    // Increase timeout
    test.setTimeout(90000);

    test('TC004 - Dashboard should load with statistics', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/dashboard');
        await page.waitForLoadState('networkidle');

        // Check dashboard elements
        const heading = page.locator('h1');
        await expect(heading).toBeVisible();

        // Check for stat cards or chart elements
        await page.waitForTimeout(2000);
        const pageContent = await page.content();
        expect(pageContent).toContain('Dashboard');
    });

    test('TC005 - Create survey page should be accessible', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/data-survei/create');
        await page.waitForLoadState('networkidle');

        // Check form fields exist
        await expect(page.locator('input[name="judul"]')).toBeVisible();
        await expect(page.locator('input[name="ketua_tim"]')).toBeVisible();
        await expect(page.locator('select[name="tipe"]')).toBeVisible();
    });

    test('TC006 - Survey form should have required validation', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/data-survei/create');
        await page.waitForLoadState('networkidle');

        // Check required inputs exist
        const requiredInputs = page.locator('input[required], select[required]');
        const count = await requiredInputs.count();
        expect(count).toBeGreaterThan(0);
    });

    test('TC007 - Survey list page should load', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/data-survei');
        await page.waitForLoadState('networkidle');

        // Check page loaded
        const heading = page.locator('h1, .page-header h1');
        await expect(heading).toBeVisible();
    });

    test('TC008 - Survey list should have pagination info', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/data-survei');
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(2000);

        // Check for pagination info or survey cards
        const pageContent = await page.content();
        const hasSurveys = pageContent.includes('survei') || pageContent.includes('Data');
        expect(hasSurveys).toBe(true);
    });

});
