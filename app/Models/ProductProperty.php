<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductProperty extends Model
{
    /** @use HasFactory<\Database\Factories\ProductPropertyFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'html',
        'file',
        'file_name',
        'image',
    ];

    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }

    public function getFileAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(PropertyFile::class);
    }
}
