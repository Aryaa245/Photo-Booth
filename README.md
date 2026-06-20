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
