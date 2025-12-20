import { test, expect } from '@playwright/test';

/**
 * Test Suite: Public Pages
 * Testing public-facing pages accessibility and functionality
 */

test.describe('Public Pages', () => {
    // Set reasonable timeout
    test.setTimeout(30000);

    test('TC009 - Homepage should load correctly', async ({ page }) => {
        await page.goto('/');
        await page.waitForLoadState('networkidle');

        // Check navbar is visible
        await expect(page.locator('.navbar')).toBeVisible();

        // Check footer is visible
        await expect(page.locator('footer, .footer')).toBeVisible();
    });

    test('TC010 - Catalog page should load', async ({ page }) => {
        await page.goto('/katalog');
        await page.waitForLoadState('networkidle');

        // Check page content
        const pageContent = await page.content();
        expect(pageContent.toLowerCase()).toContain('katalog');
    });

    test('TC011 - Catalog search should work', async ({ page }) => {
        await page.goto('/katalog');
        await page.waitForLoadState('networkidle');

        // Fill search if input exists
        const searchInput = page.locator('input[name="search"]');
        if (await searchInput.isVisible()) {
            await searchInput.fill('seismik');
            await page.click('button[type="submit"], .filter-button');
            await page.waitForLoadState('networkidle');

            // URL should include search param
            const url = page.url();
            expect(url).toContain('search');
        }
    });

    test('TC012 - Map page should load', async ({ page }) => {
        await page.goto('/peta');
        await page.waitForLoadState('networkidle');

        // Check map container exists
        await expect(page.locator('#map')).toBeVisible();

        // Wait for map to initialize
        await page.waitForTimeout(3000);
    });

    test('TC013 - Map zoom buttons should exist', async ({ page }) => {
        await page.goto('/peta');
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(3000);

        // Check Leaflet default zoom buttons exist
        const zoomIn = page.locator('.leaflet-control-zoom-in');
        const zoomOut = page.locator('.leaflet-control-zoom-out');

        await expect(zoomIn).toBeVisible();
        await expect(zoomOut).toBeVisible();
    });

    test('TC014 - Contact page should load with form', async ({ page }) => {
        await page.goto('/kontak');
        await page.waitForLoadState('networkidle');

        // Check form exists
        await expect(page.locator('form')).toBeVisible();

        // Check form fields
        await expect(page.locator('input[name="nama_lengkap"]')).toBeVisible();
        await expect(page.locator('input[name="email"]')).toBeVisible();
    });

    test('TC015 - Contact form has validation', async ({ page }) => {
        await page.goto('/kontak');
        await page.waitForLoadState('networkidle');

        // Check required fields exist
        const requiredInputs = page.locator('input[required]');
        const count = await requiredInputs.count();
        expect(count).toBeGreaterThan(0);
    });

    test('TC016 - About page should load', async ({ page }) => {
        await page.goto('/tentang');
        await page.waitForLoadState('networkidle');

        // Page should load
        const pageContent = await page.content();
        expect(pageContent.length).toBeGreaterThan(1000);
    });



});
