<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // XSS Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Content Type Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Frame Options
        $response->headers->set('X-Frame-Options', 'DENY');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // HSTS (HTTP Strict Transport Security) for HTTPS
        if ($request->isSecure() && config('security.security_headers.hsts.enabled')) {
            $hstsValue = 'max-age=' . config('security.security_headers.hsts.max_age');
            if (config('security.security_headers.hsts.include_subdomains')) {
                $hstsValue .= '; includeSubDomains';
            }
            $response->headers->set('Strict-Transport-Security', $hstsValue);
        }

        // Content Security Policy
        if (config('security.security_headers.csp.enabled')) {
            $cspHeader = config('security.security_headers.csp.report_only') ?
                'Content-Security-Policy-Report-Only' : 'Content-Security-Policy';

            $response->headers->set(
                $cspHeader,
                "default-src 'self'; " .
                    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://code.jquery.com https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com; " .
                    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com; " .
                    "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:; " .
                    "img-src 'self' data: https:; " .
                    "connect-src 'self' https://unpkg.com https://cdn.jsdelivr.net;"
            );
        }

        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
