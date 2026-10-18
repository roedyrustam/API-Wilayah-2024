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
use App\Models\Kabupaten;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CascaderController extends BaseApiController
{
    /**
     * Get single-trip hierarchical cascader tree for a province (Provinsi -> Kabupaten -> Kecamatan).
     *
     * @param string $id
     * @return JsonResponse
     */
    public function provinsi(string $id): JsonResponse
    {
        $cachedResult = $this->cached('cascader_provinsi', ['id' => $id], function () use ($id) {
            $prov = Provinsi::where('id', $id)
                ->orWhere('code', $id)
                ->first();

            if (!$prov) {
                return null;
            }

            $kabupatens = Kabupaten::where('provinsi_id', $prov->id)
                ->with(['kecamatans:id,name,code,full_code,kabupaten_id'])
                ->select(['id', 'provinsi_id', 'type', 'name', 'code', 'full_code', 'latitude', 'longitude'])
                ->orderBy('full_code', 'asc')
                ->get();

            return [
                'id'         => $prov->id,
                'name'       => $prov->name,
                'code'       => $prov->code,
                'timezone'   => $prov->timezone,
                'latitude'   => $prov->latitude ? (float) $prov->latitude : null,
                'longitude'  => $prov->longitude ? (float) $prov->longitude : null,
                'total_kab'  => $kabupatens->count(),
                'kabupatens' => $kabupatens->map(function ($kab) {
                    return [
                        'id'         => $kab->id,
                        'name'       => $kab->name,
                        'type'       => $kab->type,
                        'code'       => $kab->code,
                        'full_code'  => $kab->full_code,
                        'latitude'   => $kab->latitude ? (float) $kab->latitude : null,
                        'longitude'  => $kab->longitude ? (float) $kab->longitude : null,
                        'kecamatans' => $kab->kecamatans->map(function ($kec) {
                            return [
                                'id'        => $kec->id,
                                'name'      => $kec->name,
                                'code'      => $kec->code,
                                'full_code' => $kec->full_code,
                            ];
                        }),
                    ];
                }),
            ];
        });

        if (!$cachedResult) {
            return $this->sendError('Provinsi tidak ditemukan.', [], 404);
        }

        return $this->sendResponse($cachedResult, 'Hierarki cascader provinsi berhasil dimuat.');
    }

    /**
     * Get single-trip hierarchical cascader tree for a regency (Kabupaten -> Kecamatan -> Kelurahan).
     *
     * @param string $id
     * @return JsonResponse
     */
    public function kabupaten(string $id): JsonResponse
    {
        $cachedResult = $this->cached('cascader_kabupaten', ['id' => $id], function () use ($id) {
            $kab = Kabupaten::where('id', $id)
                ->orWhere('full_code', $id)
                ->with([
                    'provinsi:id,name,code,timezone',
                    'kecamatans' => function ($q) {
                        $q->select(['id', 'kabupaten_id', 'name', 'code', 'full_code'])
                          ->orderBy('full_code', 'asc');
                    },
                    'kecamatans.kelurahans' => function ($q) {
                        $q->select(['id', 'kecamatan_id', 'name', 'code', 'full_code', 'pos_code'])
                          ->orderBy('full_code', 'asc');
                    }
                ])
                ->first();

            if (!$kab) {
                return null;
            }

            return [
                'id'          => $kab->id,
                'name'        => $kab->name,
                'type'        => $kab->type,
                'code'        => $kab->code,
                'full_code'   => $kab->full_code,
                'latitude'    => $kab->latitude ? (float) $kab->latitude : null,
                'longitude'   => $kab->longitude ? (float) $kab->longitude : null,
                'provinsi'    => $kab->provinsi ? [
                    'id'   => $kab->provinsi->id,
                    'name' => $kab->provinsi->name,
                    'code' => $kab->provinsi->code,
                ] : null,
                'kecamatans'  => $kab->kecamatans->map(function ($kec) {
                    return [
                        'id'         => $kec->id,
                        'name'       => $kec->name,
                        'code'       => $kec->code,
                        'full_code'  => $kec->full_code,
                        'kelurahans' => $kec->kelurahans->map(function ($kel) {
                            return [
                                'id'        => $kel->id,
                                'name'      => $kel->name,
                                'code'      => $kel->code,
                                'full_code' => $kel->full_code,
                                'pos_code'  => $kel->pos_code,
                            ];
                        }),
                    ];
                }),
            ];
        });

        if (!$cachedResult) {
            return $this->sendError('Kabupaten/Kota tidak ditemukan.', [], 404);
        }

        return $this->sendResponse($cachedResult, 'Hierarki cascader kabupaten berhasil dimuat.');
    }
}
