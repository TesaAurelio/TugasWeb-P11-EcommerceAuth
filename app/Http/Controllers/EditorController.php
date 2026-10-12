<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class EditorController extends Controller
{
    public function index(Request $request): View
    {
        return view('editor.dashboard', [
            'posts' => $request->user()->posts()->latest()->take(10)->get(),
        ]);
    }
}
