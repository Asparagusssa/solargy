<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MainBanner extends Model
{
    /** @use HasFactory<\Database\Factories\MainBannerFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'description',
        'image',
        'order',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
