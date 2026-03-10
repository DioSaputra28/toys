<?php

namespace App\Filament\Pages;

use App\Settings\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSiteSetting extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static string $settings = SiteSetting::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Branding')
                        ->description('Set the core brand identity used in the website header and footer.')
                        ->schema([
                            TextInput::make('site_name')
                                ->label('Website name')
                                ->required()
                                ->default('toys and dolls')
                                ->maxLength(255)
                                ->helperText('Used as the text fallback when no logo is uploaded.')
                                ->validationMessages([
                                    'required' => 'Please enter the website name.',
                                    'max' => 'Website name must be 255 characters or fewer.',
                                ]),
                            FileUpload::make('logo_path')
                                ->label('Logo')
                                ->disk('public')
                                ->directory('settings/branding')
                                ->image()
                                ->maxSize(5120)
                                ->helperText('Optional. Max file size 5 MB.'),
                            FileUpload::make('favicon_path')
                                ->label('Favicon')
                                ->disk('public')
                                ->directory('settings/branding')
                                ->image()
                                ->maxSize(5120)
                                ->helperText('Optional. Max file size 5 MB.'),
                        ])
                        ->columns(2),
                    Section::make('Social Media')
                        ->description('Leave blank for platforms you do not use. URLs are auto-normalized to https://.')
                        ->schema([
                            self::socialUrlInput('instagram_url', 'Instagram URL'),
                            self::socialUrlInput('facebook_url', 'Facebook URL'),
                            self::socialUrlInput('youtube_url', 'YouTube URL'),
                            self::socialUrlInput('tiktok_url', 'TikTok URL'),
                            self::socialUrlInput('twitter_url', 'Twitter / X URL'),
                            self::socialUrlInput('linkedin_url', 'LinkedIn URL'),
                            self::socialUrlInput('threads_url', 'Threads URL'),
                            self::socialUrlInput('other_social_url', 'Other social URL'),
                        ])
                        ->columns(2),
                ]),
                Section::make('Contact')
                    ->description('These values are shown publicly on the website contact areas.')
                    ->schema([
                        TextInput::make('whatsapp_number')
                            ->label('WhatsApp number')
                            ->tel()
                            ->placeholder('6281234567890')
                            ->helperText('Use digits only and start with 62.')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $set('whatsapp_number', self::normalizeWhatsappNumber($state));
                            })
                            ->dehydrateStateUsing(fn (?string $state): ?string => self::normalizeWhatsappNumber($state))
                            ->rule('regex:/^62\d{7,15}$/')
                            ->validationMessages([
                                'regex' => 'WhatsApp number must start with 62 and contain only digits.',
                            ]),
                        TextInput::make('phone_number')
                            ->label('Phone number')
                            ->tel()
                            ->placeholder('0812 3456 7890')
                            ->helperText('Optional. Can start with 08 or +62, spaces and dashes are allowed.')
                            ->rule('regex:/^(?:08|\+62)[0-9\s\-()]{6,20}$/')
                            ->validationMessages([
                                'regex' => 'Phone number must start with 08 or +62 and use a valid format.',
                            ]),
                        TextInput::make('contact_email')
                            ->label('Contact email')
                            ->email()
                            ->placeholder('hello@example.com')
                            ->helperText('Optional public email shown on the contact page.')
                            ->validationMessages([
                                'email' => 'Please enter a valid email address.',
                            ]),
                        Textarea::make('location')
                            ->label('Location')
                            ->rows(3)
                            ->maxLength(1000)
                            ->dehydrateStateUsing(fn (?string $state): ?string => self::normalizeTextarea($state))
                            ->placeholder("Jl. Example No. 123\nJakarta Selatan")
                            ->helperText('Optional full location/address shown on the contact page.')
                            ->validationMessages([
                                'max' => 'Location must be 1000 characters or fewer.',
                            ])
                            ->columnSpanFull(),
                        Textarea::make('opening_hours')
                            ->label('Opening hours')
                            ->rows(3)
                            ->maxLength(1000)
                            ->dehydrateStateUsing(fn (?string $state): ?string => self::normalizeTextarea($state))
                            ->placeholder("Mon-Sun: 10am - 7pm\nHoliday: 11am - 5pm")
                            ->helperText('Optional opening hours text. Line breaks are allowed.')
                            ->validationMessages([
                                'max' => 'Opening hours must be 1000 characters or fewer.',
                            ])
                            ->columnSpanFull(),
                        Textarea::make('map_embed_html')
                            ->label('Map embed HTML')
                            ->rows(6)
                            ->maxLength(5000)
                            ->dehydrateStateUsing(fn (?string $state): ?string => self::normalizeTextarea($state))
                            ->placeholder('<iframe src="https://www.google.com/maps/embed?..." loading="lazy"></iframe>')
                            ->helperText('Paste the full Google Maps iframe embed code. The map section will be hidden if this is empty.')
                            ->rule('regex:/<iframe\b[^>]*>.*?<\/iframe>/is')
                            ->validationMessages([
                                'regex' => 'Map embed must be a full iframe HTML snippet.',
                                'max' => 'Map embed HTML must be 5000 characters or fewer.',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    private static function socialUrlInput(string $name, string $label): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->placeholder('https://example.com/your-account')
            ->live(onBlur: true)
            ->afterStateUpdated(function (Set $set, ?string $state) use ($name): void {
                $set($name, self::normalizeUrl($state));
            })
            ->dehydrateStateUsing(fn (?string $state): ?string => self::normalizeUrl($state))
            ->url()
            ->validationMessages([
                'url' => 'Please enter a valid URL.',
            ]);
    }

    private static function normalizeUrl(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmedValue = trim($value);

        if ($trimmedValue === '') {
            return null;
        }

        if (! str_starts_with($trimmedValue, 'http://') && ! str_starts_with($trimmedValue, 'https://')) {
            return 'https://'.ltrim($trimmedValue, '/');
        }

        return $trimmedValue;
    }

    private static function normalizeWhatsappNumber(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value);

        if ($digits === '' || $digits === null) {
            return null;
        }

        return $digits;
    }

    private static function normalizeTextarea(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmedValue = trim($value);

        return $trimmedValue === '' ? null : $trimmedValue;
    }
}
