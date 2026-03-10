<?php

namespace App\Filament\Resources\AboutSections\RelationManagers;

use App\Models\AboutSection;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_type')
                    ->label('Item type')
                    ->options(fn (): array => $this->itemTypeOptions())
                    ->live()
                    ->required()
                    ->helperText('Only item types that are still relevant for this section are available.')
                    ->validationMessages([
                        'required' => 'Please choose an item type.',
                    ]),
                TextInput::make('title')
                    ->label('Title')
                    ->maxLength(255)
                    ->required(fn (callable $get): bool => in_array($get('item_type'), ['story_feature', 'value_card'], true))
                    ->helperText('Used for feature labels and value card titles.'),
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->required(fn (callable $get): bool => in_array($get('item_type'), ['story_paragraph', 'story_feature', 'value_card'], true))
                    ->helperText('Long text content for story paragraphs, story feature copy, and value cards.')
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Image file')
                    ->disk('public')
                    ->directory('about')
                    ->image()
                    ->maxSize(5120)
                    ->required(fn (callable $get): bool => in_array($get('item_type'), ['hero_image', 'story_image'], true))
                    ->helperText('Optional image used by hero or story image items. Upload up to 5 MB.')
                    ->validationMessages([
                        'required' => 'Please upload an image for this item type.',
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
                    ->helperText('Optional destination URL for this item.')
                    ->validationMessages([
                        'url' => 'Please enter a valid URL starting with http:// or https://.',
                    ]),
                TextInput::make('icon')
                    ->label('Icon name')
                    ->maxLength(255)
                    ->required(fn (callable $get): bool => in_array($get('item_type'), ['story_feature', 'value_card'], true))
                    ->helperText('Material symbol name, such as forest, verified, or handyman.'),
                Select::make('accent_color')
                    ->label('Accent color')
                    ->options([
                        'teal' => 'Teal',
                        'mustard' => 'Mustard',
                        'terracotta' => 'Terracotta',
                    ])
                    ->required(fn (callable $get): bool => $get('item_type') === 'value_card')
                    ->helperText('Used for value card accent styling.'),
                TextInput::make('sort_order')
                    ->label('Sort order')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->helperText('Lower numbers appear earlier within this section.')
                    ->validationMessages([
                        'required' => 'Please set a sort order for this item.',
                        'numeric' => 'Sort order must be a number.',
                    ]),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Only active items are eligible for About page display.'),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('sort_order')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->orderBy('sort_order')->orderBy('id'))
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('item_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => Str::headline((string) $state))
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Title')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn (): bool => $this->canCreateItems())
                    ->modalHeading(fn (): string => 'Create '.Str::headline($this->getOwnerRecord()?->section_key ?? 'item')),
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

    private function itemTypeOptions(): array
    {
        $ownerRecord = $this->getOwnerRecord();

        if (! $ownerRecord instanceof AboutSection) {
            return [];
        }

        $existingItemCounts = $ownerRecord->items()
            ->selectRaw('item_type, count(*) as aggregate')
            ->groupBy('item_type')
            ->pluck('aggregate', 'item_type');

        return collect($this->sectionItemRules($ownerRecord->section_key))
            ->filter(function (array $rule, string $itemType) use ($existingItemCounts): bool {
                $max = $rule['max'];

                if ($max === null) {
                    return true;
                }

                return (int) ($existingItemCounts[$itemType] ?? 0) < $max;
            })
            ->mapWithKeys(fn (array $rule, string $itemType): array => [$itemType => $rule['label']])
            ->all();
    }

    protected function canCreateItems(): bool
    {
        $ownerRecord = $this->getOwnerRecord();

        if (! $ownerRecord instanceof AboutSection) {
            return false;
        }

        return $this->itemTypeOptions() !== [];
    }

    private function sectionItemRules(?string $sectionKey): array
    {
        return match ($sectionKey) {
            'hero' => [
                'hero_image' => ['label' => 'Hero image', 'max' => 1],
            ],
            'quote' => [],
            'story' => [
                'story_paragraph' => ['label' => 'Story paragraph', 'max' => 6],
                'story_image' => ['label' => 'Story image', 'max' => 2],
                'story_feature' => ['label' => 'Story feature', 'max' => 1],
            ],
            'values' => [
                'value_card' => ['label' => 'Value card', 'max' => null],
            ],
            default => [],
        };
    }
}
