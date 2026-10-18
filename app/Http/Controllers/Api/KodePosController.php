<?php

/**
 * API Wilayah Indonesia 2026 - Kemendagri Standard
 * 
 * @author Roedy Rustam <roedyrustam@gmail.com>
 * @repository https://github.com/roedyrustam/API-Wilayah-2024
 * @license MIT
 */

namespace App\Http\Controllers\Api;

use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KodePosController extends BaseApiController
{
    /**
     * Search and list postal codes with full administrative hierarchy.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $params = $request->query();

        $cachedResult = $this->cached('kodepos_index', $params, function () use ($request) {
            $query = Kelurahan::with([
                'kecamatan:id,name,code,full_code,kabupaten_id',
                'kecamatan.kabupaten:id,name,type,code,full_code,provinsi_id',
                'kecamatan.kabupaten.provinsi:id,name,code,timezone'
            ])
            ->whereNotNull('pos_code')
            ->where('pos_code', '!=', '');

            if ($request->has('search') || $request->has('q')) {
                $q = trim((string) ($request->query('search') ?? $request->query('q')));
                $query->where(function ($w) use ($q) {
                    $w->where('pos_code', 'like', "{$q}%")
                      ->orWhere('name', 'like', "%{$q}%");
                });
            }

            if ($request->has('pos_code')) {
                $query->where('pos_code', trim((string) $request->query('pos_code')));
            }

            $limit = min((int) $request->query('limit', 20), 100);
            if ($limit <= 0) {
                $limit = 20;
            }

            return $query->paginate($limit)->appends($request->query());
        });

        return $this->sendResponse($cachedResult, 'Daftar kode pos wilayah berhasil diambil.');
    }

    /**
     * Get detailed administrative hierarchy by exact 5-digit postal code.
     *
     * @param string $code
     * @return JsonResponse
     */
    public function show(string $code): JsonResponse
    {
        $code = trim($code);

        $cachedResult = $this->cached('kodepos_show', ['code' => $code], function () use ($code) {
            $villages = Kelurahan::with([
                'kecamatan:id,name,code,full_code,kabupaten_id',
                'kecamatan.kabupaten:id,name,type,code,full_code,provinsi_id',
                'kecamatan.kabupaten.provinsi:id,name,code,timezone'
            ])
            ->where('pos_code', $code)
            ->get();

            if ($villages->isEmpty()) {
                return null;
            }

            $formatted = $villages->map(function ($v) {
                $kec = $v->kecamatan;
                $kab = $kec ? $kec->kabupaten : null;
                $prov = $kab ? $kab->provinsi : null;

                return [
                    'id'          => $v->id,
                    'kelurahan'   => $v->name,
                    'code'        => $v->code,
                    'full_code'   => $v->full_code,
                    'pos_code'    => $v->pos_code,
                    'latitude'    => $v->latitude ? (float) $v->latitude : null,
                    'longitude'   => $v->longitude ? (float) $v->longitude : null,
                    'kecamatan'   => $kec ? [
                        'id'        => $kec->id,
                        'name'      => $kec->name,
                        'full_code' => $kec->full_code,
                    ] : null,
                    'kabupaten'   => $kab ? [
                        'id'        => $kab->id,
                        'name'      => $kab->name,
                        'type'      => $kab->type,
                        'full_code' => $kab->full_code,
                    ] : null,
                    'provinsi'    => $prov ? [
                        'id'        => $prov->id,
                        'name'      => $prov->name,
                        'code'      => $prov->code,
                        'timezone'  => $prov->timezone,
                    ] : null,
                ];
            });

            return [
                'pos_code'        => $code,
                'total_villages'  => $formatted->count(),
                'villages'        => $formatted,
            ];
        });

        if (!$cachedResult) {
            return $this->sendError("Kode pos {$code} tidak ditemukan dalam database wilayah resmi.", [], 404);
        }

        return $this->sendResponse($cachedResult, "Data kode pos {$code} berhasil ditemukan.");
    }
}
