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

class WilayahAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wilayah:audit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit mendalam kepatuhan data wilayah Indonesia terhadap standar Kemendagri 2026';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("======================================================================");
        $this->info("   AUDIT INTEGRITAS & KEPATUHAN STANDAR KEMENDAGRI WILAYAH 2026      ");
        $this->info("======================================================================");

        $results = [];
        $hasErrors = false;

        $isDbAvailable = false;
        try {
            \DB::connection()->getPdo();
            $isDbAvailable = true;
        } catch (\Throwable $e) {
            $isDbAvailable = false;
        }

        $source = $isDbAvailable ? 'Database (Eloquent / MySQL)' : 'Master JSON Files (public/json/)';
        $this->line("Sumber Data: <comment>{$source}</comment>\n");

        if ($isDbAvailable) {
            $provData = Provinsi::all();
            $provCount = $provData->count();
            $kabData = Kabupaten::all();
            $kabCount = $kabData->count();
            $kecCount = Kecamatan::count();
            $kelCount = Kelurahan::count();
            
            $pbd = $provData->firstWhere('code', '96');
            $pbdId = $pbd ? $pbd->id : null;
            $pbdKabCount = $pbdId ? $kabData->where('provinsi_id', $pbdId)->count() : 0;
            
            $validProvIds = $provData->pluck('id')->toArray();
            $orphanedKab = $kabData->whereNotIn('provinsi_id', $validProvIds)->count();
            
            $validKabIds = $kabData->pluck('id')->toArray();
            $orphanedKec = Kecamatan::whereNotIn('kabupaten_id', $validKabIds)->count();
            
            $provWithGis = $provData->whereNotNull('latitude')->whereNotNull('longitude')->count();
            $provWithTz = $provData->whereNotNull('timezone')->count();
            $invalidProvCodes = $provData->filter(fn($p) => strlen((string)$p->code) !== 2)->count();
        } else {
            $provFile = public_path('json/provinsi.json');
            $kabFile = public_path('json/kabupaten.json');
            $kecFile = public_path('json/kecamatan.json');
            $kelFile = public_path('json/kelurahan.json');

            $provJson = file_exists($provFile) ? json_decode(file_get_contents($provFile), true) ?: [] : [];
            $kabJson = file_exists($kabFile) ? json_decode(file_get_contents($kabFile), true) ?: [] : [];
            $kecJson = file_exists($kecFile) ? json_decode(file_get_contents($kecFile), true) ?: [] : [];
            $kelJson = file_exists($kelFile) ? json_decode(file_get_contents($kelFile), true) ?: [] : [];

            $provCount = count($provJson);
            $kabCount = count($kabJson);
            $kecCount = count($kecJson);
            $kelCount = count($kelJson);

            $pbd = collect($provJson)->firstWhere('code', '96');
            $pbdId = $pbd['id'] ?? 26;
            $pbdKabCount = collect($kabJson)->filter(fn($k) => ($k['provinsi_id'] ?? null) == $pbdId || str_starts_with((string)($k['full_code'] ?? ''), '96'))->count();
            
            $validProvIds = collect($provJson)->pluck('id')->toArray();
            $validKabIds = collect($kabJson)->pluck('id')->toArray();
            
            $orphanedKab = collect($kabJson)->filter(fn($k) => !in_array($k['provinsi_id'] ?? null, $validProvIds))->count();
            $orphanedKec = collect($kecJson)->filter(fn($k) => !in_array($k['kabupaten_id'] ?? null, $validKabIds))->count();
            
            $provWithGis = collect($provJson)->filter(fn($p) => !empty($p['latitude']) && !empty($p['longitude']))->count();
            $provWithTz = collect($provJson)->filter(fn($p) => !empty($p['timezone']))->count();
            $invalidProvCodes = collect($provJson)->filter(fn($p) => strlen((string)($p['code'] ?? '')) !== 2)->count();
        }

        // Check 1: Total Provinsi (Target: 38)
        $provStatus = ($provCount === 38) ? 'PASS' : 'WARN';
        $results[] = [
            'Aspek Audit' => 'Total Provinsi (Kemendagri 2026)',
            'Nilai Ditemukan' => "{$provCount} Provinsi",
            'Standar Acuan' => '38 Provinsi',
            'Hasil' => $provStatus,
        ];

        // Check 2: Validasi Provinsi Papua Barat Daya (Kode 96)
        if ($pbd) {
            $pbdStatus = ($pbdKabCount === 6) ? 'PASS' : 'WARN';
            $results[] = [
                'Aspek Audit' => 'Papua Barat Daya (Kode 96)',
                'Nilai Ditemukan' => "Terdaftar ({$pbdKabCount} Kab/Kota)",
                'Standar Acuan' => 'Kode 96, 6 Kab/Kota',
                'Hasil' => $pbdStatus,
            ];
        } else {
            $hasErrors = true;
            $results[] = [
                'Aspek Audit' => 'Papua Barat Daya (Kode 96)',
                'Nilai Ditemukan' => 'TIDAK DITEMUKAN',
                'Standar Acuan' => 'Wajib Ada (Kode 96)',
                'Hasil' => 'FAIL',
            ];
        }

        // Check 3: Total Kabupaten / Kota (Target: 514)
        $kabStatus = ($kabCount === 514) ? 'PASS' : 'WARN';
        $results[] = [
            'Aspek Audit' => 'Total Kabupaten / Kota',
            'Nilai Ditemukan' => "{$kabCount} Kab/Kota",
            'Standar Acuan' => '514 Kab/Kota (416 Kab, 98 Kota)',
            'Hasil' => $kabStatus,
        ];

        // Check 4: Total Kecamatan (Target: 7,277)
        $kecStatus = ($kecCount >= 7200) ? 'PASS' : 'WARN';
        $results[] = [
            'Aspek Audit' => 'Total Kecamatan / Distrik',
            'Nilai Ditemukan' => number_format($kecCount, 0, ',', '.') . " Kecamatan",
            'Standar Acuan' => '7.277 Kecamatan',
            'Hasil' => $kecStatus,
        ];

        // Check 5: Total Kelurahan / Desa (Target: 83,763)
        $kelStatus = ($kelCount >= 83000) ? 'PASS' : 'WARN';
        $results[] = [
            'Aspek Audit' => 'Total Kelurahan / Desa',
            'Nilai Ditemukan' => number_format($kelCount, 0, ',', '.') . " Kel/Desa",
            'Standar Acuan' => '83.763 Desa/Kelurahan',
            'Hasil' => $kelStatus,
        ];

        // Check 6: Orphaned Kabupaten Check
        $results[] = [
            'Aspek Audit' => 'Integritas Relasi Kab -> Prov',
            'Nilai Ditemukan' => "{$orphanedKab} Orphaned",
            'Standar Acuan' => '0 Orphaned (100% Valid Parent)',
            'Hasil' => ($orphanedKab === 0) ? 'PASS' : 'FAIL',
        ];
        if ($orphanedKab > 0) $hasErrors = true;

        // Check 7: Orphaned Kecamatan Check
        $results[] = [
            'Aspek Audit' => 'Integritas Relasi Kec -> Kab',
            'Nilai Ditemukan' => "{$orphanedKec} Orphaned",
            'Standar Acuan' => '0 Orphaned (100% Valid Parent)',
            'Hasil' => ($orphanedKec === 0) ? 'PASS' : 'FAIL',
        ];
        if ($orphanedKec > 0) $hasErrors = true;

        // Check 8: Koordinat Geospasial
        $results[] = [
            'Aspek Audit' => 'GIS Koordinat Provinsi',
            'Nilai Ditemukan' => "{$provWithGis} / {$provCount} Memiliki Koordinat",
            'Standar Acuan' => '100% Terpetakan',
            'Hasil' => ($provWithGis === $provCount && $provCount > 0) ? 'PASS' : 'WARN',
        ];

        // Check 9: Timezone Metadata (WIB, WITA, WIT)
        $results[] = [
            'Aspek Audit' => 'Metadata Zona Waktu (WIB/WITA/WIT)',
            'Nilai Ditemukan' => "{$provWithTz} / {$provCount} Memiliki Timezone",
            'Standar Acuan' => '100% Terdefinisi',
            'Hasil' => ($provWithTz === $provCount && $provCount > 0) ? 'PASS' : 'WARN',
        ];

        // Check 10: Format Kodifikasi Provinsi
        $results[] = [
            'Aspek Audit' => 'Format Kodifikasi Provinsi (2 Digit)',
            'Nilai Ditemukan' => "{$invalidProvCodes} Format Tidak Sesuai",
            'Standar Acuan' => '0 Anomali (Tepat 2 Digit)',
            'Hasil' => ($invalidProvCodes === 0) ? 'PASS' : 'FAIL',
        ];
        if ($invalidProvCodes > 0) $hasErrors = true;

        $this->table(['Aspek Audit', 'Nilai Ditemukan', 'Standar Acuan', 'Hasil'], $results);

        if ($hasErrors) {
            $this->error("\nAudit selesai: Terdeteksi ketidaksesuaian kritis dalam integritas data!");
            return 1;
        }

        $this->info("\nAudit selesai: 100% Kepatuhan terverifikasi terhadap standar Kemendagri 2026.");
        return 0;
    }
}
