<x-filament-widgets::widget>
    <x-filament::section
        description="Issues that can make the storefront feel incomplete or block key visitor actions."
        heading="Content Alerts"
    >
        @if (count($alerts))
            <div style="display: grid; gap: 0.75rem;">
                @foreach ($alerts as $alert)
                    @php
                        $toneStyle = match ($alert['tone']) {
                            'danger' => 'border: 1px solid rgba(248, 113, 113, 0.25); background: rgba(127, 29, 29, 0.18);',
                            'warning' => 'border: 1px solid rgba(251, 191, 36, 0.28); background: rgba(120, 53, 15, 0.18);',
                            default => 'border: 1px solid rgba(255, 255, 255, 0.08); background: rgba(255, 255, 255, 0.04);',
                        };
                    @endphp

                    <div style="{{ $toneStyle }} border-radius: 1rem; padding: 1rem;">
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;">
                            <div>
                                <p style="margin: 0; color: #f8fafc; font-size: 0.95rem; font-weight: 700;">{{ $alert['title'] }}</p>
                                <p style="margin: 0.35rem 0 0; color: rgba(226, 232, 240, 0.78); font-size: 0.88rem; line-height: 1.55;">{{ $alert['detail'] }}</p>
                            </div>

                            <a
                                href="{{ $alert['actionUrl'] }}"
                                style="flex-shrink: 0; border-radius: 999px; border: 1px solid rgba(255, 255, 255, 0.12); background: rgba(255, 255, 255, 0.08); padding: 0.55rem 0.85rem; color: #f8fafc; font-size: 0.75rem; font-weight: 700; text-decoration: none; white-space: nowrap;"
                            >
                                {{ $alert['actionLabel'] }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="border: 1px solid rgba(74, 222, 128, 0.22); background: rgba(22, 101, 52, 0.16); border-radius: 1rem; padding: 1.1rem 1.25rem;">
                <p style="margin: 0; color: #86efac; font-size: 0.95rem; font-weight: 700;">No urgent content alerts.</p>
                <p style="margin: 0.35rem 0 0; color: rgba(220, 252, 231, 0.85); font-size: 0.88rem; line-height: 1.55;">Core homepage and contact signals look healthy right now.</p>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
