<?php

namespace App\Http\Controllers\Api;

use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KelurahanController extends BaseApiController
{
    /**
     * Get list of kelurahan/desa with filtering and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Kelurahan::with('kecamatan:id,name,full_code,kabupaten_id');

        if ($request->has('kecamatan_id')) {
            $query->where('kecamatan_id', $request->query('kecamatan_id'));
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

        if ($request->has('pos_code')) {
            $query->where('pos_code', $request->query('pos_code'));
        }

        $query->orderBy('full_code', 'asc');

        // Kelurahan default is paginated because there are 83,763 records
        $perPage = min((int) $request->query('per_page', 25), 100);
        $paginated = $query->paginate($perPage);

        return $this->sendResponse(
            $paginated->items(),
            'Daftar kelurahan/desa berhasil diambil',
            [
                'current_page' => $paginated->currentPage(),
                'per_page'     => $paginated->perPage(),
                'total'        => $paginated->total(),
                'last_page'    => $paginated->lastPage(),
            ]
        );
    }

    /**
     * Get kelurahan/desa detail by id or full_code.
     */
    public function show(string|int $id): JsonResponse
    {
        $kelurahan = is_numeric($id) && (int) $id <= 100000 && strlen((string) $id) <= 6
            ? Kelurahan::with('kecamatan.kabupaten.provinsi')->where('id', $id)->first()
            : Kelurahan::with('kecamatan.kabupaten.provinsi')->where('full_code', (string) $id)->first();

        if (!$kelurahan) {
            return $this->sendError('Kelurahan/Desa tidak ditemukan', [], 404);
        }

        return $this->sendResponse($kelurahan, 'Detail kelurahan/desa berhasil diambil');
    }
}
