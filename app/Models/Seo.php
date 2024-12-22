<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seo extends Model
{
    /** @use HasFactory<\Database\Factories\SeoFactory> */
    use HasFactory;

    protected $fillable = [
        'page_id', 'name', 'content',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
