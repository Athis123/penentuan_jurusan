# 🧪 PENENTUAN JURUSAN – Laravel 9

Aplikasi berbasis Laravel 9 untuk manajemen data internal

---

## 🚀 Fitur Utama

-   Autentikasi pengguna (Login)
-   Manajemen data CRUD
-   Export data ke Excel & PDF
-   Filter data per tanggal
-   Responsive UI dengan Bootstrap

---

## ⚙️ Requirement

-   PHP 8.2.28
-   Composer 2.8.9
-   MySQL / PostgreSQL
-   Node.js v22.16.0 & npm 10.9.2
-   Laravel Framework 9.52.20

---

## 🛠️ Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/Athis123/penentuan_jurusan.git
cd penentuan_jurusan
```

-   composer install
-   npm install
-   npm run build
-   cp .env.example .env
-   Atur Konfigurasi Database di .env
-   php artisan key:generate
-   php artisan migrate --seed
-   php artisan serve

| Role  | USERNAME    | Password |
| ----- | ------      | -------- |
| Admin | admin       | password |
