<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'is_top',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(ProductProperty::class);
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'option_values');
//            ->withPivot('value_id');
    }

    public function values(): BelongsToMany
    {
        return $this->belongsToMany(Value::class, 'option_values');
//            ->withPivot('option_id');
    }
}
