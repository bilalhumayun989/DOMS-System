<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowIframeEmbedding
{
    /**
     * Handle an incoming request.
     *
     * Removes the X-Frame-Options header that browsers use to block iframes,
     * and replaces it with a permissive Content-Security-Policy frame-ancestors
     * directive so only the broshtech.com marketing site can embed this app.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Remove the default SAMEORIGIN restriction.
        $response->headers->remove('X-Frame-Options');

        // Allow embedding only from the marketing site and local dev.
        $response->headers->set(
            'Content-Security-Policy',
            "frame-ancestors 'self' https://www.broshtech.com https://broshtech.com http://localhost:* http://127.0.0.1:*"
        );

        return $response;
    }
}
