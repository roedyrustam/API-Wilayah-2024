# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
