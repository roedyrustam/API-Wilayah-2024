<?php

/**
 * API Wilayah Indonesia 2026 - Kemendagri Standard
 * 
 * @author Roedy Rustam <roedyrustam@gmail.com>
 * @repository https://github.com/roedyrustam/API-Wilayah-2024
 * @license MIT
 */

namespace App\Http\Controllers\Api;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KecamatanController extends BaseApiController
{
    /**
     * Get list of kecamatan with filtering, pagination, and caching.
     */
    public function index(Request $request): JsonResponse
    {
        $params = $request->query();
        $cachedResult = $this->cached('kecamatan_index', $params, function () use ($request) {
            $query = Kecamatan::with('kabupaten:id,name,type,full_code');

            if ($request->has('kabupaten_id')) {
                $query->where('kabupaten_id', $request->query('kabupaten_id'));
            }

            if ($request->has('search')) {
                $search = $request->query('search');
                $query->where('name', 'like', "%{$search}%");
            }

            if ($request->has('code')) {
                $query->where('code', $request->query('code'));
            }

            if ($request->has('full_code')) {
                $query->where('full_code', $request->query('full_code'));
            }

            $query->orderBy('full_code', 'asc');

            $perPage = min((int) $request->query('per_page', 25), 100);
            $paginated = $query->paginate($perPage);

            return [
                'items'   => $paginated->items(),
                'message' => 'Daftar kecamatan berhasil diambil',
                'meta'    => [
                    'current_page' => $paginated->currentPage(),
                    'per_page'     => $paginated->perPage(),
                    'total'        => $paginated->total(),
                    'last_page'    => $paginated->lastPage(),
                ],
            ];
        });

        return $this->sendResponse($cachedResult['items'], $cachedResult['message'], $cachedResult['meta'] ?? null);
    }

    /**
     * Get kecamatan detail by id or full_code with caching.
     */
    public function show(string|int $id): JsonResponse
    {
        $kecamatan = $this->cached('kecamatan_show', ['id' => $id], function () use ($id) {
            return is_numeric($id) && (int) $id <= 10000 && strlen((string) $id) <= 5
                ? Kecamatan::with('kabupaten:id,name,type,full_code')->where('id', $id)->first()
                : Kecamatan::with('kabupaten:id,name,type,full_code')->where('full_code', (string) $id)->first();
        });

        if (!$kecamatan) {
            return $this->sendError('Kecamatan tidak ditemukan', [], 404);
        }

        return $this->sendResponse($kecamatan, 'Detail kecamatan berhasil diambil');
    }

    /**
     * Get kelurahan in a kecamatan with caching.
     */
    public function kelurahan(string|int $id, Request $request): JsonResponse
    {
        $params = array_merge(['parent_id' => $id], $request->query());
        $cachedResult = $this->cached('kecamatan_kelurahan', $params, function () use ($id, $request) {
            $kecamatan = is_numeric($id) && (int) $id <= 10000 && strlen((string) $id) <= 5
                ? Kecamatan::where('id', $id)->first()
                : Kecamatan::where('full_code', (string) $id)->first();

            if (!$kecamatan) {
                return null;
            }

            $query = $kecamatan->kelurahan();

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->query('search') . '%');
            }

            if ($request->has('pos_code')) {
                $query->where('pos_code', $request->query('pos_code'));
            }

            $query->orderBy('full_code', 'asc');

            if ($request->boolean('paginate', false) || $request->has('per_page') || $request->has('page')) {
                $perPage = min((int) $request->query('per_page', 25), 100);
                $paginated = $query->paginate($perPage);

                return [
                    'items'   => $paginated->items(),
                    'message' => "Daftar kelurahan/desa di {$kecamatan->name} berhasil diambil",
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
                'message' => "Daftar seluruh kelurahan/desa di {$kecamatan->name} berhasil diambil",
                'meta'    => ['total' => $data->count()],
            ];
        });

        if ($cachedResult === null) {
            return $this->sendError('Kecamatan tidak ditemukan', [], 404);
        }

        return $this->sendResponse($cachedResult['items'], $cachedResult['message'], $cachedResult['meta'] ?? null);
    }
}
