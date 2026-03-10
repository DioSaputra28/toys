<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\HomeCategoryPick;
use App\Models\HomePoster;
use App\Models\HomeSection;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class HomeStressSeeder extends Seeder
{
    public function run(): void
    {
        $sectionDefinitions = [
            'hero' => [
                'title' => 'PLAY TIME REIMAGINED',
                'subtitle' => 'Explore new ideas for imaginative play with rich visual storytelling and seasonal highlights.',
                'sort_order' => 1,
                'items' => $this->heroItems(),
            ],
            'marquee' => [
                'title' => null,
                'subtitle' => null,
                'sort_order' => 2,
                'items' => $this->marqueeItems(),
            ],
            'editorial_picks' => [
                'title' => "EDITOR'S PICKS",
                'subtitle' => 'High-volume cards to test grid and clipping behavior with many entries.',
                'sort_order' => 3,
                'items' => $this->editorialItems(),
            ],
            'quote' => [
                'title' => 'Play gives children a chance to practice what they are learning.',
                'subtitle' => 'Fred Rogers',
                'sort_order' => 4,
                'items' => [],
            ],
            'story_feature' => [
                'title' => 'CRAFTED FOR MEMORIES',
                'subtitle' => 'This section intentionally includes many items, while the current UI reads only the first item.',
                'sort_order' => 5,
                'items' => $this->storyFeatureItems(),
            ],
            'category_links' => [
                'title' => 'SHOP BY COLLECTION',
                'subtitle' => null,
                'sort_order' => 6,
                'items' => $this->categorySectionItems(),
            ],
        ];

        foreach ($sectionDefinitions as $sectionKey => $definition) {
            $section = HomeSection::query()->updateOrCreate(
                ['section_key' => $sectionKey],
                [
                    'title' => $definition['title'],
                    'subtitle' => $definition['subtitle'],
                    'sort_order' => $definition['sort_order'],
                    'is_active' => true,
                ],
            );

            $section->items()->delete();

            foreach ($definition['items'] as $index => $item) {
                $section->items()->create([
                    ...$item,
                    'sort_order' => $index,
                    'is_active' => true,
                ]);
            }
        }

        Banner::query()->delete();

        foreach ($this->topBanners() as $index => $banner) {
            Banner::query()->create([
                ...$banner,
                'placement' => 'home_top',
                'insert_after_section_key' => null,
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }

        $betweenSectionKeys = ['hero', 'marquee', 'editorial_picks', 'quote', 'story_feature', 'category_links'];

        foreach ($this->betweenBanners($betweenSectionKeys) as $index => $banner) {
            Banner::query()->create([
                ...$banner,
                'placement' => 'home_between_sections',
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }

        HomePoster::query()->delete();

        foreach ($this->betweenPosters($betweenSectionKeys) as $index => $poster) {
            HomePoster::query()->create([
                ...$poster,
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }

        HomeCategoryPick::query()->delete();

        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit(12)
            ->get();

        foreach ($categories as $index => $category) {
            HomeCategoryPick::query()->create([
                'category_id' => $category->id,
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }

    private function heroItems(): array
    {
        $images = [
            'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?auto=format&fit=crop&w=1800&q=80',
            'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=1800&q=80',
            'https://images.unsplash.com/photo-1545558014-8692077e9b5c?auto=format&fit=crop&w=1800&q=80',
            'https://images.unsplash.com/photo-1511739001486-6bfe10ce785f?auto=format&fit=crop&w=1800&q=80',
            'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&w=1800&q=80',
            'https://images.unsplash.com/photo-1516627145497-ae6968895b74?auto=format&fit=crop&w=1800&q=80',
            'https://images.unsplash.com/photo-1587654780291-39c9404d746b?auto=format&fit=crop&w=1800&q=80',
            'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?auto=format&fit=crop&w=1800&q=80',
            'https://images.unsplash.com/photo-1503919545889-aef636e10ad4?auto=format&fit=crop&w=1800&q=80',
            'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=1800&q=80',
            'https://images.unsplash.com/photo-1533077166777-cf6b2c5c6db7?auto=format&fit=crop&w=1800&q=80',
            'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1800&q=80',
        ];

        return array_map(fn (int $index, string $image): array => [
            'title' => 'Hero Slide '.($index + 1),
            'image_path' => $image,
            'image_alt' => 'Hero visual '.($index + 1),
            'link_url' => '/products?page='.(($index % 4) + 1),
        ], array_keys($images), $images);
    }

    private function marqueeItems(): array
    {
        $titles = [
            'Sustainable Toys',
            'New Arrivals Weekly',
            'Handmade Favorites',
            'Open-Ended Play',
            'Parent Approved',
            'Gift Ready Bundles',
            'Creative Learning',
            'Toddler Friendly',
            'Limited Seasonal Editions',
            'Artisan Collaborations',
            'STEM Discovery',
            'Soft Neutral Palettes',
            'Montessori Picks',
            'Playroom Essentials',
        ];

        return array_map(fn (string $title): array => [
            'title' => $title,
            'image_path' => null,
            'image_alt' => null,
            'link_url' => null,
        ], $titles);
    }

    private function editorialItems(): array
    {
        $images = $this->heroItems();
        $items = [];

        for ($index = 0; $index < 15; $index++) {
            $image = $images[$index % count($images)]['image_path'];
            $items[] = [
                'title' => 'Editorial Pick '.($index + 1),
                'image_path' => $image,
                'image_alt' => 'Editorial pick image '.($index + 1),
                'link_url' => '/products?sort='.(($index % 2 === 0) ? 'featured' : 'newest'),
            ];
        }

        return $items;
    }

    private function storyFeatureItems(): array
    {
        $heroItems = $this->heroItems();
        $items = [];

        for ($index = 0; $index < 12; $index++) {
            $items[] = [
                'title' => 'Story CTA '.($index + 1),
                'image_path' => $heroItems[$index % count($heroItems)]['image_path'],
                'image_alt' => 'Story feature image '.($index + 1),
                'link_url' => '/about?story='.(($index + 1)),
            ];
        }

        return $items;
    }

    private function categorySectionItems(): array
    {
        $items = [];

        for ($index = 0; $index < 12; $index++) {
            $items[] = [
                'title' => 'Category teaser '.($index + 1),
                'image_path' => null,
                'image_alt' => null,
                'link_url' => '/products?page='.(($index % 5) + 1),
            ];
        }

        return $items;
    }

    private function topBanners(): array
    {
        $images = $this->heroItems();
        $banners = [];

        for ($index = 0; $index < 8; $index++) {
            $banners[] = [
                'title' => 'Top Banner '.($index + 1),
                'image_path' => $images[$index % count($images)]['image_path'],
                'link_url' => '/products?sort=featured&page='.(($index % 4) + 1),
            ];
        }

        return $banners;
    }

    private function betweenBanners(array $afterKeys): array
    {
        $images = $this->heroItems();
        $banners = [];
        $perSection = 6;

        foreach ($afterKeys as $sectionIndex => $afterKey) {
            for ($itemIndex = 0; $itemIndex < $perSection; $itemIndex++) {
                $globalIndex = ($sectionIndex * $perSection) + $itemIndex;

                $banners[] = [
                    'title' => 'Between Banner '.($globalIndex + 1),
                    'image_path' => $images[$globalIndex % count($images)]['image_path'],
                    'link_url' => '/products?sort='.(($globalIndex % 2 === 0) ? 'price_asc' : 'price_desc'),
                    'insert_after_section_key' => $afterKey,
                ];
            }
        }

        return $banners;
    }

    private function betweenPosters(array $afterKeys): array
    {
        $images = $this->heroItems();
        $posters = [];
        $sizeCycle = ['portrait', 'landscape', 'full'];
        $styleCycle = ['image_only', 'overlay_text'];
        $perSection = 6;

        foreach ($afterKeys as $sectionIndex => $afterKey) {
            for ($itemIndex = 0; $itemIndex < $perSection; $itemIndex++) {
                $globalIndex = ($sectionIndex * $perSection) + $itemIndex;

                $posters[] = [
                    'title' => 'Poster Feature '.($globalIndex + 1),
                    'image_path' => $images[$globalIndex % count($images)]['image_path'],
                    'link_url' => '/products?category=&sort='.(($globalIndex % 2 === 0) ? 'featured' : 'newest'),
                    'insert_after_section_key' => $afterKey,
                    'size_variant' => $sizeCycle[$globalIndex % count($sizeCycle)],
                    'display_style' => $styleCycle[$globalIndex % count($styleCycle)],
                ];
            }
        }

        return $posters;
    }
}
