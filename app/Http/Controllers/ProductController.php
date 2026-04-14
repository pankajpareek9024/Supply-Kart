<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function categories()
    {
        $categories = Category::withCount('products')->get();
        return view('website.categories.index', compact('categories'));
    }

    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('website.products.list', compact('products', 'categories'));
    }

    public function details($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()->take(4)->get();

        return view('website.products.details', compact('product', 'relatedProducts'));
    }

    public function liveSearch(Request $request)
    {
        $query = $request->get('query');
        if (empty($query)) {
            return response()->json([]);
        }

        $products = Product::where('name', 'LIKE', "%{$query}%")
                            ->where('is_active', true)
                            ->select('name', 'slug', 'image', 'price')
                            ->take(5)
                            ->get();

        return response()->json($products);
    }

    public function categoryProducts($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        $query = Product::where('category_id', $category->id)->where('is_active', true);

        // Search
        if (request()->has('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('website.products.list', compact('products', 'categories', 'category'));
    }
}
