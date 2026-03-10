<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomePoster;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ManagePosters extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = HomeSectionResource::class;

    protected static ?string $title = 'Manage Posters';

    protected string $view = 'filament.resources.home-sections.pages.manage-posters';

    public function table(Table $table): Table
    {
        return $table
            ->query(HomePoster::query())
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->circular(),
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('insert_after_section_key')
                    ->label('Insert after')
                    ->placeholder('—')
                    ->badge(),
                TextColumn::make('size_variant')
                    ->label('Size')
                    ->badge(),
                TextColumn::make('display_style')
                    ->label('Style')
                    ->badge(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add poster')
                    ->modalHeading('Create poster')
                    ->form($this->posterFormSchema()),
            ])
            ->recordActions([
                EditAction::make()
                    ->form($this->posterFormSchema()),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function posterFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255)
                ->helperText('Internal title for this poster entry.')
                ->validationMessages([
                    'required' => 'Please enter a title for this poster.',
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
                ->required()
                ->helperText('Select where this poster should appear on the homepage.')
                ->validationMessages([
                    'required' => 'Please choose which section this poster should follow.',
                ]),
            Select::make('size_variant')
                ->label('Size variant')
                ->options([
                    'portrait' => 'Portrait',
                    'landscape' => 'Landscape',
                    'full' => 'Full width',
                ])
                ->default('portrait')
                ->required()
                ->helperText('Controls the poster card proportion inside the carousel. It no longer creates an oversized tile layout.')
                ->validationMessages([
                    'required' => 'Please choose a size variant.',
                ]),
            Select::make('display_style')
                ->label('Display style')
                ->options([
                    'image_only' => 'Image only',
                    'overlay_text' => 'Overlay text',
                ])
                ->default('image_only')
                ->required()
                ->validationMessages([
                    'required' => 'Please choose a display style.',
                ]),
            FileUpload::make('image_path')
                ->label('Poster image')
                ->disk('public')
                ->directory('posters')
                ->image()
                ->maxSize(5120)
                ->required()
                ->helperText('Upload poster image up to 5 MB.')
                ->validationMessages([
                    'required' => 'Please upload a poster image.',
                    'image' => 'The uploaded file must be an image.',
                    'max' => 'The poster image must be 5 MB or smaller.',
                ]),
            TextInput::make('link_url')
                ->label('Link URL')
                ->url()
                ->maxLength(255)
                ->helperText('Optional URL when visitor clicks this poster.')
                ->validationMessages([
                    'url' => 'Please enter a valid URL starting with http:// or https://.',
                ]),
            TextInput::make('sort_order')
                ->label('Sort order')
                ->numeric()
                ->default(0)
                ->required()
                ->helperText('Lower numbers appear first after a section and define the order of slides in the carousel.')
                ->validationMessages([
                    'required' => 'Please set a sort order.',
                    'numeric' => 'Sort order must be a number.',
                ]),
            Toggle::make('is_active')
                ->label('Active')
                ->default(true)
                ->helperText('Only active posters are rendered on the homepage.'),
        ];
    }
}
