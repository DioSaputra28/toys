<x-filament-widgets::widget>
    <x-filament::section
        description="A section-by-section read on what is live on the homepage and how much content backs each block."
        heading="Homepage Overview"
    >
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            @foreach ($sections as $section)
                <a
                    href="{{ $section['editUrl'] }}"
                    style="display: block; border: 1px solid rgba(255, 255, 255, 0.08); background: rgba(255, 255, 255, 0.04); border-radius: 1rem; padding: 1.1rem; text-decoration: none;"
                >
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;">
                        <div>
                            <p style="margin: 0; color: #f8fafc; font-size: 0.95rem; font-weight: 700; line-height: 1.35;">{{ $section['title'] }}</p>
                            <p style="margin: 0.35rem 0 0; color: rgba(148, 163, 184, 0.8); font-size: 0.72rem; letter-spacing: 0.18em; text-transform: uppercase;">{{ $section['sectionKey'] }}</p>
                        </div>

                        <span style="{{ $section['isActive'] ? 'background: rgba(34, 197, 94, 0.12); color: #86efac; border: 1px solid rgba(34, 197, 94, 0.18);' : 'background: rgba(255, 255, 255, 0.06); color: rgba(226, 232, 240, 0.8); border: 1px solid rgba(255, 255, 255, 0.08);' }} border-radius: 999px; padding: 0.35rem 0.55rem; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; white-space: nowrap;">
                            {{ $section['isActive'] ? 'Live' : 'Off' }}
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem; margin-top: 1rem;">
                        <div style="border-radius: 0.85rem; background: rgba(255, 255, 255, 0.04); padding: 0.85rem;">
                            <p style="margin: 0; color: rgba(148, 163, 184, 0.8); font-size: 0.68rem; letter-spacing: 0.16em; text-transform: uppercase;">Active Items</p>
                            <p style="margin: 0.5rem 0 0; color: #f8fafc; font-size: 1.65rem; font-weight: 700;">{{ $section['activeItemsCount'] }}</p>
                        </div>

                        <div style="border-radius: 0.85rem; background: rgba(255, 255, 255, 0.04); padding: 0.85rem;">
                            <p style="margin: 0; color: rgba(148, 163, 184, 0.8); font-size: 0.68rem; letter-spacing: 0.16em; text-transform: uppercase;">Posters</p>
                            <p style="margin: 0.5rem 0 0; color: #f8fafc; font-size: 1.65rem; font-weight: 700;">{{ $section['posterCount'] }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
