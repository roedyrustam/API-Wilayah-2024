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

class GeoJsonController extends BaseApiController
{
    /**
     * Get GeoJSON RFC 7946 FeatureCollection of Provinces.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function provinsi(Request $request): JsonResponse
    {
        $params = $request->query();

        $cached = $this->cached('geojson_provinsi', $params, function () use ($request) {
            $query = Provinsi::whereNotNull('latitude')->whereNotNull('longitude');

            if ($request->has('search')) {
                $search = $request->query('search');
                $query->where('name', 'like', "%{$search}%");
            }

            if ($request->has('code')) {
                $query->where('code', $request->query('code'));
            }

            $items = $query->orderBy('code', 'asc')->get();

            $features = [];
            foreach ($items as $item) {
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float) $item->longitude, (float) $item->latitude],
                    ],
                    'properties' => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'code' => $item->code,
                        'timezone' => $item->timezone,
                        'level' => 'provinsi',
                    ],
                ];
            }

            return [
                'type' => 'FeatureCollection',
                'features' => $features,
                'metadata' => [
                    'generated_at' => now()->toIso8601String(),
                    'total_features' => count($features),
                    'standard' => 'RFC 7946',
                ],
            ];
        });

        return response()->json($cached)
            ->header('Content-Type', 'application/geo+json; charset=utf-8');
    }

    /**
     * Get GeoJSON RFC 7946 Feature of single Province.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function provinsiDetail(string $id): JsonResponse
    {
        $cached = $this->cached('geojson_provinsi_detail', ['id' => $id], function () use ($id) {
            $item = Provinsi::where('id', $id)->orWhere('code', $id)->first();

            if (!$item || $item->latitude === null || $item->longitude === null) {
                return null;
            }

            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float) $item->longitude, (float) $item->latitude],
                ],
                'properties' => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->code,
                    'timezone' => $item->timezone,
                    'level' => 'provinsi',
                ],
            ];
        });

        if (!$cached) {
            return $this->sendError('Data koordinat GeoJSON provinsi tidak ditemukan.', [], 404);
        }

        return response()->json($cached)
            ->header('Content-Type', 'application/geo+json; charset=utf-8');
    }

    /**
     * Get GeoJSON RFC 7946 FeatureCollection of Regencies / Cities (Kabupaten/Kota).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function kabupaten(Request $request): JsonResponse
    {
        $params = $request->query();

        $cached = $this->cached('geojson_kabupaten', $params, function () use ($request) {
            $query = Kabupaten::with('provinsi:id,name,code')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            if ($request->has('provinsi_id')) {
                $query->where('provinsi_id', $request->query('provinsi_id'));
            }

            if ($request->has('search')) {
                $query->where('name', 'like', "%" . $request->query('search') . "%");
            }

            $items = $query->orderBy('full_code', 'asc')->get();

            $features = [];
            foreach ($items as $item) {
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float) $item->longitude, (float) $item->latitude],
                    ],
                    'properties' => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'type' => $item->type,
                        'code' => $item->code,
                        'full_code' => $item->full_code,
                        'provinsi' => $item->provinsi ? $item->provinsi->name : null,
                        'timezone' => $item->timezone,
                        'level' => 'kabupaten',
                    ],
                ];
            }

            return [
                'type' => 'FeatureCollection',
                'features' => $features,
                'metadata' => [
                    'generated_at' => now()->toIso8601String(),
                    'total_features' => count($features),
                    'standard' => 'RFC 7946',
                ],
            ];
        });

        return response()->json($cached)
            ->header('Content-Type', 'application/geo+json; charset=utf-8');
    }
}
