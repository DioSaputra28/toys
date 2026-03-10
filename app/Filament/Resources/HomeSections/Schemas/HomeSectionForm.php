<?php

namespace App\Filament\Resources\HomeSections\Schemas;

use App\Models\HomeSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class HomeSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Section Details')
                    ->description('Control which homepage section is shown and in what order.')
                    ->schema([
                        Select::make('section_key')
                            ->label('Section key')
                            ->options(fn ($record): array => self::availableSectionKeyOptions($record instanceof HomeSection ? $record : null))
                            ->live()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Only section keys that are not used yet are shown in this list.')
                            ->validationMessages([
                                'required' => 'Please choose a section key.',
                                'unique' => 'This section key is already configured. Please choose another one.',
                            ]),
                        TextInput::make('title')
                            ->label('Title')
                            ->maxLength(255)
                            ->helperText(fn (Get $get): string => match ($get('section_key')) {
                                'quote' => 'Main quote text shown in the homepage quote section.',
                                'hero' => 'Main headline shown for the hero section.',
                                default => 'Optional heading shown for this section.',
                            }),
                        TextInput::make('subtitle')
                            ->label('Subtitle')
                            ->maxLength(255)
                            ->helperText(fn (Get $get): string => match ($get('section_key')) {
                                'quote' => 'Quote author or attribution text.',
                                'hero' => 'Optional supporting text under the hero title.',
                                default => 'Optional supporting text under the title.',
                            }),
                        TextInput::make('sort_order')
                            ->label('Sort order')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Lower numbers appear earlier on the homepage.')
                            ->validationMessages([
                                'required' => 'Please set a sort order.',
                                'numeric' => 'Sort order must be a number.',
                            ]),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active sections are rendered on the homepage.'),
                    ])
                    ->columns(2),
            ]);
    }

    private static function availableSectionKeyOptions(?HomeSection $record = null): array
    {
        $allOptions = [
            'hero' => 'Hero',
            'marquee' => 'Marquee',
            'editorial_picks' => 'Editorial picks',
            'quote' => 'Quote',
            'story_feature' => 'Story feature',
            'category_links' => 'Category links',
        ];

        $usedKeys = HomeSection::query()
            ->when($record?->getKey(), fn ($query, $recordId) => $query->whereKeyNot($recordId))
            ->pluck('section_key')
            ->all();

        return array_diff_key($allOptions, array_flip($usedKeys));
    }
}
