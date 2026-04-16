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
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($q2) use ($searchTerm) {
                      $q2->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
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

        $products = Product::where('is_active', true)
                            ->where(function($q) use ($query) {
                                $q->where('name', 'LIKE', "%{$query}%")
                                  ->orWhereHas('category', function($q2) use ($query) {
                                      $q2->where('name', 'LIKE', "%{$query}%");
                                  });
                            })
                            ->with('category')
                            ->select('id', 'name', 'slug', 'image', 'price', 'category_id', 'mrp')
                            ->take(5)
                            ->get()
                            ->map(function($product) {
                                $product->image_url = $product->image_url;
                                return $product;
                            });

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

    public function ajaxCategoryProducts($id)
    {
        $products = Product::where('category_id', $id)->where('is_active', true)->take(8)->get();
        // Return rendered blade partial
        $html = '';
        foreach($products as $product) {
            $discount = $product->mrp > $product->price ? round((($product->mrp - $product->price) / $product->mrp) * 100) : 0;
            $html .= '<div class="col">';
            $html .= view('website.layouts.partials.product-card', [
                'id' => $product->id,
                'slug' => $product->slug,
                'title' => $product->name,
                'price' => $product->price,
                'originalPrice' => $product->mrp,
                'margin' => $discount,
                'discount' => $discount,
                'category' => $product->category->name ?? 'General',
                'image' => $product->image_url,
                'stock' => $product->stock,
                'description' => $product->description
            ])->render();
            $html .= '</div>';
        }
        
        if ($products->isEmpty()) {
            $html = '<div class="col-12 text-center text-muted py-4"><p>No products found in this category.</p></div>';
        }

        return response()->json(['html' => $html]);
    }
}
