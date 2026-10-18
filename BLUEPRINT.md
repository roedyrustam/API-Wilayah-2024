# Blueprint Arsitektur — API Wilayah Indonesia 2026

## 1. Ikhtisar Arsitektur
API Wilayah Indonesia 2026 dibangun menggunakan framework **Laravel 10**, **Filament 3**, dan standar **OpenAPI 3.1**.

```
Client / Frontend (Web/Mobile)
        │
        ▼ (HTTP REST)
┌───────────────────────────────────────────────┐
│              Laravel 10 HTTP Core             │
│  - routes/api.php (RESTful Endpoints)         │
│  - routes/web.php (Swagger UI Documentation)  │
└───────────────────────┬───────────────────────┘
                        │
                        ▼
┌───────────────────────────────────────────────┐
│               API Controllers                 │
│  - ProvinsiController                         │
│  - KabupatenController                        │
│  - KecamatanController                        │
│  - KelurahanController                        │
└───────────────────────┬───────────────────────┘
                        │
                        ▼
┌───────────────────────────────────────────────┐
│               Eloquent Models                 │
│  - Provinsi (code, name)                      │
│  - Kabupaten (type, code, name, full_code)    │
│  - Kecamatan (code, name, full_code)          │
│  - Kelurahan (code, name, full_code, pos_code)│
└───────────────────────┬───────────────────────┘
                        │
                        ▼
┌───────────────────────────────────────────────┐
│        Storage: MySQL / JSON Master           │
│  - public/json/*.json (Master Data 2026)      │
│  - database/seeders (FromJsonSeeder)          │
└───────────────────────────────────────────────┘
```

## 2. Struktur Kontrak JSON Master Data 2026
- **Provinsi**:
  - `code`: 2-digit string (`11`..`96`)
  - `name`: Nama resmi Kemendagri
- **Kabupaten**:
  - `type`: `Kabupaten` | `Kota`
  - `code`: 2-digit string
  - `full_code`: 4-digit string (`provinsi_code + kabupaten_code`)
  - `provinsi_id`: Foreign key ke Provinsi
- **Kecamatan**:
  - `code`: 2-digit string
  - `full_code`: 6-digit string (`kabupaten_full_code + kecamatan_code`)
  - `kabupaten_id`: Foreign key ke Kabupaten
- **Kelurahan**:
  - `code`: 4-digit string (`1xxx` untuk Kelurahan, `2xxx` untuk Desa)
  - `full_code`: 10-digit string (`kecamatan_full_code + kelurahan_code`)
  - `pos_code`: 5-digit kode pos
  - `kecamatan_id`: Foreign key ke Kecamatan

## 3. Matriks Perubahan DOB Papua Barat Daya
| Entitas | Nama | Kode Lama | Kode Baru 2026 |
|---|---|---|---|
| Provinsi | Papua Barat Daya | `92` | **`96`** |
| Kabupaten | Sorong | `9201` | **`9601`** |
| Kabupaten | Sorong Selatan | `9204` | **`9602`** |
| Kabupaten | Raja Ampat | `9205` | **`9603`** |
| Kabupaten | Tambrauw | `9209` | **`9604`** |
| Kabupaten | Maybrat | `9210` | **`9605`** |
| Kota | Sorong | `9271` | **`9671`** |
| 132 Kecamatan | Seluruh distrik di 6 kab/kota di atas | `92xxxx` | **`96xxxx`** |
| 1.013 Kelurahan | Seluruh kampung/kelurahan di 132 distrik | `92xxxxxx` | **`96xxxxxx`** |

## 4. Spesifikasi Kontrak RESTful API v1
Setiap respons API dibungkus dalam amplop standar JSON:

```json
{
  "success": true,
  "message": "Deskripsi status operasi",
  "data": [ ... ],
  "meta": {
    "total": 38,
    "current_page": 1,
    "per_page": 15,
    "last_page": 3
  }
}
```

### Parameter Global:
- `search`: Pencarian nama wilayah secara case-insensitive menggunakan wildcard (`%keyword%`).
- `paginate`: Boolean flag (`true` / `false`) untuk mengaktifkan atau menonaktifkan paginasi.
- `per_page`: Jumlah item per halaman (standar: 15–25, batas maksimal: 100).
- `page`: Nomor halaman aktif saat paginasi diaktifkan.

### Routing Endpoints:
```
GET /api/v1/provinsi                     -> List seluruh provinsi
GET /api/v1/provinsi/{id_or_code}        -> Detail provinsi
GET /api/v1/provinsi/{id}/kabupaten      -> List kabupaten/kota dalam provinsi
GET /api/v1/kabupaten                    -> List kabupaten/kota (filter provinsi_id, type)
GET /api/v1/kabupaten/{id_or_code}       -> Detail kabupaten/kota
GET /api/v1/kabupaten/{id}/kecamatan     -> List kecamatan dalam kabupaten/kota
GET /api/v1/kecamatan                    -> List kecamatan (filter kabupaten_id)
GET /api/v1/kecamatan/{id_or_code}       -> Detail kecamatan
GET /api/v1/kecamatan/{id}/kelurahan     -> List kelurahan dalam kecamatan (filter pos_code)
GET /api/v1/kelurahan                    -> List kelurahan (filter kecamatan_id, pos_code)
GET /api/v1/kelurahan/{id_or_code}       -> Detail kelurahan lengkap hierarki induk
```

## 5. Diagram Hubungan Entitas (ERD)
```
┌─────────────────┐       1:N       ┌─────────────────┐
│    provinsis    │ ─────────────── │   kabupatens    │
│─────────────────│                 │─────────────────│
│ id (PK)         │                 │ id (PK)         │
│ code (VARCHAR)  │                 │ provinsi_id(FK) │
│ name (VARCHAR)  │                 │ type (VARCHAR)  │
└─────────────────┘                 │ code (VARCHAR)  │
                                    │ full_code (VAR) │
                                    │ name (VARCHAR)  │
                                    └────────┬────────┘
                                             │ 1:N
                                             ▼
┌─────────────────┐       1:N       ┌─────────────────┐
│   kelurahans    │ ─────────────── │   kecamatans    │
│─────────────────│                 │─────────────────│
│ id (PK)         │                 │ id (PK)         │
│ kecamatan_id(FK)│                 │ kabupaten_id(FK)│
│ code (VARCHAR)  │                 │ code (VARCHAR)  │
│ name (VARCHAR)  │                 │ full_code (VAR) │
│ full_code (VAR) │                 │ name (VARCHAR)  │
│ pos_code (VAR)  │                 └─────────────────┘
└─────────────────┘
```

## 6. Panel Admin Filament 3
Terintegrasi pada rute `/admin` dengan modul navigasi grup `Wilayah`:
- **ProvinsiResource**: Manajemen kode dan nama 38 provinsi.
- **KabupatenResource**: Dropdown relasi provinsi, manajemen tipe (Kabupaten/Kota), kode, dan full code.
- **KecamatanResource**: Dropdown relasi kabupaten/kota, manajemen kode dan full code distrik.
- **KelurahanResource**: Dropdown relasi kecamatan, manajemen kode, full code, dan kode pos 5-digit.
- **TpsResource & PemilihResource**: Pengelolaan data TPS dan pemilih berbasis pemetaan wilayah administratif.

## 7. Optimasi Kinerja & Database Seeding
- **Chunking 1.000 Records**: `FromJsonSeeder` memproses 83.763 data kelurahan secara bertahap dalam ukuran batch 1.000 untuk mencegah lonjakan konsumsi memori PHP (`memory_limit` safe < 64MB).
- **Foreign Key Integrity**: Penonaktifan sementara constraint (`Schema::disableForeignKeyConstraints()`) saat pembersihan tabel memastikan proses truncate bebas deadlock.
- **Indeks Kolom**: Kolom `code`, `full_code`, dan `provinsi_id` / `kabupaten_id` / `kecamatan_id` dioptimalkan untuk pencarian cepat sub-10ms.

