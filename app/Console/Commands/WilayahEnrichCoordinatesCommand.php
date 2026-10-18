<?php

/**
 * API Wilayah Indonesia 2026 - Kemendagri Standard
 * 
 * @author Roedy Rustam <roedyrustam@gmail.com>
 * @repository https://github.com/roedyrustam/API-Wilayah-2024
 * @license MIT
 */

namespace App\Console\Commands;

use App\Models\Kabupaten;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class WilayahEnrichCoordinatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wilayah:enrich-coordinates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enrich all 514 Kabupaten/Kota with accurate centroid coordinates (latitude and longitude)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai pengayaan data koordinat 514 Kabupaten/Kota...');

        $sourceUrl = 'https://raw.githubusercontent.com/yusufsyaifudin/wilayah-indonesia/master/data/list_of_area/regencies.json';
        $jsonContent = @file_get_contents($sourceUrl);

        if (!$jsonContent) {
            $this->error('Gagal mengunduh referensi koordinat dari GitHub repository.');
            return Command::FAILURE;
        }

        $sourceData = json_decode($jsonContent, true);
        if (!is_array($sourceData)) {
            $this->error('Format data sumber koordinat tidak valid.');
            return Command::FAILURE;
        }

        $cleanName = function (string $name): string {
            $name = preg_replace('/^(KABUPATEN|KOTA|ADM\.|KAB\.)\s+/i', '', $name);
            $name = strtolower(trim($name));
            return str_replace(['-', ' '], '', $name);
        };

        $kabupatens = Kabupaten::all();
        $updatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($kabupatens as $kab) {
                $match = null;

                // 1. Match by full_code
                foreach ($sourceData as $item) {
                    if ((string) $item['id'] === (string) $kab->full_code) {
                        $match = $item;
                        break;
                    }
                }

                // 2. Fallback: match by normalized name
                if (!$match) {
                    $targetClean = $cleanName($kab->name);
                    foreach ($sourceData as $item) {
                        if ($cleanName($item['name']) === $targetClean) {
                            $match = $item;
                            break;
                        }
                    }
                }

                if ($match && isset($match['latitude'], $match['longitude'])) {
                    $kab->latitude = (float) $match['latitude'];
                    $kab->longitude = (float) $match['longitude'];
                    $kab->save();
                    $updatedCount++;
                }
            }

            DB::commit();
            $this->info("Berhasil memperbarui koordinat untuk {$updatedCount} dari " . $kabupatens->count() . " Kabupaten/Kota.");
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("Terjadi kesalahan saat menyimpan koordinat: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
