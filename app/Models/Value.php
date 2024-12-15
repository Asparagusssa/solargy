<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Value extends Model
{
    /** @use HasFactory<\Database\Factories\ValueFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'option_id',
        'value',
        'price',
        'image',
    ];

    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'option_values', 'value_id', 'product_id');
    }
}
