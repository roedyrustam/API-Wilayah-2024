<?php

namespace App\Http\Controllers\Api;

use App\Models\Kabupaten;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KabupatenController extends BaseApiController
{
    /**
     * Get list of kabupaten/kota with filtering, pagination, and caching.
     */
    public function index(Request $request): JsonResponse
    {
        $params = $request->query();
        $cachedResult = $this->cached('kabupaten_index', $params, function () use ($request) {
            $query = Kabupaten::with('provinsi:id,name,code');

            if ($request->has('provinsi_id')) {
                $query->where('provinsi_id', $request->query('provinsi_id'));
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

            if ($request->has('type')) {
                $query->where('type', $request->query('type'));
            }

            $query->orderBy('full_code', 'asc');

            if ($request->boolean('paginate', false) || $request->has('per_page') || $request->has('page')) {
                $perPage = min((int) $request->query('per_page', 15), 100);
                $paginated = $query->paginate($perPage);

                return [
                    'items'   => $paginated->items(),
                    'message' => 'Daftar kabupaten/kota berhasil diambil',
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
                'message' => 'Daftar kabupaten/kota berhasil diambil',
                'meta'    => ['total' => $data->count()],
            ];
        });

        return $this->sendResponse($cachedResult['items'], $cachedResult['message'], $cachedResult['meta'] ?? null);
    }

    /**
     * Get kabupaten/kota detail by id or full_code with caching.
     */
    public function show(string|int $id): JsonResponse
    {
        $kabupaten = $this->cached('kabupaten_show', ['id' => $id], function () use ($id) {
            return is_numeric($id) && (int) $id <= 1000 && strlen((string) $id) <= 3
                ? Kabupaten::with('provinsi:id,name,code')->where('id', $id)->first()
                : Kabupaten::with('provinsi:id,name,code')->where('full_code', (string) $id)->first();
        });

        if (!$kabupaten) {
            return $this->sendError('Kabupaten/Kota tidak ditemukan', [], 404);
        }

        return $this->sendResponse($kabupaten, 'Detail kabupaten/kota berhasil diambil');
    }

    /**
     * Get kecamatan in a kabupaten/kota with caching.
     */
    public function kecamatan(string|int $id, Request $request): JsonResponse
    {
        $params = array_merge(['parent_id' => $id], $request->query());
        $cachedResult = $this->cached('kabupaten_kecamatan', $params, function () use ($id, $request) {
            $kabupaten = is_numeric($id) && (int) $id <= 1000 && strlen((string) $id) <= 3
                ? Kabupaten::where('id', $id)->first()
                : Kabupaten::where('full_code', (string) $id)->first();

            if (!$kabupaten) {
                return null;
            }

            $query = $kabupaten->kecamatan();

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->query('search') . '%');
            }

            $query->orderBy('full_code', 'asc');

            if ($request->boolean('paginate', false) || $request->has('per_page') || $request->has('page')) {
                $perPage = min((int) $request->query('per_page', 15), 100);
                $paginated = $query->paginate($perPage);

                return [
                    'items'   => $paginated->items(),
                    'message' => "Daftar kecamatan di {$kabupaten->name} berhasil diambil",
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
                'message' => "Daftar seluruh kecamatan di {$kabupaten->name} berhasil diambil",
                'meta'    => ['total' => $data->count()],
            ];
        });

        if ($cachedResult === null) {
            return $this->sendError('Kabupaten/Kota tidak ditemukan', [], 404);
        }

        return $this->sendResponse($cachedResult['items'], $cachedResult['message'], $cachedResult['meta'] ?? null);
    }
}
