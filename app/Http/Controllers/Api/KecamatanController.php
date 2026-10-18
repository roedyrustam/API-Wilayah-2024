<?php

namespace App\Http\Controllers\Api;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KecamatanController extends BaseApiController
{
    /**
     * Get list of kecamatan with filtering and pagination.
     */
    public function index(Request $request): JsonResponse
    {
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

        // Kecamatan default is paginated because there are 7,277 records
        $perPage = min((int) $request->query('per_page', 25), 100);
        $paginated = $query->paginate($perPage);

        return $this->sendResponse(
            $paginated->items(),
            'Daftar kecamatan berhasil diambil',
            [
                'current_page' => $paginated->currentPage(),
                'per_page'     => $paginated->perPage(),
                'total'        => $paginated->total(),
                'last_page'    => $paginated->lastPage(),
            ]
        );
    }

    /**
     * Get kecamatan detail by id or full_code.
     */
    public function show(string|int $id): JsonResponse
    {
        $kecamatan = is_numeric($id) && (int) $id <= 10000 && strlen((string) $id) <= 5
            ? Kecamatan::with('kabupaten:id,name,type,full_code')->where('id', $id)->first()
            : Kecamatan::with('kabupaten:id,name,type,full_code')->where('full_code', (string) $id)->first();

        if (!$kecamatan) {
            return $this->sendError('Kecamatan tidak ditemukan', [], 404);
        }

        return $this->sendResponse($kecamatan, 'Detail kecamatan berhasil diambil');
    }

    /**
     * Get kelurahan in a kecamatan.
     */
    public function kelurahan(string|int $id, Request $request): JsonResponse
    {
        $kecamatan = is_numeric($id) && (int) $id <= 10000 && strlen((string) $id) <= 5
            ? Kecamatan::where('id', $id)->first()
            : Kecamatan::where('full_code', (string) $id)->first();

        if (!$kecamatan) {
            return $this->sendError('Kecamatan tidak ditemukan', [], 404);
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

            return $this->sendResponse(
                $paginated->items(),
                "Daftar kelurahan/desa di {$kecamatan->name} berhasil diambil",
                [
                    'current_page' => $paginated->currentPage(),
                    'per_page'     => $paginated->perPage(),
                    'total'        => $paginated->total(),
                    'last_page'    => $paginated->lastPage(),
                ]
            );
        }

        $data = $query->get();
        return $this->sendResponse($data, "Daftar seluruh kelurahan/desa di {$kecamatan->name} berhasil diambil", [
            'total' => $data->count(),
        ]);
    }
}
