<x-filament-widgets::widget>
    <x-filament::section
        description="Shortcut links to the pages that usually need the fastest follow-up."
        heading="Quick Actions"
    >
        <div style="display: grid; gap: 0.75rem;">
            @foreach ($actions as $action)
                <a
                    href="{{ $action['url'] }}"
                    style="display: block; border: 1px solid rgba(255, 255, 255, 0.08); background: linear-gradient(180deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.03)); border-radius: 1rem; padding: 1rem; text-decoration: none;"
                >
                    <p style="margin: 0; color: #f8fafc; font-size: 0.95rem; font-weight: 700;">{{ $action['label'] }}</p>
                    <p style="margin: 0.35rem 0 0; color: rgba(226, 232, 240, 0.78); font-size: 0.88rem; line-height: 1.55;">{{ $action['description'] }}</p>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
