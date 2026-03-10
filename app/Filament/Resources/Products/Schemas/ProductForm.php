<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basics')
                    ->schema([
                        TextInput::make('name')
                            ->label('Product name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->helperText('Used in admin and storefront listings.')
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            })
                            ->validationMessages([
                                'required' => 'Please enter a product name.',
                                'max' => 'The product name must be 255 characters or fewer.',
                            ]),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated from the name, but you can edit it.')
                            ->validationMessages([
                                'required' => 'Please add a slug for this product.',
                                'max' => 'The slug must be 255 characters or fewer.',
                                'unique' => 'This slug is already in use. Please choose another one.',
                            ]),
                    ])
                    ->columns(2),
                Section::make('Category and Status')
                    ->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Choose where this product appears in the catalog.')
                            ->validationMessages([
                                'required' => 'Please choose a category for this product.',
                            ]),
                        Toggle::make('is_featured')
                            ->label('Featured product')
                            ->helperText('Featured products can be highlighted in key sections.')
                            ->default(false),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Only active products are shown to customers.')
                            ->default(true),
                    ])
                    ->columns(3),
                Section::make('Pricing')
                    ->schema([
                        TextInput::make('display_price')
                            ->label('Harga tampilan')
                            ->live(onBlur: true)
                            ->maxLength(255)
                            ->placeholder('Rp 129.000')
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $set('display_price', Product::normalizeDisplayPrice($state));
                            })
                            ->dehydrateStateUsing(fn (?string $state): ?string => Product::normalizeDisplayPrice($state))
                            ->rule(function (): \Closure {
                                return function (string $attribute, mixed $value, \Closure $fail): void {
                                    if ($value === null || trim((string) $value) === '') {
                                        return;
                                    }

                                    if (Product::normalizeDisplayPrice((string) $value) === null) {
                                        $fieldLabel = str_replace('_', ' ', $attribute);

                                        $fail(ucfirst($fieldLabel).' harus berisi angka Rupiah yang valid.');
                                    }
                                };
                            })
                            ->helperText('Harga promosi opsional yang ditampilkan ke pelanggan. Kamu bisa mengetik angka saja, lalu nilainya akan dinormalisasi ke format Rupiah.')
                            ->validationMessages([
                                'max' => 'Harga tampilan harus 255 karakter atau kurang.',
                            ]),
                    ]),
                Section::make('Description')
                    ->schema([
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(5)
                            ->helperText('Optional longer description for this product.'),
                    ]),
                Section::make('Images')
                    ->description('Manage product images directly here.')
                    ->schema([
                        Repeater::make('images')
                            ->relationship('images')
                            ->addActionLabel('Add image')
                            ->collapsible()
                            ->orderColumn('sort_order')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Image file')
                                    ->disk('public')
                                    ->directory('products')
                                    ->image()
                                    ->maxSize(5120)
                                    ->required()
                                    ->helperText('Upload JPG, PNG, or WebP. Max 5 MB.')
                                    ->validationMessages([
                                        'required' => 'Please upload an image file.',
                                        'image' => 'The uploaded file must be an image.',
                                        'max' => 'Each image must be 5 MB or smaller.',
                                    ]),
                                TextInput::make('alt_text')
                                    ->label('Alt text')
                                    ->maxLength(255)
                                    ->helperText('Describe the image for accessibility.'),
                                Toggle::make('is_primary')
                                    ->label('Primary image')
                                    ->helperText('Mark one image as the main product image.')
                                    ->default(false),
                                Toggle::make('show_in_gallery')
                                    ->label('Show in gallery')
                                    ->helperText('Enable this image for gallery display.')
                                    ->default(true),
                                TextInput::make('sort_order')
                                    ->label('Sort order')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->helperText('Lower numbers appear first.')
                                    ->validationMessages([
                                        'required' => 'Please set a sort order.',
                                        'numeric' => 'Sort order must be a number.',
                                    ]),
                                Toggle::make('is_active')
                                    ->label('Image active')
                                    ->helperText('Only active images are shown to customers.')
                                    ->default(true),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
