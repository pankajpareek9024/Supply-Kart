<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Add Categories
        $categories = [
            ['name' => 'Pan Masala', 'image' => 'images/default-category.svg'],
            ['name' => 'Cigarettes', 'image' => 'images/default-category.svg'],
            ['name' => 'Cold Drinks', 'image' => 'images/default-category.svg'],
            ['name' => 'Snacks & Chips', 'image' => 'images/default-category.svg'],
            ['name' => 'Electronics', 'image' => 'images/default-category.svg'],
            ['name' => 'Grocery', 'image' => 'images/default-category.svg'],
            ['name' => 'Stationery', 'image' => 'images/default-category.svg'],
            ['name' => 'Home Care', 'image' => 'images/default-category.svg'],
        ];

        foreach ($categories as $cat) {
            $category = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'image' => $cat['image'],
            ]);

            // Add 10 random products for each category
            for ($i = 1; $i <= 8; $i++) {
                $price = rand(50, 2000);
                Product::create([
                    'category_id' => $category->id,
                    'name' => 'Wholesale ' . $cat['name'] . ' Item ' . $i,
                    'slug' => Str::slug('Wholesale ' . $cat['name'] . ' Item ' . $i . ' ' . rand(100, 999)),
                    'description' => 'Premium quality ' . lcfirst($cat['name']) . ' for wholesale buyers. Minimum order quantity applies.',
                    'price' => $price,
                    'mrp' => $price + rand(50, 500),
                    'unit' => ['Box', 'Pcs', 'Carton', 'Pack'][rand(0, 3)],
                    'stock' => rand(20, 500),
                    'image' => 'images/default-product.svg', // Local fallback image
                ]);
            }
        }
    }
}
