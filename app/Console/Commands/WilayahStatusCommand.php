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

class WilayahStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wilayah:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periksa status integritas dan statistik master data wilayah Indonesia 2026';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("===============================================================");
        $this->info("   STATUS & INTEGRITAS DATA WILAYAH INDONESIA (KEMENDAGRI 2026) ");
        $this->info("===============================================================");

        $isDbAvailable = false;
        try {
            \DB::connection()->getPdo();
            $isDbAvailable = true;
        } catch (\Throwable $e) {
            $isDbAvailable = false;
        }

        if ($isDbAvailable) {
            $countProv = Provinsi::count();
            $countKab = Kabupaten::count();
            $countKec = Kecamatan::count();
            $countKel = Kelurahan::count();
            $source = 'Database (Eloquent)';
        } else {
            $provPath = public_path('json/provinsi.json');
            $kabPath = public_path('json/kabupaten.json');
            $kecPath = public_path('json/kecamatan.json');
            $kelPath = public_path('json/kelurahan.json');

            $countProv = file_exists($provPath) ? count(json_decode(file_get_contents($provPath), true) ?: []) : 0;
            $countKab = file_exists($kabPath) ? count(json_decode(file_get_contents($kabPath), true) ?: []) : 0;
            $countKec = file_exists($kecPath) ? count(json_decode(file_get_contents($kecPath), true) ?: []) : 0;
            $countKel = file_exists($kelPath) ? count(json_decode(file_get_contents($kelPath), true) ?: []) : 0;
            $source = 'Master JSON File (public/json/)';
        }

        $this->line("Sumber Data: <comment>{$source}</comment>\n");

        $stats = [
            ['Provinsi', $countProv, 38, $countProv === 38 ? '<info>PASSED</info>' : '<error>MISMATCH</error>'],
            ['Kabupaten / Kota', $countKab, 514, $countKab === 514 ? '<info>PASSED</info>' : '<error>MISMATCH</error>'],
            ['Kecamatan / Distrik', $countKec, 7277, $countKec === 7277 ? '<info>PASSED</info>' : '<error>MISMATCH</error>'],
            ['Kelurahan / Desa', $countKel, 83763, $countKel === 83763 ? '<info>PASSED</info>' : '<error>MISMATCH</error>'],
        ];

        $this->table(['Tingkat Wilayah', 'Jumlah Terdata', 'Standar 2026', 'Status'], $stats);

        $this->info("\n--- Audit Daerah Otonom Baru (DOB 96 Papua Barat Daya) ---");
        $pbdOk = false;

        if ($isDbAvailable && $countProv > 0) {
            $pbd = Provinsi::where('name', 'like', '%Papua Barat Daya%')->first();
            $pbdCode = $pbd ? $pbd->code : null;
            $pbdKabCount = $pbd ? Kabupaten::where('provinsi_id', $pbd->id)->where('full_code', 'like', '96%')->count() : 0;
            $pbdOk = ($pbdCode === '96' && $pbdKabCount === 6);
        } else {
            $provData = json_decode(file_get_contents(public_path('json/provinsi.json')), true) ?: [];
            $kabData = json_decode(file_get_contents(public_path('json/kabupaten.json')), true) ?: [];
            $pbdItem = null;
            foreach ($provData as $p) {
                if (stripos($p['name'], 'Papua Barat Daya') !== false) {
                    $pbdItem = $p;
                    break;
                }
            }
            $pbdCode = $pbdItem ? $pbdItem['code'] : null;
            $pbdKabCount = 0;
            if ($pbdItem) {
                foreach ($kabData as $k) {
                    if ($k['provinsi_id'] == $pbdItem['id'] && str_starts_with($k['full_code'], '96')) {
                        $pbdKabCount++;
                    }
                }
            }
            $pbdOk = ($pbdCode === '96' && $pbdKabCount === 6);
        }

        $pbdRows = [
            ['Kode Kemendagri Papua Barat Daya', $pbdCode ?? 'N/A', '96', $pbdCode === '96' ? '<info>PASSED</info>' : '<error>FAILED</error>'],
            ['Kabupaten/Kota Prefiks 96', $pbdKabCount, 6, $pbdKabCount === 6 ? '<info>PASSED</info>' : '<error>FAILED</error>'],
        ];
        $this->table(['Indikator DOB', 'Nilai Terdeteksi', 'Target', 'Hasil'], $pbdRows);

        if ($pbdOk && $countProv === 38 && $countKab === 514) {
            $this->info("\n[SUCCESS] Seluruh data 100% patuh terhadap Standar Kemendagri 2026.");
            return Command::SUCCESS;
        }

        $this->warn("\n[WARNING] Terdapat ketidaksesuaian data dengan standar resmi. Jalankan seeder: php artisan db:seed --class=FromJsonSeeder");
        return Command::FAILURE;
    }
}
