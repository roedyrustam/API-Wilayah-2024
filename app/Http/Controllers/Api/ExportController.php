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
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\JsonResponse;

class ExportController extends BaseApiController
{
    /**
     * Export master data into CSV or JSON format.
     *
     * @param Request $request
     * @param string $level
     * @return StreamedResponse|JsonResponse
     */
    public function export(Request $request, string $level)
    {
        $level = strtolower($level);
        $validLevels = ['provinsi', 'kabupaten', 'kecamatan', 'kelurahan'];

        if (!in_array($level, $validLevels, true)) {
            return $this->sendError("Tingkat wilayah '{$level}' tidak valid. Pilihan: provinsi, kabupaten, kecamatan, kelurahan.", [], 404);
        }

        $format = strtolower((string) $request->query('format', 'csv'));
        $filename = "api_wilayah_{$level}_2026";

        if ($format === 'json') {
            return $this->exportJson($level, $filename);
        }

        return $this->exportCsv($level, $filename);
    }

    /**
     * Export data as formatted CSV download.
     */
    protected function exportCsv(string $level, string $filename): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($level) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            switch ($level) {
                case 'provinsi':
                    fputcsv($handle, ['id', 'code', 'name', 'latitude', 'longitude', 'timezone']);
                    Provinsi::orderBy('code')->chunk(500, function ($rows) use ($handle) {
                        foreach ($rows as $row) {
                            fputcsv($handle, [
                                $row->id,
                                $row->code,
                                $row->name,
                                $row->latitude,
                                $row->longitude,
                                $row->timezone,
                            ]);
                        }
                    });
                    break;

                case 'kabupaten':
                    fputcsv($handle, ['id', 'provinsi_id', 'provinsi_name', 'type', 'name', 'code', 'full_code', 'latitude', 'longitude', 'timezone']);
                    Kabupaten::with('provinsi:id,name')->orderBy('full_code')->chunk(500, function ($rows) use ($handle) {
                        foreach ($rows as $row) {
                            fputcsv($handle, [
                                $row->id,
                                $row->provinsi_id,
                                $row->provinsi ? $row->provinsi->name : '',
                                $row->type,
                                $row->name,
                                $row->code,
                                $row->full_code,
                                $row->latitude,
                                $row->longitude,
                                $row->timezone,
                            ]);
                        }
                    });
                    break;

                case 'kecamatan':
                    fputcsv($handle, ['id', 'kabupaten_id', 'kabupaten_name', 'name', 'code', 'full_code', 'latitude', 'longitude', 'timezone']);
                    Kecamatan::with('kabupaten:id,name,type')->orderBy('full_code')->chunk(1000, function ($rows) use ($handle) {
                        foreach ($rows as $row) {
                            fputcsv($handle, [
                                $row->id,
                                $row->kabupaten_id,
                                $row->kabupaten ? "{$row->kabupaten->type} {$row->kabupaten->name}" : '',
                                $row->name,
                                $row->code,
                                $row->full_code,
                                $row->latitude,
                                $row->longitude,
                                $row->timezone,
                            ]);
                        }
                    });
                    break;

                case 'kelurahan':
                    fputcsv($handle, ['id', 'kecamatan_id', 'kecamatan_name', 'name', 'code', 'full_code', 'pos_code', 'latitude', 'longitude', 'timezone']);
                    Kelurahan::with('kecamatan:id,name')->orderBy('full_code')->chunk(1000, function ($rows) use ($handle) {
                        foreach ($rows as $row) {
                            fputcsv($handle, [
                                $row->id,
                                $row->kecamatan_id,
                                $row->kecamatan ? $row->kecamatan->name : '',
                                $row->name,
                                $row->code,
                                $row->full_code,
                                $row->pos_code,
                                $row->latitude,
                                $row->longitude,
                                $row->timezone,
                            ]);
                        }
                    });
                    break;
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export data as formatted JSON stream.
     */
    protected function exportJson(string $level, string $filename): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'application/json; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.json\"",
        ];

        return response()->stream(function () use ($level) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "[\n");

            $isFirst = true;
            $modelMap = [
                'provinsi'  => Provinsi::query()->orderBy('code'),
                'kabupaten' => Kabupaten::with('provinsi:id,name,code')->orderBy('full_code'),
                'kecamatan' => Kecamatan::with('kabupaten:id,name,type,full_code')->orderBy('full_code'),
                'kelurahan' => Kelurahan::with('kecamatan:id,name,full_code')->orderBy('full_code'),
            ];

            $query = $modelMap[$level];
            $query->chunk(1000, function ($rows) use ($handle, &$isFirst) {
                foreach ($rows as $row) {
                    $json = json_encode($row->toArray(), JSON_UNESCAPED_UNICODE);
                    if (!$isFirst) {
                        fwrite($handle, ",\n");
                    } else {
                        $isFirst = false;
                    }
                    fwrite($handle, "  " . $json);
                }
            });

            fwrite($handle, "\n]\n");
            fclose($handle);
        }, 200, $headers);
    }
}
