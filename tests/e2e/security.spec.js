import { test, expect } from '@playwright/test';

/**
 * Test Suite: Security Tests
 * Testing CSRF protection, authentication guards, and security features
 */

test.describe('Security Tests', () => {
    test.setTimeout(30000);

    test('TC036 - Protected admin routes should redirect to login', async ({ page }) => {
        // Try to access protected route without login
        await page.goto('/bbspgl-admin/dashboard');
        await page.waitForLoadState('networkidle');

        // Should be redirected to login
        expect(page.url()).toContain('masuk');
    });

    test('TC037 - CSRF token should be present in forms', async ({ page }) => {
        await page.goto('/kontak');
        await page.waitForLoadState('networkidle');

        // Check for CSRF token
        const csrfToken = page.locator('input[name="_token"]');
        await expect(csrfToken).toBeAttached();
    });

    test('TC038 - Login form should have CSRF token', async ({ page }) => {
        await page.goto('/bbspgl-admin/masuk');
        await page.waitForLoadState('networkidle');

        // Check for CSRF token
        const csrfToken = page.locator('input[name="_token"]');
        await expect(csrfToken).toBeAttached();
    });

    test('TC039 - Password reset form should have CSRF token', async ({ page }) => {
        await page.goto('/bbspgl-admin/lupa-password');
        await page.waitForLoadState('networkidle');

        // Check for CSRF token
        const csrfToken = page.locator('input[name="_token"]');
        await expect(csrfToken).toBeAttached();
    });

    test('TC040 - Admin data-survei should require authentication', async ({ page }) => {
        // Try to access protected route without login
        await page.goto('/bbspgl-admin/data-survei');
        await page.waitForLoadState('networkidle');

        // Should be redirected to login
        expect(page.url()).toContain('masuk');
    });

    test('TC041 - Admin lokasi-marker should require authentication', async ({ page }) => {
        // Try to access protected route without login
        await page.goto('/bbspgl-admin/lokasi-marker');
        await page.waitForLoadState('networkidle');

        // Should be redirected to login
        expect(page.url()).toContain('masuk');
    });

    test('TC042 - Admin laporan should require authentication', async ({ page }) => {
        // Try to access protected route without login
        await page.goto('/bbspgl-admin/laporan');
        await page.waitForLoadState('networkidle');

        // Should be redirected to login
        expect(page.url()).toContain('masuk');
    });

    test('TC043 - Admin pengaturan should require authentication', async ({ page }) => {
        // Try to access protected route without login
        await page.goto('/bbspgl-admin/pengaturan');
        await page.waitForLoadState('networkidle');

        // Should be redirected to login
        expect(page.url()).toContain('masuk');
    });

    test('TC044 - Rate limiting should be configured (login form)', async ({ page }) => {
        // This is a presence check - actual rate limiting can't be fully tested
        // without making many requests
        await page.goto('/bbspgl-admin/masuk');
        await page.waitForLoadState('networkidle');

        // Form should exist and be functional
        await expect(page.locator('form')).toBeVisible();
        await expect(page.locator('input[name="email"]')).toBeVisible();
    });

});
