# API Wilayah Indonesia 2026

[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-3.0-orange.svg)](https://filamentphp.com)
[![Kemendagri Standard](https://img.shields.io/badge/Standard-Kemendagri%202026-blue.svg)](https://kemendagri.go.id)
[![OpenAPI Specification](https://img.shields.io/badge/OpenAPI-3.1-brightgreen.svg)](/api/documentation)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

**API Wilayah Indonesia 2026** adalah proyek open-source berbasis **Laravel 10** dan **Filament 3 (TALL Stack)** yang menyediakan master data dan RESTful API wilayah administratif terlengkap di Republik Indonesia. Data telah dimutakhirkan sesuai dengan regulasi resmi Kementerian Dalam Negeri (Kemendagri) 2024–2026.

---

## 📊 Statistik Master Data 2026

| Tingkat Wilayah | Jumlah Data | Keterangan |
|---|---|---|
| **Provinsi** | **38** | Termasuk 4 DOB Papua (Papua Selatan, Papua Tengah, Papua Pegunungan, dan Papua Barat Daya dengan kode resmi **`96`**) serta pemutakhiran nomenklatur (Aceh, DKI Jakarta, DI Yogyakarta, NTB, NTT) |
| **Kabupaten / Kota** | **514** | 416 Kabupaten dan 98 Kota di seluruh Indonesia |
| **Kecamatan** | **7.277** | Seluruh distrik/kecamatan dengan kode berjenjang (*hierarchical code*) |
| **Kelurahan / Desa** | **83.763** | Dilengkapi dengan **Kode Pos** 5-digit |

---

## 🚀 Fitur Utama

- **RESTful API Publik & Fleksibel**:
  - Endpoint berjenjang untuk Provinsi, Kabupaten/Kota, Kecamatan, dan Kelurahan.
  - Dukungan pencarian nama (`?search=`), kode wilayah (`?code=`, `?full_code=`), tipe (`?type=Kabupaten|Kota`), dan kode pos (`?pos_code=`).
  - Dukungan *pagination* dan pengambilan data penuh (*all*).
- **OpenAPI 3.1 & Swagger UI Terintegrasi**:
  - Tersedia di `/api/documentation` untuk eksplorasi dan uji coba API langsung di browser.
- **Master Data JSON 2026**:
  - Tersedia di `public/json/` (`provinsi.json`, `kabupaten.json`, `kecamatan.json`, `kelurahan.json`).
- **Database Seeder Cepat & Aman**:
  - `FromJsonSeeder` dengan chunking 1.000 records dan integrasi timestamp otomatis.
- **Filament 3 Admin Dashboard**:
  - Manajemen master data wilayah via dashboard interaktif di `/admin`.

---

## 🛠️ Persyaratan Sistem

- PHP 8.1 / 8.2+
- MySQL / MariaDB
- Composer 2.x
- Git

---

## 📦 Panduan Instalasi

1. **Clone repository**:
   ```bash
   git clone https://github.com/roedyrustam/API-Wilayah-2024.git
   cd API-Wilayah-2024
   ```

2. **Install dependency**:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Sesuaikan konfigurasi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) pada file `.env`.*

4. **Migrasi Database & Seeding Data**:
   ```bash
   php artisan migrate
   php artisan db:seed --class=FromJsonSeeder
   ```

5. **Jalankan Development Server**:
   ```bash
   php artisan serve
   ```

---

## 🎮 Live Playground & Dokumentasi REST API

- 🕹️ **Interactive Developer Playground & Cascading Dropdown**:  
  👉 **http://127.0.0.1:8000/demo** (atau `/playground`)
- 📖 **Dokumentasi Interaktif Swagger UI**:  
  👉 **http://127.0.0.1:8000/api/documentation**
- 📄 **Spesifikasi OpenAPI 3.1 YAML**: `public/docs/openapi.yaml`

### Ringkasan Endpoint

| Method | Endpoint | Deskripsi | Parameter Query |
|---|---|---|---|
| `GET` | `/api/v1/provinsi` | Daftar seluruh provinsi (+ koordinat & zona waktu) | `search`, `code`, `paginate`, `per_page` |
| `GET` | `/api/v1/provinsi/{id}` | Detail provinsi berdasarkan ID / Kode | - |
| `GET` | `/api/v1/provinsi/{id}/kabupaten` | Kabupaten/Kota di provinsi tertentu | `search`, `type`, `paginate` |
| `GET` | `/api/v1/kabupaten` | Daftar seluruh kabupaten/kota | `provinsi_id`, `search`, `full_code`, `type` |
| `GET` | `/api/v1/kabupaten/{id}` | Detail kabupaten/kota | - |
| `GET` | `/api/v1/kabupaten/{id}/kecamatan` | Kecamatan di kabupaten/kota tertentu | `search`, `paginate` |
| `GET` | `/api/v1/kecamatan` | Daftar seluruh kecamatan | `kabupaten_id`, `search`, `full_code`, `page` |
| `GET` | `/api/v1/kecamatan/{id}` | Detail kecamatan | - |
| `GET` | `/api/v1/kecamatan/{id}/kelurahan` | Kelurahan/Desa di kecamatan tertentu | `search`, `pos_code`, `paginate` |
| `GET` | `/api/v1/kelurahan` | Daftar seluruh kelurahan/desa | `kecamatan_id`, `search`, `pos_code`, `page` |
| `GET` | `/api/v1/kelurahan/{id}` | Detail kelurahan/desa lengkap hierarki | - |
| `GET` | `/api/v1/search` | **Autocomplete Global** (cari simultan 4 level wilayah) | `q` (minimal 2 karakter) |
| `GET` | `/api/v1/nearby` | **Pencarian Proximity / Radius (GIS)** via Haversine | `lat`, `lng`, `radius` (km), `level`, `limit` |
| `GET` | `/api/v1/geojson/provinsi` | **GeoJSON RFC 7946** seluruh provinsi untuk Leaflet/Mapbox | `search`, `code` |
| `GET` | `/api/v1/geojson/kabupaten` | **GeoJSON RFC 7946** seluruh kabupaten/kota | `provinsi_id`, `search` |
| `GET` | `/api/v1/export/{level}` | **Ekspor Master Data (Stream)** format CSV / JSON | `format` (`csv` / `json`) |

*Catatan:*
- *Endpoint juga dapat diakses tanpa prefix `v1` (misal: `/api/provinsi`, `/api/search`, `/api/nearby`, `/api/geojson/provinsi`).*
- *Dilengkapi **In-Memory Caching (TTL 24 jam)** untuk respons sub-5ms.*
- *Dilengkapi **API Rate Limiter** 120 request/menit per alamat IP untuk mencegah abuse.*

### Format Standar Response JSON

```json
{
  "success": true,
  "status": true,
  "message": "Daftar provinsi berhasil diambil",
  "data": [
    {
      "id": 26,
      "name": "Papua Barat Daya",
      "code": "96",
      "latitude": -0.8762,
      "longitude": 131.2558,
      "timezone": "WIT"
    }
  ],
  "meta": {
    "total": 38
  }
}
```

---

## 🧩 Reusable Blade Component

Gunakan komponen dropdown cascading bertingkat 4 level langsung di view Blade aplikasi Anda:

```blade
<!-- Di dalam form Blade Anda -->
<form action="/simpan-alamat" method="POST">
    @csrf
    
    <x-wilayah-cascade 
        :province-name="'provinsi_id'"
        :regency-name="'kabupaten_id'"
        :district-name="'kecamatan_id'"
        :village-name="'kelurahan_id'"
        :show-postal-code="true"
    />

    <button type="submit">Simpan Alamat</button>
</form>
```

---

## 🐳 Deployment Cepat dengan Docker

API Wilayah Indonesia 2026 sudah dilengkapi dengan container production berbasis **PHP 8.2-FPM + Nginx + OPcache Preloading**:

```bash
# Jalankan container langsung dalam 1 perintah
docker compose up -d

# Periksa status service dan healthcheck
docker compose ps
```
Aplikasi akan langsung aktif di **http://localhost:8080** lengkap dengan dokumentasi OpenAPI, Playground, dan endpoint REST API.

---

## 📦 Universal Client SDK (JavaScript & TypeScript)

Tersedia SDK siap pakai tanpa dependensi eksternal di direktori `public/sdk/`:
- **UMD Bundle (Browser `<script>`)**: `/sdk/api-wilayah.js`
- **ES Module (Vite, React, Vue, Next.js, Nuxt)**: `/sdk/api-wilayah.esm.js`
- **TypeScript Declarations**: `/sdk/api-wilayah.d.ts`

```javascript
import { WilayahClient } from '/sdk/api-wilayah.esm.js';

const api = new WilayahClient({ baseUrl: 'http://127.0.0.1:8000' });

// Ambil 38 Provinsi
const provinces = await api.getProvinces();

// Ambil Kab/Kota di Papua Barat Daya
const regencies = await api.getRegencies('96');

// Pencarian Radius Proximity (GPS)
const nearby = await api.getNearby(-0.8762, 131.2558, { radius: 50 });

// Format GeoJSON RFC 7946
const geojson = await api.getGeoJson('provinsi');
```

---

## 🛠️ CLI Artisan Commands & Testing

API Wilayah Indonesia 2026 dilengkapi serangkaian perintah CLI dan suite automated testing:

```bash
# Periksa status ringkas master data
php artisan wilayah:status

# Audit mendalam kepatuhan 100% standar Kemendagri 2026 (DOB 96, foreign keys, GIS)
php artisan wilayah:audit

# Cari data wilayah secara instan langsung dari terminal shell
php artisan wilayah:search Sorong

# Ekspor master data ke CSV / JSON di storage/app/exports
php artisan wilayah:export provinsi --format=csv
php artisan wilayah:export kabupaten --format=csv

# Jalankan automated test suite (PHPUnit / Feature Tests)
php artisan test
```

---

## 💻 Filament Admin Dashboard

- **URL**: http://127.0.0.1:8000/admin
- **Default Akun**:
  - Email: `admin@gmail.com`
  - Password: `admin12345`

Untuk membuat user admin baru:
```bash
php artisan make:filament-user
```

---

## 🗺️ Roadmap & Pembaruan

- [x] Master data 38 Provinsi, 514 Kab/Kota, 7.277 Kecamatan, 83.763 Kelurahan/Desa
- [x] Sinkronisasi kode DOB Papua Barat Daya ke standar Kemendagri (`96`)
- [x] Penyelarasan kode pos lengkap pada seluruh kelurahan/desa
- [x] RESTful API Publik dengan filter, rate-limiter, dan in-memory cache
- [x] Dokumentasi OpenAPI 3.1 & Swagger UI di `/api/documentation`
- [x] Filament 3 Admin Dashboard untuk manajemen data
- [x] Dukungan koordinat Latitude & Longitude serta zona waktu (WIB/WITA/WIT) (v2.5)
- [x] Pencarian proximity radius Haversine (`/api/v1/nearby`) (v2.5)
- [x] Developer Playground interaktif di `/demo` (v2.5 & v3.0)
- [x] GeoJSON Standar RFC 7946 FeatureCollection (`/api/v1/geojson/...`) (v3.0)
- [x] Streaming Exporter memori-aman CSV/JSON (`/api/v1/export/...`) (v3.0)
- [x] Reusable Laravel Blade Cascading Component `<x-wilayah-cascade />` (v3.0)
- [x] Universal Client SDK JS/TS & Docker Containerization (v3.1)
- [x] Audit CLI Kemendagri 2026 (`php artisan wilayah:audit`) & Modern CI/CD (v3.1)

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

