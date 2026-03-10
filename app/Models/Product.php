<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'display_price',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    protected function formattedDisplayPrice(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): ?string => self::normalizeDisplayPrice(
                $attributes['display_price'] ?? (is_string($value) ? $value : null)
            ),
        );
    }

    public static function normalizeDisplayPrice(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmedValue = trim($value);

        if ($trimmedValue === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $trimmedValue);

        if ($digits === null || $digits === '') {
            return null;
        }

        return 'Rp '.number_format((int) $digits, 0, ',', '.');
    }
}
