# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.2.0] - 2026-10-18

### Added
- **Zero-Friction Vercel Serverless Deployment**: Menambahkan serverless function bridge di [`api/index.php`](file:///c:/apiwilayah2027/API-Wilayah-2024/api/index.php), penanganan direktori writable otomatis di `/tmp/storage` (AWS Lambda), dan panduan lengkap [`DEPLOY_VERCEL.md`](file:///c:/apiwilayah2027/API-Wilayah-2024/DEPLOY_VERCEL.md).
- **Bundled Pre-Seeded SQLite Database**: Menyertakan database lokal SQLite lengkap (38 provinsi, 514 kab/kota, 7.277 kec, 83.763 kelurahan/desa) di `database/database.sqlite` (8 MB) yang langsung disalin ke `/tmp/database.sqlite` saat inisialisasi cold-start tanpa membutuhkan server database eksternal berbayar.
- **OpenAPI Native JSON Specification**: Mengompilasi [`public/docs/openapi.json`](file:///c:/apiwilayah2027/API-Wilayah-2024/public/docs/openapi.json) untuk parsing instan Swagger UI dan memperbaiki seluruh unquoted colons pada [`public/docs/openapi.yaml`](file:///c:/apiwilayah2027/API-Wilayah-2024/public/docs/openapi.yaml) (OpenAPI 3.0.3).
- **Global CORS & Edge CDN Caching**: Konfigurasi header `Access-Control-Allow-Origin: *` dan `Cache-Control` s-maxage pada [`vercel.json`](file:///c:/apiwilayah2027/API-Wilayah-2024/vercel.json) untuk akses cross-origin instan dari aplikasi web, SPA, dan mobile.
- **Atribusi Author & Standarisasi Packagist**: Menyelaraskan lisensi, composer strict validation, docblock backend, dan author Roedy Rustam pada 32 file di seluruh repositori.

## [3.1.0] - 2026-10-18

### Added
- **Docker Containerization**: Menambahkan `Dockerfile` production-grade berbasis PHP 8.2-FPM Alpine + Nginx + OPcache, konfigurasi `docker/nginx.conf`, `docker/supervisord.conf`, `docker/opcache.ini`, script `docker/entrypoint.sh`, dan `docker-compose.yml` untuk deployment instan satu perintah (`docker compose up -d`).
- **Universal Client SDK (JavaScript & TypeScript)**: Menambahkan library klien tanpa dependensi pihak ketiga di `public/sdk/api-wilayah.js` (format UMD untuk `<script>`), `public/sdk/api-wilayah.esm.js` (format ES Module untuk React/Vue/Vite/Next.js), dan `public/sdk/api-wilayah.d.ts` (definisi tipe TypeScript lengkap).
- **Artisan Deep Audit Command (`php artisan wilayah:audit`)**: Perintah CLI untuk melakukan 10 kategori pengujian kepatuhan menyeluruh terhadap master data (38 provinsi, DOB 96, 514 kab/kota, 7.277 kec, 83.763 kel, integritas relasi foreign key, kodifikasi digit, GIS bounding box, dan metadata zona waktu).
- **Pembaruan Playground `/demo`**: Menambahkan Tab 4 (GeoJSON Explorer interaktif dengan copy & live viewer) dan Tab 5 (Streaming Exporter & SDK Quickstart) pada antarmuka web.
- **GitHub Actions Modern CI/CD Pipeline (`.github/workflows/ci.yml`)**: Otomatisasi pengujian matrix PHP 8.2 dan 8.3 dengan cache composer, eksekusi test suite PHPUnit, dan audit integritas master data via CLI.

## [3.0.0] - 2026-10-18

### Added
- **GeoJSON RFC 7946 Standar Ekosistem**: Menambahkan [`GeoJsonController.php`](file:///c:/apiwilayah2027/API-Wilayah-2024/app/Http/Controllers/Api/GeoJsonController.php) dan endpoint `/api/v1/geojson/provinsi`, `/api/v1/geojson/provinsi/{id}`, dan `/api/v1/geojson/kabupaten` dengan format GeoJSON FeatureCollection ber-header `application/geo+json` yang langsung kompatibel dengan Leaflet, Mapbox, QGIS, dan Google Maps.
- **Data Export Streaming (CSV & JSON Stream)**: Menambahkan [`ExportController.php`](file:///c:/apiwilayah2027/API-Wilayah-2024/app/Http/Controllers/Api/ExportController.php) dan endpoint `/api/v1/export/{level}?format=csv|json` dengan UTF-8 BOM untuk kompatibilitas Excel dan memory-safe streaming.
- **Komponen Blade Cascading Dropdown `<x-wilayah-cascade />`**: Komponen reusable Blade di [`resources/views/components/wilayah-cascade.blade.php`](file:///c:/apiwilayah2027/API-Wilayah-2024/resources/views/components/wilayah-cascade.blade.php) yang dapat di-embed langsung ke proyek Laravel apa pun dengan binding form dan live kode pos.
- **Artisan Exporter Command**: Menambahkan perintah `php artisan wilayah:export {level} {--format=csv|json}`.
- **PHPUnit & Feature Test Suite Komprehensif**: Menambahkan [`tests/Feature/WilayahApiTest.php`](file:///c:/apiwilayah2027/API-Wilayah-2024/tests/Feature/WilayahApiTest.php) dengan 14 test case dan 51 assertion (100% PASS via `php artisan test`).

## [2.5.0] - 2026-10-18

### Added
- **Fitur GIS & Koordinat Centroid**: Menambahkan kolom `latitude` dan `longitude` ke tabel `provinsis`, `kabupatens`, `kecamatans`, dan `kelurahans` melalui migrasi database `2026_10_18_000001_add_coordinates_and_metadata_to_wilayah_tables.php`.
- **Informasi Zona Waktu (WIB / WITA / WIT)**: Menambahkan field `timezone` pada model Provinsi dan Kabupaten dengan fallback kalkulasi otomatis berdasarkan kode resmi Kemendagri.
- **Pencarian Proximity Geospatial (`GET /api/v1/nearby`)**: Endpoint pencarian wilayah administratif dalam radius kilometer tertentu dari koordinat GPS menggunakan formula Haversine dengan presisi tinggi dan optimasi bounding box.
- **Developer Playground & Live Console (`/demo` & `/playground`)**: Antarmuka interaktif modern dengan Cascading Dropdown 4-level, generator kode integrasi instan (Fetch API, cURL), pencarian autocomplete real-time, dan penguji GPS radius.
- **CLI Artisan Commands**:
  - `php artisan wilayah:status`: Audit integritas master data 2026 dan status DOB 96 Papua Barat Daya dalam bentuk tabel ASCII.
  - `php artisan wilayah:search {query}`: Pencarian instan wilayah 4-level langsung dari terminal shell.

## [2.0.0] - 2026-10-18

### Added
- **RESTful API Engine**: Menambahkan endpoint REST API publik lengkap untuk Provinsi, Kabupaten/Kota, Kecamatan, dan Kelurahan/Desa (`/api/v1/...` dan `/api/...`) dengan filter pencarian, kode wilayah, kode pos, dan pagination.
- **In-Memory Caching Layer**: Integrasi query caching otomatis via method `cached()` pada `BaseApiController` dengan TTL 24 jam dan parameter fingerprinting untuk menghasilkan waktu respons sub-5ms.
- **Rate Limiting Guard**: Konfigurasi rate limiter `120 request/menit` per IP dengan format JSON error 429 yang informatif untuk mencegah abuse.
- **Global Autocomplete Search**: Menambahkan controller & endpoint `/api/v1/search?q=` untuk pencarian kata kunci simultan lintas 4 level wilayah sekaligus.
- **OpenAPI 3.1 & Swagger UI**: Menambahkan file spesifikasi `public/docs/openapi.yaml` dan viewer interaktif di `/api/documentation`.
- **BaseApiController**: Menambahkan standarisasi format response JSON untuk konsistensi API publik.
- **PRD & Blueprint**: Menambahkan `PRD.md` dan `BLUEPRINT.md` sebagai dokumentasi arsitektur dan persyaratan sistem.

### Changed
- **Pembaruan Kode DOB Papua Barat Daya**:
  - Mengubah kode Provinsi Papua Barat Daya dari `92` (duplikat) menjadi **`96`** di `provinsi.json`.
  - Mengubah kode dan full code 6 kabupaten/kota Papua Barat Daya (`9601` Kab. Sorong, `9602` Kab. Sorong Selatan, `9603` Kab. Raja Ampat, `9604` Kab. Tambrauw, `9605` Kab. Maybrat, `9671` Kota Sorong) di `kabupaten.json`.
  - Mengubah full code 132 kecamatan di bawah Papua Barat Daya dari prefix `92` ke **`96xxxx`** di `kecamatan.json`.
  - Mengubah full code 1.013 kelurahan/desa di bawah Papua Barat Daya dari prefix `92` ke **`96xxxxxx`** di `kelurahan.json`.
- **Penyelarasan Nomenklatur Provinsi**: Memperbarui nama resmi provinsi sesuai standar Kemendagri (Aceh, DKI Jakarta, Daerah Istimewa Yogyakarta, Nusa Tenggara Barat, Nusa Tenggara Timur).
- **README.md**: Memperbarui seluruh dokumentasi ke edisi "API Wilayah Indonesia 2026" beserta ringkasan statistik dan panduan REST API.
- **FromJsonSeeder & ToJsonSeeder**: Menambahkan penanganan otomatis timestamp, penonaktifan foreign key saat truncate, dan ekspor bersih kolom JSON.

### Fixed
- **Model Kelurahan**: Memperbaiki properti `$fillable` (mengganti `kabupaten_id` yang salah menjadi `kecamatan_id`, `code`, `name`, `full_code`, `pos_code`) dan memperbaiki relasi `belongsTo(Kecamatan::class)`.
- **Model Kabupaten**: Menambahkan `type`, `code`, `full_code` ke properti `$fillable`.
- **Model Kecamatan**: Memperbaiki relasi parent dari `provinsi()` menjadi `kabupaten()`.
- **Model Provinsi**: Memperbaiki relasi `kecamatan()` menggunakan `hasManyThrough`.
- **Filament Resources**: Memperbaiki relasi dropdown form dan kolom tabel pada `KabupatenResource`, `KecamatanResource`, dan `KelurahanResource`.
