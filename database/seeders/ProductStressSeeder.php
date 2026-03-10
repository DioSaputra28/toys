<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductStressSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Wooden Toys', 'slug' => 'wooden-toys'],
            ['name' => 'Educational Toys', 'slug' => 'educational-toys'],
            ['name' => 'STEM Kits', 'slug' => 'stem-kits'],
            ['name' => 'Puzzles', 'slug' => 'puzzles'],
            ['name' => 'Dolls', 'slug' => 'dolls'],
            ['name' => 'Pretend Play', 'slug' => 'pretend-play'],
            ['name' => 'Outdoor Play', 'slug' => 'outdoor-play'],
            ['name' => 'Montessori', 'slug' => 'montessori'],
            ['name' => 'Sensory Play', 'slug' => 'sensory-play'],
            ['name' => 'Baby & Toddler', 'slug' => 'baby-toddler'],
            ['name' => 'Board Games', 'slug' => 'board-games'],
            ['name' => 'Creative Craft', 'slug' => 'creative-craft'],
        ];

        $imagePool = [
            'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1545558014-8692077e9b5c?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1516627145497-ae6968895b74?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1503919545889-aef636e10ad4?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1533077166777-cf6b2c5c6db7?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1511739001486-6bfe10ce785f?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1587654780291-39c9404d746b?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?auto=format&fit=crop&w=1400&q=80',
        ];

        $productsPerCategory = 16;

        foreach ($categories as $categoryIndex => $categoryData) {
            $category = ProductCategory::query()->updateOrCreate(
                ['slug' => $categoryData['slug']],
                [
                    'name' => $categoryData['name'],
                    'is_active' => true,
                    'sort_order' => $categoryIndex,
                ],
            );

            for ($productIndex = 1; $productIndex <= $productsPerCategory; $productIndex++) {
                $slug = Str::slug($categoryData['slug'].'-'.$productIndex);
                $basePrice = (($categoryIndex + 3) * 45000) + ($productIndex * 12500);
                $priceText = Product::normalizeDisplayPrice((string) $basePrice);

                $product = Product::query()->updateOrCreate(
                    ['slug' => $slug],
                    [
                        'category_id' => $category->id,
                        'name' => $categoryData['name'].' Item '.$productIndex,
                        'description' => 'A premium '.$categoryData['name'].' product designed for imaginative play, built with durable materials, and curated for families who value safe and timeless toys.',
                        'display_price' => $priceText,
                        'is_featured' => $productIndex % 4 === 0,
                        'is_active' => $productIndex % 15 !== 0,
                    ],
                );

                $product->images()->delete();

                for ($imageIndex = 0; $imageIndex < 3; $imageIndex++) {
                    $poolIndex = ($categoryIndex + $productIndex + $imageIndex) % count($imagePool);

                    $product->images()->create([
                        'image_path' => $imagePool[$poolIndex],
                        'alt_text' => $product->name.' image '.($imageIndex + 1),
                        'is_primary' => $imageIndex === 0,
                        'show_in_gallery' => true,
                        'sort_order' => $imageIndex,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
