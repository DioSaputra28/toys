<?php

namespace App\Models;

use App\Models\Concerns\InteractsWithManagedFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AboutSectionItem extends Model
{
    use HasFactory;
    use InteractsWithManagedFiles;

    protected $fillable = [
        'about_section_id',
        'item_type',
        'title',
        'description',
        'image_path',
        'image_alt',
        'link_url',
        'icon',
        'accent_color',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function aboutSection(): BelongsTo
    {
        return $this->belongsTo(AboutSection::class, 'about_section_id');
    }
}
