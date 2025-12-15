<?php

namespace App\Services;

class HtmlSanitizerService
{
    private static $purifier = null;

    /**
     * Get configured HTMLPurifier instance
     */
    private static function getPurifier(): \HTMLPurifier
    {
        if (self::$purifier === null) {
            $config = \HTMLPurifier_Config::createDefault();

            // Configure allowed HTML elements and attributes for survey data
            $config->set('HTML.Allowed', 'div[class],p,br,strong,b,em,i,u,s,strike,span[style],ul,li,ol,blockquote,h1,h2,h3,h4,h5,h6,a[href|title],table[class],thead,tbody,tr,td[colspan|rowspan],th[colspan|rowspan],code,pre,img[src|alt|width|height|class]');

            // Allow safe CSS properties
            $config->set('CSS.AllowedProperties', 'color,background-color,font-weight,font-style,text-decoration,text-align,margin,padding,border,width,height');

            // Configure URI handling
            $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);
            $config->set('URI.DisableExternalResources', false);

            // Enable auto-formatting
            $config->set('AutoFormat.AutoParagraph', true);
            $config->set('AutoFormat.RemoveEmpty', true);
            $config->set('AutoFormat.RemoveSpansWithoutAttributes', true);

            // Security settings
            $config->set('HTML.Nofollow', true); // Add nofollow to external links
            $config->set('HTML.TargetBlank', true); // Open external links in new tab

            self::$purifier = new \HTMLPurifier($config);
        }

        return self::$purifier;
    }

    /**
     * Sanitize HTML content for survey descriptions
     */
    public static function sanitize(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return null;
        }

        return self::getPurifier()->purify($html);
    }

    /**
     * Sanitize HTML content with strict settings (for user input)
     */
    public static function sanitizeStrict(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return null;
        }

        $config = \HTMLPurifier_Config::createDefault();

        // Very restrictive settings for user input
        $config->set('HTML.Allowed', 'p,br,strong,b,em,i,u,a[href]');
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true]);
        $config->set('HTML.Nofollow', true);
        $config->set('HTML.TargetBlank', true);
        $config->set('AutoFormat.AutoParagraph', true);
        $config->set('AutoFormat.RemoveEmpty', true);

        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($html);
    }

    /**
     * Extract plain text from HTML (for search indexing, meta descriptions, etc.)
     */
    public static function toPlainText(?string $html, ?int $maxLength = null): ?string
    {
        if ($html === null || trim($html) === '') {
            return null;
        }

        // First sanitize, then strip all tags
        $sanitized = self::sanitize($html);
        $plainText = strip_tags($sanitized);

        // Clean up whitespace
        $plainText = preg_replace('/\s+/', ' ', $plainText);
        $plainText = trim($plainText);

        // Truncate if needed
        if ($maxLength && strlen($plainText) > $maxLength) {
            $plainText = substr($plainText, 0, $maxLength);
            $plainText = substr($plainText, 0, strrpos($plainText, ' ')) . '...';
        }

        return $plainText;
    }

    /**
     * Generate excerpt from HTML content
     */
    public static function excerpt(?string $html, int $length = 150): ?string
    {
        return self::toPlainText($html, $length);
    }
}
