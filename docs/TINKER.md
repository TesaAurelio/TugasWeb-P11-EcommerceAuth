# Dokumentasi 5 Query Tinker (Bagian A – item 4)

Jalankan setelah `php artisan migrate:fresh --seed`:

```bash
php artisan tinker
```

> Model di `App\Models` sudah otomatis di-alias oleh Tinker, jadi cukup tulis `Product`, `Category`, dst.
> **Ambil screenshot** tiap query *beserta hasilnya* dan tempel ke laporan/README repo.
> Hasil (angka/nama) akan berbeda di komputer Anda karena sebagian data dibuat acak oleh factory.

---

## Query 1 — Eager loading + 2 scope sekaligus
Produk aktif yang stoknya ada, lengkap dengan nama kategori (hanya 2 query ke database, bukan N+1).

```php
Product::with('category:id,name')->active()->inStock()->orderBy('name')->take(5)->get(['id','category_id','name','price','stock']);
```

## Query 2 — Hitung relasi (`withCount`)
Jumlah produk per kategori.

```php
Category::withCount('products')->get(['id','name'])->map(fn ($c) => [$c->name, $c->products_count]);
```

## Query 3 — Relasi bertingkat (nested eager loading)
Satu pesanan lengkap: pemilik, alamat, item, dan produk tiap item.

```php
$order = Order::with(['user:id,name,email', 'address', 'items.product:id,name'])->first();
$order->user->name;
$order->items->map(fn ($i) => [$i->product->name, $i->quantity, $i->unit_price, $i->subtotal()]);
```

## Query 4 — Scope `search` + `priceBetween`
Produk "kemeja" dengan harga Rp 100.000 – Rp 200.000.

```php
Product::search('kemeja')->priceBetween(100000, 200000)->get(['id','name','price']);
```

## Query 5 — Agregasi relasi (`withAvg`)
Tiga produk dengan rata-rata rating tertinggi (hanya yang sudah punya review).

```php
Product::withAvg('reviews', 'rating')->withCount('reviews')
    ->having('reviews_count', '>', 0)
    ->orderByDesc('reviews_avg_rating')
    ->take(3)
    ->get(['id','name'])
    ->map(fn ($p) => [$p->name, round($p->reviews_avg_rating, 2), $p->reviews_count]);
```

---

### Bonus: bukti eager loading mengurangi query
```bash
php artisan demo:eager --limit=20
```
Menampilkan tabel perbandingan jumlah query: lazy loading (≈21 query) vs `with()` (2 query).
