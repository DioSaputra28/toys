<?php

namespace App\Filament\Resources\AboutSections\Schemas;

use App\Models\AboutSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class AboutSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Section Details')
                    ->description('Control About page sections and their order.')
                    ->schema([
                        Select::make('section_key')
                            ->label('Section key')
                            ->options(fn ($record): array => self::availableSectionKeyOptions($record instanceof AboutSection ? $record : null))
                            ->live()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Only section keys that are not used yet are shown in this list.')
                            ->validationMessages([
                                'required' => 'Please choose a section key.',
                                'unique' => 'This section key is already configured. Please choose another one.',
                            ]),
                        TextInput::make('eyebrow')
                            ->label('Eyebrow')
                            ->maxLength(255)
                            ->visible(fn (Get $get): bool => $get('section_key') === 'hero')
                            ->helperText('Small heading text shown above the hero title.'),
                        TextInput::make('title')
                            ->label('Title')
                            ->maxLength(255)
                            ->helperText(fn (Get $get): string => match ($get('section_key')) {
                                'quote' => 'Main quote text shown in the quote section.',
                                'values' => 'Optional heading shown above the values grid.',
                                default => 'Main title text for this section.',
                            }),
                        TextInput::make('highlight_text')
                            ->label('Highlight text')
                            ->maxLength(255)
                            ->visible(fn (Get $get): bool => in_array($get('section_key'), ['hero', 'story'], true))
                            ->helperText('Emphasized title text shown with accent styling.'),
                        Textarea::make('subtitle')
                            ->label('Subtitle')
                            ->rows(3)
                            ->helperText(fn (Get $get): string => match ($get('section_key')) {
                                'quote' => 'Author or attribution text shown below the quote.',
                                'story' => 'Optional supporting copy for the story section.',
                                default => 'Optional supporting text for this section.',
                            })
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Sort order')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Lower numbers appear earlier on the About page.')
                            ->validationMessages([
                                'required' => 'Please set a sort order.',
                                'numeric' => 'Sort order must be a number.',
                            ]),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active sections are rendered on the About page.'),
                    ])
                    ->columns(2),
            ]);
    }

    private static function availableSectionKeyOptions(?AboutSection $record = null): array
    {
        $allOptions = [
            'hero' => 'Hero',
            'quote' => 'Quote',
            'story' => 'Story',
            'values' => 'Values',
        ];

        $usedKeys = AboutSection::query()
            ->when($record?->getKey(), fn ($query, $recordId) => $query->whereKeyNot($recordId))
            ->pluck('section_key')
            ->all();

        return array_diff_key($allOptions, array_flip($usedKeys));
    }
}
