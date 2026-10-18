<?php

/**
 * API Wilayah Indonesia 2026 - Kemendagri Standard
 * 
 * @author Roedy Rustam <roedyrustam@gmail.com>
 * @repository https://github.com/roedyrustam/API-Wilayah-2024
 * @license MIT
 */

namespace App\Http\Controllers\Api;

use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProvinsiController extends BaseApiController
{
    /**
     * Get list of provinces with search, optional pagination, and caching.
     */
    public function index(Request $request): JsonResponse
    {
        $params = $request->query();
        $cachedResult = $this->cached('provinsi_index', $params, function () use ($request) {
            $query = Provinsi::query();

            if ($request->has('search')) {
                $search = $request->query('search');
                $query->where('name', 'like', "%{$search}%");
            }

            if ($request->has('code')) {
                $query->where('code', $request->query('code'));
            }

            $query->orderBy('code', 'asc');

            if ($request->boolean('paginate', false) || $request->has('per_page') || $request->has('page')) {
                $perPage = min((int) $request->query('per_page', 15), 100);
                $paginated = $query->paginate($perPage);

                return [
                    'items'   => $paginated->items(),
                    'message' => 'Daftar provinsi berhasil diambil',
                    'meta'    => [
                        'current_page' => $paginated->currentPage(),
                        'per_page'     => $paginated->perPage(),
                        'total'        => $paginated->total(),
                        'last_page'    => $paginated->lastPage(),
                    ],
                ];
            }

            $data = $query->get();
            return [
                'items'   => $data,
                'message' => 'Daftar seluruh provinsi berhasil diambil',
                'meta'    => ['total' => $data->count()],
            ];
        });

        return $this->sendResponse($cachedResult['items'], $cachedResult['message'], $cachedResult['meta'] ?? null);
    }

    /**
     * Get province detail by id or code with caching.
     */
    public function show(string|int $id): JsonResponse
    {
        $provinsi = $this->cached('provinsi_show', ['id' => $id], function () use ($id) {
            return is_numeric($id) && strlen((string) $id) <= 2 && (int) $id <= 38
                ? Provinsi::where('id', $id)->orWhere('code', (string) $id)->first()
                : Provinsi::where('code', (string) $id)->first();
        });

        if (!$provinsi) {
            return $this->sendError('Provinsi tidak ditemukan', [], 404);
        }

        return $this->sendResponse($provinsi, 'Detail provinsi berhasil diambil');
    }

    /**
     * Get kabupaten by province id or code with caching.
     */
    public function kabupaten(string|int $id, Request $request): JsonResponse
    {
        $params = array_merge(['parent_id' => $id], $request->query());
        $cachedResult = $this->cached('provinsi_kabupaten', $params, function () use ($id, $request) {
            $provinsi = is_numeric($id) && strlen((string) $id) <= 2 && (int) $id <= 38
                ? Provinsi::where('id', $id)->orWhere('code', (string) $id)->first()
                : Provinsi::where('code', (string) $id)->first();

            if (!$provinsi) {
                return null;
            }

            $query = $provinsi->kabupaten();

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->query('search') . '%');
            }

            if ($request->has('type')) {
                $query->where('type', $request->query('type'));
            }

            $query->orderBy('full_code', 'asc');

            if ($request->boolean('paginate', false) || $request->has('per_page') || $request->has('page')) {
                $perPage = min((int) $request->query('per_page', 15), 100);
                $paginated = $query->paginate($perPage);

                return [
                    'items'   => $paginated->items(),
                    'message' => "Daftar kabupaten/kota di {$provinsi->name} berhasil diambil",
                    'meta'    => [
                        'current_page' => $paginated->currentPage(),
                        'per_page'     => $paginated->perPage(),
                        'total'        => $paginated->total(),
                        'last_page'    => $paginated->lastPage(),
                    ],
                ];
            }

            $data = $query->get();
            return [
                'items'   => $data,
                'message' => "Daftar seluruh kabupaten/kota di {$provinsi->name} berhasil diambil",
                'meta'    => ['total' => $data->count()],
            ];
        });

        if ($cachedResult === null) {
            return $this->sendError('Provinsi tidak ditemukan', [], 404);
        }

        return $this->sendResponse($cachedResult['items'], $cachedResult['message'], $cachedResult['meta'] ?? null);
    }
}
