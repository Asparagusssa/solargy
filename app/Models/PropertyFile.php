<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyFile extends Model
{
    protected $fillable = [
        'product_property_id', 'file', 'filename',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(ProductProperty::class);
    }

    public function getFileAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }
}
