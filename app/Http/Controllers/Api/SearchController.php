<?php

namespace App\Http\Controllers\Api;

use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends BaseApiController
{
    /**
     * Unified autocomplete search across all levels of administrative regions.
     */
    public function search(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if (strlen($q) < 2) {
            return $this->sendError('Kata kunci pencarian minimal 2 karakter', [], 422);
        }

        $cached = $this->cached('global_search', ['q' => $q], function () use ($q) {
            $provinsi = Provinsi::where('name', 'like', "%{$q}%")
                ->orWhere('code', $q)
                ->limit(5)
                ->get();

            $kabupaten = Kabupaten::with('provinsi:id,name,code')
                ->where('name', 'like', "%{$q}%")
                ->orWhere('full_code', $q)
                ->limit(10)
                ->get();

            $kecamatan = Kecamatan::with('kabupaten:id,name,type,full_code')
                ->where('name', 'like', "%{$q}%")
                ->orWhere('full_code', $q)
                ->limit(10)
                ->get();

            $kelurahan = Kelurahan::with('kecamatan:id,name,full_code')
                ->where('name', 'like', "%{$q}%")
                ->orWhere('pos_code', $q)
                ->orWhere('full_code', $q)
                ->limit(15)
                ->get();

            return [
                'provinsi'  => $provinsi,
                'kabupaten' => $kabupaten,
                'kecamatan' => $kecamatan,
                'kelurahan' => $kelurahan,
            ];
        });

        return $this->sendResponse($cached, "Hasil pencarian untuk '{$q}' berhasil diambil");
    }
}
