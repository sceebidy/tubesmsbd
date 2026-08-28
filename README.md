# 🌿 Sipirok Indah - Sistem Manajemen Operasional & Kepegawaian

Sistem Manajemen Operasional & Kepegawaian **Sipirok Indah** adalah aplikasi berbasis web yang dirancang untuk mengelola operasional harian, presensi pegawai, pelaporan hasil panen, evaluasi kinerja (rapot), patroli keamanan, hingga pengajuan izin/sakit secara terintegrasi dengan kontrol akses berbasis peran (*Role-Based Access Control*).

---

## 🚀 Fitur Utama Berdasarkan Peran (Role)

Aplikasi ini mendukung **7 peran pengguna (role)** dengan hak akses dan fitur yang disesuaikan:

### 🔴 Admin
- **Kelola Data Pegawai**: Penambahan, pembaruan, dan penghapusan pegawai (termasuk *force delete* dengan riwayat).
- **Approval Pengajuan Izin/Sakit**: Peninjauan dan persetujuan/penolakan surat izin atau sakit pegawai.
- **Manajemen Rapot Kinerja**: Pembuatan evaluasi kinerja harian/bulanan, perhitungan skor otomatis, dan ekspor rapot ke format **PDF**.
- **Log Absensi & Ekspor Data**: Rekapitulasi absensi seluruh pegawai serta ekspor laporan ke format **CSV / Excel**.
- **Pengumuman**: Pengelolaan dan publikasi pengumuman internal perusahaan.

### 🔵 Manager
- **Dashboard Manajerial**: Visualisasi dan monitoring ringkasan kinerja serta operasional kepegawaian.
- **Audit Log Absensi**: Peninjauan data kehadiran seluruh staf.
- **Kelola Pegawai & Pengumuman**: Akses manajemen staf dan pengumuman tingkat manajerial.
- **Laporan & Ekspor Data**: Pengunduhan rekapitulasi data operasional.

### 🟢 Mandor
- **Dashboard Mandor**: Ringkasan aktivitas dan hasil kerja tim di bawah pengawasannya.
- **Verifikasi Laporan Panen**: Peninjauan dan verifikasi hasil laporan panen harian yang diinput oleh pekerja/petani.

### 🟡 Pekerja / Field User
- **Presensi Digital**: Check-in dan Check-out kehadiran berbasis lokasi/waktu.
- **Laporan Hasil Panen**: Input data kuantitas dan detail hasil panen harian.
- **Pengajuan Izin/Sakit**: Form pengajuan ketidakhadiran beserta pengunggahan berkas pendukung.
- **Rapot Saya**: Melihat riwayat evaluasi kinerja dan skor rapot pribadi.
- **Pengumuman**: Akses informasi resmi dari manajemen.

### 🛡️ Security
- **Dashboard Keamanan**: Monitoring status keamanan area kerja.
- **Log Patroli**: Input dan pencatatan riwayat patroli rutin area perkebunan/fasilitas.

### 🧹 Cleaning Service
- **Dashboard Kebersihan**: Monitoring jadwal dan area tugas kebersihan.
- **Log Kinerja Kebersihan**: Input laporan pemeliharaan dan kebersihan area kerja.

### 🏢 Staf Kantoran
- **Presensi & Layanan Mandiri**: Absensi harian serta pengajuan izin/sakit staf administrasi.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: [Laravel 12.x](https://laravel.com) (PHP >= 8.2)
- **Frontend**: Blade Templates, [Tailwind CSS v4](https://tailwindcss.com), [Alpine.js](https://alpinejs.dev), [Vite](https://vitejs.dev)
- **Database**: MySQL / MariaDB (atau SQLite untuk pengujian)
- **Library Utama**:
  - `barryvdh/laravel-dompdf`: Generator dokumen PDF untuk rapot evaluasi.
  - `phpoffice/phpspreadsheet` & `maatwebsite/excel`: Parser dan ekspor laporan ke Excel/CSV.

---

## 📋 Persyaratan Sistem

Sebelum menjalankan proyek ini, pastikan sistem Anda telah terinstal:
- **PHP** >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `gd`, `zip`, `openssl`, `cURL`)
- **Composer** >= 2.x
- **Node.js** >= 18.x & **NPM**
- **MySQL / MariaDB Database Server**

---

## ⚙️ Langkah Instalasi & Konfigurasi

Follow langkah-langkah berikut untuk menjalankan proyek secara lokal:

### 1. Clone Repository
```bash
git clone <repository-url>
cd tubesmsbd
```

### 2. Install Dependensi PHP & Node.js
```bash
composer install
npm install
```

### 3. Konfigurasi Environment File
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi database pada file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipirokindah
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Impor Database / Migrasi & Seeder
Anda dapat memilih salah satu dari dua cara berikut:

**Opsi A: Menggunakan file SQL bawaan (`sipirokindah.sql`)**
Buat database bernama `sipirokindah` di MySQL/phpMyAdmin, lalu impor file [sipirokindah.sql](file:///d:/Herd/tubesmsbd/sipirokindah.sql).

**Opsi B: Menggunakan Migration & Seeder Laravel**
```bash
php artisan migrate --seed
```

### 6. Buat Symbolic Link Storage (Wajib!)
Jalankan perintah berikut agar file unggahan (seperti lampiran pengajuan izin) dapat diakses publik:
```bash
php artisan storage:link
```

### 7. Jalankan Server Pengembangan
Anda dapat menjalankan server menggunakan satu perintah composer:
```bash
composer run dev
```
Atau secara terpisah pada dua terminal:
```bash
# Terminal 1: Frontend Bundler
npm run dev

# Terminal 2: PHP Development Server
php artisan serve
```
Aplikasi dapat diakses melalui browser di `http://127.0.0.1:8000`.

---

## 🔑 Akun Pengujian Default (Seeder)

Jika menggunakan `php artisan db:seed`, berikut adalah daftar akun pengujian default:

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@gmail.com` | `12345678` |
| **Manager** | `manager@gmail.com` | `12345678` |
| **Mandor** | `mandor@gmail.com` | `12345678` |
| **User (Pekerja)** | `user@gmail.com` | `12345678` |
| **Security** | `security@gmail.com` | `12345678` |
| **Cleaning** | `cleaning@gmail.com` | `12345678` |
| **Kantoran** | `kantoran@gmail.com` | `12345678` |

---

## 📁 Struktur Utama Direktori

```text
tubesmsbd/
├── app/
│   ├── Http/Controllers/     # Controller pengatur logika aplikasi per role & fitur
│   └── Models/               # Model Eloquent (User, Attendance, LaporanPanen, Rapot, dll)
├── database/
│   ├── migrations/           # Skema tabel database
│   └── seeders/              # Data pengujian awal
├── public/                   # Asset publik & storage link
├── resources/
│   ├── css/                  # Styling Tailwind CSS
│   ├── js/                   # Script frontend Alpine.js
│   └── views/                # Blade views (Dashboard, Absensi, Rapot, Laporan, dll)
├── routes/
│   └── web.php               # Routing aplikasi berbasis autentikasi & role middleware
├── sipirokindah.sql          # Dump file database awal
└── vite.config.js            # Konfigurasi bundler Vite
```

---

## 📄 Lisensi

Proyek ini dikembangkan untuk kebutuhan Tugas Besar Manajemen Sistem Basis Data (MSBD). Lisensi bersifat internal dan mengikuti standar lisensi open-source Laravel (MIT License).

