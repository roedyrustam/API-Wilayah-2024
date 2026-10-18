# Product Requirements Document (PRD)
**Project**: API Wilayah Indonesia 2026  
**Version**: 3.1.0  
**Status**: Approved & Released  
**Created**: 2026-10-18  
**Last Updated**: 2026-10-18  

---

## 1. Executive Summary
API Wilayah Indonesia 2026 adalah platform open-source berbasis Laravel 10 dan Filament 3 yang menyediakan master data wilayah administratif Republik Indonesia (Provinsi, Kabupaten/Kota, Kecamatan, dan Kelurahan/Desa) termutakhir sesuai standar regulasi Kementerian Dalam Negeri 2024–2026. Platform ini menyediakan RESTful API berkinerja tinggi dengan query caching (sub-5ms), pencarian geospatial proximity (Haversine radius), standar GeoJSON RFC 7946 untuk visualisasi peta, streaming ekspor CSV/JSON, reusable Blade component `<x-wilayah-cascade />`, Universal Client SDK (UMD/ESM/TypeScript), Docker containerization production-ready, CLI Artisan commands (`wilayah:status`, `wilayah:audit`, `wilayah:search`, `wilayah:export`), interactive developer playground di `/demo`, serta suite pengujian automated PHPUnit lengkap dan GitHub Actions CI/CD pipeline.

## 2. Problem Statement
- **Inkonsistensi Kode DOB**: Daerah Otonom Baru (DOB) Papua Barat Daya sebelumnya tercatat menggunakan kode duplikat `92` (Papua Barat), padahal kode resmi Kemendagri adalah `96`, mengakibatkan rusaknya integritas hierarki data kabupaten, kecamatan, dan kelurahan.
- **Ketiadaan REST API Publik**: Endpoint API publik belum terdefinisi di `routes/api.php`, menyulitkan pengembang pihak ketiga untuk mengonsumsi data secara programmatic.
- **Ketiadaan Fitur Geospasial & Peta**: Pengembang aplikasi logistik, e-commerce, dan GIS kesulitan mengintegrasikan titik koordinat atau memetakan wilayah ke Leaflet / Mapbox.
- **Ketiadaan Komponen UI Siap Pakai**: Developer kesulitan membuat cascading dropdown 4 tingkat secara manual berulang-ulang di setiap proyek.

## 3. Goals & Success Metrics
| Goal | Metric | Target |
|---|---|---|
| Pemutakhiran Kode 2026 | Integritas Kode Wilayah Papua Barat Daya | 100% tersinkronisasi ke prefix `96` (6 Kab/Kota, 132 Kec, 1.013 Kel) |
| Ketersediaan REST API | Cakupan Endpoint Wilayah | 100% endpoint CRUD read-only (List, Detail, Filter Relasi, Nearby, GeoJSON, Export) |
| Latensi API Caching | Waktu Respons HTTP | Sub-5ms untuk cached query, sub-50ms untuk uncached |
| Fitur Geospatial & GeoJSON | Standar RFC 7946 & Radius Lookup | Format GeoJSON valid dan pencarian radius km akurat via Haversine |
| Automated Testing | Kesiapan CI Pipeline | 100% lolos pengujian PHPUnit (`php artisan test`) |
| Dokumentasi & Demo | Interaktivitas Developer | Swagger UI di `/api/documentation`, Playground di `/demo`, dan `<x-wilayah-cascade />` |

## 4. User Personas
### Persona 1: Backend / Fullstack Developer
- **Role**: Software Engineer membangun aplikasi e-commerce, logistik, atau fintech di Indonesia.
- **Goals**: Membutuhkan dropdown wilayah bertingkat (Provinsi -> Kab -> Kec -> Kel -> Kode Pos) yang akurat dan responsif via REST API atau Blade component `<x-wilayah-cascade />`.

### Persona 2: Frontend & GIS Engineer
- **Role**: Pengembang Web GIS / Mobile App.
- **Goals**: Mengonsumsi GeoJSON FeatureCollection (`/api/v1/geojson/provinsi`) untuk visualisasi peta Leaflet/Mapbox dan radius pencarian terdekat (`/api/v1/nearby`).

### Persona 3: Data Administrator & Operator
- **Role**: Operator Sistem / Pegawai Pemerintahan.
- **Goals**: Mengelola, memverifikasi, dan mengekspor master data wilayah melalui Filament Admin Dashboard dan CLI commands (`php artisan wilayah:status` dan `php artisan wilayah:export`).

## 5. Feature Requirements
### Core, Extended & Enterprise Features (v3.0 - 2026 Release)
- [x] **Hierarchical Code Update**: Sinkronisasi kode Provinsi Papua Barat Daya (`96`), 6 kabupaten (`9601`..`9671`), 132 kecamatan (`96xxxx`), dan 1.013 kelurahan (`96xxxxxx`).
- [x] **Nomenclature Standardization**: Penyelarasan nama resmi provinsi (Aceh, DKI Jakarta, DI Yogyakarta, Nusa Tenggara Barat, Nusa Tenggara Timur).
- [x] **Eloquent Models Fix & Metadata**:
  - Perbaikan `$fillable` dan relasi pada `Kelurahan.php`, `Kecamatan.php`, `Kabupaten.php`, dan `Provinsi.php`.
  - Dukungan metadata `latitude`, `longitude`, dan `timezone` (WIB, WITA, WIT).
- [x] **RESTful API Engine**:
  - `GET /api/v1/provinsi` & `GET /api/v1/provinsi/{id}`
  - `GET /api/v1/provinsi/{id}/kabupaten`
  - `GET /api/v1/kabupaten` & `GET /api/v1/kabupaten/{id}`
  - `GET /api/v1/kabupaten/{id}/kecamatan`
  - `GET /api/v1/kecamatan` & `GET /api/v1/kecamatan/{id}`
  - `GET /api/v1/kecamatan/{id}/kelurahan`
  - `GET /api/v1/kelurahan` & `GET /api/v1/kelurahan/{id}`
  - `GET /api/v1/search?q=` (Autocomplete Multi-tier)
  - `GET /api/v1/nearby?lat=&lng=&radius=` (Geospatial Radius Haversine)
- [x] **GeoJSON RFC 7946 Standard**:
  - `GET /api/v1/geojson/provinsi`
  - `GET /api/v1/geojson/provinsi/{id}`
  - `GET /api/v1/geojson/kabupaten`
- [x] **Data Export Streaming Engine**:
  - `GET /api/v1/export/{level}?format=csv|json`
- [x] **Reusable Blade Cascading Component**:
  - Komponen `<x-wilayah-cascade />` siap pakai di form Blade mana pun.
- [x] **Developer Playground**: Antarmuka live testing di `/demo` (Cascading dropdown, copy code generator, live search, nearby radius).
- [x] **CLI Artisan Tools**:
  - `php artisan wilayah:status`: Validasi 100% kepatuhan data terhadap standar Kemendagri 2026.
  - `php artisan wilayah:audit`: Audit mendalam 10 kategori kepatuhan Kemendagri (DOB 96, foreign keys, GIS bounding box, timezone).
  - `php artisan wilayah:search {query}`: Pencarian instan dari terminal.
  - `php artisan wilayah:export {level} {--format=}`: Ekspor lokal ke CSV/JSON.
- [x] **Universal Client SDK**:
  - `public/sdk/api-wilayah.js` (UMD), `public/sdk/api-wilayah.esm.js` (ESM), `public/sdk/api-wilayah.d.ts` (TypeScript).
- [x] **Docker Containerization**:
  - `Dockerfile` (PHP 8.2-FPM + Nginx + OPcache Preloading) & `docker-compose.yml`.
- [x] **Automated Testing & CI/CD**:
  - Suite tes PHPUnit di `tests/Feature/WilayahApiTest.php` (100% Pass) & `.github/workflows/ci.yml`.
- [x] **OpenAPI 3.1 & Swagger UI**: File `public/docs/openapi.yaml` dan viewer `/api/documentation`.
- [x] **Database Seeders**: Pemutakhiran `FromJsonSeeder.php` dan `ToJsonSeeder.php`.

## 6. Technical Architecture
### Stack Decision
| Layer | Technology | Rationale |
|---|---|---|
| Framework | Laravel 10.x | LTS Framework andalan ekosistem PHP dengan performa routing matang |
| Database | MySQL / SQLite | Relasional database dengan foreign key cascade dan index GIS |
| Admin Panel | Filament 3 TALL Stack | Dashboard modern berbasis Livewire 3 dan Tailwind CSS |
| API Docs | OpenAPI 3.1 + Swagger UI Standalone | Spesifikasi standar industri tanpa bloat paket composer eksternal |
| Developer UI | Vanilla JS + Modern CSS Native | Zero-dependency, performa tinggi, responsif, dan glassmorphism |

## 7. Data Model (High-Level)
```
Provinsi (1) ──has_many──> Kabupaten (N)
Kabupaten (1) ──has_many──> Kecamatan (N)
Kecamatan (1) ──has_many──> Kelurahan (N)
```

## 8. Non-Functional Requirements
| Requirement | Target |
|---|---|
| Latensi Cached Query | < 5ms (Fingerprinted Cache Remember) |
| Latensi Nearby Haversine | < 15ms dengan Bounding Box Pre-filtering |
| Database Seeding | < 60 detik untuk 83.763 records dengan chunking 1.000 |
| Strict Integrity | Zero orphan records across all 4 levels |
| Code Quality | Anti-Slop Pillar 1-7 compliance, PHP 8.2 typed properties |
