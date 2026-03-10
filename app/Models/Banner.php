<?php

namespace App\Models;

use App\Models\Concerns\InteractsWithManagedFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    use InteractsWithManagedFiles;

    protected $fillable = [
        'title',
        'placement',
        'image_path',
        'link_url',
        'insert_after_section_key',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
