<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\Banner;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ManageBanners extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = HomeSectionResource::class;

    protected static ?string $title = 'Manage Banners';

    protected string $view = 'filament.resources.home-sections.pages.manage-banners';

    public function table(Table $table): Table
    {
        return $table
            ->query(Banner::query()->where('placement', '!=', 'home_top'))
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('placement')
                    ->label('Placement')
                    ->badge()
                    ->sortable(),
                TextColumn::make('insert_after_section_key')
                    ->label('Insert after section')
                    ->placeholder('—')
                    ->badge(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add banner')
                    ->modalHeading('Create banner')
                    ->form($this->bannerFormSchema()),
            ])
            ->recordActions([
                EditAction::make()
                    ->form($this->bannerFormSchema()),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function bannerFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255)
                ->helperText('Internal label for this banner placement.')
                ->validationMessages([
                    'required' => 'Please enter a title for this banner.',
                    'max' => 'The banner title must be 255 characters or fewer.',
                ]),
            Select::make('placement')
                ->label('Placement')
                ->options([
                    'home_between_sections' => 'Between sections',
                    'popup' => 'Popup',
                ])
                ->required()
                ->live()
                ->afterStateUpdated(function (Set $set, ?string $state): void {
                    if ($state !== 'home_between_sections') {
                        $set('insert_after_section_key', null);
                    }
                })
                ->helperText('Choose where this banner should appear.')
                ->validationMessages([
                    'required' => 'Please choose where this banner should be displayed.',
                ]),
            Select::make('insert_after_section_key')
                ->label('Insert after section key')
                ->options([
                    'hero' => 'Hero',
                    'marquee' => 'Marquee',
                    'editorial_picks' => 'Editorial picks',
                    'quote' => 'Quote',
                    'story_feature' => 'Story feature',
                    'category_links' => 'Category links',
                ])
                ->visible(fn (Get $get): bool => $get('placement') === 'home_between_sections')
                ->required(fn (Get $get): bool => $get('placement') === 'home_between_sections')
                ->helperText('Required only when placement is set to between sections.')
                ->validationMessages([
                    'required' => 'Please choose which section this banner should follow.',
                ]),
            FileUpload::make('image_path')
                ->label('Banner image')
                ->disk('public')
                ->directory('banners')
                ->image()
                ->maxSize(5120)
                ->required()
                ->helperText('Upload a banner image up to 5 MB.')
                ->validationMessages([
                    'required' => 'Please upload a banner image.',
                    'image' => 'The uploaded file must be an image.',
                    'max' => 'The banner image must be 5 MB or smaller.',
                ]),
            TextInput::make('link_url')
                ->label('Link URL')
                ->url()
                ->maxLength(255)
                ->helperText('Optional URL opened when a visitor clicks the banner.')
                ->validationMessages([
                    'url' => 'Please enter a valid URL starting with http:// or https://.',
                ]),
            TextInput::make('sort_order')
                ->label('Sort order')
                ->numeric()
                ->default(0)
                ->required()
                ->helperText('Lower numbers appear first for the same placement.')
                ->validationMessages([
                    'required' => 'Please set a sort order for this banner.',
                    'numeric' => 'Sort order must be a number.',
                ]),
            Toggle::make('is_active')
                ->label('Active')
                ->default(true)
                ->helperText('Only active banners are eligible for display.'),
        ];
    }
}
