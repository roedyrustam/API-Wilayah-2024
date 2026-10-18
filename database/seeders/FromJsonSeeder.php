<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FromJsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_prov = public_path('json/provinsi.json');
        $file_kab = public_path('json/kabupaten.json');
        $file_kec = public_path('json/kecamatan.json');
        $file_kel = public_path('json/kelurahan.json');

        $json_prov = file_get_contents($file_prov);
        $json_kab = file_get_contents($file_kab);
        $json_kec = file_get_contents($file_kec);
        $json_kel = file_get_contents($file_kel);

        $data_prov = json_decode($json_prov, true);
        $data_kab = json_decode($json_kab, true);
        $data_kec = json_decode($json_kec, true);
        $data_kel = json_decode($json_kel, true);

        $now = now();
        $addTimestamps = function (&$items) use ($now) {
            foreach ($items as &$item) {
                $item['created_at'] = $now;
                $item['updated_at'] = $now;
            }
        };

        $addTimestamps($data_prov);
        $addTimestamps($data_kab);
        $addTimestamps($data_kec);
        $addTimestamps($data_kel);

        echo "Mengosongkan tabel wilayah sebelumnya...\n";
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('kelurahans')->truncate();
        DB::table('kecamatans')->truncate();
        DB::table('kabupatens')->truncate();
        DB::table('provinsis')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        echo "Memulai proses seeder data Provinsi...\n";
        DB::table('provinsis')->insert($data_prov);
        echo "Done seeder data Provinsi...\n";

        echo "Memulai proses seeder data Kabupaten...\n";
        DB::table('kabupatens')->insert($data_kab);
        echo "Done seeder data Kabupaten...\n";

        $chunk_kec = array_chunk($data_kec, 1000);
        foreach ($chunk_kec as $key => $chunk) {
            $step = ($key + 1) * 1000;
            echo "Memulai proses seeder data Kecamatan... ke-{$step}\n";
            DB::table('kecamatans')->insert($chunk);
            echo "Done seeder data Kecamatan... ke-{$step}\n";
        }

        $chunk_kel = array_chunk($data_kel, 1000);
        foreach ($chunk_kel as $key => $chunk) {
            $step = ($key + 1) * 1000;
            echo "Memulai proses seeder data Kelurahan... ke-{$step}\n";
            DB::table('kelurahans')->insert($chunk);
            echo "Done seeder data Kelurahan... ke-{$step}\n";
        }
    }
}
