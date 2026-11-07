<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyEcosystemApiKey
{
    /**
     * Handle an incoming ecosystem API request.
     *
     * Validates that the request contains the valid shared ecosystem API key,
     * allowing trusted inter-service communication (e.g., ZAVORA ↔ Learnerium).
     *
     * Supports:
     * - Authorization: Bearer <key>
     * - X-Ecosystem-Key: <key>
     * - ?api_key=<key> (fallback)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $expectedSecret = config('services.ecosystem.api_secret');

        if (empty($expectedSecret)) {
            return response()->json([
                'success' => false,
                'error'   => 'configuration_error',
                'message' => 'Ecosystem integration key (ECOSYSTEM_API_SECRET) is not configured on this LMS instance.',
            ], 500);
        }

        // Extract token from bearer header, custom header, or query param
        $providedToken = $request->bearerToken()
            ?: ($request->header('X-Ecosystem-Key')
            ?: $request->query('api_key'));

        if (empty($providedToken) || !hash_equals((string) $expectedSecret, (string) $providedToken)) {
            return response()->json([
                'success' => false,
                'error'   => 'unauthorized',
                'message' => 'Unauthorized. A valid Ecosystem API key is required.',
            ], 401);
        }

        return $next($request);
    }
}
