<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
