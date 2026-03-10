<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GalleryStressSeeder extends Seeder
{
    public function run(): void
    {
        GalleryItem::query()->delete();

        $galleryImages = [
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

        $titles = [
            'Playroom Editorial',
            'Quiet Shelf',
            'Wooden Wonder',
            'Soft Storytelling',
            'Creative Corner',
            'Imagination Table',
            'Morning Light',
            'Curated Keepsakes',
            'Family Play Hour',
            'Tactile Discovery',
            'Tiny Makers',
            'Color and Craft',
        ];

        for ($index = 0; $index < 36; $index++) {
            GalleryItem::query()->create([
                'title' => $titles[$index % count($titles)].' '.($index + 1),
                'image_path' => $galleryImages[$index % count($galleryImages)],
                'alt_text' => 'Gallery moment '.($index + 1),
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }
}
