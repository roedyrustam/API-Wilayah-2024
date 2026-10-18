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
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NearbyController extends BaseApiController
{
    /**
     * Search administrative regions within a specified radius (km) from given GPS coordinates.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        if ($lat === null || $lng === null || !is_numeric($lat) || !is_numeric($lng)) {
            return $this->sendError('Parameter "lat" dan "lng" wajib disertakan dan harus berupa angka numerik valid.', [], 422);
        }

        $lat = (float) $lat;
        $lng = (float) $lng;

        if ($lat < -90.0 || $lat > 90.0 || $lng < -180.0 || $lng > 180.0) {
            return $this->sendError('Koordinat tidak valid. Latitude harus antara -90 dan 90, Longitude antara -180 dan 180.', [], 422);
        }

        $radius = (float) $request->query('radius', 50.0);
        if ($radius <= 0.0 || $radius > 2000.0) {
            $radius = 50.0;
        }

        $level = strtolower((string) $request->query('level', 'provinsi'));
        $validLevels = ['provinsi', 'kabupaten', 'kecamatan', 'kelurahan'];
        if (!in_array($level, $validLevels, true)) {
            $level = 'provinsi';
        }

        $limit = (int) $request->query('limit', 10);
        if ($limit <= 0 || $limit > 50) {
            $limit = 10;
        }

        $cacheParams = [
            'lat'    => round($lat, 4),
            'lng'    => round($lng, 4),
            'radius' => round($radius, 1),
            'level'  => $level,
            'limit'  => $limit,
        ];

        $cachedResult = $this->cached('nearby', $cacheParams, function () use ($lat, $lng, $radius, $level, $limit) {
            // Model mapping and relationships
            $modelMap = [
                'provinsi' => [
                    'class' => Provinsi::class,
                    'with'  => [],
                ],
                'kabupaten' => [
                    'class' => Kabupaten::class,
                    'with'  => ['provinsi:id,name,code'],
                ],
                'kecamatan' => [
                    'class' => Kecamatan::class,
                    'with'  => ['kabupaten:id,name,type,full_code,provinsi_id', 'kabupaten.provinsi:id,name,code'],
                ],
                'kelurahan' => [
                    'class' => Kelurahan::class,
                    'with'  => ['kecamatan:id,name,full_code,kabupaten_id', 'kecamatan.kabupaten:id,name,type,full_code'],
                ],
            ];

            $config = $modelMap[$level];
            $modelClass = $config['class'];

            // Bounding box calculation for query optimization
            $latDelta = $radius / 111.0;
            $lngDelta = $radius / (111.0 * max(0.01, cos(deg2rad($lat))));

            $query = $modelClass::query()
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->whereBetween('latitude', [$lat - $latDelta, $lat + $latDelta])
                ->whereBetween('longitude', [$lng - $lngDelta, $lng + $lngDelta]);

            if (!empty($config['with'])) {
                $query->with($config['with']);
            }

            $candidates = $query->get();

            // Haversine exact calculation and sorting
            $earthRadiusKm = 6371.0;
            $results = [];

            foreach ($candidates as $item) {
                $itemLat = (float) $item->latitude;
                $itemLng = (float) $item->longitude;

                $dLat = deg2rad($itemLat - $lat);
                $dLon = deg2rad($itemLng - $lng);

                $a = sin($dLat / 2) * sin($dLat / 2) +
                     cos(deg2rad($lat)) * cos(deg2rad($itemLat)) *
                     sin($dLon / 2) * sin($dLon / 2);

                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $distanceKm = $earthRadiusKm * $c;

                if ($distanceKm <= $radius) {
                    $itemArray = $item->toArray();
                    $itemArray['distance_km'] = round($distanceKm, 2);
                    $results[] = $itemArray;
                }
            }

            usort($results, function ($a, $b) {
                return $a['distance_km'] <=> $b['distance_km'];
            });

            $limitedResults = array_slice($results, 0, $limit);

            return [
                'origin' => [
                    'latitude'  => $lat,
                    'longitude' => $lng,
                ],
                'level'       => $level,
                'radius_km'   => $radius,
                'total_found' => count($limitedResults),
                'data'        => $limitedResults,
            ];
        });

        return $this->sendResponse($cachedResult, 'Data wilayah sekitar berhasil ditemukan.');
    }
}
