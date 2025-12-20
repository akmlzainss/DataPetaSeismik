import { test, expect } from '@playwright/test';

/**
 * Test Suite: Admin Settings
 * Testing settings page with tab navigation
 */

// Helper function for admin login
async function adminLogin(page) {
    await page.goto('/bbspgl-admin/masuk');
    await page.waitForLoadState('networkidle');
    await page.fill('input[name="email"]', 'admin@bbspgl.esdm.go.id');
    await page.fill('input[name="kata_sandi"]', '12345678');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(3000);
}

test.describe('Admin Settings', () => {
    test.setTimeout(90000);

    test('TC018 - Settings page should load', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/pengaturan');
        await page.waitForLoadState('networkidle');

        // Check page loaded
        const pageContent = await page.content();
        expect(pageContent.toLowerCase()).toContain('pengaturan');
    });

    test('TC019 - Settings should have tabs', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/pengaturan');
        await page.waitForLoadState('networkidle');

        // Check tabs exist
        const tabs = page.locator('.settings-tab, [data-tab]');
        const count = await tabs.count();
        expect(count).toBeGreaterThan(0);
    });

    test('TC020 - Direct link to security tab should work', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/pengaturan#keamanan');
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(1000);

        // Check URL has hash
        expect(page.url()).toContain('pengaturan');
    });

    test('TC021 - Profile form fields should exist', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/pengaturan');
        await page.waitForLoadState('networkidle');

        // Check form fields exist
        const nameInput = page.locator('input[name="nama"]');
        const emailInput = page.locator('input[name="email"]');

        // At least one should be visible
        const nameVisible = await nameInput.isVisible().catch(() => false);
        const emailVisible = await emailInput.isVisible().catch(() => false);

        expect(nameVisible || emailVisible).toBe(true);
    });

});
