import { test, expect } from '@playwright/test';

/**
 * Test Suite: Admin Lokasi Marker
 * Testing location marker management functionality
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

test.describe('Admin Lokasi Marker', () => {
    test.setTimeout(90000);

    test('TC022 - Lokasi Marker page should load', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/lokasi-marker');
        await page.waitForLoadState('networkidle');

        // Check page loaded - should have map or marker list
        const pageContent = await page.content();
        const hasContent = pageContent.includes('Lokasi') ||
            pageContent.includes('Marker') ||
            pageContent.includes('map');
        expect(hasContent).toBe(true);
    });

    test('TC023 - Lokasi Marker should have map component', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/lokasi-marker');
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(2000);

        // Check for map container
        const mapContainer = page.locator('#map, .map-container, [class*="map"]');
        const hasMap = await mapContainer.count() > 0;
        expect(hasMap).toBe(true);
    });

    test('TC024 - Lokasi Marker should have survey dropdown', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/lokasi-marker');
        await page.waitForLoadState('networkidle');

        // Check for survey selector
        const surveySelect = page.locator('select, [class*="select"]');
        const hasSelect = await surveySelect.count() > 0;
        expect(hasSelect).toBe(true);
    });

});
