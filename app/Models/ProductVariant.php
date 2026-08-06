<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id',
        'color',
        'color_hex',
        'size',
        'sku',
        'price',
        'sale_price',
        'stock',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($variant) {
            if (empty($variant->sku)) {
                $variant->sku = 'VAR-' . strtoupper(Str::random(8));
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('sort_order');
    }

    /**
     * Human readable label, e.g. "Red / XL".
     */
    public function getLabelAttribute()
    {
        $parts = array_filter([$this->color, $this->size]);

        return $parts ? implode(' / ', $parts) : 'Default';
    }

    /**
     * Variants fall back to the parent product's price when they don't override it.
     */
    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price ?? $this->product?->final_price;
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        }

        if ($this->stock <= 10) {
            return 'low_stock';
        }

        return 'in_stock';
    }
}
