<?php

namespace App\Models;

use App\Models\Concerns\InteractsWithManagedFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeSectionItem extends Model
{
    use HasFactory;
    use InteractsWithManagedFiles;

    protected $fillable = [
        'home_section_id',
        'title',
        'image_path',
        'image_alt',
        'link_url',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function homeSection(): BelongsTo
    {
        return $this->belongsTo(HomeSection::class, 'home_section_id');
    }
}
