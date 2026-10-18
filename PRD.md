# Product Requirements Document (PRD)
**Project**: API Wilayah Indonesia 2026  
**Version**: 2.0.0  
**Status**: Approved  
**Created**: 2026-10-18  
**Last Updated**: 2026-10-18  

---

## 1. Executive Summary
API Wilayah Indonesia 2026 adalah platform open-source berbasis Laravel 10 dan Filament 3 yang menyediakan master data wilayah administratif Republik Indonesia (Provinsi, Kabupaten/Kota, Kecamatan, dan Kelurahan/Desa) termutakhir sesuai standar regulasi Kementerian Dalam Negeri 2024–2026. Platform ini menyediakan RESTful API berkinerja tinggi, database seeder teroptimasi, serta antarmuka dokumentasi interaktif OpenAPI 3.1 / Swagger UI.

## 2. Problem Statement
- **Inkonsistensi Kode DOB**: Daerah Otonom Baru (DOB) Papua Barat Daya sebelumnya tercatat menggunakan kode duplikat `92` (Papua Barat), padahal kode resmi Kemendagri adalah `96`, mengakibatkan rusaknya integritas hierarki data kabupaten, kecamatan, dan kelurahan.
- **Ketiadaan REST API Publik**: Endpoint API publik belum terdefinisi di `routes/api.php`, menyulitkan pengembang pihak ketiga untuk mengonsumsi data secara programmatic.
- **Ketiadaan Dokumentasi OpenAPI**: Tidak adanya spesifikasi OpenAPI/Swagger di `/api/documentation` bertolak belakang dengan panduan instalasi.
- **Defek Model Eloquent**: Model `Kelurahan` dan `Kabupaten` memiliki properti `$fillable` dan relasi yang tidak sinkron dengan skema tabel database.

## 3. Goals & Success Metrics
| Goal | Metric | Target |
|---|---|---|
| Pemutakhiran Kode 2026 | Integritas Kode Wilayah Papua Barat Daya | 100% tersinkronisasi ke prefix `96` (6 Kab/Kota, 132 Kec, 1.013 Kel) |
| Ketersediaan REST API | Cakupan Endpoint Wilayah | 100% endpoint CRUD read-only (List, Detail, Filter Relasi) |
| Latensi API | Waktu Respons HTTP | Sub-50ms untuk endpoint tanpa filter berat |
| Integritas Relasi DB | Zero-Orphan Records | 0 kabupaten, kecamatan, atau kelurahan tanpa parent |
| Dokumentasi API | Kepatuhan OpenAPI 3.1 | Swagger UI interaktif aktif di `/api/documentation` |

## 4. User Personas
### Persona 1: Backend / Fullstack Developer
- **Role**: Software Engineer membangun aplikasi e-commerce, logistik, atau fintech di Indonesia.
- **Goals**: Membutuhkan dropdown wilayah bertingkat (Provinsi -> Kab -> Kec -> Kel -> Kode Pos) yang akurat dan responsif via REST API.
- **Frustrations**: Data wilayah usang, pemekaran provinsi Papua tidak lengkap, atau format kode pos hilang.

### Persona 2: Data Administrator & Operator
- **Role**: Operator Sistem / Pegawai Pemerintahan.
- **Goals**: Mengelola, memverifikasi, dan mengekspor master data wilayah melalui Filament Admin Dashboard.

## 5. Feature Requirements
### MVP Features (v2.0 - 2026 Release)
- [x] **Hierarchical Code Update**: Sinkronisasi kode Provinsi Papua Barat Daya (`96`), 6 kabupaten (`9601`..`9671`), 132 kecamatan (`96xxxx`), dan 1.013 kelurahan (`96xxxxxx`).
- [x] **Nomenclature Standardization**: Penyelarasan nama resmi provinsi (Aceh, DKI Jakarta, DI Yogyakarta, Nusa Tenggara Barat, Nusa Tenggara Timur).
- [x] **Eloquent Models Fix**: Perbaikan properti `$fillable` dan relasi pada `Kelurahan.php` (`kecamatan()`) dan `Kabupaten.php`.
- [x] **RESTful API Engine**:
  - `GET /api/provinsi` & `GET /api/provinsi/{id_or_code}`
  - `GET /api/provinsi/{id_or_code}/kabupaten`
  - `GET /api/kabupaten` & `GET /api/kabupaten/{id_or_code}`
  - `GET /api/kabupaten/{id_or_code}/kecamatan`
  - `GET /api/kecamatan` & `GET /api/kecamatan/{id_or_code}`
  - `GET /api/kecamatan/{id_or_code}/kelurahan`
  - `GET /api/kelurahan` & `GET /api/kelurahan/{id_or_code}`
- [x] **OpenAPI 3.1 & Swagger UI**: File `public/docs/openapi.yaml` dan viewer `/api/documentation`.
- [x] **Database Seeders**: Pemutakhiran `FromJsonSeeder.php` dan `ToJsonSeeder.php`.

## 6. Technical Architecture
### Stack Decision
| Layer | Technology | Rationale |
|---|---|---|
| Framework | Laravel 10.x | LTS Framework andalan ekosistem PHP dengan performa routing matang |
| Database | MySQL / MariaDB | Relasional database dengan foreign key cascade |
| Admin Panel | Filament 3 TALL Stack | Dashboard modern berbasis Livewire 3 dan Tailwind CSS |
| API Docs | OpenAPI 3.1 + Swagger UI Standalone | Spesifikasi standar industri tanpa bloat paket composer eksternal |

### Architecture Decisions (ADRs)
- **ADR-001 (OpenAPI Standalone)**: Menyediakan Swagger UI dari distribusi CDN/standalone lokal di `public/docs` untuk menghindari breaking dependencies pada PHP 8.2 / Laravel 10.
- **ADR-002 (Hierarchical Routing & Id/Code Lookups)**: Controller mendukung pencarian via numeric database `id` ATAU string `code`/`full_code` untuk fleksibilitas developer.

## 7. Data Model (High-Level)
```
Provinsi (1) ──has_many──> Kabupaten (N)
Kabupaten (1) ──has_many──> Kecamatan (N)
Kecamatan (1) ──has_many──> Kelurahan (N)
```

## 8. Non-Functional Requirements
| Requirement | Target |
|---|---|
| Database Seeding | < 60 detik untuk 83.763 records dengan chunking 1.000 |
| Memory Footprint | < 128MB saat proses seeding / JSON export |
| Strict Integrity | Zero orphan records across all 4 levels |
| Code Quality | Anti-Slop Pillar 1-7 compliance, PHP 8.2 typed properties |

## 9. Out of Scope
- Layanan geocoding (Polygon boundaries / koordinat GeoJSON) — dijadwalkan untuk v2.5.
- API Key / Rate limiting berbayar (API dirancang open & public secara default).
