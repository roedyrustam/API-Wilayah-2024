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

class WilayahSearchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wilayah:search {query : Kata kunci pencarian wilayah} {--limit=10 : Jumlah maksimal hasil per level}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cari data wilayah administratif (Provinsi, Kab/Kota, Kecamatan, Kelurahan) dari CLI';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $q = trim((string) $this->argument('query'));
        $limit = max(1, min(50, (int) $this->option('limit')));

        if (mb_strlen($q) < 2) {
            $this->error('Kata kunci pencarian minimal 2 karakter.');
            return Command::INVALID;
        }

        $this->info("Mencari wilayah dengan kata kunci: '{$q}'...\n");

        $isDbAvailable = false;
        try {
            \DB::connection()->getPdo();
            $isDbAvailable = true;
        } catch (\Throwable $e) {
            $isDbAvailable = false;
        }

        $rows = [];

        if ($isDbAvailable) {
            // Database search
            $provinsis = Provinsi::where('name', 'like', "%{$q}%")
                ->orWhere('code', 'like', "{$q}%")
                ->limit($limit)->get();
            foreach ($provinsis as $p) {
                $rows[] = ['Provinsi', $p->id, $p->code, $p->name, '-', '-', $p->timezone ?? 'WIB'];
            }

            $kabupatens = Kabupaten::with('provinsi')
                ->where('name', 'like', "%{$q}%")
                ->orWhere('full_code', 'like', "{$q}%")
                ->limit($limit)->get();
            foreach ($kabupatens as $k) {
                $rows[] = ['Kabupaten/Kota', $k->id, $k->full_code, "{$k->type} {$k->name}", $k->provinsi->name ?? '-', '-', $k->timezone ?? 'WIB'];
            }

            $kecamatans = Kecamatan::with(['kabupaten.provinsi'])
                ->where('name', 'like', "%{$q}%")
                ->orWhere('full_code', 'like', "{$q}%")
                ->limit($limit)->get();
            foreach ($kecamatans as $kc) {
                $parent = ($kc->kabupaten ? "{$kc->kabupaten->type} {$kc->kabupaten->name}" : '-');
                $rows[] = ['Kecamatan', $kc->id, $kc->full_code, $kc->name, $parent, '-', $kc->timezone ?? 'WIB'];
            }

            $kelurahans = Kelurahan::with(['kecamatan.kabupaten'])
                ->where('name', 'like', "%{$q}%")
                ->orWhere('full_code', 'like', "{$q}%")
                ->orWhere('pos_code', 'like', "{$q}%")
                ->limit($limit)->get();
            foreach ($kelurahans as $kl) {
                $parent = ($kl->kecamatan ? $kl->kecamatan->name : '-');
                $rows[] = ['Kelurahan/Desa', $kl->id, $kl->full_code, $kl->name, $parent, $kl->pos_code ?? '-', $kl->timezone ?? 'WIB'];
            }
        } else {
            // Master JSON fallback
            $provData = json_decode(file_get_contents(public_path('json/provinsi.json')), true) ?: [];
            $kabData = json_decode(file_get_contents(public_path('json/kabupaten.json')), true) ?: [];

            $provMap = [];
            $provTzMap = [];
            foreach ($provData as $p) {
                $provMap[$p['id']] = $p['name'];
                $provTzMap[$p['id']] = $p['timezone'] ?? 'WIB';
                if (stripos($p['name'], $q) !== false || str_starts_with($p['code'], $q)) {
                    $rows[] = ['Provinsi', $p['id'], $p['code'], $p['name'], '-', '-', $p['timezone'] ?? 'WIB'];
                }
            }

            foreach ($kabData as $k) {
                if (stripos($k['name'], $q) !== false || str_starts_with($k['full_code'], $q)) {
                    $parent = $provMap[$k['provinsi_id']] ?? '-';
                    $tz = $provTzMap[$k['provinsi_id']] ?? 'WIB';
                    $rows[] = ['Kabupaten/Kota', $k['id'], $k['full_code'], "{$k['type']} {$k['name']}", $parent, '-', $tz];
                    if (count($rows) >= 30) break;
                }
            }
        }

        if (empty($rows)) {
            $this->warn("Tidak ditemukan wilayah yang sesuai dengan '{$q}'.");
            return Command::SUCCESS;
        }

        $this->table(['Tingkat', 'ID', 'Kode', 'Nama Wilayah', 'Induk Wilayah', 'Kode Pos', 'Zona'], $rows);
        $this->info("Total ditemukan: " . count($rows) . " entri.");

        return Command::SUCCESS;
    }
}
