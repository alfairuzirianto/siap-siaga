<h1 align="center">⚡ SiapSiaga ⚡</h1>
<p align="center">
  Sistem Informasi Pengelolaan Peralatan Siaga
</p>

<p align="center">
<a href="https://laravel.com/docs/12.x"><img src="https://img.shields.io/badge/Laravel-v12.x-FF2D20?logo=laravel&logoColor=white" alt="Laravel Version"></a>
<a href="https://livewire.laravel.com/docs/3.x/quickstart"><img src="https://img.shields.io/badge/Livewire-v3.x-4e56e6?logo=livewire&logoColor=white" alt="Livewire Version"></a>
<a href="https://v3.tailwindcss.com/docs/installation"><img src="https://img.shields.io/badge/TailwindCSS-v3.x-38BDF8?logo=tailwindcss&logoColor=white" alt="Tailwind Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## 📋 Tentang Aplikasi

**SiapSiaga** adalah aplikasi berbasis web yang digunakan **PT. PLN (Persero) UP3 Palembang** untuk mengelola data peralatan, proses peminjaman, pemeliharaan, serta pelaporan secara terpusat. Aplikasi ini membantu memastikan ketersediaan, kondisi, dan riwayat penggunaan peralatan dapat dipantau dengan baik.

## ✨ Fitur Utama

- Pengelolaan peralatan
- Peminjaman dan pengembalian
- Persetujuan dan verifikasi
- Dashboard dan pelaporan
- Manajemen pengguna

## 🚀 Instalasi

### Langkah-langkah

```bash
# 1. Clone repository
git clone https://github.com/alfairuzirianto/siap-siaga
cd siap-siaga

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
copy .env.example .env
php artisan key:generate

# 4. Konfigurasi database di file .env
# DB_DATABASE=siaga_pln
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Jalankan migrasi & seeder
php artisan migrate --seed

# 6. Buat symbolic link storage (untuk foto & file PDF)
php artisan storage:link

# 7. Build asset frontend
npm run build
```

### Menjalankan Aplikasi (Development)

```bash
php artisan serve
npm run dev
```

Aplikasi dapat diakses di `http://localhost:8000`.

## 📄 Lisensi

Proyek ini dikembangkan secara internal untuk PT PLN (Persero) dan dibangun di atas framework [Laravel](https://laravel.com) yang berlisensi [MIT](https://opensource.org/licenses/MIT).