<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use Illuminate\Database\Seeder;

class AboutContentSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            'hero' => [
                'section' => [
                    'title' => 'TENTANG',
                    'subtitle' => 'More than a toy store. We are curators of childhood wonder, crafting memories one wooden block at a time.',
                    'eyebrow' => 'Established 1985',
                    'highlight_text' => 'KAMI',
                    'sort_order' => 1,
                    'is_active' => true,
                ],
                'items' => [
                    [
                        'item_type' => 'hero_image',
                        'title' => 'Workshop moments',
                        'description' => null,
                        'image_path' => 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?auto=format&fit=crop&w=1800&q=80',
                        'image_alt' => 'Children and parents in a toy workshop',
                        'link_url' => null,
                        'icon' => null,
                        'accent_color' => null,
                        'is_active' => true,
                    ],
                ],
            ],
            'quote' => [
                'section' => [
                    'title' => 'We believe that the best toys are 90% child and 10% toy.',
                    'subtitle' => 'Elena Rossi, Founder',
                    'eyebrow' => null,
                    'highlight_text' => null,
                    'sort_order' => 2,
                    'is_active' => true,
                ],
                'items' => [],
            ],
            'story' => [
                'section' => [
                    'title' => 'OUR',
                    'subtitle' => null,
                    'eyebrow' => null,
                    'highlight_text' => 'STORY',
                    'sort_order' => 3,
                    'is_active' => true,
                ],
                'items' => [
                    [
                        'item_type' => 'story_paragraph',
                        'title' => null,
                        'description' => 'It began in a small garage in 1985. Tired of fragile toys flooding the market, our founder Elena set out to create something different.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => null,
                        'accent_color' => null,
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'story_paragraph',
                        'title' => null,
                        'description' => 'What started as simple wooden cars has grown into a curated boutique of dolls, puzzles, and playsets.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => null,
                        'accent_color' => null,
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'story_paragraph',
                        'title' => null,
                        'description' => 'Our core philosophy remains unchanged: fewer gimmicks, more imagination, and timeless design that survives trends.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => null,
                        'accent_color' => null,
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'story_paragraph',
                        'title' => null,
                        'description' => 'Every piece in our boutique is hand-selected from artisans who share our values of sustainability and child-safe craftsmanship.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => null,
                        'accent_color' => null,
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'story_paragraph',
                        'title' => null,
                        'description' => 'Over the years, families from many cities have brought our toys into birthdays, playrooms, and heirloom shelves.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => null,
                        'accent_color' => null,
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'story_paragraph',
                        'title' => null,
                        'description' => 'Today, we continue to pair modern educational insight with old-world charm so each toy sparks stories for years.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => null,
                        'accent_color' => null,
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'story_image',
                        'title' => 'Artisan workshop',
                        'description' => null,
                        'image_path' => 'https://images.unsplash.com/photo-1542909168-82c3e7fdca5c?auto=format&fit=crop&w=1400&q=80',
                        'image_alt' => 'Toy makers in an artisan workshop',
                        'link_url' => null,
                        'icon' => null,
                        'accent_color' => null,
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'story_image',
                        'title' => 'Wooden toy display',
                        'description' => null,
                        'image_path' => 'https://images.unsplash.com/photo-1516627145497-ae6968895b74?auto=format&fit=crop&w=1400&q=80',
                        'image_alt' => 'Wooden toys displayed on shelves',
                        'link_url' => null,
                        'icon' => null,
                        'accent_color' => null,
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'story_feature',
                        'title' => 'Handmade with precision and love.',
                        'description' => 'Handmade with precision and love.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => 'handyman',
                        'accent_color' => null,
                        'is_active' => true,
                    ],
                ],
            ],
            'values' => [
                'section' => [
                    'title' => 'OUR VALUES',
                    'subtitle' => null,
                    'eyebrow' => null,
                    'highlight_text' => null,
                    'sort_order' => 4,
                    'is_active' => true,
                ],
                'items' => [
                    [
                        'item_type' => 'value_card',
                        'title' => 'Sustainable',
                        'description' => 'We prioritize renewable materials like FSC-certified wood and organic cotton.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => 'forest',
                        'accent_color' => 'teal',
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'value_card',
                        'title' => 'Artistic',
                        'description' => 'Toys should be beautiful enough to live on your shelf, not just in a toy box.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => 'palette',
                        'accent_color' => 'mustard',
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'value_card',
                        'title' => 'Safe',
                        'description' => 'Safety is non-negotiable: non-toxic paints and rigorous finishing standards.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => 'verified',
                        'accent_color' => 'terracotta',
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'value_card',
                        'title' => 'Educational',
                        'description' => 'Our collections are designed to support creativity, logic, and motor development.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => 'school',
                        'accent_color' => 'teal',
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'value_card',
                        'title' => 'Inclusive',
                        'description' => 'We curate toys that welcome different abilities, play styles, and family traditions.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => 'diversity_3',
                        'accent_color' => 'mustard',
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'value_card',
                        'title' => 'Durable',
                        'description' => 'Built to last for siblings and future generations, not just one holiday season.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => 'construction',
                        'accent_color' => 'terracotta',
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'value_card',
                        'title' => 'Community First',
                        'description' => 'We collaborate with local makers and small workshops to keep craftsmanship alive.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => 'groups',
                        'accent_color' => 'teal',
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'value_card',
                        'title' => 'Gender Neutral',
                        'description' => 'Play has no labels, so our curation encourages open-ended discovery for everyone.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => 'transgender',
                        'accent_color' => 'mustard',
                        'is_active' => true,
                    ],
                    [
                        'item_type' => 'value_card',
                        'title' => 'Minimal Waste',
                        'description' => 'From packaging to logistics, we keep waste low and reusable materials high.',
                        'image_path' => null,
                        'image_alt' => null,
                        'link_url' => null,
                        'icon' => 'recycling',
                        'accent_color' => 'terracotta',
                        'is_active' => true,
                    ],
                ],
            ],
        ];

        foreach ($sections as $sectionKey => $payload) {
            $section = AboutSection::query()->updateOrCreate(
                ['section_key' => $sectionKey],
                $payload['section'],
            );

            $section->items()->delete();

            foreach ($payload['items'] as $index => $item) {
                $section->items()->create([
                    ...$item,
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
