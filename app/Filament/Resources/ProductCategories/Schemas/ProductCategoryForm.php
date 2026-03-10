<?php

namespace App\Filament\Resources\ProductCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basics')
                    ->schema([
                        TextInput::make('name')
                            ->label('Category name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->helperText('Visible to admins and customers when browsing categories.')
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            })
                            ->validationMessages([
                                'required' => 'Please enter a category name.',
                                'max' => 'The category name must be 255 characters or fewer.',
                            ]),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated from the category name, but editable.')
                            ->validationMessages([
                                'required' => 'Please add a slug for this category.',
                                'max' => 'The slug must be 255 characters or fewer.',
                                'unique' => 'This category slug already exists. Please choose another one.',
                            ]),
                    ])
                    ->columns(2),
                Section::make('Status and Sorting')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active categories are available for products.'),
                        TextInput::make('sort_order')
                            ->label('Sort order')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Lower numbers are displayed first.')
                            ->validationMessages([
                                'required' => 'Please enter a sort order.',
                                'numeric' => 'Sort order must be a number.',
                            ]),
                    ])
                    ->columns(2),
            ]);
    }
}
