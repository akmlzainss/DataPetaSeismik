import { test, expect } from '@playwright/test';

/**
 * Test Suite: Admin Laporan (Reports)
 * Testing report generation and export functionality
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

test.describe('Admin Laporan', () => {
    test.setTimeout(90000);

    test('TC025 - Laporan page should load', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/laporan');
        await page.waitForLoadState('networkidle');

        // Check page loaded
        const pageContent = await page.content();
        const hasContent = pageContent.includes('Laporan') ||
            pageContent.includes('Report') ||
            pageContent.includes('Export');
        expect(hasContent).toBe(true);
    });

    test('TC026 - Laporan should have filter options', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/laporan');
        await page.waitForLoadState('networkidle');

        // Check for filter form elements
        const filters = page.locator('select, input[type="date"], input[type="text"]');
        const hasFilters = await filters.count() > 0;
        expect(hasFilters).toBe(true);
    });

    test('TC027 - Laporan should have export buttons', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/laporan');
        await page.waitForLoadState('networkidle');

        // Check for export buttons (Excel/PDF)
        const pageContent = await page.content();
        const hasExport = pageContent.includes('Export') ||
            pageContent.includes('Excel') ||
            pageContent.includes('PDF') ||
            pageContent.includes('Download');
        expect(hasExport).toBe(true);
    });

    test('TC028 - Export Excel button should exist', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/laporan');
        await page.waitForLoadState('networkidle');

        // Check for Excel export button/link
        const excelBtn = page.locator('a[href*="export/excel"], a[href*="Excel"], button:has-text("Excel"), a:has-text("Excel")');
        const hasExcel = await excelBtn.count() > 0;

        // Export feature should exist
        expect(hasExcel).toBe(true);
    });

    test('TC029 - Export PDF button should exist', async ({ page }) => {
        await adminLogin(page);
        await page.goto('/bbspgl-admin/laporan');
        await page.waitForLoadState('networkidle');

        // Check for PDF export button/link
        const pdfBtn = page.locator('a[href*="export/pdf"], a[href*="PDF"], button:has-text("PDF"), a:has-text("PDF")');
        const hasPdf = await pdfBtn.count() > 0;

        // Export feature should exist
        expect(hasPdf).toBe(true);
    });

});
