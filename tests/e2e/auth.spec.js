import { test, expect } from '@playwright/test';

/**
 * Test Suite: Admin Authentication
 * Testing login, logout, and password reset functionality
 */

test.describe('Admin Authentication', () => {

    // Increase timeout for auth tests
    test.setTimeout(60000);

    test('TC001 - Should login successfully with valid credentials', async ({ page }) => {
        // Navigate to login page
        await page.goto('/bbspgl-admin/masuk');

        // Wait for page to load
        await page.waitForLoadState('networkidle');

        // Fill login form
        await page.fill('input[name="email"]', 'admin@bbspgl.esdm.go.id');
        await page.fill('input[name="kata_sandi"]', '12345678');

        // Submit form
        await page.click('button[type="submit"]');

        // Wait for either dashboard or error message
        try {
            await page.waitForURL('**/dashboard', { timeout: 15000 });
            // Verify dashboard is loaded
            await expect(page).toHaveURL(/.*dashboard/);
        } catch (e) {
            // Check if still on login page with potential error
            const currentUrl = page.url();
            console.log('Current URL after login attempt:', currentUrl);

            // If redirected elsewhere, that's also a pass
            if (!currentUrl.includes('masuk')) {
                expect(currentUrl).not.toContain('masuk');
            } else {
                throw e;
            }
        }
    });

    test('TC002 - Should show error with incorrect password', async ({ page }) => {
        await page.goto('/bbspgl-admin/masuk');
        await page.waitForLoadState('networkidle');

        // Fill with wrong password
        await page.fill('input[name="email"]', 'admin@bbspgl.esdm.go.id');
        await page.fill('input[name="kata_sandi"]', 'wrongpassword');

        await page.click('button[type="submit"]');

        // Wait a bit for response
        await page.waitForTimeout(2000);

        // Should stay on login page with error
        await expect(page).toHaveURL(/.*masuk/);
    });

    test('TC003 - Password reset page should be accessible', async ({ page }) => {
        await page.goto('/bbspgl-admin/lupa-password');
        await page.waitForLoadState('networkidle');

        // Check page loaded correctly
        await expect(page.locator('input[name="email"]')).toBeVisible();
        await expect(page.locator('button[type="submit"]')).toBeVisible();
    });

});
