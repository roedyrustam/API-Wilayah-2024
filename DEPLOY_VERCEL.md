# 🚀 Panduan Deployment ke Vercel (Serverless Laravel)

Dokumen ini menjelaskan langkah-langkah praktis untuk mendeploy **API Wilayah Indonesia 2026** ke platform serverless **Vercel**.

---

## ⚡ Arsitektur Serverless di Vercel

Vercel menggunakan arsitektur fungsi serverless (AWS Lambda) dengan runtime `vercel-php`. Proyek ini telah dikonfigurasi secara optimal melalui:
1. [`vercel.json`](file:///c:/apiwilayah2027/API-Wilayah-2024/vercel.json): Konfigurasi runtime PHP, routing aset statis, dan mapping request ke `api/index.php`.
2. [`api/index.php`](file:///c:/apiwilayah2027/API-Wilayah-2024/api/index.php): Bridge serverless yang secara otomatis mengarahkan file write Laravel (views compile, cache, log) ke `/tmp/storage` (direktori writable di AWS Lambda).
3. [`.vercelignore`](file:///c:/apiwilayah2027/API-Wilayah-2024/.vercelignore): Menjaga ukuran deployment payload tetap ringkas (< 50MB) sehingga cold-start berlangsung sangat cepat.

---

## 🛠️ Pilihan Mode Database

Anda dapat memilih salah satu dari 2 mode operasi di Vercel:

### 1. Mode Standalone JSON (Rekomendasi - Tanpa Database Eksternal)
API ini secara otomatis membaca dataset master resmi dari `public/json/` yang terpasang langsung di dalam proyek.
- ✅ **100% Gratis di Vercel Free Tier**
- ✅ Tidak memerlukan server database MySQL/PostgreSQL tambahan
- ✅ Waktu respons sub-5ms berkat in-memory caching
- 💡 Seluruh endpoint REST API, Playground `/demo`, Swagger UI, GeoJSON, dan Exporter langsung berfungsi 100%.

### 2. Mode Database Eksternal (MySQL / PostgreSQL)
Jika Anda ingin menggunakan database relasional aktif atau menjalankan Filament Admin:
- Hubungkan ke penyedia database serverless gratis seperti **Neon.tech** (PostgreSQL), **Supabase** (PostgreSQL), **TiDB Cloud** (MySQL), atau **Railway** (MySQL/PostgreSQL).
- Masukkan variabel lingkungan database pada Dashboard Vercel.

---

## 📋 Langkah-Langkah Deploy

### Metode 1: Menggunakan Vercel Dashboard (GitHub Integration)
1. Push repository Anda ke GitHub:
   ```bash
   git push origin main
   ```
2. Buka [vercel.com](https://vercel.com) dan login.
3. Klik **"Add New..."** -> **"Project"**.
4. Pilih repository **`API-Wilayah-2024`** Anda, lalu klik **"Import"**.
5. Pada bagian **Environment Variables**, tambahkan:
   | Key | Value Contoh | Deskripsi |
   |---|---|---|
   | `APP_KEY` | `base64:7aVz...` | Salin dari file `.env` lokal Anda |
   | `APP_URL` | `https://api-wilayah.vercel.app` | URL domain Vercel Anda |
   | `APP_DEBUG` | `false` | Matikan mode debug di production |
   | *(Opsional)* `DB_CONNECTION` | `mysql` / `pgsql` | Jika menggunakan DB eksternal |
   | *(Opsional)* `DB_HOST` | `aws.connect.psdb.cloud` | Host database eksternal |
   | *(Opsional)* `DB_DATABASE` | `apiwilayah2026` | Nama database |
   | *(Opsional)* `DB_USERNAME` | `username` | User database |
   | *(Opsional)* `DB_PASSWORD` | `password` | Password database |
6. Klik **"Deploy"**! Dalam 30–60 detik, API Anda akan live di seluruh dunia.

---

### Metode 2: Menggunakan Vercel CLI (Terminal)
Jika Anda memiliki Vercel CLI terpasang di komputer:
```bash
# Login ke akun Vercel Anda (jika belum)
npx vercel login

# Deploy ke Preview Environment
npx vercel

# Deploy langsung ke Production Environment
npx vercel --prod
```

Saat CLI meminta konfigurasi:
- `Set up and deploy?`: `y`
- `Which scope?`: Pilih akun Vercel Anda
- `Link to existing project?`: `n`
- `What's your project's name?`: `api-wilayah-indonesia`
- `In which directory is your code located?`: `./`

Setelah selesai, tambahkan variabel lingkungan `APP_KEY`:
```bash
npx vercel env add APP_KEY production
```

---

## 🧪 Verifikasi Deployment di Vercel

Setelah deployment selesai, buka browser Anda dan uji URL berikut:
1. **Developer Playground**: `https://<project-name>.vercel.app/demo`
2. **Dokumentasi Swagger UI**: `https://<project-name>.vercel.app/api/documentation`
3. **Endpoint Provinsi**: `https://<project-name>.vercel.app/api/v1/provinsi`
4. **Endpoint Papua Barat Daya (Kode 96)**: `https://<project-name>.vercel.app/api/v1/provinsi/96`
5. **Endpoint GeoJSON**: `https://<project-name>.vercel.app/api/v1/geojson/provinsi`
6. **Endpoint Status Master Data**: `https://<project-name>.vercel.app/api/v1/status`

---

## 💡 Troubleshooting & FAQ

#### 1. Error: `No application encryption key has been specified`
- **Penyebab**: Variabel `APP_KEY` belum diset pada tab *Settings* -> *Environment Variables* di Vercel Dashboard.
- **Solusi**: Jalankan `php artisan key:generate --show` di lokal, lalu salin string tersebut ke Vercel Environment Variables.

#### 2. Error: `The /var/task/storage/framework/views directory must be present and writable`
- **Penyebab**: Vercel Serverless memiliki filesystem root yang *read-only*.
- **Solusi**: File [`api/index.php`](file:///c:/apiwilayah2027/API-Wilayah-2024/api/index.php) pada repo ini sudah secara otomatis mengalihkan direktori storage ke `/tmp/storage` yang writable. Pastikan file `api/index.php` dan `vercel.json` ikut ter-commit.

#### 3. Cold Start Latency
- Pada request pertama setelah idle beberapa menit, Vercel melakukan initialization serverless function (~200ms).
- Request berikutnya yang terlayani oleh function yang hangat akan merespons dalam tempo sub-5ms karena in-memory caching.
