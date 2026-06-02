<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: API Key Authentication
 *
 * Endpoint yang dilindungi middleware ini memerlukan header:
 *   X-API-KEY: <api_key_dari_.env>
 *
 * Daftarkan di bootstrap/app.php atau Kernel.php alias 'api.key'
 */
class CheckApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-KEY');

        // Ambil daftar API keys dari .env (bisa lebih dari satu, pisah koma)
        $validKeys = array_filter(
            explode(',', env('API_KEYS', ''))
        );

        if (empty($validKeys) || !in_array($key, $validKeys, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid or missing API Key.',
                'hint'    => 'Sertakan header X-API-KEY yang valid.',
            ], 401);
        }

        return $next($request);
    }
}
