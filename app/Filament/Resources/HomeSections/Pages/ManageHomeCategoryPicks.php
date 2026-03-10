<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomeCategoryPick;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ManageHomeCategoryPicks extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = HomeSectionResource::class;

    protected static ?string $title = 'Manage Home Category Picks';

    protected string $view = 'filament.resources.home-sections.pages.manage-home-category-picks';

    public function table(Table $table): Table
    {
        return $table
            ->query(HomeCategoryPick::query()->with('category'))
            ->columns([
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add category pick')
                    ->modalHeading('Create category pick')
                    ->form($this->categoryPickFormSchema()),
            ])
            ->recordActions([
                EditAction::make()
                    ->form($this->categoryPickFormSchema()),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function categoryPickFormSchema(): array
    {
        return [
            Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->unique(ignoreRecord: true)
                ->helperText('Each category can be picked only once on the homepage.')
                ->validationMessages([
                    'required' => 'Please choose a category to feature.',
                    'unique' => 'This category is already in the picks list.',
                ]),
            TextInput::make('sort_order')
                ->label('Sort order')
                ->numeric()
                ->default(0)
                ->required()
                ->helperText('Lower numbers appear first in the picks list.')
                ->validationMessages([
                    'required' => 'Please set a sort order.',
                    'numeric' => 'Sort order must be a number.',
                ]),
            Toggle::make('is_active')
                ->label('Active')
                ->default(true)
                ->helperText('Only active picks are shown on the homepage.'),
        ];
    }
}
