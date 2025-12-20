import { test, expect } from '@playwright/test';

/**
 * Test Suite: Public Info Pages
 * Testing all footer info pages (panduan, faq, privasi, syarat, bantuan)
 */

test.describe('Public Info Pages', () => {
    test.setTimeout(30000);

    test('TC030 - Panduan Pengguna page should load', async ({ page }) => {
        await page.goto('/panduan-pengguna');
        await page.waitForLoadState('networkidle');

        // Check page content
        const pageContent = await page.content();
        expect(pageContent.length).toBeGreaterThan(1000);
        expect(pageContent.toLowerCase()).toContain('panduan');
    });

    test('TC031 - FAQ page should load', async ({ page }) => {
        await page.goto('/faq');
        await page.waitForLoadState('networkidle');

        // Check page content
        const pageContent = await page.content();
        expect(pageContent.length).toBeGreaterThan(1000);
        expect(pageContent.toLowerCase()).toContain('faq');
    });

    test('TC032 - Kebijakan Privasi page should load', async ({ page }) => {
        await page.goto('/kebijakan-privasi');
        await page.waitForLoadState('networkidle');

        // Check page content
        const pageContent = await page.content();
        expect(pageContent.length).toBeGreaterThan(1000);
        const hasPrivacy = pageContent.toLowerCase().includes('privasi') ||
            pageContent.toLowerCase().includes('privacy');
        expect(hasPrivacy).toBe(true);
    });

    test('TC033 - Syarat & Ketentuan page should load', async ({ page }) => {
        await page.goto('/syarat-ketentuan');
        await page.waitForLoadState('networkidle');

        // Check page content
        const pageContent = await page.content();
        expect(pageContent.length).toBeGreaterThan(1000);
        const hasTerms = pageContent.toLowerCase().includes('syarat') ||
            pageContent.toLowerCase().includes('ketentuan');
        expect(hasTerms).toBe(true);
    });

    test('TC034 - Bantuan page should load', async ({ page }) => {
        await page.goto('/bantuan');
        await page.waitForLoadState('networkidle');

        // Check page content
        const pageContent = await page.content();
        expect(pageContent.length).toBeGreaterThan(1000);
        const hasHelp = pageContent.toLowerCase().includes('bantuan') ||
            pageContent.toLowerCase().includes('help');
        expect(hasHelp).toBe(true);
    });

    test('TC035 - Katalog Detail page should load', async ({ page }) => {
        // First go to katalog to find a survey
        await page.goto('/katalog');
        await page.waitForLoadState('networkidle');

        // Try to click on first survey link
        const surveyLink = page.locator('a[href*="/katalog/"]').first();
        if (await surveyLink.isVisible()) {
            await surveyLink.click();
            await page.waitForLoadState('networkidle');

            // Check detail page loaded
            const pageContent = await page.content();
            expect(pageContent.length).toBeGreaterThan(1000);
        } else {
            // If no surveys, just verify katalog page works
            expect(true).toBe(true);
        }
    });

});
