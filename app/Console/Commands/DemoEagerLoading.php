<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DemoEagerLoading extends Command
{
    protected $signature = 'demo:eager {--limit=20 : Jumlah produk yang diambil}';

    protected $description = 'Demo (bonus): membandingkan jumlah query lazy loading (N+1) vs eager loading';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');

        DB::enableQueryLog();

        DB::flushQueryLog();
        foreach (Product::limit($limit)->get() as $product) {
            $product->category->name;
        }
        $lazy = count(DB::getQueryLog());

        DB::flushQueryLog();
        foreach (Product::with('category')->limit($limit)->get() as $product) {
            $product->category->name;
        }
        $eager = count(DB::getQueryLog());

        $this->table(
            ['Strategi', 'Jumlah query'],
            [
                ['Lazy loading (N+1)', $lazy],
                ['Eager loading with()', $eager],
            ]
        );

        $this->info("Dengan {$limit} produk, eager loading menghemat ".($lazy - $eager).' query.');

        return self::SUCCESS;
    }
}
