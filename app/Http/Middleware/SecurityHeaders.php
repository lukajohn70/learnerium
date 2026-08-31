<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Apply security headers to every HTTP response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking — SAMEORIGIN allows iframes within our own pages (e.g. lesson player)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Control Referrer information sent with requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restrict browser features available to the page (leave payment enabled for Paystack)
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Force HTTPS for 2 years (only applies when served over HTTPS)
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
        }

        // Remove server/version disclosure headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // Content Security Policy — allows YouTube/Vimeo/Drive in player, Plyr CDN, Paystack
        $response->headers->set(
            'Content-Security-Policy',
            implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://js.paystack.co https://cdn.plyr.io https://www.youtube.com https://s.ytimg.com",
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.plyr.io",
                "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com https://cdn.plyr.io",
                "img-src 'self' data: https: blob:",
                "media-src 'self' https: blob:",
                "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://player.vimeo.com https://drive.google.com https://checkout.paystack.com https://*.paystack.com",
                "connect-src 'self' https://api.paystack.co https://checkout.paystack.com https://*.paystack.com https://*.paystack.co https://cdn.plyr.io",
                "worker-src blob:",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self' https://checkout.paystack.com https://*.paystack.com https://api.paystack.co",
                // frame-ancestors controls who can embed US — 'self' allows our own lesson page to embed YouTube iframes
                "frame-ancestors 'self'",
            ])
        );

        return $response;
    }
}
