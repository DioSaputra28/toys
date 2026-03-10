<?php

namespace App\Models;

use App\Models\Concerns\InteractsWithManagedFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;
    use InteractsWithManagedFiles;

    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'is_primary',
        'show_in_gallery',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'show_in_gallery' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
