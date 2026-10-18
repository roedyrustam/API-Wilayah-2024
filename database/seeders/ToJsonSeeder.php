<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ToJsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data_prov = Provinsi::select('id', 'name', 'code')->orderBy('id')->get();
        $data_kab = Kabupaten::select('id', 'type', 'name', 'code', 'full_code', 'provinsi_id')->orderBy('id')->get();
        $data_kec = Kecamatan::select('id', 'name', 'code', 'full_code', 'kabupaten_id')->orderBy('id')->get();
        $data_kel = Kelurahan::select('id', 'name', 'code', 'full_code', 'pos_code', 'kecamatan_id')->orderBy('id')->get();

        $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE;
        $json_prov = json_encode($data_prov, $flags);
        $json_kab = json_encode($data_kab, $flags);
        $json_kec = json_encode($data_kec, $flags);
        $json_kel = json_encode($data_kel, $flags);

        $file_prov = public_path('json/provinsi.json');
        $file_kab = public_path('json/kabupaten.json');
        $file_kec = public_path('json/kecamatan.json');
        $file_kel = public_path('json/kelurahan.json');

        file_put_contents($file_prov, $json_prov);
        file_put_contents($file_kab, $json_kab);
        file_put_contents($file_kec, $json_kec);
        file_put_contents($file_kel, $json_kel);
        echo "Berhasil mengekspor master data JSON 2026 ke public/json\n";
    }
}
