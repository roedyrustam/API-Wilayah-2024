# Product Requirements Document (PRD)
**Project**: API Wilayah Indonesia 2026  
**Version**: 2.5.0  
**Status**: Approved & Deployed  
**Created**: 2026-10-18  
**Last Updated**: 2026-10-18  

---

## 1. Executive Summary
API Wilayah Indonesia 2026 adalah platform open-source berbasis Laravel 10 dan Filament 3 yang menyediakan master data wilayah administratif Republik Indonesia (Provinsi, Kabupaten/Kota, Kecamatan, dan Kelurahan/Desa) termutakhir sesuai standar regulasi Kementerian Dalam Negeri 2024–2026. Platform ini menyediakan RESTful API berkinerja tinggi dengan query caching (sub-5ms), pencarian geospatial proximity (Haversine radius), kalkulasi zona waktu otomatis (WIB/WITA/WIT), CLI Artisan commands, serta interactive developer playground di `/demo`.

## 2. Problem Statement
- **Inkonsistensi Kode DOB**: Daerah Otonom Baru (DOB) Papua Barat Daya sebelumnya tercatat menggunakan kode duplikat `92` (Papua Barat), padahal kode resmi Kemendagri adalah `96`, mengakibatkan rusaknya integritas hierarki data kabupaten, kecamatan, dan kelurahan.
- **Ketiadaan REST API Publik**: Endpoint API publik belum terdefinisi di `routes/api.php`, menyulitkan pengembang pihak ketiga untuk mengonsumsi data secara programmatic.
- **Ketiadaan Fitur Geospasial**: Pengembang aplikasi logistik dan e-commerce kesulitan mencari wilayah administratif terdekat dari titik GPS pengguna.
- **Ketiadaan Playground Terintegrasi**: Developer kesulitan menguji cascading dropdown dan pencarian autocomplete tanpa setup frontend mandiri.

## 3. Goals & Success Metrics
| Goal | Metric | Target |
|---|---|---|
| Pemutakhiran Kode 2026 | Integritas Kode Wilayah Papua Barat Daya | 100% tersinkronisasi ke prefix `96` (6 Kab/Kota, 132 Kec, 1.013 Kel) |
| Ketersediaan REST API | Cakupan Endpoint Wilayah | 100% endpoint CRUD read-only (List, Detail, Filter Relasi, Nearby) |
| Latensi API Caching | Waktu Respons HTTP | Sub-5ms untuk cached query, sub-50ms untuk uncached |
| Fitur Geospatial | Proximity Radius Lookup | Kemampuan pencarian radius km akurat berbasis Haversine formula |
| Integritas Relasi DB | Zero-Orphan Records | 0 kabupaten, kecamatan, atau kelurahan tanpa parent |
| Dokumentasi & Demo | Interaktivitas Developer | Swagger UI di `/api/documentation` dan Playground di `/demo` |

## 4. User Personas
### Persona 1: Backend / Fullstack Developer
- **Role**: Software Engineer membangun aplikasi e-commerce, logistik, atau fintech di Indonesia.
- **Goals**: Membutuhkan dropdown wilayah bertingkat (Provinsi -> Kab -> Kec -> Kel -> Kode Pos) yang akurat dan responsif via REST API serta pencarian nearby GPS.

### Persona 2: Mobile App Developer
- **Role**: iOS / Android / Flutter Engineer.
- **Goals**: Mengirim koordinat GPS pengguna (`lat`, `lng`) dan langsung mendapatkan wilayah administrasi terdekat beserta zona waktu (WIB/WITA/WIT).

### Persona 3: Data Administrator & Operator
- **Role**: Operator Sistem / Pegawai Pemerintahan.
- **Goals**: Mengelola, memverifikasi, dan mengekspor master data wilayah melalui Filament Admin Dashboard dan CLI commands.

## 5. Feature Requirements
### Core & Extended Features (v2.5 - 2026 Release)
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
- [x] **Developer Playground**: Antarmuka live testing di `/demo` (Cascading dropdown, copy code generator, live search, nearby radius).
- [x] **CLI Artisan Tools**:
  - `php artisan wilayah:status`: Validasi 100% kepatuhan data terhadap standar Kemendagri 2026.
  - `php artisan wilayah:search {query}`: Pencarian instan dari terminal.
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
