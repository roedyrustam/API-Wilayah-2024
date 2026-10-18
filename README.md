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

## 📖 Dokumentasi REST API

Akses dokumentasi interaktif Swagger UI di browser:  
👉 **http://127.0.0.1:8000/api/documentation**

Spesifikasi OpenAPI 3.1 YAML tersedia di: `public/docs/openapi.yaml`.

### Ringkasan Endpoint

| Method | Endpoint | Deskripsi | Parameter Query |
|---|---|---|---|
| `GET` | `/api/v1/provinsi` | Daftar seluruh provinsi | `search`, `code`, `paginate`, `per_page` |
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

*Catatan: Endpoint juga dapat diakses tanpa prefix `v1` (misal: `/api/provinsi`).*

### Format Standar Response JSON

```json
{
  "success": true,
  "message": "Daftar provinsi berhasil diambil",
  "data": [
    {
      "id": 26,
      "name": "Papua Barat Daya",
      "code": "96"
    }
  ],
  "meta": {
    "total": 38
  }
}
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
- [x] RESTful API Publik dengan filter dan pencarian
- [x] Dokumentasi OpenAPI 3.1 & Swagger UI di `/api/documentation`
- [x] Filament 3 Admin Dashboard untuk manajemen data
- [ ] Dukungan koordinat Latitude & Longitude (v2.5)
- [ ] Polygon GeoJSON batas wilayah administratif (v3.0)

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
