<?php

namespace App\Filament\Resources\HomeSections\RelationManagers;

use App\Models\HomeSection;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public static function canViewForOwnerRecord(mixed $ownerRecord): bool
    {
        if (! $ownerRecord instanceof HomeSection) {
            return true;
        }

        return $ownerRecord->section_key !== 'quote';
    }

    public static function canCreateForOwnerRecord(mixed $ownerRecord): bool
    {
        if (! $ownerRecord instanceof HomeSection) {
            return true;
        }

        if ($ownerRecord->section_key === 'quote') {
            return false;
        }

        if ($ownerRecord->section_key !== 'story_feature') {
            return true;
        }

        return $ownerRecord->items()
            ->where('is_active', true)
            ->count() < 4;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->maxLength(255)
                    ->helperText('Optional title used by sections such as editorial picks. For story feature, the first item becomes the main story and the next items become supporting cards.'),
                FileUpload::make('image_path')
                    ->label('Image file')
                    ->disk('public')
                    ->directory('home')
                    ->image()
                    ->maxSize(5120)
                    ->helperText('Optional image for this item. Upload up to 5 MB.')
                    ->validationMessages([
                        'image' => 'The uploaded file must be an image.',
                        'max' => 'The image must be 5 MB or smaller.',
                    ]),
                TextInput::make('image_alt')
                    ->label('Image alt text')
                    ->maxLength(255)
                    ->helperText('Describe the image for accessibility readers.'),
                TextInput::make('link_url')
                    ->label('Link URL')
                    ->url()
                    ->maxLength(255)
                    ->helperText('Optional destination URL when this item is clicked.')
                    ->validationMessages([
                        'url' => 'Please enter a valid URL starting with http:// or https://.',
                    ]),
                TextInput::make('sort_order')
                    ->label('Sort order')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->helperText('Lower numbers appear earlier within this section. In story feature, only the first four active items can be used, and the add button disappears after four active items exist.')
                    ->validationMessages([
                        'required' => 'Please set a sort order for this item.',
                        'numeric' => 'Sort order must be a number.',
                    ]),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Only active items are eligible for homepage display.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('title')
                    ->label('Title')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('link_url')
                    ->label('Link')
                    ->limit(40)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->hidden(fn (): bool => ! self::canCreateForOwnerRecord($this->getOwnerRecord())),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
