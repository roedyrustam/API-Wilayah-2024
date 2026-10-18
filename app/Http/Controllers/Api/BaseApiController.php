<?php

/**
 * API Wilayah Indonesia 2026 - Kemendagri Standard
 * 
 * @author Roedy Rustam <roedyrustam@gmail.com>
 * @repository https://github.com/roedyrustam/API-Wilayah-2024
 * @license MIT
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class BaseApiController extends Controller
{
    /**
     * Cache TTL in seconds (default: 86400 / 24 hours).
     */
    protected int $cacheTtl = 86400;

    /**
     * Helper to remember query result with request parameter fingerprinting.
     */
    protected function cached(string $resource, array $params, callable $callback): mixed
    {
        ksort($params);
        $key = 'api_wilayah:' . $resource . ':' . md5(serialize($params));

        return Cache::remember($key, $this->cacheTtl, $callback);
    }

    /**
     * Send a standardized success JSON response.
     */
    public function sendResponse(mixed $result, string $message = 'Data retrieved successfully', ?array $meta = null, int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'status'  => true,
            'message' => $message,
            'data'    => $result,
        ];

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }

    /**
     * Send a standardized error JSON response.
     */
    public function sendError(string $error, array $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'status'  => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
