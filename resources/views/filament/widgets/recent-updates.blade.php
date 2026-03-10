<x-filament-widgets::widget>
    <x-filament::section
        description="The latest content touched across the catalog and editorial sections."
        heading="Recent Updates"
    >
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
            @foreach ($groups as $group)
                <div style="border: 1px solid rgba(255, 255, 255, 0.08); background: rgba(255, 255, 255, 0.04); border-radius: 1rem; padding: 1rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                        <p style="margin: 0; color: #f8fafc; font-size: 0.95rem; font-weight: 700;">{{ $group['title'] }}</p>
                        <span style="border-radius: 999px; background: rgba(255, 255, 255, 0.06); padding: 0.35rem 0.55rem; color: rgba(226, 232, 240, 0.82); font-size: 0.68rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; white-space: nowrap;">
                            {{ count($group['items']) }} items
                        </span>
                    </div>

                    <div style="display: grid; gap: 0.75rem; margin-top: 1rem;">
                        @forelse ($group['items'] as $item)
                            <a href="{{ $item['url'] }}" style="display: block; border-radius: 0.85rem; background: rgba(255, 255, 255, 0.05); padding: 0.85rem; text-decoration: none;">
                                <p style="margin: 0; color: #f8fafc; font-size: 0.9rem; font-weight: 600; line-height: 1.45;">{{ $item['label'] }}</p>
                                <p style="margin: 0.35rem 0 0; color: rgba(148, 163, 184, 0.8); font-size: 0.68rem; letter-spacing: 0.16em; text-transform: uppercase;">{{ $item['meta'] }}</p>
                            </a>
                        @empty
                            <div style="border-radius: 0.85rem; background: rgba(255, 255, 255, 0.05); padding: 0.85rem; color: rgba(226, 232, 240, 0.78); font-size: 0.88rem;">
                                No updates yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
