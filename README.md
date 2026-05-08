# ManageTrip System

ManageTrip adalah aplikasi manajemen perjalanan dinas berbasis web yang digunakan untuk mengatur proses pengajuan, persetujuan, hingga penyelesaian trip kendaraan perusahaan secara terstruktur dan efisien.

---

## Features

- Request Trip
- Approval Management
- Vehicle Management
- Trip Monitoring
- Dashboard Monitoring
- Trip Status Tracking
- Fuel Usage Input
- Kilometer Tracking
- Multi Role Access

---

## User Roles

### Manager
Level akses tertinggi yang dapat mengakses seluruh fitur dan form pada sistem.

### Admin Transportation
Hanya dapat mengakses:
- Dashboard
- Vehicle Management
- Approval Management

### Admin
Hanya dapat mengakses:
- Dashboard
- Trip Management

### Karyawan
Hanya dapat mengakses:
- Dashboard
- Status Monitoring Trip

Tanpa akses diagram dan fitur manajemen lainnya.

### Driver
Digunakan untuk operasional perjalanan kendaraan dan monitoring trip yang berjalan.

---

## Workflow System

1. Admin membuat request trip
2. Admin Transportation melakukan approval atau rejection
3. Manager melakukan approval akhir
4. Jika seluruh approval disetujui, trip dapat dimulai
5. Setelah perjalanan selesai:
   - Admin menekan tombol selesai
   - Menginput penggunaan bensin
   - Menginput kilometer akhir kendaraan

---

## Tech Stack

- Laravel
- PHP
- MySQL
- Bootstrap / Tailwind CSS
- JavaScript

---

## Installation

Clone repository:

```bash
git clone https://github.com/zadidferdy/managetrip.git
```

Masuk ke folder project:

```bash
cd managetrip
```

Install dependency:

```bash
composer install
```

Copy file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Import database terlebih dahulu, lalu sesuaikan konfigurasi database pada file `.env`.

Jalankan migration jika diperlukan:

```bash
php artisan migrate
```

Run project:

```bash
php artisan serve
```

---

## Database

Database SQL telah disediakan di dalam folder:

```bash
storage/database/
```

Silakan import file `.sql` menggunakan:
- phpMyAdmin
- MySQL Workbench
- atau MySQL Command Line

Contoh konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=managetrip
DB_USERNAME=root
DB_PASSWORD=
```

---

## Access Level Summary

| Role | Access |
|------|------|
| Manager | Full Access |
| Admin Transportation | Vehicle, Approval, Dashboard |
| Admin | Trip, Dashboard |
| Karyawan | Dashboard & Trip Status |
| Driver | Trip Operational |

---

## License

This project is developed for transportation and trip management purposes.
