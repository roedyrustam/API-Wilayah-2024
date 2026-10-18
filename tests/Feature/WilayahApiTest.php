<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class WilayahApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate');

        $now = now();

        // Seed 38 provinces
        $provData = json_decode(file_get_contents(public_path('json/provinsi.json')), true) ?: [];
        foreach ($provData as &$p) {
            $p['created_at'] = $now;
            $p['updated_at'] = $now;
        }
        DB::table('provinsis')->insert($provData);

        // Seed 514 kabupatens
        $kabData = json_decode(file_get_contents(public_path('json/kabupaten.json')), true) ?: [];
        foreach ($kabData as &$k) {
            $k['created_at'] = $now;
            $k['updated_at'] = $now;
        }
        DB::table('kabupatens')->insert($kabData);
    }

    /**
     * Test list of provinces returns 38 provinces with 2026 format.
     */
    public function test_get_provinsi_list(): void
    {
        $response = $this->getJson('/api/v1/provinsi');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status'  => true,
            ])
            ->assertJsonCount(38, 'data');
    }

    /**
     * Test Papua Barat Daya has official 2026 Kemendagri code 96 and WIT timezone.
     */
    public function test_papua_barat_daya_conforms_to_code_96(): void
    {
        $response = $this->getJson('/api/v1/provinsi/96');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'code' => '96',
                    'name' => 'Papua Barat Daya',
                    'timezone' => 'WIT',
                ],
            ]);
    }

    /**
     * Test getting regencies in Papua Barat Daya (all prefix 96).
     */
    public function test_get_kabupaten_under_papua_barat_daya(): void
    {
        $pbd = Provinsi::where('code', '96')->first();
        $this->assertNotNull($pbd);

        $response = $this->getJson("/api/v1/provinsi/{$pbd->id}/kabupaten");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $data = $response->json('data');
        $this->assertCount(6, $data);
        foreach ($data as $kab) {
            $this->assertStringStartsWith('96', $kab['full_code']);
        }
    }

    /**
     * Test Global Autocomplete Search endpoint.
     */
    public function test_global_search_returns_matches(): void
    {
        $response = $this->getJson('/api/v1/search?q=Sorong');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertGreaterThan(0, $response->json('data.total_matches'));
    }

    /**
     * Test Global Search validates minimum 2 characters.
     */
    public function test_global_search_rejects_single_character(): void
    {
        $response = $this->getJson('/api/v1/search?q=a');

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test Nearby Geospatial Proximity Search with Haversine formula.
     */
    public function test_nearby_geospatial_radius_search(): void
    {
        // Coordinate for Sorong: -0.8762, 131.2558
        $response = $this->getJson('/api/v1/nearby?lat=-0.8762&lng=131.2558&radius=100&level=provinsi');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $data = $response->json('data.data');
        $this->assertNotEmpty($data);
        $this->assertEquals('Papua Barat Daya', $data[0]['name']);
        $this->assertLessThan(1.0, $data[0]['distance_km']);
    }

    /**
     * Test GeoJSON RFC 7946 FeatureCollection endpoint for provinces.
     */
    public function test_geojson_provinsi_rfc7946(): void
    {
        $response = $this->get('/api/v1/geojson/provinsi');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/geo+json; charset=utf-8')
            ->assertJson([
                'type' => 'FeatureCollection',
            ]);

        $features = $response->json('features');
        $this->assertCount(38, $features);
        $this->assertEquals('Point', $features[0]['geometry']['type']);
        $this->assertCount(2, $features[0]['geometry']['coordinates']);
    }

    /**
     * Test GeoJSON single province feature endpoint.
     */
    public function test_geojson_single_province_detail(): void
    {
        $response = $this->get('/api/v1/geojson/provinsi/96');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/geo+json; charset=utf-8')
            ->assertJson([
                'type' => 'Feature',
                'properties' => [
                    'code' => '96',
                    'name' => 'Papua Barat Daya',
                    'timezone' => 'WIT',
                ],
            ]);
    }

    /**
     * Test CSV Export stream.
     */
    public function test_export_provinsi_as_csv(): void
    {
        $response = $this->get('/api/v1/export/provinsi?format=csv');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        
        $content = $response->streamedContent();
        $this->assertStringContainsString('Papua Barat Daya', $content);
        $this->assertStringContainsString('96', $content);
    }

    /**
     * Test Artisan CLI commands execution.
     */
    public function test_artisan_wilayah_status_and_export_commands(): void
    {
        $statusCode = Artisan::call('wilayah:status');
        $this->assertEquals(0, $statusCode);

        $searchCode = Artisan::call('wilayah:search', ['query' => 'Sorong']);
        $this->assertEquals(0, $searchCode);

        $exportCode = Artisan::call('wilayah:export', ['level' => 'provinsi', '--format' => 'csv']);
        $this->assertEquals(0, $exportCode);

        $auditCode = Artisan::call('wilayah:audit');
        $this->assertEquals(0, $auditCode);
    }
}
