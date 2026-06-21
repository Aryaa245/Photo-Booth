<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# 📸 SnapStudio — Website Photobooth Laravel

Website photobooth digital premium dengan desain dark luxury menggunakan **Laravel** + **Tailwind CSS**.

---

## ✨ Fitur Utama

- 📷 **Capture Kamera Real-time** — akses webcam langsung dari browser
- 🎨 **6 Filter Foto** — Normal, B&W, Sepia, Vintage, Cool, Warm
- 🖼️ **6 Frame Pilihan** — Classic Gold, Hitam, Putih, Merah Muda, Biru, Hijau
- 🎞️ **Strip Mode** — ambil 4 foto sekaligus seperti photobooth sungguhan
- ⬇️ **Download Instan** — simpan strip foto ke perangkat
- 🗂️ **Galeri Online** — simpan foto ke server, tampilkan di galeri
- 💡 **Countdown Timer** — hitungan mundur sebelum jepretan
- ⚡ **Flash Effect** — animasi kilatan saat foto diambil

---

## 🚀 Cara Instalasi

### Prasyarat
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / SQLite

### Langkah-langkah

**1. Buat project Laravel baru**
```bash
composer create-project laravel/laravel snapstudio
cd snapstudio
```

**2. Salin semua file dari repo ini ke folder project:**
```
app/Http/Controllers/PhotoController.php
app/Models/Photo.php
database/migrations/*_create_photos_table.php
resources/views/layouts/app.blade.php
resources/views/pages/home.blade.php
resources/views/pages/photobooth.blade.php
resources/views/pages/gallery.blade.php
routes/web.php
```

**3. Install Tailwind CSS**
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

Tambah ke `tailwind.config.js`:
```js
content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
],
```

**4. Setup database**

Edit `.env`:
```env
APP_NAME=SnapStudio
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=snapstudio
DB_USERNAME=root
DB_PASSWORD=
```

Atau gunakan SQLite:
```env
DB_CONNECTION=sqlite
```
```bash
touch database/database.sqlite
```

**5. Jalankan migrasi**
```bash
php artisan migrate
```

**6. Buat symlink storage**
```bash
php artisan storage:link
```

**7. Build assets & jalankan server**
```bash
npm run dev
php artisan serve
```

Buka `http://localhost:8000` 🎉

---

## 📁 Struktur File

```
snapstudio/
├── app/
│   ├── Http/Controllers/
│   │   └── PhotoController.php     # Controller utama
│   └── Models/
│       └── Photo.php               # Model foto
├── database/migrations/
│   └── ..._create_photos_table.php # Skema database
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php           # Layout utama + navbar + footer
│   └── pages/
│       ├── home.blade.php           # Halaman beranda
│       ├── photobooth.blade.php     # Studio kamera interaktif
│       └── gallery.blade.php        # Galeri foto
└── routes/
    └── web.php                     # Routing
```

---

## 🎨 Design System

| Token       | Nilai      | Fungsi                   |
|-------------|------------|--------------------------|
| `obsidian`  | `#0D0D0D`  | Background utama         |
| `cream`     | `#F5F0E8`  | Teks primer              |
| `gold`      | `#C9A96E`  | Aksen, CTA, highlight    |
| `gold-light`| `#E8D5B0`  | Hover state gold         |
| `charcoal`  | `#2A2A2A`  | Border, divider          |
| `film`      | `#1A1A1A`  | Card / section background|

**Font:**
- Display: `Playfair Display` (heading, italic)
- Body: `Inter` (teks, UI)

---

## 📡 API Endpoints

| Method | URL | Deskripsi |
|--------|-----|-----------|
| GET | `/` | Halaman beranda |
| GET | `/galeri` | Galeri semua foto |
| GET | `/photobooth` | Studio kamera |
| POST | `/photos` | Simpan foto (JSON) |
| DELETE | `/photos/{id}` | Hapus foto |

### POST `/photos` — Body

```json
{
  "photos": ["data:image/jpeg;base64,..."],
  "filter": "vintage",
  "frame_id": 1
}
```

---

## 🔧 Kustomisasi

**Menambah frame baru** — edit method `getFrames()` di `PhotoController.php`:
```php
['id' => 7, 'name' => 'Ungu', 'preview_color' => '#9B59B6'],
```

**Menambah filter** — tambah di `photobooth.blade.php` di bagian filter list dan CSS.

**Mengubah jumlah foto strip** — ubah kondisi di JS:
```js
if (stripMode && photos.length < 6) { // ubah 4 → 6
```

---

## 📄 Lisensi

MIT License — bebas digunakan dan dimodifikasi.
>>>>>>> a7b2c3850e30da3e1ca892b3a8c972f2b99eb37a
