<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'userCounts' => User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role'),
            'productCount' => Product::count(),
            'orderCount' => Order::count(),
            'revenue' => Order::revenue()->sum('total_amount'),
        ]);
    }

    public function users(): View
    {
        return view('admin.users', [
            'users' => User::orderBy('role')->orderBy('name')->paginate(15),
        ]);
    }
}
