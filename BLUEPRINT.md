# Blueprint Arsitektur Sistem API Wilayah Indonesia 2026

Dokumen arsitektur teknis dan cetak biru perancangan ulang sistem **API Wilayah Indonesia 2026** (Standardisasi Kemendagri 2024–2026).

---

## 1. Topologi Data Wilayah Administratif
Sistem memetakan 4 tingkatan administrasi pemerintahan Indonesia:
1. **Level 1 — Provinsi**: 38 Provinsi (termasuk pemekaran DOB Papua).
2. **Level 2 — Kabupaten / Kota**: 514 Kabupaten dan Kota Administratif.
3. **Level 3 — Kecamatan / Distrik**: 7.277 Kecamatan / Distrik.
4. **Level 4 — Kelurahan / Desa**: 83.763 Kelurahan, Desa, dan Kampung.

```
[Provinsi: 38]
       │ (1:N)
       ▼
[Kabupaten/Kota: 514]
       │ (1:N)
       ▼
[Kecamatan: 7.277]
       │ (1:N)
       ▼
[Kelurahan/Desa: 83.763]
```

---

## 2. Standar Pengkodean Wilayah Kemendagri
- **Provinsi**:
  - `code`: 2-digit string numerik (contoh: `11` Aceh, `31` DKI Jakarta, `96` Papua Barat Daya).
- **Kabupaten / Kota**:
  - `code`: 2-digit string numerik lokal (`01`–`70` untuk Kabupaten, `71`–`99` untuk Kota).
  - `full_code`: 4-digit string (`provinsi_code + kabupaten_code`, contoh: `9601` Kab. Sorong, `9671` Kota Sorong).
  - `provinsi_id`: Foreign key ke Provinsi.
- **Kecamatan**:
  - `code`: 2-digit string.
  - `full_code`: 6-digit string (`kabupaten_full_code + kecamatan_code`).
  - `kabupaten_id`: Foreign key ke Kabupaten.
- **Kelurahan**:
  - `code`: 4-digit string (`1xxx` untuk Kelurahan, `2xxx` untuk Desa/Kampung).
  - `full_code`: 10-digit string (`kecamatan_full_code + kelurahan_code`).
  - `pos_code`: 5-digit kode pos resmi pos Indonesia.
  - `kecamatan_id`: Foreign key ke Kecamatan.

---

## 3. Matriks Perubahan DOB Papua Barat Daya
| Entitas | Nama | Kode Lama | Kode Baru 2026 |
|---|---|---|---|
| Provinsi | Papua Barat Daya | `92` (duplikat) | **`96`** |
| Kabupaten | Sorong | `9201` | **`9601`** |
| Kabupaten | Sorong Selatan | `9204` | **`9602`** |
| Kabupaten | Raja Ampat | `9205` | **`9603`** |
| Kabupaten | Tambrauw | `9209` | **`9604`** |
| Kabupaten | Maybrat | `9210` | **`9605`** |
| Kota | Sorong | `9271` | **`9671`** |
| 132 Kecamatan | Seluruh distrik di 6 kab/kota di atas | `92xxxx` | **`96xxxx`** |
| 1.013 Kelurahan | Seluruh kampung/kelurahan di 132 distrik | `92xxxxxx` | **`96xxxxxx`** |

---

## 4. Spesifikasi Kontrak RESTful API v1
Setiap respons API dibungkus dalam amplop standar JSON:

```json
{
  "success": true,
  "status": true,
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
GET /api/v1/provinsi                     -> List seluruh provinsi (+ koordinat & zona)
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
GET /api/v1/search?q=                    -> Autocomplete simultan lintas 4 level wilayah
GET /api/v1/nearby?lat=&lng=&radius=     -> Pencarian Proximity radius GPS (Haversine formula)
```

---

## 5. Diagram Hubungan Entitas (ERD)
```
┌─────────────────┐       1:N       ┌─────────────────┐
│    provinsis    │ ─────────────── │   kabupatens    │
│─────────────────│                 │─────────────────│
│ id (PK)         │                 │ id (PK)         │
│ code (VARCHAR)  │                 │ provinsi_id(FK) │
│ name (VARCHAR)  │                 │ type (VARCHAR)  │
│ latitude (DEC)  │                 │ code (VARCHAR)  │
│ longitude (DEC) │                 │ full_code (VAR) │
│ timezone (VAR)  │                 │ name (VARCHAR)  │
└─────────────────┘                 │ latitude (DEC)  │
                                    │ longitude (DEC) │
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
│ pos_code (VAR)  │                 │ latitude (DEC)  │
│ latitude (DEC)  │                 │ longitude (DEC) │
│ longitude (DEC) │                 └─────────────────┘
└─────────────────┘
```

---

## 6. Panel Admin Filament 3 & Developer Playground
- **Admin Panel** (`/admin`): Manajemen lengkap data wilayah, TPS, dan pemilih.
- **Developer Playground** (`/demo` & `/playground`): Cascading Dropdown 4-Level interaktif, generator kode integrasi otomatis, live search autocomplete, dan penguji GPS radius.
- **Swagger Documentation** (`/api/documentation`): Spesifikasi OpenAPI 3.1 visual documentation.

---

## 7. Optimasi Kinerja & Database Seeding
- **Chunking 1.000 Records**: `FromJsonSeeder` memproses 83.763 data kelurahan secara bertahap dalam ukuran batch 1.000 untuk mencegah lonjakan konsumsi memori PHP (`memory_limit` safe < 64MB).
- **In-Memory Caching (TTL 24 Jam)**: Cache otomatis dengan query fingerprinting menghasilkan latensi sub-5ms untuk query berulang.
- **Bounding Box Pre-filtering**: Formula Haversine didahului filter bounding box bujur/lintang untuk performa tinggi sub-15ms.
- **Artisan CLI**: Perintah `php artisan wilayah:status` dan `php artisan wilayah:search` untuk operasi cepat dari server shell.
