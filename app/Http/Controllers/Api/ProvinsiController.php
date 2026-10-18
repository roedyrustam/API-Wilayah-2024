<?php

namespace App\Http\Controllers\Api;

use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProvinsiController extends BaseApiController
{
    /**
     * Get list of provinces with search and optional pagination.
     */
    public function index(Request $request): JsonResponse
    {
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

            return $this->sendResponse(
                $paginated->items(),
                'Daftar provinsi berhasil diambil',
                [
                    'current_page' => $paginated->currentPage(),
                    'per_page'     => $paginated->perPage(),
                    'total'        => $paginated->total(),
                    'last_page'    => $paginated->lastPage(),
                ]
            );
        }

        $data = $query->get();
        return $this->sendResponse($data, 'Daftar seluruh provinsi berhasil diambil', [
            'total' => $data->count(),
        ]);
    }

    /**
     * Get province detail by id or code.
     */
    public function show(string|int $id): JsonResponse
    {
        $provinsi = is_numeric($id) && strlen((string) $id) <= 2 && (int) $id <= 38
            ? Provinsi::where('id', $id)->orWhere('code', (string) $id)->first()
            : Provinsi::where('code', (string) $id)->first();

        if (!$provinsi) {
            return $this->sendError('Provinsi tidak ditemukan', [], 404);
        }

        return $this->sendResponse($provinsi, 'Detail provinsi berhasil diambil');
    }

    /**
     * Get kabupaten by province id or code.
     */
    public function kabupaten(string|int $id, Request $request): JsonResponse
    {
        $provinsi = is_numeric($id) && strlen((string) $id) <= 2 && (int) $id <= 38
            ? Provinsi::where('id', $id)->orWhere('code', (string) $id)->first()
            : Provinsi::where('code', (string) $id)->first();

        if (!$provinsi) {
            return $this->sendError('Provinsi tidak ditemukan', [], 404);
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

            return $this->sendResponse(
                $paginated->items(),
                "Daftar kabupaten/kota di {$provinsi->name} berhasil diambil",
                [
                    'current_page' => $paginated->currentPage(),
                    'per_page'     => $paginated->perPage(),
                    'total'        => $paginated->total(),
                    'last_page'    => $paginated->lastPage(),
                ]
            );
        }

        $data = $query->get();
        return $this->sendResponse($data, "Daftar seluruh kabupaten/kota di {$provinsi->name} berhasil diambil", [
            'total' => $data->count(),
        ]);
    }
}
