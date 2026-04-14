<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $featuredProducts = Product::where('is_active', true)->inRandomOrder()->take(8)->get();

        return view('website.index', compact('categories', 'featuredProducts'));
    }
}
