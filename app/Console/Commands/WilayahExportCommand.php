<?php

/**
 * API Wilayah Indonesia 2026 - Kemendagri Standard
 * 
 * @author Roedy Rustam <roedyrustam@gmail.com>
 * @repository https://github.com/roedyrustam/API-Wilayah-2024
 * @license MIT
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class WilayahExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wilayah:export 
                            {level : Tingkat wilayah (provinsi, kabupaten, kecamatan, kelurahan)} 
                            {--format=csv : Format output (csv, json)} 
                            {--output= : Path file tujuan (default: storage/app/exports/...)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ekspor master data wilayah ke file CSV atau JSON lokal';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $level = strtolower($this->argument('level'));
        $format = strtolower($this->option('format') ?: 'csv');
        $validLevels = ['provinsi', 'kabupaten', 'kecamatan', 'kelurahan'];

        if (!in_array($level, $validLevels, true)) {
            $this->error("Tingkat wilayah '{$level}' tidak valid. Pilihan: provinsi, kabupaten, kecamatan, kelurahan.");
            return Command::INVALID;
        }

        $exportDir = storage_path('app/exports');
        if (!is_dir($exportDir)) {
            mkdir($exportDir, 0755, true);
        }

        $defaultPath = "{$exportDir}/wilayah_{$level}_2026.{$format}";
        $outputPath = $this->option('output') ?: $defaultPath;

        $this->info("Memulai proses ekspor data {$level} ke format {$format}...");

        if ($format === 'json') {
            $jsonFile = public_path("json/{$level}.json");
            if (file_exists($jsonFile)) {
                copy($jsonFile, $outputPath);
            } else {
                $this->error("File master JSON untuk {$level} tidak ditemukan di {$jsonFile}.");
                return Command::FAILURE;
            }
        } else {
            $fp = fopen($outputPath, 'w');
            // Write UTF-8 BOM
            fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $sourceJson = public_path("json/{$level}.json");
            if (!file_exists($sourceJson)) {
                $this->error("File master JSON {$sourceJson} tidak ditemukan.");
                fclose($fp);
                return Command::FAILURE;
            }

            $data = json_decode(file_get_contents($sourceJson), true);
            if (empty($data)) {
                $this->warn("Data {$level} kosong.");
                fclose($fp);
                return Command::SUCCESS;
            }

            // Write header from keys of first item
            fputcsv($fp, array_keys($data[0]));

            foreach ($data as $row) {
                fputcsv($fp, array_values($row));
            }

            fclose($fp);
        }

        $fileSize = number_format(filesize($outputPath) / 1024, 2);
        $this->info("[SUCCESS] Berhasil mengekspor data ke: {$outputPath} ({$fileSize} KB)");

        return Command::SUCCESS;
    }
}
