# TugasWeb-P11-EcommerceAuth

**Tugas Rutin 11 — E-Commerce DB + Secure Auth** (Laravel 12, MySQL, Laravel Breeze)

| | |
|---|---|
| Nama | _isi nama Anda_ |
| NIM | _isi NIM Anda_ |
| Mata Kuliah | Pemrograman Web |
| Pertemuan | 11 |

---

## Daftar Isi
1. [Ringkasan](#1-ringkasan)
2. [Instalasi (XAMPP + MySQL)](#2-instalasi-xampp--mysql)
3. [Akun Demo](#3-akun-demo)
4. [Bagian A — Database & Eloquent](#4-bagian-a--database--eloquent)
5. [Dokumentasi 5 Query Tinker](#5-dokumentasi-5-query-tinker)
6. [Bagian B — Auth & Security](#6-bagian-b--auth--security)
7. [Pengujian](#7-pengujian)
8. [Bonus](#8-bonus)

---

## 1. Ringkasan

Aplikasi e-commerce sederhana dengan struktur database lengkap, data awal yang realistis, serta autentikasi dan otorisasi berbasis role.

| Bagian | Item | Status |
|---|---|---|
| A | Migrations 7 tabel e-commerce + foreign key | ✅ |
| A | Seeders + factories (60 produk realistis) | ✅ |
| A | Model + relationships + scope | ✅ |
| A | Dokumentasi 5 query Tinker | ✅ (lihat bagian 5) |
| B | Laravel Breeze (login / register / logout) | ✅ |
| B | Multi-role (admin / editor / user) + custom middleware | ✅ |
| B | PostPolicy untuk otorisasi edit / delete | ✅ |
| B | Route protection + pengujian 2 role | ✅ |
| Bonus | Demo eager loading | ✅ `php artisan demo:eager` |
| Bonus | Filament admin panel | ➖ tidak dikerjakan |

**Teknologi:** PHP 8.2, Laravel 12, MySQL (XAMPP), Laravel Breeze (Blade), Tailwind CSS, Vite, PHPUnit.

---

## 2. Instalasi (XAMPP + MySQL)

**Prasyarat:** PHP ≥ 8.2, Composer, Node.js + npm, XAMPP.

```bash
# 1. Clone repo
git clone https://github.com/<username>/TugasWeb-P11-EcommerceAuth.git
cd TugasWeb-P11-EcommerceAuth

# 2. Install dependensi
composer install
npm install

# 3. Salin file environment dan buat app key
cp .env.example .env          # Windows CMD: copy .env.example .env
php artisan key:generate
```

**4. Siapkan database.** Jalankan **MySQL** di XAMPP Control Panel, lalu buat database `tugasweb_p11` lewat phpMyAdmin (`http://localhost/phpmyadmin`) dengan collation `utf8mb4_unicode_ci`.

**5. Atur `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tugasweb_p11
DB_USERNAME=root
DB_PASSWORD=
```

**6. Migrasi + seeder, build frontend, jalankan server:**
```bash
php artisan config:clear
php artisan migrate:fresh --seed
npm run build
php artisan serve
```
Buka `http://127.0.0.1:8000`.

> Urutan kerja setiap kali: **Start MySQL dulu**, baru `php artisan serve`. Laravel menyimpan session di database, sehingga tanpa MySQL halaman apa pun akan error.

---

## 3. Akun Demo

Password semua akun: `password`

| Role | Email |
|---|---|
| admin | `admin@example.com` |
| editor | `editor@example.com` |
| user | `user@example.com` |

---

## 4. Bagian A — Database & Eloquent

### 4.1 Tabel (migrations)

| Tabel | Keterangan | Foreign key |
|---|---|---|
| `categories` | Kategori produk | — |
| `products` | Produk (harga dalam Rupiah, stok, status aktif) | `category_id` → `categories` (restrict) |
| `addresses` | Alamat pengiriman pelanggan | `user_id` → `users` (cascade) |
| `orders` | Pesanan | `user_id` → `users` (cascade), `address_id` → `addresses` (set null) |
| `order_items` | Item pesanan (menyimpan `unit_price` saat transaksi) | `order_id` → `orders` (cascade), `product_id` → `products` (restrict) |
| `reviews` | Ulasan produk (unik per user + produk) | `user_id`, `product_id` (cascade) |
| `cart_items` | Keranjang belanja (unik per user + produk) | `user_id`, `product_id` (cascade) |

Tabel tambahan: `posts` (konten toko untuk PostPolicy) dan kolom `users.role`.

### 4.2 Seeder dan factory
- `UserSeeder` — 3 akun demo + 10 pelanggan acak
- `CategorySeeder` — 6 kategori
- `ProductSeeder` — **60 produk** realistis (10 per kategori)
- `ShopActivitySeeder` — alamat, pesanan + item, review, dan keranjang
- `PostSeeder` — 12 post (campuran terbit dan draft)

### 4.3 Model, relasi, dan scope

| Model | Relasi | Scope |
|---|---|---|
| `User` | `addresses`, `orders`, `reviews`, `cartItems`, `posts` | — |
| `Category` | `products` | — |
| `Product` | `category`, `reviews`, `orderItems`, `orders` | `active()`, `inStock()`, `search($term)`, `priceBetween($min,$max)` |
| `Order` | `user`, `address`, `items`, `products` | `status($s)`, `revenue()` |
| `OrderItem` | `order`, `product` | — |
| `Review`, `CartItem`, `Address`, `Post` | relasi `belongsTo` / `hasMany` terkait | — |

---

## 5. Dokumentasi 5 Query Tinker

Jalankan dari folder project (pastikan MySQL aktif):

```bash
php artisan tinker
```

Daftarkan model satu kali di awal sesi Tinker:

```php
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
```

> Nilai seperti stok, pesanan, dan rating dibuat acak oleh factory, sehingga hasil di komputer lain bisa berbeda.

### Query 1 — Eager loading + dua scope
Produk aktif yang stoknya masih ada, beserta nama kategorinya. Eager loading (`with`) hanya memakai 2 query, bukan N+1.

```php
Product::with('category:id,name')->active()->inStock()->orderBy('name')->take(5)->get(['id','category_id','name','price','stock']);
```

![Query 1](docs/screenshots/tinker-1.png)

### Query 2 — Menghitung relasi dengan `withCount`
Jumlah produk pada setiap kategori.

```php
Category::withCount('products')->get()->map(fn ($c) => [$c->name, $c->products_count]);
```

![Query 2](docs/screenshots/tinker-2.png)

### Query 3 — Nested eager loading
Satu pesanan lengkap: pemilik, alamat, item, dan produk tiap item. Jalankan dua baris berikut satu per satu.

```php
$order = Order::with(['user:id,name,email', 'address', 'items.product:id,name'])->first();
```
```php
$order->items->map(fn ($i) => [$i->product->name, $i->quantity, $i->unit_price, $i->subtotal()]);
```

![Query 3](docs/screenshots/tinker-3.png)

### Query 4 — Scope `search` + `priceBetween`
Produk dengan kata "kemeja" dan harga Rp 100.000 – Rp 200.000.

```php
Product::search('kemeja')->priceBetween(100000, 200000)->get(['id','name','price']);
```

![Query 4](docs/screenshots/tinker-4.png)

### Query 5 — Agregasi relasi `withAvg` + `withCount`
Tiga produk dengan rata-rata rating tertinggi, hanya yang sudah memiliki review.

```php
Product::withAvg('reviews', 'rating')->withCount('reviews')->having('reviews_count', '>', 0)->orderByDesc('reviews_avg_rating')->take(3)->get()->map(fn ($p) => [$p->name, round($p->reviews_avg_rating, 2), $p->reviews_count]);
```

![Query 5](docs/screenshots/tinker-5.png)

---

## 6. Bagian B — Auth & Security

### 6.1 Role dan middleware
- Role disimpan di kolom `users.role` (`admin`, `editor`, `user`). Pendaftar baru otomatis `user`.
- Kolom `role` **tidak** ada di `$fillable`, sehingga tidak bisa disisipkan lewat form register (mencegah privilege escalation).
- Custom middleware `CheckRole` (`app/Http/Middleware/CheckRole.php`) didaftarkan dengan alias `role` di `bootstrap/app.php`.
  - Belum login → diarahkan ke halaman login.
  - Sudah login tetapi role tidak sesuai → **403 Forbidden**.

### 6.2 PostPolicy

| Aksi | admin | editor | user |
|---|---|---|---|
| Lihat post terbit | ✅ | ✅ | ✅ |
| Lihat draft | ✅ | hanya miliknya | ❌ |
| Buat post | ✅ | ✅ | ❌ |
| Edit / hapus post | ✅ semua | hanya miliknya | ❌ |

### 6.3 Proteksi route

| Route | Akses |
|---|---|
| `/`, `/products` | Publik |
| `/dashboard`, `/profile`, `/posts`, `/posts/{post}` | Login (semua role) |
| `/posts/create`, `POST /posts` | `role:admin,editor` |
| `/posts/{post}/edit`, `PUT` dan `DELETE /posts/{post}` | Login + PostPolicy |
| `/editor/dashboard` | `role:admin,editor` |
| `/admin/dashboard`, `/admin/users` | `role:admin` |

### 6.4 Hasil pengujian manual dengan 2 role (incognito)

| Skenario | Hasil yang diharapkan | Screenshot |
|---|---|---|
| Admin (jendela biasa) membuka `/admin/dashboard` | Halaman tampil | ![](docs/screenshots/admin-dashboard.png) |
| User (incognito) membuka `/admin/dashboard` | **403 Forbidden** | ![](docs/screenshots/user-403.png) |
| User (incognito) membuka `/posts` | Tidak ada tombol Edit / Hapus | ![](docs/screenshots/user-posts.png) |
| Tanpa login membuka `/admin/dashboard` | Diarahkan ke halaman login | ![](docs/screenshots/guest-redirect.png) |

---

## 7. Pengujian

```bash
php artisan test
```

Hasil: **42 test lulus (129 assertion)**. Cakupan:
- Guest diarahkan ke login pada route terproteksi
- User biasa mendapat 403 di area admin dan editor
- Editor mendapat 403 di area admin, admin dapat mengakses semuanya
- PostPolicy: edit / hapus milik sendiri vs milik orang lain
- Registrasi tidak bisa menyelundupkan `role=admin`
- Relasi model, scope, dan foreign key (kategori yang masih punya produk tidak bisa dihapus)

![Hasil test](docs/screenshots/test-pass.png)

---

## 8. Bonus

### Demo eager loading
```bash
php artisan demo:eager --limit=20
```
Menampilkan perbandingan jumlah query antara lazy loading (N+1) dan eager loading (`with()`).

![Eager loading](docs/screenshots/eager-demo.png)

### Katalog produk publik
`/products` memakai `Product::with('category')->active()->inStock()->search()` sehingga N+1 tidak terjadi.

---

## Struktur penting

```
app/
├── Console/Commands/DemoEagerLoading.php
├── Http/Controllers/{Admin,Editor,Post,Product}Controller.php
├── Http/Middleware/CheckRole.php
├── Models/            # User, Category, Product, Address, Order, OrderItem, Review, CartItem, Post
└── Policies/PostPolicy.php
database/
├── factories/
├── migrations/
└── seeders/
routes/web.php
tests/Feature/         # RoleAccessTest, PostPolicyTest, ShopRelationsTest
docs/TINKER.md
```