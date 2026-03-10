<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ContentAlerts;
use App\Filament\Widgets\HomepageOverview;
use App\Filament\Widgets\QuickActions;
use App\Filament\Widgets\RecentUpdates;
use App\Filament\Widgets\WebsiteSnapshot;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $title = 'Dashboard';

    protected ?string $subheading = 'Monitor storefront readiness, spot missing content, and jump straight into the areas that need attention.';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Livewire::make(WebsiteSnapshot::class),
                Grid::make(12)
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Livewire::make(ContentAlerts::class),
                                Livewire::make(RecentUpdates::class),
                            ])
                            ->columnSpan(['lg' => 8]),
                        Grid::make(1)
                            ->schema([
                                Livewire::make(QuickActions::class),
                            ])
                            ->columnSpan(['lg' => 4]),
                    ]),
                Livewire::make(HomepageOverview::class),
            ]);
    }
}
