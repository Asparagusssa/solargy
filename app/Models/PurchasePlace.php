<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PurchasePlace extends Model
{
    /** @use HasFactory<\Database\Factories\PurchasePlaceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'image',
        'image_active',
        'image_disable',
        'url',
    ];

    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }

    public function getImageActiveAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }

    public function getImageDisableAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'market_product', 'purchase_place_id', 'product_id')
            ->withPivot('url');
    }
}
